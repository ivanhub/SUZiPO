<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class BookingCalendar extends Widget
{
    protected string $view = 'filament.widgets.booking-calendar';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return true;
    }

    public function render(): View
    {
        return view('filament.widgets.booking-calendar', [
            'bookings' => $this->getBookings(),
        ]);
    }

    public function getBookings()
    {
        return Booking::with(['audience', 'teacher'])
            ->get()
            ->map(function ($booking) {
                $colors = [
                    'active' => '#6b7280',
                    'cancelled' => '#ef4444',
                    'completed' => '#10b981',
                ];

                return [
                    'id' => $booking->id,
                    'title' => $booking->audience->number . ' - ' . $booking->teacher->fio,
                    'start' => $booking->start_date->format('Y-m-d'),
                    'end' => $booking->end_date ? $booking->end_date->copy()->addDay()->format('Y-m-d') : null,
                    'backgroundColor' => $colors[$booking->status] ?? '#6b7280',
                    'borderColor' => $colors[$booking->status] ?? '#6b7280',
                    'textColor' => '#ffffff',
                    'audience' => $booking->audience->number,
                    'teacher' => $booking->teacher->fio,
                    'status' => $booking->status,
                    'url' => '/admin/bookings/' . $booking->id . '/edit',
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Получение статистики по бронированиям
     */
    public function getStats(): array
    {
        $total = Booking::count();
        $active = Booking::where('status', 'active')->count();
        $cancelled = Booking::where('status', 'cancelled')->count();
        $completed = Booking::where('status', 'completed')->count();

        return [
            'total' => $total,
            'active' => $active,
            'cancelled' => $cancelled,
            'completed' => $completed,
        ];
    }
}