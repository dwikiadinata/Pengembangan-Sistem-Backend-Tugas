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
$m3->setNilai(85);

$daftarMahasiswa = [$m1, $m2, $m3];
$predikatDipilih = $_GET['predikat'] ?? '';
$predikatValid = is_string($predikatDipilih)
    && in_array($predikatDipilih, ['A', 'B', 'C', 'D'], true);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Filter Mahasiswa</title>
</head>
<body>
    <h2>Hasil Filter Mahasiswa</h2>
    <?php
    if (!$predikatValid) {
        echo '<p>Pilih predikat A, B, C, atau D dari <a href="cari.php">form pencarian</a>.</p>';
    } else {
        $ditemukan = false;

        foreach ($daftarMahasiswa as $m) {
            if ($m->getPredikat() === $predikatDipilih) {
                echo htmlspecialchars($m->nama) . ' (' . htmlspecialchars((string) $m->getNilai())
                    . ') - ' . htmlspecialchars($m->getPredikat()) . '<br>';
                $ditemukan = true;
            }
        }

        if (!$ditemukan) {
            echo 'Tidak ada data';
        }
    }
    ?>
    <p><a href="cari.php">Kembali ke form pencarian</a></p>
</body>
</html>
