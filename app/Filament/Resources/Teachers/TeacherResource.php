<?php

namespace App\Filament\Resources\Teachers;

use App\Filament\Resources\Teachers\Pages;
use App\Models\Teacher;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Преподаватели';
    protected static ?string $pluralModelLabel = 'Преподаватели';
    protected static ?string $modelLabel = 'Преподаватель';
    protected static ?string $slug = 'directories/teachers';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('fio')
                    ->label('ФИО')
                    ->required()
                    ->maxLength(500),
                TextInput::make('profession')
                    ->label('Профессия')
                    ->maxLength(500),
                TextInput::make('division1')
                    ->label('Подразделение 1')
                    ->maxLength(500),
                TextInput::make('division2')
                    ->label('Подразделение 2')
                    ->maxLength(500),
                TextInput::make('division3')
                    ->label('Подразделение 3')
                    ->maxLength(500),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fio')
                    ->label('ФИО')
                    ->searchable(),
                TextColumn::make('profession')
                    ->label('Профессия')
                    ->searchable(),
                TextColumn::make('division1')
                    ->label('Подразделение 1'),
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}