<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
use App\Models\FormOption;
use App\Models\HeroSlide;
use App\Models\Sector;
use App\SupplierForms\OptionLists;
use Database\Seeders\Concerns\SeedsTranslatedContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    use SeedsTranslatedContent;

    /**
     * Content edited from the back-office is never overwritten: slides are only seeded while
     * empty, list items are matched by technical key (or French label) and only created when missing.
     */
    public function run(): void
    {
        $catalog = require __DIR__.'/data/catalog.php';

        if (HeroSlide::query()->doesntExist()) {
            foreach ($catalog['hero_slides'] as $sort => $slide) {
                HeroSlide::create([
                    'image' => $this->image($slide['image']),
                    'image_alt' => $this->tr($slide['image_alt']),
                    'eyebrow' => $this->tr($slide['eyebrow']),
                    'title' => $this->tr($slide['title']),
                    'text' => $this->tr($slide['text']),
                    'cta_label' => $this->tr($slide['cta_label']),
                    'cta_url' => $slide['cta_url'],
                    'cta2_label' => $this->tr($slide['cta2_label']),
                    'cta2_url' => $slide['cta2_url'],
                    'sort' => $sort,
                ]);
            }
        }

        foreach ($catalog['sectors'] as $sort => $sector) {
            $this->seedReferenceItem(Sector::class, $sector, $sort, [
                'short_name' => $this->tr($sector['short_name'] ?? null),
                'image' => $this->image($sector['image'] ?? null),
                'show_on_home' => $sector['home'] ?? false,
            ]);
        }

        foreach ($catalog['equipment_types'] as $sort => $equipmentType) {
            $this->seedReferenceItem(EquipmentType::class, $equipmentType, $sort);
        }

        $lists = [...$catalog['form_options'], ...require __DIR__.'/data/supplier_options.php'];

        foreach ($lists as $field => $options) {
            foreach ($options as $sort => [$key, $label]) {
                $this->seedFormOption($field, $key, $label, $sort, $options[$sort][2] ?? null);
            }
        }

        OptionLists::flush();
    }

    /**
     * @param  class-string<Model>  $model
     * @param  array<string, mixed>  $item
     * @param  array<string, mixed>  $attributes  only used when the item is created
     */
    private function seedReferenceItem(string $model, array $item, int $sort, array $attributes = []): void
    {
        $keyAttributes = [
            'key' => $item['key'],
            'is_other' => $item['other'] ?? false,
            'show_on_site' => $item['site'] ?? true,
        ];

        if ($model::query()->where('key', $item['key'])->exists()) {
            return;
        }

        // Items created before the technical keys existed are matched by their French name.
        $existing = $model::query()->whereNull('key')->where('name->fr', $item['name'][0])->first();

        if ($existing) {
            $existing->update([...$keyAttributes, 'form_label' => $existing->form_label ?? $this->tr($item['form_label'] ?? null)]);

            return;
        }

        $model::create([
            ...$keyAttributes,
            ...$attributes,
            'name' => $this->tr($item['name']),
            'form_label' => $this->tr($item['form_label'] ?? null),
            'icon' => $item['icon'],
            'sort' => $sort + 100,
        ]);
    }

    /**
     * @param  array<int, string>  $label
     */
    private function seedFormOption(string $field, string $key, array $label, int $sort, ?string $flag): void
    {
        $flags = ['is_other' => $flag === 'other', 'is_exclusive' => $flag === 'exclusive'];

        if (FormOption::query()->where('field', $field)->where('key', $key)->exists()) {
            return;
        }

        $existing = FormOption::query()->where('field', $field)->whereNull('key')->where('label->fr', $label[0])->first();

        if ($existing) {
            $existing->update(['key' => $key, ...$flags]);

            return;
        }

        FormOption::create([
            'field' => $field,
            'key' => $key,
            'label' => $this->tr($label),
            'sort' => $sort,
            ...$flags,
        ]);
    }
}
