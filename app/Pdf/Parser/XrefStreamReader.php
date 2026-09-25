<?php

namespace App\Pdf\Parser;

use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\CrossReference\ReaderInterface;
use setasign\Fpdi\PdfParser\Type\PdfArray;
use setasign\Fpdi\PdfParser\Type\PdfDictionary;
use setasign\Fpdi\PdfParser\Type\PdfStream;

/**
 * Cross-reference stream (PDF 1.5+): the binary replacement of the "xref" table.
 * Entries are either free (type 0), at a byte offset (type 1) or stored inside an object stream (type 2).
 */
class XrefStreamReader implements ReaderInterface
{
    /**
     * @var array<int, array{0: int, 1: int, 2: int}> object number => [type, field 2, field 3]
     */
    private array $entries = [];

    public function __construct(private PdfStream $stream)
    {
        $dictionary = $stream->value;
        $widths = array_map(fn ($width): int => (int) $width->value, PdfArray::ensure(PdfDictionary::get($dictionary, 'W'))->value);

        if (count($widths) !== 3) {
            throw new CrossReferenceException('Invalid /W entry in a cross-reference stream.', CrossReferenceException::INVALID_DATA);
        }

        $size = (int) PdfDictionary::get($dictionary, 'Size')->value;
        $index = PdfDictionary::get($dictionary, 'Index');
        $index = $index instanceof PdfArray ? array_map(fn ($value): int => (int) $value->value, $index->value) : [0, $size];

        $data = StreamDecoder::decode($stream);
        $entryLength = array_sum($widths);
        $position = 0;

        for ($i = 0; $i + 1 < count($index); $i += 2) {
            for ($objectNumber = $index[$i], $end = $index[$i] + $index[$i + 1]; $objectNumber < $end; $objectNumber++) {
                if ($position + $entryLength > strlen($data)) {
                    break 2;
                }

                $fields = [];

                foreach ($widths as $width) {
                    $fields[] = $width === 0 ? null : self::number(substr($data, $position, $width));
                    $position += $width;
                }

                $this->entries[$objectNumber] ??= [$fields[0] ?? 1, $fields[1] ?? 0, $fields[2] ?? 0];
            }
        }
    }

    /**
     * @return array{0: int, 1: int, 2: int}|null [type, field 2, field 3], null when this section does not describe the object
     */
    public function getEntry(int $objectNumber): ?array
    {
        return $this->entries[$objectNumber] ?? null;
    }

    public function getOffsetFor($objectNumber)
    {
        $entry = $this->getEntry((int) $objectNumber);

        return $entry !== null && $entry[0] === 1 ? $entry[1] : false;
    }

    public function getTrailer()
    {
        return $this->stream->value;
    }

    private static function number(string $bytes): int
    {
        $value = 0;

        foreach (str_split($bytes) as $byte) {
            $value = ($value << 8) | ord($byte);
        }

        return $value;
    }
}
