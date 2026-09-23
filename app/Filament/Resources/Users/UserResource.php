<?php

namespace App\Filament\Resources\Users;

use App\Enums\UserRole;
use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'utilisateur';

    protected static ?string $pluralModelLabel = 'utilisateurs';

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    /**
     * An administrator cannot delete their own account.
     */
    public static function canDelete(Model $record): bool
    {
        return $record->isNot(auth()->user());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label('Nom')->required()->maxLength(255),
                TextInput::make('email')->label('E-mail')->email()->required()->unique(ignoreRecord: true),
                Select::make('role')->label('Rôle')->options(UserRole::class)->required()->default(UserRole::Editor)
                    ->helperText('Un éditeur gère le contenu et les demandes ; un administrateur gère aussi les langues, les réglages et les utilisateurs.'),
                TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->helperText(fn (string $operation): ?string => $operation === 'edit' ? 'Laisser vide pour conserver le mot de passe actuel.' : null),
                Toggle::make('is_active')->label('Compte actif')->default(true)
                    ->helperText('Un compte désactivé ne peut plus se connecter.')
                    ->disabled(fn (?User $record): bool => $record?->is(auth()->user()) ?? false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')->label('Nom')->searchable(),
                TextColumn::make('email')->label('E-mail')->searchable(),
                TextColumn::make('role')->label('Rôle')->badge(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
                TextColumn::make('last_login_at')->label('Dernière connexion')->dateTime('d/m/Y H:i')->placeholder('Jamais'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }
}
