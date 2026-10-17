<?php
$nim = "250030086";
$nama = "Ida Bagus Kade Dwiki Adinata";
$kodejurusan = "SI";
$angkatan = 2025;
$umur = 19;
$gender = "Laki-laki";
$hobi = array("Coding", "Desain Grafis");

echo "NIM : $nim" . "<br>";
echo "Nama : $nama" . "<br>";
echo "Kode Jurusan: $kodejurusan" . "<br>";
echo "Angkatan: $angkatan" . "<br>";
echo "Umur : $umur" . "<br>";
echo "Gender: $gender" . "<br>";
echo "Hobi: " . implode (",", $hobi); 
?>