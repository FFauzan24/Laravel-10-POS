<?php
function format_uang($angka)
{
    return number_format($angka, 0, ',', '.');
}

function terbilang($angka)
{
    $angka = abs($angka); // Mengambil nilai absolut dari angka
    $baca = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
    $temp = "";

    if ($angka < 12) {
        $temp = $baca[$angka];
    } else if ($angka < 20) {
        $temp = terbilang($angka - 10) . " belas";
    } else if ($angka < 100) {
        $temp = terbilang((int)($angka / 10)) . " puluh " . terbilang($angka % 10);
    } else if ($angka < 200) {
        $temp = "seratus " . terbilang($angka - 100);
    } else if ($angka < 1000) {
        $temp = terbilang((int)($angka / 100)) . " ratus " . terbilang($angka % 100);
    } else if ($angka < 2000) {
        $temp = "seribu " . terbilang($angka - 1000);
    } else if ($angka < 1000000) {
        $temp = terbilang((int)($angka / 1000)) . " ribu " . terbilang($angka % 1000);
    } else if ($angka < 1000000000) {
        $temp = terbilang((int)($angka / 1000000)) . " juta " . terbilang($angka % 1000000);
    }

    // Trim whitespace pada hasil akhir
    return trim($temp);
}

function tanggal_indonesia($tgl, $tampil_hari = true)
{
    $nama_hari = array("Minggu", "Senin", "selasa", "Rabu", "Kamis", "Jum'at", "Sabtu");
    $nama_bulan = array(1 => "januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    $tahun = substr($tgl, 0, 4);
    $bulan = $nama_bulan[(int) substr($tgl, 5, 2)];
    $tanggal = substr($tgl, 8, 2);
    $text = '';

    if ($tampil_hari) {
        $urutan_hari = date('w', mktime(0, 0, 0, substr($tgl, 5, 2), $tanggal, $tahun));
        $hari = $nama_hari[$urutan_hari];
        $text .= "$hari, $tanggal $bulan $tahun";
    } else {
        $text .= "$tanggal $bulan $tahun";
    }
    return $text;
}

function tambahkan_0($value, $threshold = null)
{
    return sprintf("%0" . $threshold . "s", $value);
}
