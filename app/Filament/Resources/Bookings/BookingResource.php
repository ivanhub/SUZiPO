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
use Illuminate\Support\Collection;

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
                DatePicker::make('date')
                    ->label('Дата')
                    ->required()
                    ->displayFormat('d.m.Y')
                    ->native(false)
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        // Сбрасываем выбор аудитории и преподавателя при смене даты
                        $set('audience_id', null);
                        $set('teacher_id', null);
                    }),

                Select::make('audience_id')
                    ->label('Аудитория')
                    ->options(function (callable $get) {
                        $date = $get('date');
                        
                        // Если дата не выбрана, возвращаем пустую коллекцию
                        if (!$date) {
                            return Collection::empty();
                        }

                        return Audience::whereDoesntHave('bookings', function ($query) use ($date) {
                            $query->whereDate('date', $date);
                        })->pluck('number', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->dehydrated(),

                Select::make('teacher_id')
                    ->label('Преподаватель')
                    ->options(function (callable $get) {
                        $date = $get('date');
                        $audienceId = $get('audience_id');
                        
                        // Если дата не выбрана, возвращаем пустую коллекцию
                        if (!$date) {
                            return Collection::empty();
                        }

                        $query = Teacher::whereDoesntHave('bookings', function ($query) use ($date) {
                            $query->whereDate('date', $date);
                        });

                        // Если выбрана аудитория, исключаем преподавателей, которые уже заняты в этой аудитории
                        if ($audienceId) {
                            $query->whereDoesntHave('bookings', function ($query) use ($date, $audienceId) {
                                $query->whereDate('date', $date)
                                      ->where('audience_id', $audienceId);
                            });
                        }

                        return $query->pluck('fio', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->dehydrated(),

                Select::make('status')
                    ->label('Статус')
                    ->options([
                        'active' => 'Активно',
                        'cancelled' => 'Отменено',
                        'completed' => 'Завершено',
                    ])
                    ->default('active')
                    ->required()
                    ->dehydrated(),

                Textarea::make('notes')
                    ->label('Примечание')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Дата')
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
            ->defaultSort('date', 'desc')
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