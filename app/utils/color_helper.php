<?php
// Đây là nơi chứa logic xử lý màu sắc
function getColorName($hexCode) {
    $colors = [
        '#FFFFFF' => 'Trắng',
        '#000000' => 'Đen',
        '#FF0000' => 'Đỏ',
        '#0000FF' => 'Xanh Dương',
        // ...
    ];
    return isset($colors[strtoupper($hexCode)]) ? $colors[strtoupper($hexCode)] : $hexCode;
}
?>