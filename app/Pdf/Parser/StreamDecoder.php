<?php

namespace App\Pdf\Parser;

use setasign\Fpdi\PdfParser\Filter\Flate;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfArray;
use setasign\Fpdi\PdfParser\Type\PdfDictionary;
use setasign\Fpdi\PdfParser\Type\PdfName;
use setasign\Fpdi\PdfParser\Type\PdfNumeric;
use setasign\Fpdi\PdfParser\Type\PdfStream;

/**
 * Decodes the internal streams FPDI's free parser cannot read: cross-reference streams and object streams,
 * compressed with FlateDecode and usually a PNG predictor.
 */
class StreamDecoder
{
    public static function decode(PdfStream $stream): string
    {
        $filters = $stream->getFilters();

        if ($filters === []) {
            return (string) $stream->getStream();
        }

        $names = array_map(fn ($filter): string => $filter instanceof PdfName ? $filter->value : '', $filters);

        if ($names !== ['FlateDecode'] && $names !== ['Fl']) {
            return $stream->getUnfilteredStream();
        }

        $data = (new Flate)->decode((string) $stream->getStream());
        $parameters = PdfDictionary::get($stream->value, 'DecodeParms');

        if ($parameters instanceof PdfArray) {
            $parameters = $parameters->value[0] ?? null;
        }

        if (! $parameters instanceof PdfDictionary) {
            return $data;
        }

        $predictor = (int) PdfDictionary::get($parameters, 'Predictor', PdfNumeric::create(1))->value;

        if ($predictor < 10) {
            if ($predictor > 1) {
                throw new PdfParserException('Unsupported TIFF predictor in an internal stream.');
            }

            return $data;
        }

        $colors = (int) PdfDictionary::get($parameters, 'Colors', PdfNumeric::create(1))->value;
        $bits = (int) PdfDictionary::get($parameters, 'BitsPerComponent', PdfNumeric::create(8))->value;
        $columns = (int) PdfDictionary::get($parameters, 'Columns', PdfNumeric::create(1))->value;

        return self::unpredictPng($data, max(1, (int) ceil($colors * $bits / 8)), (int) ceil($colors * $bits * $columns / 8));
    }

    /**
     * Reverses the PNG predictors: every row starts with its filter type (None, Sub, Up, Average, Paeth).
     */
    public static function unpredictPng(string $data, int $bytesPerPixel, int $rowLength): string
    {
        $output = '';
        $previous = array_fill(0, $rowLength, 0);
        $length = strlen($data);

        for ($offset = 0; $offset < $length; $offset += $rowLength + 1) {
            $type = ord($data[$offset]);
            $row = array_values(unpack('C*', str_pad(substr($data, $offset + 1, $rowLength), $rowLength, "\0")) ?: []);

            for ($i = 0; $i < $rowLength; $i++) {
                $left = $i >= $bytesPerPixel ? $row[$i - $bytesPerPixel] : 0;
                $up = $previous[$i];
                $upLeft = $i >= $bytesPerPixel ? $previous[$i - $bytesPerPixel] : 0;

                $row[$i] = ($row[$i] + match ($type) {
                    1 => $left,
                    2 => $up,
                    3 => intdiv($left + $up, 2),
                    4 => self::paeth($left, $up, $upLeft),
                    default => 0,
                }) & 0xFF;
            }

            $output .= pack('C*', ...$row);
            $previous = $row;
        }

        return $output;
    }

    private static function paeth(int $left, int $up, int $upLeft): int
    {
        $estimate = $left + $up - $upLeft;
        $toLeft = abs($estimate - $left);
        $toUp = abs($estimate - $up);
        $toUpLeft = abs($estimate - $upLeft);

        return match (true) {
            $toLeft <= $toUp && $toLeft <= $toUpLeft => $left,
            $toUp <= $toUpLeft => $up,
            default => $upLeft,
        };
    }
}
