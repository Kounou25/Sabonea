<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Support\Translatable;
use App\Models\PageSection;
use App\Support\SiteLink;
use Filament\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

/**
 * Form of a page section: only the fields its layout displays are shown.
 */
class PageSectionForm
{
    private const MARKDOWN_HINT = 'Mise en forme possible : **gras**, *italique*.';

    /**
     * @return array<int, Component|Field>
     */
    public static function components(?PageSection $section): array
    {
        if ($section === null) {
            return [];
        }

        $uses = fn (string $field): bool => $section->uses($field);

        return array_values(array_filter([
            Toggle::make('is_visible')
                ->label('Afficher cette section sur le site'),

            Translatable::tabs(fn (string $locale, bool $isReference): array => array_values(array_filter([
                $uses('eyebrow') ? TextInput::make("eyebrow.{$locale}")->label('Surtitre')->required($isReference) : null,
                $uses('title') ? TextInput::make("title.{$locale}")->label('Titre')->required($isReference) : null,
                $uses('subtitle') ? Textarea::make("subtitle.{$locale}")->label('Sous-titre / chapeau')->rows(2)->helperText(self::MARKDOWN_HINT)->required($isReference) : null,
                $uses('body') ? Textarea::make("body.{$locale}")->label('Texte')->rows(4)->helperText(self::MARKDOWN_HINT)->required($isReference) : null,
                $uses('content') ? RichEditor::make("body.{$locale}")->label('Texte de la page')->required($isReference)
                    ->toolbarButtons([['bold', 'italic', 'link'], ['h2', 'h3'], ['bulletList', 'orderedList', 'blockquote'], ['undo', 'redo']]) : null,
                $uses('note') ? Textarea::make("note.{$locale}")->label('Texte complémentaire')->rows(3)->helperText(self::MARKDOWN_HINT) : null,
                $uses('image') ? TextInput::make("image_alt.{$locale}")->label('Texte alternatif de l\'image') : null,
                $uses('cta') ? TextInput::make("cta_label.{$locale}")->label('Bouton : libellé')->required($isReference) : null,
                $uses('cta2') ? TextInput::make("cta2_label.{$locale}")->label('2e bouton : libellé')->required($isReference) : null,
            ]))),

            $uses('image') || $uses('cta') || $uses('cta2')
                ? Grid::make(2)->columnSpanFull()->schema(array_values(array_filter([
                    $uses('image') ? FileUpload::make('image')->label('Image')->image()->disk('public')->directory('uploads')->maxSize(4096)->required()->columnSpanFull() : null,
                    $uses('cta') ? Select::make('cta_url')->label('Bouton : page de destination')->options(SiteLink::pageOptions())->required() : null,
                    $uses('cta2') ? Select::make('cta2_url')->label('2e bouton : page de destination')->options(SiteLink::pageOptions())->required() : null,
                ])))
                : null,

            $section->hasItems()
                ? Section::make('Éléments de la liste')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('items')
                            ->hiddenLabel()
                            ->relationship()
                            ->orderColumn('sort')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->collapsed()
                            ->cloneable()
                            ->addActionLabel('Ajouter un élément')
                            ->itemLabel(fn (array $state): ?string => $state['title']['fr'] ?? null)
                            ->schema(self::itemComponents($section)),
                    ])
                : null,
        ]));
    }

    /**
     * @return array<int, Component|Field>
     */
    private static function itemComponents(PageSection $section): array
    {
        $uses = fn (string $field): bool => in_array($field, $section->item_fields ?? [], true);

        return array_values(array_filter([
            Translatable::tabs(fn (string $locale, bool $isReference): array => array_values(array_filter([
                $uses('title') ? TextInput::make("title.{$locale}")->label('Titre')->required($isReference) : null,
                $uses('text') ? Textarea::make("text.{$locale}")->label('Texte')->rows(2)->helperText(self::MARKDOWN_HINT) : null,
            ]))),
            $uses('icon')
                ? TextInput::make('icon')
                    ->label('Icône')
                    ->placeholder('fa fa-plane')
                    ->helperText('Classe Font Awesome 5, par exemple « fa fa-plane ».')
                    ->hintAction(Action::make('icons')->label('Voir les icônes')->url('https://fontawesome.com/v5/search?m=free', shouldOpenInNewTab: true))
                    ->required()
                : null,
            $uses('variant')
                ? Select::make('variant')
                    ->label('Couleur')
                    ->placeholder('Violet (par défaut)')
                    ->options($section->key === 'tags'
                        ? ['green' => 'Vert (certification)']
                        : ['alt-green' => 'Vert', 'alt-orange' => 'Orange'])
                : null,
            $uses('image')
                ? FileUpload::make('image')->label('Image')->image()->disk('public')->directory('uploads')->maxSize(4096)->required()
                : null,
        ]));
    }
}
