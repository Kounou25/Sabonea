<?php

namespace App\Pdf\Parser;

use setasign\Fpdi\PdfParser\PdfParser as BasePdfParser;

/**
 * FPDI's free parser only reads classic cross-reference tables (PDF 1.4 and older layouts).
 * This one also reads the compressed structures of PDF 1.5+, used by most office and design tools.
 */
class PdfParser extends BasePdfParser
{
    public function getCrossReference()
    {
        if ($this->xref === null) {
            $this->xref = new CrossReference($this, $this->resolveFileHeader());
        }

        return $this->xref;
    }
}
