<?php

namespace Database\Seeders;

use App\Models\Page;
use Database\Seeders\Concerns\SeedsTranslatedContent;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    use SeedsTranslatedContent;

    private const SECTION_TRANSLATABLE = ['eyebrow', 'title', 'subtitle', 'body', 'note', 'image_alt', 'cta_label', 'cta2_label'];

    /**
     * Only missing pages and sections are created: content edited from the back-office is never overwritten.
     */
    public function run(): void
    {
        $pages = [...require __DIR__.'/data/pages.php', ...require __DIR__.'/data/pages_supplier.php'];

        foreach ($pages as $key => $data) {
            $page = Page::query()->where('key', $key)->first() ?? Page::create([
                'key' => $key,
                'name' => $data['name'],
                'meta_title' => $this->tr($data['meta_title']),
                'meta_description' => $this->tr($data['meta_description'] ?? null),
                'meta_keywords' => $this->tr($data['meta_keywords'] ?? null),
                'noindex' => $data['noindex'] ?? false,
                'header_title' => $this->tr($data['header_title'] ?? null),
                'breadcrumb' => $this->tr($data['breadcrumb'] ?? null),
                'header_image' => $this->image($data['header_image'] ?? null),
                'header_image_alt' => $this->tr($data['header_image_alt'] ?? null),
            ]);

            foreach ($data['sections'] as $sort => $sectionData) {
                if ($page->sections()->where('key', $sectionData['key'])->doesntExist()) {
                    $this->createSection($page, $sectionData, $sort);
                }
            }
        }
    }

    /**
     * @param  array<string, mixed>  $sectionData
     */
    private function createSection(Page $page, array $sectionData, int $sort): void
    {
        $section = $page->sections()->create([
            'key' => $sectionData['key'],
            'name' => $sectionData['name'],
            'fields' => $sectionData['fields'],
            'item_fields' => $sectionData['item_fields'] ?? null,
            'image' => $this->image($sectionData['image'] ?? null),
            'cta_url' => $sectionData['cta_url'] ?? null,
            'cta2_url' => $sectionData['cta2_url'] ?? null,
            'sort' => $sort,
            ...collect(self::SECTION_TRANSLATABLE)
                ->mapWithKeys(fn (string $field) => [$field => $this->tr($sectionData[$field] ?? null)])
                ->all(),
        ]);

        foreach ($sectionData['items'] ?? [] as $itemSort => $item) {
            $section->items()->create([
                'title' => $this->tr($item['title'] ?? null),
                'text' => $this->tr($item['text'] ?? null),
                'icon' => $item['icon'] ?? null,
                'image' => $this->image($item['image'] ?? null),
                'variant' => $item['variant'] ?? null,
                'sort' => $itemSort,
            ]);
        }
    }
}
