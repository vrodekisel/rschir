<?php
require_once __DIR__ . '/insertion_sort.php';

$error = null;
$inputArray = [];
$sortedArray = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['nums'])) {
    try {
        $inputArray = parseArrayFromQuery('nums');

        if ($inputArray === []) {
            $error = 'Массив пустой — введите хотя бы одно число.';
        } else {
            $sortedArray = insertionSort($inputArray);
        }
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сортировка вставками (вариант 2)</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            max-width: 700px;
            margin: 40px auto;
            background: #111;
            color: #eee;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
        }
        button {
            margin-top: 10px;
            padding: 8px 16px;
            cursor: pointer;
        }
        .error {
            color: #ff8080;
            margin-top: 10px;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #444;
            background: #1a1a1a;
        }
        code {
            font-family: "JetBrains Mono", monospace;
        }
    </style>
</head>
<body>
    <h1>Сервис сортировки вставками</h1>
    <p>Вариант 2. Массив передаётся как строка с числами, разделёнными запятыми.</p>

    <form method="get" action="sort.php">
        <label>
            Введите массив:
            <br>
            <input type="text" name="nums"
                   placeholder="Например: 5, 3, 10, 1, -2"
                   value="<?php echo isset($_GET['nums']) ? htmlspecialchars($_GET['nums']) : ''; ?>">
        </label>
        <br>
        <button type="submit">Отсортировать</button>
    </form>

    <?php if ($error !== null): ?>
        <div class="error">
            Ошибка: <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($sortedArray !== []): ?>
        <div class="result">
            <p><strong>Исходный массив:</strong></p>
            <p><code>[<?php echo implode(', ', $inputArray); ?>]</code></p>

            <p><strong>Отсортированный массив (вставками):</strong></p>
            <p><code>[<?php echo implode(', ', $sortedArray); ?>]</code></p>
        </div>
    <?php endif; ?>

    <hr>
    <p>
        Можно вызывать сервис и напрямую через адресную строку, например:
        <br>
        <code>?nums=5,3,10,1,-2</code>
    </p>
</body>
</html>
