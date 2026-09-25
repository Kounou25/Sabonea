<?php

namespace App\Pdf;

use App\Pdf\Parser\PdfParser;
use setasign\Fpdi\Fpdi as BaseFpdi;
use setasign\Fpdi\PdfParser\StreamReader;

/**
 * FPDI with the parser that reads PDF 1.5+ files, and bookmarks (the navigation panel of PDF viewers).
 */
class Fpdi extends BaseFpdi
{
    /**
     * @var array<int, array{title: string, page: int, parent?: int, prev?: int, next?: int}>
     */
    private array $bookmarks = [];

    private ?int $outlinesObject = null;

    protected function getPdfParserInstance(StreamReader $streamReader, #[\SensitiveParameter] array $parserParams = [])
    {
        return new PdfParser($streamReader);
    }

    /**
     * Bookmark to the current page (a single level: the profile, then each annex).
     */
    public function addBookmark(string $title): void
    {
        $this->bookmarks[] = ['title' => $title, 'page' => $this->PageNo()];
    }

    protected function _putresources()
    {
        parent::_putresources();

        if ($this->bookmarks === []) {
            return;
        }

        $first = $this->n + 1;
        $last = $first + count($this->bookmarks) - 1;
        $root = $last + 1;

        foreach ($this->bookmarks as $index => $bookmark) {
            $this->_newobj();
            $this->_put('<</Title '.$this->_textstring($bookmark['title']).' /Parent '.$root.' 0 R');

            if ($index > 0) {
                $this->_put('/Prev '.($first + $index - 1).' 0 R');
            }

            if ($first + $index < $last) {
                $this->_put('/Next '.($first + $index + 1).' 0 R');
            }

            $this->_put(sprintf('/Dest [%d 0 R /XYZ null null null]>>', $this->PageInfo[$bookmark['page']]['n']));
            $this->_put('endobj');
        }

        $this->_newobj();
        $this->outlinesObject = $this->n;
        $this->_put('<</Type /Outlines /First '.$first.' 0 R /Last '.$last.' 0 R /Count '.count($this->bookmarks).'>>');
        $this->_put('endobj');
    }

    protected function _putcatalog()
    {
        parent::_putcatalog();

        if ($this->outlinesObject !== null) {
            $this->_put('/Outlines '.$this->outlinesObject.' 0 R');
            $this->_put('/PageMode /UseOutlines');
        }
    }
}
