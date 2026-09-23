<?php

namespace App\Filament\Support;

use App\Support\Locales;
use Closure;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Back-office helpers for attributes stored as {"fr": ..., "en": ..., "de": ..., "zh": ...}.
 */
class Translatable
{
    /**
     * One tab per site language. $fields receives the locale code and whether it is the
     * reference language (whose texts are mandatory and used as fallback).
     *
     * @param  Closure(string, bool): array<int, Component|Field>  $fields
     */
    public static function tabs(Closure $fields, string $label = 'Textes'): Tabs
    {
        $tabs = [];

        foreach (Locales::all() as $code => $name) {
            $isReference = $code === Locales::reference();

            $tabs[] = Tab::make($name)
                ->key("locale-{$code}")
                ->badge($isReference ? 'référence' : null)
                ->schema($fields($code, $isReference));
        }

        return Tabs::make($label)
            ->tabs($tabs)
            ->columnSpanFull();
    }

    /**
     * Text of a translatable attribute in the reference language, searchable.
     */
    public static function column(string $attribute, string $label): TextColumn
    {
        $reference = Locales::reference();

        return TextColumn::make("{$attribute}_{$reference}")
            ->label($label)
            ->state(fn (Model $record): ?string => $record->t($attribute, $reference))
            ->wrap()
            ->searchable(query: fn (Builder $query, string $search): Builder => $query->whereLike("{$attribute}->{$reference}", "%{$search}%"));
    }

    /**
     * Badges listing the online languages still missing a translation.
     *
     * @param  (Closure(Model): array<int, string>)|null  $missingLocales
     */
    public static function statusColumn(?Closure $missingLocales = null): TextColumn
    {
        return TextColumn::make('translation_status')
            ->label('Traductions')
            ->state(function (Model $record) use ($missingLocales): array {
                $missing = $missingLocales ? $missingLocales($record) : $record->missingLocales();

                return $missing === [] ? ['Complet'] : array_map(fn (string $code): string => strtoupper($code).' à traduire', $missing);
            })
            ->badge()
            ->color(fn (string $state): string => $state === 'Complet' ? 'success' : 'warning');
    }
}
