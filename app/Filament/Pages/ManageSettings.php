<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\SupplierForms;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageSettings extends Page
{
    /**
     * @var array<int, string>
     */
    private const KEYS = [
        'contact_email', 'notification_email',
        'linkedin_url', 'linkedin_label',
        'instagram_url', 'instagram_label',
        'facebook_url', 'facebook_label',
        SupplierForms::PUBLIC_SETTING,
    ];

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Réglages';

    protected static ?string $title = 'Réglages du site';

    protected static ?string $slug = 'reglages';

    /**
     * @var array<string, ?string>
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    public function mount(): void
    {
        $this->form->fill([
            ...collect(self::KEYS)->mapWithKeys(fn (string $key): array => [$key => Setting::get($key)])->all(),
            SupplierForms::PUBLIC_SETTING => Setting::get(SupplierForms::PUBLIC_SETTING) === '1',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Coordonnées')
                    ->columns(2)
                    ->schema([
                        TextInput::make('contact_email')->label('E-mail de contact affiché sur le site')->email()->required(),
                        TextInput::make('notification_email')->label('E-mail qui reçoit les nouvelles demandes')->email()
                            ->helperText('Laisser vide pour utiliser l\'e-mail de contact.'),
                    ]),
                Section::make('Formulaire fournisseur')
                    ->description('Tant que le formulaire est fermé, seuls les utilisateurs connectés au back-office le voient (mode test : leurs réponses sont exclues des exports). À ouvrir une fois la politique de confidentialité publiée.')
                    ->schema([
                        Toggle::make(SupplierForms::PUBLIC_SETTING)->label('Formulaire « Devenir fournisseur » ouvert au public'),
                    ]),
                Section::make('Réseaux sociaux')
                    ->description('Laisser l\'adresse vide pour masquer un réseau sur le site.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('linkedin_url')->label('LinkedIn : adresse')->url(),
                        TextInput::make('linkedin_label')->label('LinkedIn : libellé (page Contact)'),
                        TextInput::make('instagram_url')->label('Instagram : adresse')->url(),
                        TextInput::make('instagram_label')->label('Instagram : libellé (page Contact)'),
                        TextInput::make('facebook_url')->label('Facebook : adresse')->url(),
                        TextInput::make('facebook_label')->label('Facebook : libellé (page Contact)'),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')->label('Enregistrer')->submit('save'),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::put($key, is_bool($value) ? ($value ? '1' : '0') : $value);
        }

        Notification::make()->title('Réglages enregistrés')->success()->send();
    }
}
