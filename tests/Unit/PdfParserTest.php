<?php

namespace Tests\Unit;

use App\Pdf\Fpdi;
use App\Pdf\Parser\StreamDecoder;
use PHPUnit\Framework\TestCase;
use setasign\Fpdi\Fpdi as BaseFpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\StreamReader;

class PdfParserTest extends TestCase
{
    private function fixture(string $name): string
    {
        return dirname(__DIR__)."/Fixtures/pdf/{$name}";
    }

    public function test_the_free_fpdi_parser_cannot_read_compressed_cross_references(): void
    {
        $this->expectException(CrossReferenceException::class);

        (new BaseFpdi)->setSourceFile($this->fixture('xref-stream-landscape.pdf'));
    }

    public function test_pdf_files_with_cross_reference_and_object_streams_can_be_imported(): void
    {
        $pdf = new Fpdi;

        $this->assertSame(2, $pdf->setSourceFile($this->fixture('xref-stream-landscape.pdf')));

        foreach ([1, 2] as $number) {
            $template = $pdf->importPage($number);
            $size = $pdf->getTemplateSize($template);
            $this->assertSame('L', $size['orientation']);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($template);
        }

        $this->assertSame(2, (new Fpdi)->setSourceFile(StreamReader::createByString($pdf->Output('S'))));
    }

    public function test_encrypted_files_are_refused(): void
    {
        $this->expectExceptionCode(CrossReferenceException::ENCRYPTED);

        (new Fpdi)->setSourceFile($this->fixture('encrypted.pdf'));
    }

    public function test_png_predictors_are_reversed(): void
    {
        // Two rows of 2 bytes, each prefixed by its filter: "Up" adds the byte above, "Sub" the byte on the left.
        $this->assertSame("\x01\x02\x02\x04", StreamDecoder::unpredictPng("\x02\x01\x02\x02\x01\x02", 1, 2));
        $this->assertSame("\x05\x08\x01\x03", StreamDecoder::unpredictPng("\x01\x05\x03\x00\x01\x03", 1, 2));
    }

    public function test_bookmarks_are_written(): void
    {
        $pdf = new Fpdi;
        $pdf->AddPage();
        $pdf->addBookmark('Fiche fournisseur');
        $pdf->AddPage();
        $pdf->addBookmark('Annexe A1 · Catalogue');

        $content = $pdf->Output('S');

        $this->assertStringContainsString('/Type /Outlines', $content);
        $this->assertStringContainsString('/Title (Fiche fournisseur)', $content);
        $this->assertStringContainsString('/PageMode /UseOutlines', $content);
    }
}
