<?php
// Скрипт для точечного удаления BOM из CourseController.php

$filePath = __DIR__ . '/app/Http/Controllers/Api/CourseController.php';

if (!file_exists($filePath)) {
    echo "❌ Файл не найден по пути: $filePath\n";
    exit(1);
}

$content = file_get_contents($filePath);

// Проверяем наличие UTF-8 BOM (\xEF\xBB\xBF) в самом начале файла
if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
    // Вырезаем первые 3 байта BOM
    $cleanContent = substr($content, 3);
    
    // Перезаписываем файл чистым контентом
    file_put_contents($filePath, $cleanContent);
    
    echo "✅ BOM успешно удален из файла CourseController.php!\n";
} else {
    echo "ℹ️ В файле CourseController.php маркера BOM не обнаружено.\n";
}
