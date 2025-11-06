<?php
function insertionSort(array $arr): array {
    $n = count($arr);

    for ($i = 1; $i < $n; $i++) {
        $key = $arr[$i];
        $j = $i - 1;
        while ($j >= 0 && $arr[$j] > $key) {
            $arr[$j + 1] = $arr[$j];
            $j--;
        }
        $arr[$j + 1] = $key;
    }
    return $arr;
}
function parseArrayFromQuery(string $paramName = 'nums'): array {
    if (!isset($_GET[$paramName]) || trim($_GET[$paramName]) === '') {
        return [];
    }
    $raw = $_GET[$paramName];
    $parts = explode(',', $raw);
    $result = [];
    foreach ($parts as $part) {
        $part = trim($part);
        if ($part === '') {
            continue;
        }
        if (!is_numeric($part)) {
            throw new InvalidArgumentException("Элемент '$part' не является числом");
        }
        $result[] = (int)$part;
    }
    return $result;
}
