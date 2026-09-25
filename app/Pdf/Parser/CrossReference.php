<?php

namespace App\Pdf\Parser;

use setasign\Fpdi\PdfParser\CrossReference\CrossReference as BaseCrossReference;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\PdfParser as BasePdfParser;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfParser\Type\PdfDictionary;
use setasign\Fpdi\PdfParser\Type\PdfIndirectObject;
use setasign\Fpdi\PdfParser\Type\PdfNumeric;
use setasign\Fpdi\PdfParser\Type\PdfStream;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;

/**
 * FPDI cross-reference that also understands PDF 1.5+ files: cross-reference streams,
 * objects compressed inside object streams and hybrid files (classic table + /XRefStm).
 */
class CrossReference extends BaseCrossReference
{
    /**
     * Decoded object streams: stream object number => [parser over the decoded data, object number => offset].
     *
     * @var array<int, array{0: BasePdfParser, 1: array<int, int>}>
     */
    private array $objectStreams = [];

    public function __construct(BasePdfParser $parser, $fileHeaderOffset = 0)
    {
        parent::__construct($parser, $fileHeaderOffset);

        // Hybrid files: the objects stored in object streams are only listed in the stream referenced by /XRefStm.
        $readers = [];

        foreach ($this->readers as $reader) {
            $readers[] = $reader;
            $xrefStream = $reader->getTrailer()->value['XRefStm'] ?? null;

            if ($xrefStream instanceof PdfNumeric && ! $reader instanceof XrefStreamReader) {
                try {
                    $readers[] = $this->readXref($xrefStream->value + $this->fileHeaderOffset);
                } catch (CrossReferenceException) {
                    // A broken /XRefStm only hides compressed objects: the classic table still works.
                }
            }
        }

        $this->readers = $readers;
    }

    protected function initReaderInstance($initValue)
    {
        if ($initValue instanceof PdfIndirectObject && $initValue->value instanceof PdfStream) {
            $type = PdfDictionary::get($initValue->value->value, 'Type');

            if ($type->value === 'XRef') {
                $this->checkForEncryption($initValue->value->value);

                return new XrefStreamReader($initValue->value);
            }
        }

        return parent::initReaderInstance($initValue);
    }

    public function getIndirectObject($objectNumber)
    {
        $objectNumber = (int) $objectNumber;

        foreach ($this->getReaders() as $reader) {
            if ($reader instanceof XrefStreamReader) {
                $entry = $reader->getEntry($objectNumber);

                if ($entry === null || $entry[0] === 0) {
                    continue;
                }

                return $entry[0] === 2
                    ? $this->getCompressedObject($objectNumber, $entry[1], $entry[2])
                    : $this->readObjectAt($objectNumber, $entry[1]);
            }

            $offset = $reader->getOffsetFor($objectNumber);

            if ($offset !== false) {
                return $this->readObjectAt($objectNumber, $offset);
            }
        }

        throw new CrossReferenceException(sprintf('Object (id:%s) not found.', $objectNumber), CrossReferenceException::OBJECT_NOT_FOUND);
    }

    private function readObjectAt(int $objectNumber, int $offset): PdfIndirectObject
    {
        $this->parser->getTokenizer()->clearStack();
        $this->parser->getStreamReader()->reset($offset + $this->fileHeaderOffset);

        try {
            $object = $this->parser->readValue(null, PdfIndirectObject::class);
        } catch (PdfTypeException $e) {
            throw new CrossReferenceException(sprintf('Object (id:%s) not found at location (%s).', $objectNumber, $offset), CrossReferenceException::OBJECT_NOT_FOUND, $e);
        }

        if (! $object instanceof PdfIndirectObject || $object->objectNumber !== $objectNumber) {
            throw new CrossReferenceException(sprintf('Wrong object found while %s was expected.', $objectNumber), CrossReferenceException::OBJECT_NOT_FOUND);
        }

        return $object;
    }

    /**
     * Object stored in an object stream: a header of "object number, offset" pairs, then the objects themselves.
     */
    private function getCompressedObject(int $objectNumber, int $streamNumber, int $index): PdfIndirectObject
    {
        if (! isset($this->objectStreams[$streamNumber])) {
            $stream = PdfStream::ensure($this->getIndirectObject($streamNumber)->value);
            $count = (int) PdfDictionary::get($stream->value, 'N')->value;
            $first = (int) PdfDictionary::get($stream->value, 'First')->value;
            $data = StreamDecoder::decode($stream);

            preg_match_all('/\d+/', substr($data, 0, $first), $numbers);
            $offsets = [];

            foreach (array_chunk(array_slice($numbers[0], 0, $count * 2), 2) as $pair) {
                if (count($pair) === 2) {
                    $offsets[(int) $pair[0]] = $first + (int) $pair[1];
                }
            }

            $this->objectStreams[$streamNumber] = [new BasePdfParser(StreamReader::createByString($data)), $offsets];
        }

        [$parser, $offsets] = $this->objectStreams[$streamNumber];

        if (! isset($offsets[$objectNumber])) {
            throw new CrossReferenceException(sprintf('Object (id:%s) not found in object stream %s (index %s).', $objectNumber, $streamNumber, $index), CrossReferenceException::OBJECT_NOT_FOUND);
        }

        $parser->getTokenizer()->clearStack();
        $parser->getStreamReader()->reset($offsets[$objectNumber]);
        $value = $parser->readValue();

        if ($value === false) {
            throw new CrossReferenceException(sprintf('Object (id:%s) could not be read from its object stream.', $objectNumber), CrossReferenceException::OBJECT_NOT_FOUND);
        }

        return PdfIndirectObject::create($objectNumber, 0, $value);
    }
}
