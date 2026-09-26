<?php

namespace App\Pdf;

use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;
use Closure;
use Dompdf\Adapter\CPDF;
use Dompdf\FontMetrics;
use Illuminate\Support\Facades\File;

/**
 * HTML to PDF with dompdf, using the Sabonea fonts: Poppins, with Noto Sans SC for Chinese (glyph by glyph).
 */
class DompdfRenderer
{
    private const MEMORY_LIMIT = 512 * 1024 * 1024;

    /** Seconds given to PHP for a profile: a web request is stopped after 30 s, a large catalogue can take longer. */
    private const TIME_LIMIT = 120;

    /**
     * Subsetting the Chinese font reads the whole file (10 MB per weight), and supplier documents can be large.
     */
    public static function raiseLimits(): void
    {
        if (ini_get('memory_limit') !== '-1' && ini_parse_quantity(ini_get('memory_limit')) < self::MEMORY_LIMIT) {
            ini_set('memory_limit', (string) self::MEMORY_LIMIT);
        }

        if (function_exists('set_time_limit')) {
            @set_time_limit(self::TIME_LIMIT);
        }
    }

    /**
     * @param  (Closure(int $page, int $pageCount): string)|null  $pageLabel  "Page X / Y" drawn at the bottom right of every page
     */
    public function render(string $html, string $orientation = 'portrait', ?Closure $pageLabel = null, bool $chinese = false): RenderedPdf
    {
        self::raiseLimits();
        $this->ensureFontCache();

        $pdf = Pdf::setOptions([
            // storage/ holds the font cache; it can be a link to another place on deployed servers.
            'chroot' => array_values(array_unique([base_path(), realpath(storage_path()) ?: storage_path()])),
            'isRemoteEnabled' => false,
            'isFontSubsettingEnabled' => true,
            'defaultFont' => 'Poppins',
            'dpi' => 96,
        ], mergeWithDefaults: true)
            ->setPaper('a4', $orientation);

        $this->registerChineseFont($pdf);
        $pdf->loadHTML($html)->render();

        if ($pageLabel !== null) {
            $this->drawPageLabels($pdf, $pageLabel, $chinese);
        }

        return new RenderedPdf((string) $pdf->output(), $pdf->getDomPDF()->getCanvas()->get_page_count());
    }

    /**
     * The total is only known once the document is laid out, so the label is drawn afterwards.
     *
     * @param  Closure(int $page, int $pageCount): string  $pageLabel
     */
    private function drawPageLabels(DomPdf $pdf, Closure $pageLabel, bool $chinese): void
    {
        $canvas = $pdf->getDomPDF()->getCanvas();

        $canvas->page_script(function (int $page, int $pageCount, CPDF $canvas, FontMetrics $metrics) use ($pageLabel, $chinese): void {
            $font = $metrics->getFont($chinese ? 'NotoSansSC' : 'Poppins');
            $text = $pageLabel($page, $pageCount);
            $width = $metrics->getTextWidth($text, $font, 7);

            $canvas->text($canvas->get_width() - 30 - $width, $canvas->get_height() - 28, $text, $font, 7, [0.54, 0.50, 0.63]);
        });
    }

    /**
     * dompdf computes the font metrics once and caches them in storage/fonts, with an index of the cached files.
     * It trusts that index blindly: if cached files were removed, the text silently falls back to Helvetica
     * (no Chinese glyphs). In that case the index is dropped so that every font is cached again.
     */
    private function ensureFontCache(): void
    {
        $directory = storage_path('fonts');
        $index = "{$directory}/installed-fonts.json";

        File::ensureDirectoryExists($directory);

        if (! File::exists($index)) {
            return;
        }

        $families = json_decode((string) File::get($index), true);

        foreach (collect(is_array($families) ? $families : [])->flatten()->filter(fn (mixed $variant): bool => is_string($variant)) as $variant) {
            $path = basename($variant) === $variant ? "{$directory}/{$variant}" : $variant;

            if (! File::exists("{$path}.ufm") || ! File::exists("{$path}.ttf")) {
                File::delete($index);

                return;
            }
        }
    }

    /**
     * Chinese fallback font (used glyph by glyph after Poppins). dompdf only matches exact weights,
     * so 500 and 600 point to the same files as regular and bold: each file is then embedded once.
     */
    private function registerChineseFont(DomPdf $pdf): void
    {
        $metrics = $pdf->getDomPDF()->getFontMetrics();

        foreach (['normal' => 'Regular', 'bold' => 'Bold'] as $weight => $file) {
            $metrics->registerFont(['family' => 'NotoSansSC', 'weight' => $weight, 'style' => 'normal'], resource_path("fonts/NotoSansSC-{$file}.ttf"));
        }

        $variants = $metrics->getFontFamilies()['notosanssc'] ?? [];
        $aliased = ['normal' => $variants['normal'] ?? null, 'bold' => $variants['bold'] ?? null, '500' => $variants['normal'] ?? null, '600' => $variants['bold'] ?? null];

        if (! in_array(null, $aliased, true) && $variants !== $aliased) {
            $metrics->setFontFamily('notosanssc', $aliased);
        }
    }
}
