<?php
class Buku {
    public $judul;
    public $penulis;
    private $tahun;
    public $kategori;
    public $cover;

    public function __construct($judul, $penulis, $kategori) {
        $this->judul = $judul;
        $this->penulis = $penulis;
        $this->kategori = $kategori;
    }
    
    public function setTahun($tahun) {
        if (!is_numeric($tahun) || $tahun < 1500 || $tahun > date('Y')) {
            return false;
        }
        $this->tahun = (int)$tahun;
        return true;
    }

    public function getTahun() {
        return $this->tahun;
    }
}
?>