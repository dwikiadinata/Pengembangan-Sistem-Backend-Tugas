<?php
class Mahasiswa {
    public function getNilaiRataRata($tugas, $kuis, $uts, $uas) {
        return ($tugas + $kuis + $uts + $uas) / 4;
    }
}

$mhs1 = new Mahasiswa();
echo "Nilai rata-rata Mahasiswa: " . $mhs1->getNilaiRataRata(80, 80, 85, 90);
?>