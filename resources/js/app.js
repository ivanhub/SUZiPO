import './bootstrap'; 
import Alpine from 'alpinejs';
import flatpickr from "flatpickr";
// Импорт базовых стилей Flatpickr
import "flatpickr/dist/flatpickr.min.css";
// Импорт темы оформления (например, "material_blue", "dark", "airbnb" или удалите эту строку для дефолтной)
//import "flatpickr/dist/themes/material_blue.css"; 
import "flatpickr/dist/themes/material_orange.css"; 
// Импорт русской локализации
import { Russian } from "flatpickr/dist/l10n/ru.js";

window.Alpine = Alpine;
Alpine.start();

// Инициализация после загрузки DOM
document.addEventListener("DOMContentLoaded", () => {
  flatpickr(".date-input", {
    locale: Russian,       // Подключаем русский язык
    dateFormat: "d.m.Y",   // Формат даты: 25.10.2026
    allowInput: false,     // Запрет ручного ввода (защита от дд.мм.гггг)
    
    // Дополнительные полезные настройки по желанию:
    //minDate: "today",   // Запретить выбирать прошедшие даты
    //disableMobile: true // Принудительно показывать красивый календарь вместо системного на смартфонах
  });
});
