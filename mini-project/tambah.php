<?php
include 'koneksi.php';

$error = '';
$judul = '';
$penulis = '';
$tahun = '';
$stok = '';

if (isset($_POST['simpan'])) {
    $judul   = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $tahun   = $_POST['tahun'];
    $stok    = $_POST['stok'];

    if ($judul === '') {
        $error = "Error: Judul buku tidak boleh kosong!";
    } elseif ($stok === '' || !is_numeric($stok) || $stok < 0) {
        $error = "Error: Stok tidak valid!";
    } else {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO buku (judul, penulis, tahun, stok) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssii", $judul, $penulis, $tahun, $stok);
        mysqli_stmt_execute($stmt);

        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku</title>
</head>
<body>
    <h2>TAMBAH DATA BUKU</h2>
    <a href="index.php">&laquo; Kembali</a>
    <br><br>

    <?php if ($error !== ''): ?>
        <p style="color: red;"><b><?= $error; ?></b></p>
    <?php endif; ?>

    <form method="post" action="">
        <table>
            <tr>
                <td>Judul Buku</td>
                <td><input type="text" name="judul" value="<?= htmlspecialchars($judul); ?>"></td>
            </tr>
            <tr>
                <td>Penulis</td>
                <td><input type="text" name="penulis" value="<?= htmlspecialchars($penulis); ?>" required></td>
            </tr>
            <tr>
                <td>Tahun Terbit</td>
                <td><input type="number" name="tahun" value="<?= htmlspecialchars($tahun); ?>" required></td>
            </tr>
            <tr>
                <td>Stok</td>
                <td><input type="number" name="stok" value="<?= htmlspecialchars($stok); ?>" required></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="simpan" value="SIMPAN"></td>
            </tr>
        </table>
    </form>
</body>
</html>
