<?php

namespace App\Filament\Resources\HeroSlides\Schemas;

use App\Filament\Support\Translatable;
use App\Support\SiteLink;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HeroSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Image et boutons')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('image')
                            ->label('Image de fond')
                            ->helperText('Format paysage, 1920 × 1080 px recommandé.')
                            ->image()
                            ->disk('public')
                            ->directory('uploads')
                            ->maxSize(4096)
                            ->required()
                            ->columnSpanFull(),
                        Select::make('cta_url')->label('Bouton principal : page de destination')->options(SiteLink::pageOptions()),
                        Select::make('cta2_url')->label('Bouton secondaire : page de destination')->options(SiteLink::pageOptions()),
                        Toggle::make('is_active')->label('Afficher ce slide')->default(true),
                    ]),
                Section::make('Textes')
                    ->columnSpanFull()
                    ->schema([
                        Translatable::tabs(fn (string $locale, bool $isReference): array => [
                            TextInput::make("eyebrow.{$locale}")->label('Surtitre'),
                            TextInput::make("title.{$locale}")->label('Titre')->required($isReference),
                            Textarea::make("text.{$locale}")->label('Texte')->rows(3),
                            TextInput::make("cta_label.{$locale}")->label('Bouton principal : libellé'),
                            TextInput::make("cta2_label.{$locale}")->label('Bouton secondaire : libellé'),
                            TextInput::make("image_alt.{$locale}")->label('Texte alternatif de l\'image'),
                        ]),
                    ]),
            ]);
    }
}
