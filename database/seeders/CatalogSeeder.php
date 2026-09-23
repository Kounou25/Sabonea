<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
use App\Models\FormOption;
use App\Models\HeroSlide;
use App\Models\Sector;
use Database\Seeders\Concerns\SeedsTranslatedContent;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    use SeedsTranslatedContent;

    /**
     * Each list is only seeded while empty: content edited from the back-office is never overwritten.
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

        if (Sector::query()->doesntExist()) {
            foreach ($catalog['sectors'] as $sort => $sector) {
                Sector::create([
                    'name' => $this->tr($sector['name']),
                    'short_name' => $this->tr($sector['short_name']),
                    'icon' => $sector['icon'],
                    'image' => $this->image($sector['image']),
                    'show_on_home' => $sector['home'],
                    'sort' => $sort,
                ]);
            }
        }

        if (EquipmentType::query()->doesntExist()) {
            foreach ($catalog['equipment_types'] as $sort => $equipmentType) {
                EquipmentType::create([
                    'name' => $this->tr($equipmentType['name']),
                    'icon' => $equipmentType['icon'],
                    'sort' => $sort,
                ]);
            }
        }

        if (FormOption::query()->doesntExist()) {
            foreach ($catalog['form_options'] as $field => $options) {
                foreach ($options as $sort => $label) {
                    FormOption::create([
                        'field' => $field,
                        'label' => $this->tr($label),
                        'sort' => $sort,
                    ]);
                }
            }
        }
    }
}
