<?php
class NilaiRataRata {
    private $rataRata;

    public function __construct() {
        $this->rataRata = 0;
    }

    public function setNilai($uts, $uas, $tugas) {
        $this->rataRata = ($uts + $uas + $tugas) / 3;
    }

    public function getNilai() {
        return $this->rataRata;
    }
}

$nilai = new NilaiRataRata();
$nilai->setNilai(80, 90, 70);
echo "Nilai rata-rata: " . $nilai->getNilai();
?>