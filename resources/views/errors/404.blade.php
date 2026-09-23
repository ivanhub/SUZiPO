<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Страница не найдена</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .error-page {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        .error-code {
            font-size: 72px;
            font-weight: bold;
            color: #ef4444;
            margin: 0;
        }
        .error-message {
            font-size: 18px;
            color: #374151;
            margin: 20px 0;
        }
        .error-button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 20px;
        }
        .error-button:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <h1 class="error-code">404</h1>
        <p class="error-message">{{ $message ?? 'Страница не найдена' }}</p>
        <a href="{{ route('dashboard') }}" class="error-button">На главную</a>
    </div>
</body>
</html>