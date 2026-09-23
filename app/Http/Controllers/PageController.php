<?php

namespace App\Http\Controllers;

use App\Models\EquipmentType;
use App\Models\FormOption;
use App\Models\HeroSlide;
use App\Models\Page;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a content page; the route name is the page key.
     */
    public function __invoke(Request $request): View
    {
        $key = $request->route()->getName();

        $page = Page::query()
            ->where('key', $key)
            ->with(['sections.items'])
            ->firstOrFail();

        return view("pages.{$key}", ['page' => $page, ...$this->extraData($key)]);
    }

    /**
     * Catalog data some pages display besides their own sections.
     *
     * @return array<string, mixed>
     */
    private function extraData(string $key): array
    {
        return match ($key) {
            'accueil' => [
                'slides' => HeroSlide::query()->published()->get(),
                'homeSectors' => Sector::query()->published()->where('show_on_home', true)->get(),
            ],
            'secteurs' => [
                'sectors' => Sector::query()->published()->get(),
                'equipmentTypes' => EquipmentType::query()->published()->get(),
            ],
            'contact' => [
                'subjects' => FormOption::query()->published(FormOption::CONTACT_SUBJECT)->get(),
            ],
            'expression-de-besoin' => [
                'sectors' => Sector::query()->published()->get(),
                'equipmentTypes' => EquipmentType::query()->published()->get(),
                'deadlines' => FormOption::query()->published(FormOption::NEED_DEADLINE)->get(),
            ],
            default => [],
        };
    }
}
