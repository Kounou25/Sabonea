<?php

namespace App\SupplierForms\Profile;

use App\Pdf\Fpdi;
use App\Pdf\RenderedPdf;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\PdfReader\PageBoundaries;
use Throwable;

/**
 * PDF documents of the supplier appended to the profile: each page is reproduced as is (vector, text kept),
 * scaled into an A4 page that carries the Sabonea header and the page number of the whole document.
 */
class ProfileAnnexes
{
    /** Pages reproduced per document: a 200-page catalogue would drown the profile. */
    public const MAX_PAGES = 40;

    /** A4 in points. */
    private const A4 = [595.28, 841.89];

    /** Area left for the document page, above the footer (these pages have no header). */
    private const MARGIN_TOP = 26;

    private const MARGIN_BOTTOM = 46;

    private const MARGIN_SIDE = 32;

    public readonly Fpdi $fpdi;

    /**
     * @var array<int, array{code: string, attachment: ProfileAttachment, pageCount: int, pages: array<int, array{template: string, width: float, height: float}>}>
     */
    private array $annexes = [];

    /**
     * @var array<string, true> ids of the documents that could not be read (protected, damaged, missing)
     */
    private array $unreadable = [];

    public function __construct()
    {
        $this->fpdi = new Fpdi('P', 'pt', 'A4');
        $this->fpdi->SetAutoPageBreak(false);
        $this->fpdi->SetMargins(0, 0);
    }

    public function add(ProfileAttachment $attachment): void
    {
        $path = $attachment->absolutePath();

        if ($path === null) {
            $this->unreadable[$attachment->id] = true;

            return;
        }

        try {
            // Read from memory: FPDI keeps its sources open until the output, which would lock the file (Windows).
            $pageCount = $this->fpdi->setSourceFile(StreamReader::createByString((string) file_get_contents($path)));
            $pages = [];

            for ($number = 1; $number <= min($pageCount, self::MAX_PAGES); $number++) {
                $template = $this->fpdi->importPage($number, PageBoundaries::CROP_BOX, true, true);
                $size = $this->fpdi->getTemplateSize($template);
                $pages[] = ['template' => $template, 'width' => (float) $size['width'], 'height' => (float) $size['height']];
            }
        } catch (Throwable) {
            // Encrypted or damaged file: listed in the profile, not reproduced.
            $this->unreadable[$attachment->id] = true;

            return;
        }

        if ($pages === []) {
            $this->unreadable[$attachment->id] = true;

            return;
        }

        $this->annexes[] = ['code' => 'A'.(count($this->annexes) + 1), 'attachment' => $attachment, 'pageCount' => $pageCount, 'pages' => $pages];
    }

    public function isEmpty(): bool
    {
        return $this->annexes === [];
    }

    public function codeOf(ProfileAttachment $attachment): ?string
    {
        foreach ($this->annexes as $annex) {
            if ($annex['attachment']->id === $attachment->id) {
                return $annex['code'];
            }
        }

        return null;
    }

    public function isUnreadable(ProfileAttachment $attachment): bool
    {
        return isset($this->unreadable[$attachment->id]);
    }

    /**
     * Pages added after the profile: a contents page, then the reproduced pages.
     */
    public function pageCount(): int
    {
        return $this->isEmpty() ? 0 : 1 + array_sum(array_map(fn (array $annex): int => count($annex['pages']), $this->annexes));
    }

    /**
     * Contents page and page frames, with their final page numbers (the profile takes the first $profilePages pages).
     *
     * @return array{contents: array<string, mixed>, frames: array<int, array<string, mixed>>}
     */
    public function layout(int $profilePages): array
    {
        $page = $profilePages + 1;
        $contents = ['page' => $page, 'annexes' => []];
        $frames = [];

        foreach ($this->annexes as $annex) {
            $contents['annexes'][] = [
                'code' => $annex['code'],
                'attachment' => $annex['attachment'],
                'pages' => count($annex['pages']),
                'pageCount' => $annex['pageCount'],
                'startPage' => $page + 1,
            ];

            foreach ($annex['pages'] as $index => $source) {
                $frames[] = [
                    'page' => ++$page,
                    'code' => $annex['code'],
                    'attachment' => $annex['attachment'],
                    'sheet' => $index + 1,
                    'sheets' => count($annex['pages']),
                    'template' => $source['template'],
                    ...self::placement($source['width'], $source['height']),
                ];
            }
        }

        return ['contents' => $contents, 'frames' => $frames];
    }

    /**
     * Where a document page goes: landscape pages get a landscape A4, scaled to fit and centred.
     *
     * @return array{orientation: string, pageWidth: float, pageHeight: float, x: float, y: float, width: float, height: float}
     */
    public static function placement(float $width, float $height): array
    {
        $landscape = $width > $height;
        [$pageWidth, $pageHeight] = $landscape ? array_reverse(self::A4) : self::A4;
        $boxWidth = $pageWidth - 2 * self::MARGIN_SIDE;
        $boxHeight = $pageHeight - self::MARGIN_TOP - self::MARGIN_BOTTOM;
        $scale = min($boxWidth / $width, $boxHeight / $height);

        return [
            'orientation' => $landscape ? 'landscape' : 'portrait',
            'pageWidth' => $pageWidth,
            'pageHeight' => $pageHeight,
            'x' => self::MARGIN_SIDE + ($boxWidth - $width * $scale) / 2,
            'y' => self::MARGIN_TOP + ($boxHeight - $height * $scale) / 2,
            'width' => $width * $scale,
            'height' => $height * $scale,
        ];
    }

    /**
     * One PDF: the profile pages, the contents page, then each document page over its frame.
     *
     * @param  array<int, array<string, mixed>>  $frames  from layout()
     * @param  array<string, RenderedPdf>  $framePdfs  rendered frames by orientation (the portrait one starts with the contents page)
     * @param  array{profile: string, contents: string, annexes: array<string, string>}  $bookmarks
     */
    public function assemble(RenderedPdf $profile, array $frames, array $framePdfs, array $bookmarks, string $title): string
    {
        $fpdi = $this->fpdi;
        $fpdi->SetTitle($title, true);
        $fpdi->SetAuthor('Sabonea', true);
        $fpdi->SetCreator('Sabonea', true);

        $profilePages = $this->importAll($profile);
        $framePages = array_map(fn (RenderedPdf $pdf): array => $this->importAll($pdf), $framePdfs);

        foreach ($profilePages as $index => $template) {
            $this->addTemplatePage($template);

            if ($index === 0) {
                $fpdi->addBookmark($bookmarks['profile']);
            }
        }

        $this->addTemplatePage(array_shift($framePages['portrait']));
        $fpdi->addBookmark($bookmarks['contents']);

        foreach ($frames as $frame) {
            $this->addTemplatePage(array_shift($framePages[$frame['orientation']]));
            $fpdi->useTemplate($frame['template'], $frame['x'], $frame['y'], $frame['width'], $frame['height']);

            if ($frame['sheet'] === 1) {
                $fpdi->addBookmark($bookmarks['annexes'][$frame['code']]);
            }
        }

        return $fpdi->Output('S');
    }

    /**
     * @return array<int, string> page templates
     */
    private function importAll(RenderedPdf $pdf): array
    {
        $count = $this->fpdi->setSourceFile(StreamReader::createByString($pdf->content));

        return array_map(fn (int $number): string => $this->fpdi->importPage($number, PageBoundaries::CROP_BOX, true, true), range(1, $count));
    }

    private function addTemplatePage(string $template): void
    {
        $size = $this->fpdi->getTemplateSize($template);
        $this->fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $this->fpdi->useTemplate($template);
    }
}
