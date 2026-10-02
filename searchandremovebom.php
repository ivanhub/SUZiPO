<?php
// find_bom.php с функцией авто-исправления

$folder = __DIR__;
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));
$fixedCount = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getRealPath();
        
        if (strpos($path, 'vendor') !== false) {
            continue;
        }

        $content = file_get_contents($path);
        
        // Проверяем наличие UTF-8 BOM
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            // Вырезаем первые 3 байта и перезаписываем файл
            $cleanContent = substr($content, 3);
            file_put_contents($path, $cleanContent);
            
            echo "✅ Исправлен файл: " . str_replace($folder . DIRECTORY_SEPARATOR, '', $path) . "\n";
            $fixedCount++;
        }
    }
}

echo "Очистка завершена. Исправлено файлов: {$fixedCount}\n";
