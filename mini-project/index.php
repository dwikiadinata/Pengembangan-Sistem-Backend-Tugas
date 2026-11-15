<?php
include 'koneksi.php';

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';

if ($cari != '') {
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM buku WHERE judul LIKE ? ORDER BY id DESC");
    $param = "%" . $cari . "%";
    mysqli_stmt_bind_param($stmt, "s", $param);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
} else {
    $data = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistem Perpustakaan</title>
</head>
<body>
    <h2>DATA BUKU PERPUSTAKAAN</h2>
    <a href="tambah.php">+ TAMBAH BUKU</a>
    <br><br>

    <form method="get" action="index.php">
        <label>Cari Judul:</label>
        <input type="text" name="cari" value="<?= htmlspecialchars($cari); ?>" placeholder="Masukkan judul buku...">
        <input type="submit" value="Cari">
        <?php if ($cari != ''): ?>
            <a href="index.php">Reset</a>
        <?php endif; ?>
    </form>
    <br>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Penulis</th>
            <th>Tahun</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php 
        $no = 1;
        if (mysqli_num_rows($data) > 0) {
            while ($d = mysqli_fetch_array($data)) { 
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($d['judul']); ?></td>
            <td><?= htmlspecialchars($d['penulis']); ?></td>
            <td><?= htmlspecialchars($d['tahun']); ?></td>
            <td><?= htmlspecialchars($d['stok']); ?></td>
            <td>
                <a href="edit.php?id=<?= $d['id']; ?>">EDIT</a> |
                <a href="hapus.php?id=<?= $d['id']; ?>" onclick="return confirm('Yakin ingin menghapus data buku ini?')">HAPUS</a>
            </td>
        </tr>
        <?php 
            } 
        } else {
        ?>
        <tr>
            <td colspan="6" align="center">Data buku tidak ditemukan.</td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
