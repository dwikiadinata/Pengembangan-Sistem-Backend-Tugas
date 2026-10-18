<?php
$tugas = 80;
$uts = 83;
$uas = 75;

function hitungNilaiAkhir($uts, $uas, $tugas){
    $nilaiAkhir = ($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4);
    return $nilaiAkhir;
}

$nilaiAkhir = hitungNilaiAkhir($uts, $uas, $tugas);

if($nilaiAkhir >= 70){
    $status = "Lulus";
}else{
    $status = "Tidak Lulus";
}

echo "Nilai Akhir: ".$nilaiAkhir . "<br>";
echo "Status : ".$status;

?>