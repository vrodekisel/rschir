<?php

header('Content-Type: image/svg+xml; charset=utf-8');

function parse_decimal_packed($num_str) {
    if (!preg_match('/^\d{9}$/', $num_str)) return null;

    $S   = intval(substr($num_str, 0, 1));
    $CC  = intval(substr($num_str, 1, 2));
    $WWW = intval(substr($num_str, 3, 3));
    $HHH = intval(substr($num_str, 6, 3));

    return [$S, $CC, $WWW, $HHH];
}

function parse_bit_packed($num_int) {
    $shape  =  ($num_int      ) & 0b11;
    $color  =  ($num_int >>  2) & 0b1111;
    $width  =  ($num_int >>  6) & 0b1111111111;
    $height =  ($num_int >> 16) & 0b1111111111;
    return [$shape, $color, $width, $height];
}

$num_raw = filter_input(INPUT_GET, 'num', FILTER_UNSAFE_RAW);
if ($num_raw === null) {
    echo "<svg xmlns='http://www.w3.org/2000/svg' width='600' height='120'><text x='10' y='60'>Pass ?num=...</text></svg>";
    exit;
}

$num_str = trim($num_raw);
if (!preg_match('/^\d+$/', $num_str)) {
    echo "<svg xmlns='http://www.w3.org/2000/svg' width='600' height='120'><text x='10' y='60'>num must be integer digits</text></svg>";
    exit;
}

$shape = $color = $width = $height = null;

$dec = parse_decimal_packed($num_str);
if ($dec) {
    [$shape, $color, $width, $height] = $dec;
    $format = 'decimal-9';
} else {
    $num_int = intval($num_str);
    [$shape, $color, $width, $height] = parse_bit_packed($num_int);
    $format = 'bit-packed';
}

$shape  = max(0, min(3, (int)$shape));
$color  = max(0, min(15, (int)$color));
$width  = max(20, min(800, (int)$width ?: 200));
$height = max(20, min(800, (int)$height ?: 150));

$palette = [
    '#000000','#FF0000','#00FF00','#0000FF','#800080','#FFA500',
    '#00FFFF','#FFC0CB','#FFFF00','#008000','#808000','#008080',
    '#800000','#A52A2A','#808080','#FFFFFF',
];
$fill = $palette[$color];

$svgW = $width + 20;
$svgH = $height + 20;
$ox = 10; $oy = 10;

$shapeName = ['rect','circle','ellipse','triangle'][$shape];

echo "<svg xmlns='http://www.w3.org/2000/svg' width='{$svgW}' height='{$svgH}' viewBox='0 0 {$svgW} {$svgH}'>";
echo "<rect x='0' y='0' width='{$svgW}' height='{$svgH}' fill='white'/>";

switch ($shapeName) {
    case 'rect':
        echo "<rect x='{$ox}' y='{$oy}' width='{$width}' height='{$height}' fill='{$fill}' stroke='black'/>";
        break;
    case 'circle':
        $r = floor(min($width, $height) / 2);
        $cx = $ox + $width/2;
        $cy = $oy + $height/2;
        echo "<circle cx='{$cx}' cy='{$cy}' r='{$r}' fill='{$fill}' stroke='black'/>";
        break;
    case 'ellipse':
        $rx = floor($width / 2);
        $ry = floor($height / 2);
        $cx = $ox + $rx;
        $cy = $oy + $ry;
        echo "<ellipse cx='{$cx}' cy='{$cy}' rx='{$rx}' ry='{$ry}' fill='{$fill}' stroke='black'/>";
        break;
    case 'triangle':
        $x1 = $ox + $width / 2;     $y1 = $oy;
        $x2 = $ox;                  $y2 = $oy + $height;
        $x3 = $ox + $width;         $y3 = $oy + $height;
        $points = "{$x1},{$y1} {$x2},{$y2} {$x3},{$y3}";
        echo "<polygon points='{$points}' fill='{$fill}' stroke='black'/>";
        break;
}

$label = htmlspecialchars("format={$format}, shape={$shapeName}, color={$color}, w={$width}, h={$height}, num={$num_str}");
echo "<text x='10' y='".($svgH-5)."' font-size='12' fill='#333'>{$label}</text>";
echo "</svg>";
