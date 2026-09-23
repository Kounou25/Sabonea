<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Support\Translatable;
use App\Models\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bandeau de la page')
                    ->description('Titre affiché en haut de la page et fil d\'Ariane. Sans image, un fond dégradé violet est utilisé.')
                    ->visible(fn (?Page $record): bool => $record?->key !== 'accueil')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('header_image')
                            ->label('Image de fond')
                            ->image()
                            ->disk('public')
                            ->directory('uploads')
                            ->maxSize(4096),
                        Translatable::tabs(fn (string $locale, bool $isReference): array => [
                            TextInput::make("header_title.{$locale}")
                                ->label('Titre du bandeau')
                                ->visible(fn (?Page $record): bool => $record?->key !== 'fournisseur-exemple'),
                            TextInput::make("breadcrumb.{$locale}")
                                ->label('Libellé dans le fil d\'Ariane')
                                ->required($isReference),
                            TextInput::make("header_image_alt.{$locale}")
                                ->label('Texte alternatif de l\'image')
                                ->helperText('Décrit l\'image pour les lecteurs d\'écran et le référencement.'),
                        ]),
                    ]),

                Section::make('Référencement (SEO)')
                    ->description('Titre de l\'onglet du navigateur et description affichée par les moteurs de recherche.')
                    ->collapsible()
                    ->columnSpanFull()
                    ->schema([
                        Translatable::tabs(fn (string $locale, bool $isReference): array => [
                            TextInput::make("meta_title.{$locale}")
                                ->label('Titre de la page')
                                ->required($isReference)
                                ->maxLength(255),
                            Textarea::make("meta_description.{$locale}")
                                ->label('Description')
                                ->rows(3)
                                ->maxLength(500),
                            TextInput::make("meta_keywords.{$locale}")
                                ->label('Mots-clés')
                                ->helperText('Séparés par des virgules.'),
                        ]),
                        Toggle::make('noindex')
                            ->label('Masquer cette page des moteurs de recherche'),
                    ]),
            ]);
    }
}
