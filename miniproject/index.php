<?php
require_once 'Buku.php';

$bukuObj = new Buku();
$error = null;
$success = null;

$editData = [
    'id' => '',
    'judul' => '',
    'penulis' => '',
    'tahun' => '',
    'stok' => ''
];
$isEdit = false;

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            if ($_POST['action'] === 'store') {
                $bukuObj->create($_POST);
                $success = "Buku berhasil ditambahkan!";
            } elseif ($_POST['action'] === 'update') {
                $bukuObj->update($_POST['id'], $_POST);
                $success = "Buku berhasil diperbarui!";
            } elseif ($_POST['action'] === 'delete') {
                $bukuObj->delete($_POST['id']);
                $success = "Buku berhasil dihapus!";
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

if (isset($_GET['edit'])) {
    $found = $bukuObj->find($_GET['edit']);
    if ($found) {
        $editData = $found;
        $isEdit = true;
    }
}

$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$daftarBuku = $bukuObj->all($keyword);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Mini Proyek Perpustakaan</title>
</head>
<body>

    <h2>DATA BUKU PERPUSTAKAAN</h2>

    <?php if ($error): ?>
        <p style="color: red;"><b><?= htmlspecialchars($error) ?></b></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color: green;"><b><?= htmlspecialchars($success) ?></b></p>
    <?php endif; ?>

    <h3><?= $isEdit ? "EDIT BUKU (ID: " . $editData['id'] . ")" : "TAMBAH BUKU BARU" ?></h3>
    <form action="index.php" method="POST">
        <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'store' ?>">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <?php endif; ?>

        <table>
            <tr>
                <td>Judul Buku</td>
                <td>:</td>
                <td><input type="text" name="judul" value="<?= htmlspecialchars($editData['judul']) ?>" required></td>
            </tr>
            <tr>
                <td>Penulis</td>
                <td>:</td>
                <td><input type="text" name="penulis" value="<?= htmlspecialchars($editData['penulis']) ?>"></td>
            </tr>
            <tr>
                <td>Tahun Terbit</td>
                <td>:</td>
                <td><input type="number" name="tahun" value="<?= htmlspecialchars($editData['tahun']) ?>"></td>
            </tr>
            <tr>
                <td>Stok</td>
                <td>:</td>
                <td><input type="number" name="stok" value="<?= htmlspecialchars($editData['stok']) ?>" required></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="submit" value="<?= $isEdit ? 'Simpan Perubahan' : 'Tambah Buku' ?>">
                    <?php if ($isEdit): ?>
                        <a href="index.php">Batal</a>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </form>

    <hr>

    <h3>DAFTAR BUKU</h3>
    <form action="index.php" method="GET">
        <label>Cari Judul:</label>
        <input type="text" name="search" placeholder="Masukkan judul..." value="<?= htmlspecialchars($keyword) ?>">
        <input type="submit" value="Cari">
        <?php if (!empty($keyword)): ?>
            <a href="index.php">Reset</a>
        <?php endif; ?>
    </form>
    <br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($daftarBuku) > 0): ?>
                <?php $no = 1; foreach ($daftarBuku as $buku): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($buku['judul']) ?></td>
                    <td><?= htmlspecialchars($buku['penulis']) ?></td>
                    <td><?= htmlspecialchars($buku['tahun']) ?></td>
                    <td><?= htmlspecialchars($buku['stok']) ?></td>
                    <td>
                        <a href="index.php?edit=<?= $buku['id'] ?>">Edit</a> | 
                        <form action="index.php" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data buku ini?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $buku['id'] ?>">
                            <input type="submit" value="Hapus" style="cursor:pointer;">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" align="center">Tidak ada data buku ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
