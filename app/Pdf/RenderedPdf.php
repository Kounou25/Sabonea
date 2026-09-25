<?php

namespace App\Pdf;

final readonly class RenderedPdf
{
    public function __construct(
        public string $content,
        public int $pageCount,
    ) {}
}
