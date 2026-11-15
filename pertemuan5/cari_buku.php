<?php
require_once 'Buku.php';

// Simulasi data penyimpanan sementara (array object/data buku)
// Pada praktikum berikutnya (Pertemuan 6-7), data ini akan diambil dari database MySQL[cite: 30]
$b1 = new Buku("Pemrograman PHP", "Andi Prasetyo", "Teknologi");
$b1->setTahun(2023);
$b1->cover = "uploads/default1.jpg";

$b2 = new Buku("Laskar Pelangi", "Andrea Hirata", "Novel");
$b2->setTahun(2005);
$b2->cover = "uploads/default2.jpg";

$b3 = new Buku("Bisnis Era Digital", "Budi Santoso", "Bisnis");
$b3->setTahun(2022);
$b3->cover = "uploads/default3.jpg";

$daftarBuku = [$b1, $b2, $b3];
$kategoriCari = $_GET['kategori'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pencarian Buku Berdasarkan Kategori</title>
</head>
<body>
    <h2>Pencarian Buku (Metode GET)</h2>
    <!-- Form Pencarian menggunakan GET -->
    <form action="" method="get">
        <label>Pilih Kategori:</label>
        <select name="kategori">
            <option value="">-- Semua Kategori --</option>
            <option value="Teknologi" <?= $kategoriCari === 'Teknologi' ? 'selected' : '' ?>>Teknologi</option>
            <option value="Novel" <?= $kategoriCari === 'Novel' ? 'selected' : '' ?>>Novel</option>
            <option value="Bisnis" <?= $kategoriCari === 'Bisnis' ? 'selected' : '' ?>>Bisnis</option>
            <option value="Sains" <?= $kategoriCari === 'Sains' ? 'selected' : '' ?>>Sains</option>
        </select>
        <button type="submit">Cari</button>
    </form>

    <hr>

    <h3>Hasil Pencarian:</h3>
    <?php
    if (!empty($kategoriCari)) {
        $ditemukan = false;
        foreach ($daftarBuku as $b) {
            if ($b->kategori === $kategoriCari) {
                echo "<strong>Judul:</strong> " . htmlspecialchars($b->judul) . "<br>";
                echo "<strong>Penulis:</strong> " . htmlspecialchars($b->penulis) . "<br>";
                echo "<strong>Tahun:</strong> " . htmlspecialchars($b->getTahun()) . "<br>";
                echo "<strong>Kategori:</strong> " . htmlspecialchars($b->kategori) . "<hr>";
                $ditemukan = true;
            }
        }
        if (!$ditemukan) {
            echo "Tidak ada buku dalam kategori tersebut.";
        }
    } else {
        echo "Silakan pilih kategori terlebih dahulu.";
    }
    ?>
    <br>
    <a href="form_buku.php">Kembali ke Form Input Buku</a>
</body>
</html>