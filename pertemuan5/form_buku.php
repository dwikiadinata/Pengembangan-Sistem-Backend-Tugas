<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Input Buku</title>
</head>
<body>
    <h2>Form Input Data Buku (POST)</h2>
    <form action="proses_buku.php" method="post" enctype="multipart/form-data">
        <div>
            <label>Judul Buku:</label><br>
            <input type="text" name="judul" required>
        </div><br>
        <div>
            <label>Penulis:</label><br>
            <input type="text" name="penulis" required>
        </div><br>
        <div>
            <label>Tahun Terbit:</label><br>
            <input type="number" name="tahun" required>
        </div><br>
        <div>
            <label>Kategori:</label><br>
            <select name="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Teknologi">Teknologi</option>
                <option value="Novel">Novel</option>
                <option value="Bisnis">Bisnis</option>
                <option value="Sains">Sains</option>
            </select>
        </div><br>
        <div>
            <label>Cover Buku (JPG/PNG, Max 2MB):</label><br>
            <input type="file" name="cover" required>
        </div><br>
        <button type="submit">Simpan Buku</button>
    </form>
    <br>
    <a href="cari_buku.php">Cari Buku Berdasarkan Kategori (GET)</a>
</body>
</html>