<?php
$nilai = 70; 

echo "Nilai Anda: $nilai <br>";

if ($nilai >= 90) {
    $grade = "A";
} elseif ($nilai >= 80 && $nilai <= 89) {
    $grade = "B";
} elseif ($nilai >= 70 && $nilai <= 79) {
    $grade = "C";
} elseif ($nilai >= 60 && $nilai <= 69) {
    $grade = "D";
} else {
    $grade = "E";
}

echo "Grade Anda: $grade";
?>