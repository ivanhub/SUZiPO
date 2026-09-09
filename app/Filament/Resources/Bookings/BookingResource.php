<?php

namespace App\Filament\Resources\Bookings;

use App\Filament\Resources\Bookings\Pages;
use App\Models\Booking;
use App\Models\Audience;
use App\Models\Teacher;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Бронирования';
    protected static ?string $pluralModelLabel = 'Бронирования';
    protected static ?string $modelLabel = 'Бронирование';
    protected static ?string $slug = 'bookings';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Дата начала
                DatePicker::make('start_date')
                    ->label('Дата начала')
                    ->required()
                    ->displayFormat('d.m.Y')
                    ->native(false)
                    ->reactive()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $set('teacher_id', null);
                        $endDate = $get('end_date');
                        $startDate = $get('start_date');
                        if ($endDate && $startDate && $endDate < $startDate) {
                            $set('end_date', $startDate);
                        }
                    }),

                // Дата окончания
                DatePicker::make('end_date')
                    ->label('Дата окончания')
                    ->required()
                    ->displayFormat('d.m.Y')
                    ->native(false)
                    ->afterStateUpdated(function (callable $set) {
                        $set('teacher_id', null);
                    }),

                // Аудитория
                Select::make('audience_id')
                    ->label('Аудитория')
                    ->relationship('audience', 'number')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('teacher_id', null);
                    }),

                // Преподаватель
                Select::make('teacher_id')
                    ->label('Преподаватель')
                    ->relationship('teacher', 'fio')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive(),

                // Статус
                Select::make('status')
                    ->label('Статус')
                    ->options([
                        'active' => 'Активно',
                        'cancelled' => 'Отменено',
                        'completed' => 'Завершено',
                    ])
                    ->default('active')
                    ->required(),

                Textarea::make('notes')
                    ->label('Примечание')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start_date')
                    ->label('Дата начала')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Дата окончания')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('audience.number')
                    ->label('Аудитория')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('teacher.fio')
                    ->label('Преподаватель')
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('status')
                    ->label('Статус')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'cancelled',
                        'warning' => 'completed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Активно',
                        'cancelled' => 'Отменено',
                        'completed' => 'Завершено',
                        default => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('audience_id')
                    ->relationship('audience', 'number')
                    ->label('Аудитория')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('teacher_id')
                    ->relationship('teacher', 'fio')
                    ->label('Преподаватель')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'active' => 'Активно',
                        'cancelled' => 'Отменено',
                        'completed' => 'Завершено',
                    ]),
            ])
            ->defaultSort('start_date', 'desc')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}