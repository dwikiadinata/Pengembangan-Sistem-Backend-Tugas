<?php
class mobil{
private $odometer;

function __construct() {
    $this->odometer = 100;
}

    public function nyalakan($merk, $type){
        return "Nyalakan mobil $merk - $type";
    }

    public function berjalan($km) {
        return $this->odometer + $km;
    }
}

$mobil1 = new mobil();
echo $mobil1->nyalakan("Toyota", "Avanza");
echo " Berjalan sejauh " . $mobil1->berjalan(100) . " km";
?>