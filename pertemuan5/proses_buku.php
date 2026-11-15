<?php
require_once 'Buku.php';

$judul    = trim($_POST['judul'] ?? '');
$penulis  = trim($_POST['penulis'] ?? '');
$tahun    = trim($_POST['tahun'] ?? '');
$kategori = $_POST['kategori'] ?? '';
$fileCover = $_FILES['cover'] ?? null;

$e = [];

// Instansiasi class Buku
$buku = new Buku($judul, $penulis, $kategori);

// Validasi input
if ($judul === '') {
    $e[] = 'Judul buku wajib diisi.';
}
if ($penulis === '') {
    $e[] = 'Nama penulis wajib diisi.';
}
if (!$buku->setTahun($tahun)) {
    $e[] = 'Tahun terbit tidak valid (harus antara 1500 - tahun sekarang).';
}
if (!in_array($kategori, ['Teknologi', 'Novel', 'Bisnis', 'Sains'], true)) {
    $e[] = 'Kategori tidak valid.';
}

// Validasi Upload File Cover
$pathCover = '';
if ($fileCover && $fileCover['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($fileCover['name'], PATHINFO_EXTENSION));
    // Validasi ekstensi (whitelist jpg, png) dan ukuran (maksimal 2MB / 2097152 bytes)
    if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
        $e[] = 'Ekstensi file cover harus JPG atau PNG.';
    } elseif ($fileCover['size'] > 2097152) {
        $e[] = 'Ukuran file cover maksimal 2MB.';
    } else {
        // Buat folder uploads jika belum ada
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        // Beri nama file unik untuk mencegah konflik
        $namaFileBaru = time() . '_' . uniqid() . '.' . $ext;
        $pathCover = 'uploads/' . $namaFileBaru;
        move_uploaded_file($fileCover['tmp_name'], $pathCover);
    }
} else {
    $e[] = 'Cover buku wajib diunggah.';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Proses Buku</title>
</head>
<body>
    <h2>Hasil Input Data Buku</h2>
    <?php
    if (!empty($e)) {
        echo "<div style='color: red;'>";
        foreach ($e as $pesan) {
            echo htmlspecialchars($pesan) . "<br>";
        }
        echo "</div><br>";
        echo '<a href="form_buku.php">Kembali ke Form</a>';
    } else {
        // Output aman dengan htmlspecialchars
        echo "Judul Buku : " . htmlspecialchars($buku->judul) . "<br>";
        echo "Penulis    : " . htmlspecialchars($buku->penulis) . "<br>";
        echo "Tahun      : " . htmlspecialchars($buku->getTahun()) . "<br>";
        echo "Kategori   : " . htmlspecialchars($buku->kategori) . "<br>";
        echo "Cover      :<br>";
        echo "<img src='" . htmlspecialchars($pathCover) . "' width='150' alt='Cover Buku'><br><br>";
        echo '<a href="form_buku.php">Input Buku Lain</a> | <a href="cari_buku.php">Cari Buku</a>';
    }
    ?>
</body>
</html>