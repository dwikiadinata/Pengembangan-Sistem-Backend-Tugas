<?php
require_once __DIR__ . '/database.php';

class Buku extends Database
{
    public function all($search = '')
    {
        $stmt = $this->conn->prepare(
            'SELECT id, judul, penulis, stok FROM buku WHERE judul LIKE ? OR penulis LIKE ? ORDER BY id DESC'
        );
        if (!$stmt) {
            throw new RuntimeException('Gagal menyiapkan pencarian buku: ' . $this->conn->error);
        }

        $pola = '%' . $search . '%';
        $stmt->bind_param('ss', $pola, $pola);
        $stmt->execute();
        $result = $stmt->get_result();
        $buku = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $buku;
    }

    public function find($id)
    {
        $id = $this->validasiId($id);
        $stmt = $this->conn->prepare('SELECT id, judul, penulis, stok FROM buku WHERE id = ?');
        if (!$stmt) {
            throw new RuntimeException('Gagal menyiapkan pencarian buku: ' . $this->conn->error);
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $buku = $result->fetch_assoc();
        $stmt->close();

        return $buku ?: null;
    }

    public function create($judul, $penulis, $stok)
    {
        $data = $this->validasiData($judul, $penulis, $stok);
        $tahun = (int) date('Y');
        $stmt = $this->conn->prepare('INSERT INTO buku (judul, penulis, tahun, stok) VALUES (?, ?, ?, ?)');
        if (!$stmt) {
            throw new RuntimeException('Gagal menyiapkan penyimpanan buku: ' . $this->conn->error);
        }

        $stmt->bind_param('ssii', $data['judul'], $data['penulis'], $tahun, $data['stok']);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();

        return $id;
    }

    public function update($id, $judul, $penulis, $stok)
    {
        $id = $this->validasiId($id);
        $data = $this->validasiData($judul, $penulis, $stok);
        $stmt = $this->conn->prepare('UPDATE buku SET judul = ?, penulis = ?, stok = ? WHERE id = ?');
        if (!$stmt) {
            throw new RuntimeException('Gagal menyiapkan perubahan buku: ' . $this->conn->error);
        }

        $stmt->bind_param('ssii', $data['judul'], $data['penulis'], $data['stok'], $id);
        $stmt->execute();
        $stmt->close();

        return true;
    }

    public function delete($id)
    {
        $id = $this->validasiId($id);
        $stmt = $this->conn->prepare('DELETE FROM buku WHERE id = ?');
        if (!$stmt) {
            throw new RuntimeException('Gagal menyiapkan penghapusan buku: ' . $this->conn->error);
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        return true;
    }

    private function validasiId($id)
    {
        if (!is_string($id) && !is_int($id)) {
            throw new InvalidArgumentException('ID buku tidak valid.');
        }

        $idValid = filter_var($id, FILTER_VALIDATE_INT);
        if ($idValid === false || $idValid < 1) {
            throw new InvalidArgumentException('ID buku tidak valid.');
        }

        return $idValid;
    }

    private function validasiData($judul, $penulis, $stok)
    {
        if (!is_string($judul)) {
            throw new InvalidArgumentException('Judul buku wajib diisi.');
        }

        $judul = trim($judul);
        if ($judul === '') {
            throw new InvalidArgumentException('Judul buku wajib diisi.');
        }

        if (!is_string($penulis)) {
            throw new InvalidArgumentException('Nama penulis wajib diisi.');
        }

        $penulis = trim($penulis);
        if ($penulis === '') {
            throw new InvalidArgumentException('Nama penulis wajib diisi.');
        }

        if (!is_string($stok) && !is_int($stok)) {
            throw new InvalidArgumentException('Stok harus berupa bilangan bulat nol atau lebih.');
        }

        $stokValid = filter_var($stok, FILTER_VALIDATE_INT);
        if ($stokValid === false || $stokValid < 0) {
            throw new InvalidArgumentException('Stok harus berupa bilangan bulat nol atau lebih.');
        }

        return ['judul' => $judul, 'penulis' => $penulis, 'stok' => $stokValid];
    }
}
