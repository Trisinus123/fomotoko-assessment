<?php

/**
 * Task 2: Hidden Item Game
 *
 * Navigasi dari posisi X:
 *   1. Up/North    A steps
 *   2. Right/East  B steps
 *   3. Down/South  C steps
 *
 * Usage: php hidden_item.php
 */

// Grid layout sesuai soal
$grid = [
    ['#','#','#','#','#','#','#','#'],
    ['#','.','.','.','.','.','.',  '#'],
    ['#','.','#','#','#','.','.','#'],
    ['#','.','.','.','#','.','#','#'],
    ['#','X','#','.','.','.','.','#'],
    ['#','#','#','#','#','#','#','#'],
];

// Temukan posisi awal X
$startRow = 0;
$startCol = 0;
foreach ($grid as $r => $row) {
    foreach ($row as $c => $cell) {
        if ($cell === 'X') {
            $startRow = $r;
            $startCol = $c;
        }
    }
}

// Input dari user
echo "Enter A (steps North): ";
$a = (int) trim(fgets(STDIN));

echo "Enter B (steps East): ";
$b = (int) trim(fgets(STDIN));

echo "Enter C (steps South): ";
$c = (int) trim(fgets(STDIN));

/**
 * Navigasi dari posisi (row, col) ke arah tertentu sejumlah $steps.
 * Return posisi akhir [row, col], atau null jika terblokir obstacle.
 */
function navigate(array $grid, int $row, int $col, string $direction, int $steps): ?array
{
    $dr = ['N' => -1, 'E' => 0, 'S' => 1];
    $dc = ['N' => 0,  'E' => 1, 'S' => 0];

    for ($i = 0; $i < $steps; $i++) {
        $newRow = $row + $dr[$direction];
        $newCol = $col + $dc[$direction];

        // Cek batas grid atau obstacle
        if (!isset($grid[$newRow][$newCol]) || $grid[$newRow][$newCol] === '#') {
            return null; // terblokir, path mati
        }

        $row = $newRow;
        $col = $newCol;
    }

    return [$row, $col]; // posisi akhir setelah $steps langkah
}

// Navigasi: North → East → South
$probableCoords = [];

$nPos = navigate($grid, $startRow, $startCol, 'N', $a);

if ($nPos !== null) {
    $ePos = navigate($grid, $nPos[0], $nPos[1], 'E', $b);

    if ($ePos !== null) {
        $sPos = navigate($grid, $ePos[0], $ePos[1], 'S', $c);

        if ($sPos !== null && $grid[$sPos[0]][$sPos[1]] === '.') {
            $probableCoords[] = $sPos;
        }
    }
}

// Tampilkan grid awal
echo "\nGrid:\n";
foreach ($grid as $row) {
    echo implode('', $row) . "\n";
}

// Tampilkan probable coordinates
echo "\nProbable coordinates (row, col):\n";
if (empty($probableCoords)) {
    echo "No probable coordinates found.\n";
} else {
    foreach ($probableCoords as $coord) {
        echo "  -> row=" . $coord[0] . ", col=" . $coord[1] . "\n";
    }
}

// Bonus: tampilkan grid dengan tanda $
$markedGrid = $grid;
foreach ($probableCoords as $coord) {
    $markedGrid[$coord[0]][$coord[1]] = '$';
}

echo "\nGrid with probable locations (\$):\n";
foreach ($markedGrid as $row) {
    echo implode('', $row) . "\n";
}

echo "\nLegend: # obstacle  . clear path  X start  \$ probable item\n";