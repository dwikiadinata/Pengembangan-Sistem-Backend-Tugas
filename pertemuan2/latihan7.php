<?php

function hitungLuasLingkaran($jari){
    $phi = 3.14;
    $luas = $phi * $jari * $jari;
    return $luas;
}

$jari = 5;
$hasil = hitungLuasLingkaran($jari);

echo "Luas Lingkaran dengan jari-jari $jari adalah: ".$hasil;
?>