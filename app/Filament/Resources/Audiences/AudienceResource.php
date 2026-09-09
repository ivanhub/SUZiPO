<?php

namespace App\Filament\Resources\Audiences;

use App\Filament\Resources\Audiences\Pages;
use App\Models\Audience;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class AudienceResource extends Resource
{
    protected static ?string $model = Audience::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Аудитории';
    protected static ?string $pluralModelLabel = 'Аудитории';
    protected static ?string $modelLabel = 'Аудитория';
    protected static ?string $slug = 'directories/audiences';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('number')
                    ->label('Номер аудитории')
                    ->required()
                    ->maxLength(100),
                TextInput::make('location')
                    ->label('Расположение')
                    ->maxLength(500),
                TextInput::make('responsible_person')
                    ->label('Ответственный')
                    ->maxLength(500),
                TextInput::make('seats')
                    ->label('Количество мест')
                    ->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Номер')
                    ->searchable(),
                TextColumn::make('location')
                    ->label('Расположение')
                    ->searchable(),
                TextColumn::make('responsible_person')
                    ->label('Ответственный'),
                TextColumn::make('seats')
                    ->label('Мест'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAudiences::route('/'),
            'create' => Pages\CreateAudience::route('/create'),
            'edit' => Pages\EditAudience::route('/{record}/edit'),
        ];
    }
}