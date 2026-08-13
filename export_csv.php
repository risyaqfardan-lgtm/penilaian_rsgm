<?php
session_start();

// PROTEKSI: hanya admin yang login yang boleh export
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

require 'koneksi.php';

// Fungsi kecil untuk mencegah "CSV/Formula Injection": kalau isi datanya
// diawali karakter =, +, -, atau @, Excel/Google Sheets bisa salah paham
// dan menjalankannya sebagai rumus. Kita kasih apostrof di depan biar
// selalu dianggap teks biasa.
function amankan_csv($value) {
    $value = (string) $value;
    if (isset($value[0]) && in_array($value[0], ['=', '+', '-', '@'])) {
        $value = "'" . $value;
    }
    return $value;
}

$filename = "ulasan_rsgm_" . date('Y-m-d_His') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

// BOM UTF-8, supaya karakter seperti "é" atau simbol lain tetap kebaca benar
// waktu dibuka di Excel/Google Sheets
fwrite($output, "\xEF\xBB\xBF");

// Header kolom
fputcsv($output, ['Nama Pasien', 'No. HP', 'Jenis Layanan', 'Sumber Informasi', 'Rating', 'Kritik/Saran', 'Tanggal'], ';');

$result = $conn->query("SELECT * FROM ulasan_rs ORDER BY tanggal_dibuat DESC");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        amankan_csv($row['nama_pasien']),
        amankan_csv($row['no_hp']),
        amankan_csv($row['jenis_layanan']),
        amankan_csv($row['sumber_info']),
        amankan_csv($row['rating']),
        amankan_csv($row['kritik_saran']),
        amankan_csv(date('d M Y, H:i', strtotime($row['tanggal_dibuat']))),
    ], ';');
}

fclose($output);
$conn->close();
exit();
?>