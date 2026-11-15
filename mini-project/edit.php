<?php
include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM buku WHERE id='$id'");
$buku = mysqli_fetch_array($data);

$error = '';
$judul   = $buku['judul'];
$penulis = $buku['penulis'];
$tahun   = $buku['tahun'];
$stok    = $buku['stok'];

if (isset($_POST['update'])) {
    $judul   = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $tahun   = $_POST['tahun'];
    $stok    = $_POST['stok'];

    if ($judul === '') {
        $error = "Error: Judul buku tidak boleh kosong!";
    } elseif ($stok === '' || !is_numeric($stok) || $stok < 0) {
        $error = "Error: Stok tidak boleh bernilai negatif!";
    } else {

        $stmt = mysqli_prepare($koneksi, "UPDATE buku SET judul=?, penulis=?, tahun=?, stok=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssiii", $judul, $penulis, $tahun, $stok, $id);
        mysqli_stmt_execute($stmt);

        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku</title>
</head>
<body>
    <h2>EDIT DATA BUKU</h2>
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
                <td><input type="submit" name="update" value="UPDATE"></td>
            </tr>
        </table>
    </form>
</body>
</html>
