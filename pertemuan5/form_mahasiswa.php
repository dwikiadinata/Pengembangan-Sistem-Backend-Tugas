<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Latihan 2 - Input POST</title>
</head>
<body>
    <h2>Form Input Nilai Mahasiswa</h2>
    <form action="proses_mhs.php" method="post">
        <div>
            <label>Nama:</label><br>
            <input type="text" name="nama" required>
        </div><br>
        <div>
            <label>NIM:</label><br>
            <input type="text" name="nim" required>
        </div><br>
        <div>
        </div>
            <label>Nilai:</label><br>
            <input type="number" name="nilai" step="any" required>
        </div><br>
        <div>
            <label>Prodi:</label><br>
            <select name="prodi" required>
                <option value="">-- Pilih Prodi --</option>
                <option value="SI">Sistem Informasi (SI)</option>
                <option value="TI">Teknologi Informasi (TI)</option>
            </select>
        </div><br>
        <button type="submit">Simpan & Hitung</button>
    </form>
</body>
</html>