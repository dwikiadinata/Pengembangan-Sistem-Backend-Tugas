<?php
require_once '../pertemuan3/mahasiswa.php';

$nama  = trim($_POST['nama'] ?? '');
$nim   = trim($_POST['nim'] ?? '');
$tugas = trim($_POST['tugas'] ?? '');
$nilai = trim($_POST['nilai'] ?? '');
$prodi = $_POST['prodi'] ?? '';

$e = [];

if ($nama === '') {
    $e[] = 'Nama wajib diisi.';
}

if (!ctype_digit($nim)) {
    $e[] = 'NIM harus berupa angka.';
}

if (!is_numeric($nilai) || (float)$nilai < 0 || (float)$nilai > 100) {
    $e[] = "Nilai " . htmlspecialchars($nilai) . " tidak valid!";
}
// Validasi pilihan prodi
if (!in_array($prodi, ['SI', 'TI'], true)) {
    $e[] = 'Prodi tidak valid.';
}

$nilaiRataRata = 0;
$predikat = '';
if (empty($e)) {
    $mhs = new Mahasiswa();

    if ($nilai >= 85) $predikat = 'A';
    elseif ($nilai >= 70) $predikat = 'B';
    elseif ($nilai >= 55) $predikat = 'C';
    elseif ($nilai >= 40) $predikat = 'D';
    else $predikat = 'E';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Proses Latihan 2</title>
</head>
<body>
    <h2>Hasil Pemrosesan Data Mahasiswa</h2>
    <?php
    if (!empty($e)) {
        echo "<div style='color: red;'>";
        foreach ($e as $pesan) {
            echo htmlspecialchars($pesan) . "<br>";
        }   
        echo "</div>";
    } else {
        echo "Nama: " . htmlspecialchars($nama) . "<br>";
        echo "NIM: " . htmlspecialchars($nim) . "<br>";
        echo "Prodi: " . htmlspecialchars($prodi) . "<br>";
        echo "Nilai: " . htmlspecialchars($nilai) . "<br>";
        echo "Predikat: " . htmlspecialchars($predikat) . "<br>";
    }
    ?>
    <br>
    <a href="form_mahasiswa.php">Kembali ke Form</a>
</body>
</html>