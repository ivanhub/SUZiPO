#!/bin/bash

PROJECT_DIR="$(pwd)"
HOST="127.0.0.1"
PORT="8000"
SITE_URL="http://$HOST:$PORT"
SERVER_PID=""

cd "$PROJECT_DIR" || { echo "❌ Директория $PROJECT_DIR не найдена"; exit 1; }

# Функция очистки: остановит фоновый сервер при завершении скрипта, если мы его запускали
cleanup() {
    if [ -n "$SERVER_PID" ]; then
        kill "$SERVER_PID" 2>/dev/null
    fi
}
trap cleanup EXIT

echo "=== 1. Проверка файлов на невидимый UTF-8 BOM ==="
BOM_FILES=$(grep -rl $'\xEF\xBB\xBF' app routes resources/views 2>/dev/null)
if [ -n "$BOM_FILES" ]; then
    echo "❌ Найдена кодировка UTF-8 with BOM в файлах:"
    echo "$BOM_FILES"
    exit 1
fi
echo "✅ Файлов с BOM не найдено"

echo "=== 2. Проверка синтаксиса всех PHP-файлов ==="
find app routes resources/views -name "*.php" -print0 | xargs -0 -n1 -P4 php -l > /dev/null
if [ $? -ne 0 ]; then
    echo "❌ Ошибка синтаксиса PHP!"
    exit 1
fi
echo "✅ Синтаксис PHP в порядке"

echo "=== 3. Проверка компиляции Blade-шаблонов ==="
php artisan view:cache > /dev/null
if [ $? -ne 0 ]; then
    echo "❌ Ошибка в Blade-шаблонах!"
    exit 1
fi
php artisan view:clear > /dev/null
echo "✅ Все шаблоны компилируются без ошибок"

echo "=== 4. Запуск автотестов Laravel ==="
if [ -f "vendor/bin/phpunit" ]; then
    php artisan test
    if [ $? -ne 0 ]; then
        echo "❌ Автотесты провалены!"
        exit 1
    fi
else
    echo "⚠️ PHPUnit не установлен в vendor, пропускаем шаг"
fi

echo "=== 5. Проверка доступности страниц ==="
# Проверяем, запущен ли уже сервер. Если нет — запускаем временно в фоне
if ! curl -s "$SITE_URL" >/dev/null 2>&1; then
    echo "ℹ️ Сервер не запущен, поднимаем временный php artisan serve..."
    php artisan serve --host="$HOST" --port="$PORT" >/dev/null 2>&1 &
    SERVER_PID=$!
    sleep 2
fi

PAGES=("/" "/login") # Добавьте сюда основные маршруты вашего проекта

for PAGE in "${PAGES[@]}"; do
    STATUS=$(curl -s -o /dev/null -w "%{http_code}" -L "$SITE_URL$PAGE")
    if [ "$STATUS" -eq 200 ]; then
        echo "✅ $PAGE -> HTTP 200 OK"
    else
        echo "❌ $PAGE -> Ошибка HTTP $STATUS"
        exit 1
    fi
done

echo "🎉 Все проверки успешно пройдены!"
