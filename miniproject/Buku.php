<?php
require_once __DIR__ . '/database.php';

class Buku extends Database {

    public function all($keyword = "") {
        $keyword = trim($keyword);
        if ($keyword !== "") {
            $stmt = $this->conn->prepare("SELECT * FROM buku WHERE judul LIKE ? OR penulis LIKE ? ORDER BY id DESC");
            $param = "%" . $keyword . "%";
            $stmt->bind_param("ss", $param, $param);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $data;
        } else {
            $result = $this->conn->query("SELECT * FROM buku ORDER BY id DESC");
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM buku WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data;
    }

    public function create($data) {
        if (!isset($data['judul']) || empty(trim($data['judul']))) {
            throw new Exception("Error: Judul buku tidak boleh kosong!");
        }
        if (!isset($data['stok']) || $data['stok'] < 0) {
            throw new Exception("Error: Stok buku tidak boleh negatif!");
        }

        $judul   = trim($data['judul']);
        $penulis = trim($data['penulis'] ?? '');
        $stok    = (int) $data['stok'];
        $tahun   = (int) ($data['tahun'] ?? date('Y'));

        $stmt = $this->conn->prepare("INSERT INTO buku (judul, penulis, tahun, stok) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $judul, $penulis, $tahun, $stok);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function update($id, $data) {
        if (!isset($data['judul']) || empty(trim($data['judul']))) {
            throw new Exception("Error: Judul buku tidak boleh kosong!");
        }
        if (!isset($data['stok']) || $data['stok'] < 0) {
            throw new Exception("Error: Stok buku tidak valid!");
        }

        $judul   = trim($data['judul']);
        $penulis = trim($data['penulis'] ?? '');
        $stok    = (int) $data['stok'];
        $id      = (int) $id;

        $stmt = $this->conn->prepare("UPDATE buku SET judul = ?, penulis = ?, stok = ? WHERE id = ?");
        $stmt->bind_param("ssii", $judul, $penulis, $stok, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id) {
        $id = (int) $id;
        $buku = $this->find($id);

        if (!$buku) {
            throw new Exception("Error: Data buku tidak ditemukan!");
        }

        $stmt = $this->conn->prepare("DELETE FROM buku WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
