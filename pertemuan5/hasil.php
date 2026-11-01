<?php
require_once '../pertemuan3/mahasiswa.php';

$m1 = new Mahasiswa();
$m1->nama = 'Andi';
$m1->nim = '22030101';
$m1->setNilai(88);

$m2 = new Mahasiswa();
$m2->nama = 'Budi';
$m2->nim = '22030102';
$m2->setNilai(72);

$m3 = new Mahasiswa();
$m3->nama = 'Cici';
$m3->nim = '22030103';
$m3->setNilai(56.25);

$daftarMahasiswa = [$m1, $m2, $m3];

$predikatDipilih = $_GET['predikat'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 1 - Filter dengan GET</title>
</head>
<body>
    <h2>Latihan 1: Filter Mahasiswa Berdasarkan Predikat (GET)</h2>

    <form action="" method="get">
        <label for="predikat">Pilih Predikat:</label>
        <select name="predikat" id="predikat">
            <option value="">-- Semua Predikat --</option>
            <option value="A" <?= $predikatDipilih === 'A' ? 'selected' : '' ?>>A</option>
            <option value="B" <?= $predikatDipilih === 'B' ? 'selected' : '' ?>>B</option>
            <option value="C" <?= $predikatDipilih === 'C' ? 'selected' : '' ?>>C</option>
            <option value="D" <?= $predikatDipilih === 'D' ? 'selected' : '' ?>>D</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <hr>

    <h3>Hasil Filter:</h3>
    <?php
    if ($predikatDipilih !== '') {
        $ditemukan = false;

        foreach ($daftarMahasiswa as $m) {
            if ($m->getPredikat() === $predikatDipilih) {
                echo htmlspecialchars($m->nama) . " (" . htmlspecialchars((string)$m->getNilai()) . ") - " . htmlspecialchars($m->getPredikat()) . "<br>";
                $ditemukan = true;
            }
        }

        if (!$ditemukan) {
            echo "Data tidak ditemukan";
        }
    }
    ?>
</body>
</html>