<?php
// find_bom.php в корне проекта Laravel

$folder = __DIR__;
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));
$bomFiles = [];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getRealPath();
        
        // Пропускаем папку vendor, там файлы оригинальные
        if (strpos($path, 'vendor') !== false) {
            continue;
        }

        $handle = fopen($path, 'r');
        if ($handle) {
            $bom = fread($handle, 3);
            fclose($handle);

            // Проверяем на UTF-8 BOM (\xEF\xBB\xBF)
            if ($bom === "\xEF\xBB\xBF") {
                $bomFiles[] = str_replace($folder . DIRECTORY_SEPARATOR, '', $path);
            }
        }
    }
}

if (empty($bomFiles)) {
    echo "Отлично! Файлов с BOM-маркером не найдено.\n";
} else {
    echo "⚠️ НАЙДЕНЫ ФАЙЛЫ С BOM:\n";
    foreach ($bomFiles as $f) {
        echo " - " . $f . "\n";
    }
}
