<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Filter Mahasiswa</title>
</head>
<body>
    <h2>Filter Mahasiswa Berdasarkan Predikat</h2>
    <form action="hasil_cari.php" method="get">
        <label for="predikat">Pilih Predikat:</label>
        <select name="predikat" id="predikat" required>
            <option value="">-- Pilih --</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select>
        <button type="submit">Filter</button>
    </form>
</body>
</html>
