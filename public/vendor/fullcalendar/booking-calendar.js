function initBookingCalendar() {
    const calendarEl = document.getElementById('booking-calendar');
    
    // Если элемент не найден или календарь уже инициализирован — выходим
    if (!calendarEl || calendarEl.dataset.calendarInitialized) {
        return false;
    }
    
    // Если FullCalendar не загрузился — выходим
    if (typeof FullCalendar === 'undefined') {
        console.error('FullCalendar не загрузился');
        return false;
    }
    
    // Получаем данные
    let bookings = [];
    try {
        bookings = JSON.parse(calendarEl.dataset.bookings || '[]');
    } catch (e) {
        console.error('Ошибка при разборе данных:', e);
        bookings = [];
    }
    
    // Создаём календарь
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'ru',
        firstDay: 1,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },
        buttonText: {
            today: 'Сегодня',
            month: 'Месяц',
            week: 'Неделя',
            day: 'День'
        },
        height: 'auto',
        expandRows: true,
        events: bookings,
        
        eventDidMount: function(info) {
            const event = info.event;
            info.el.title = `${event.extendedProps.teacher} - ${event.extendedProps.audience}`;
            info.el.style.backgroundColor = event.backgroundColor || '#6b7280';
            info.el.style.borderColor = event.borderColor || '#4b5563';
            info.el.style.color = '#ffffff';
            info.el.style.cursor = 'pointer';
            info.el.style.padding = '4px 8px';
            info.el.style.borderRadius = '4px';
            info.el.style.fontSize = '12px';
        },
        
        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url;
            }
        },
        
        dateClick: function(info) {
            const url = '/admin/bookings/create?date=' + info.dateStr;
            window.location.href = url;
        }
    });
    
    calendar.render();
    calendarEl.dataset.calendarInitialized = 'true';
    console.log('Календарь отрисован!');
    return true;
}

// Используем MutationObserver для отслеживания появления элемента
function waitForCalendar() {
    // Проверяем сразу
    if (initBookingCalendar()) {
        return;
    }
    
    // Наблюдаем за DOM
    const observer = new MutationObserver(function() {
        if (initBookingCalendar()) {
            observer.disconnect(); // Останавливаем наблюдатель
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}

// Инициализация
document.addEventListener('DOMContentLoaded', function() {
    waitForCalendar();
});

document.addEventListener('livewire:navigated', function() {
    waitForCalendar();
});