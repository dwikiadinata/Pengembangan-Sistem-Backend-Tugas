<?php
class Mahasiswa {
    public $nama;
    public $nim;
    private $nilai;

    public function setNilai($nilai) {
        if ($nilai >= 0 && $nilai <= 100) { 
            $this->nilai = $nilai;
        } else {
            echo "Nilai harus antara 0 - 100.";
        }
    }

    public function getNilai() {
        return $this->nilai;
    }

    public function getPredikat() {
        if ($this->nilai >= 85) {
            return 'A';
        } elseif ($this->nilai >= 70) {
            return 'B';
        } elseif ($this->nilai >= 55) {
            return 'C';
        } else {
            return 'D';
        }
    }
}

// $mhs = new Mahasiswa();
// $mhs->nama = "Andi";
// $mhs->nim = "22030101";
// $mhs->setNilai(90);
// echo "Nama: " . $mhs->nama . "<br>";
// echo "NIM: " . $mhs->nim . "<br>";
// echo "Nilai: " . $mhs->getNilai() . "<br>";
// echo "Predikat: " . $mhs->getPredikat() . "<br>";

// $mhs2 = new Mahasiswa();
// $mhs2->nama = "Budi";
// $mhs2->nim = "22030102";
// $mhs2->setNilai(85);
// echo "Nama: " . $mhs2->nama . "<br>";
// echo "NIM: " . $mhs2->nim . "<br>";
// echo "Nilai: " . $mhs2->getNilai() . "<br>";
// echo "Predikat: " . $mhs2->getPredikat() . "<br>";
?>