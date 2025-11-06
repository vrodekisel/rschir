<?php

$commands = [
    "whoami" => "Текущий пользователь",
    "id" => "Информация об ID пользователя",
    "ls -l /var/www/html" => "Файлы проекта",
    "ps aux" => "Список запущенных процессов",
    "uptime" => "Время работы системы",
    "df -h" => "Использование дисков",
    "free -h" => "Использование памяти"
];

function runCommand($cmd) {
    $output = shell_exec($cmd . " 2>&1");
    return htmlspecialchars($output ?: "Ошибка или пустой вывод");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Информация о сервере</title>
    <style>
        :root {
            --bg: #f5f7fa;
            --text: #222;
            --card-bg: #fff;
            --accent: #5befaaff;
            --border: #dde3ec;
            --muted: #555;
            --code-bg: #f0f3f9;
        }

        body {
            font-family: "Segoe UI", Roboto, system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 0;
        }

        header {
            background: var(--accent);
            color: #fff;
            padding: 1.2rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        header h1 {
            margin: 0;
            font-size: 1.6rem;
        }

        main {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            background: #eef3fc;
            padding: 0.8rem 1.2rem;
            border-bottom: 1px solid var(--border);
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.1rem;
            color: var(--accent);
        }

        .command {
            color: var(--muted);
            font-size: 0.9rem;
        }

        pre {
            background: var(--code-bg);
            margin: 0;
            padding: 1rem;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: "JetBrains Mono", Consolas, monospace;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        footer {
            text-align: center;
            color: var(--muted);
            padding: 1rem 0 2rem;
            font-size: 0.9rem;
        }

        footer span {
            color: var(--accent);
            font-weight: 500;
        }
    </style>
</head>
<body>
    <header>
        <h1>Информационно-административная страница сервера</h1>
    </header>

    <main>
        <?php foreach ($commands as $cmd => $title): ?>
            <div class="card">
                <div class="card-header">
                    <h2><?php echo $title; ?></h2>
                    <div class="command">$ <?php echo $cmd; ?></div>
                </div>
                <pre><?php echo runCommand($cmd); ?></pre>
            </div>
        <?php endforeach; ?>
    </main>

    <footer>
        <p>© <span>Практическая работа №2</span> | PHP 8.2 + Docker + Apache</p>
    </footer>
</body>
</html>