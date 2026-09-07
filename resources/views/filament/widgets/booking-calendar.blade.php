<x-filament-widgets::widget>
    <div class="p-4 bg-white rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-900 mb-4"></h2>
        <div 
            id="booking-calendar"
            data-bookings="{{ json_encode($bookings) }}"
        ></div>
    </div>

    <style>
        .fc-event {
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .fc-event:hover {
            opacity: 0.9;
            transform: scale(1.02);
            transition: all 0.2s ease;
        }
        
        .fc-daygrid-day {
            min-height: 100px;
        }
        
        .fc-day-today {
            background-color: #eef2ff !important;
        }
        
        .fc-event-tooltip {
            display: none;
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%) translateY(-100%);
            background: #1f2937;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            pointer-events: none;
        }
        
        .fc-event:hover .fc-event-tooltip {
            display: block;
        }
    </style>
</x-filament-widgets::widget>