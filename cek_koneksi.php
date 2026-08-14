<?php
/**
 * FILE DIAGNOSA SEMENTARA — untuk cari tahu kenapa error 500 muncul
 * waktu submit ulasan. Buka lewat browser, baca hasilnya.
 * HAPUS file ini setelah selesai dipakai.
 */

// Paksa tampilkan error PHP asli (biasanya dimatikan di hosting produksi)
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<pre style='font-family: monospace; font-size: 14px; padding: 20px;'>";

echo "=== 1. CEK KONEKSI DATABASE ===\n";
if (!file_exists('koneksi.php')) {
    echo ">> koneksi.php TIDAK DITEMUKAN di folder ini. Pastikan file ini diupload satu folder dengan koneksi.php.\n";
    echo "</pre>";
    exit();
}

require 'koneksi.php';

if ($conn->connect_error) {
    echo ">> GAGAL konek: " . $conn->connect_error . "\n";
    echo "</pre>";
    exit();
} else {
    echo "Berhasil konek ke database.\n";
    echo "Nama database aktif saat ini: " . $conn->query("SELECT DATABASE()")->fetch_row()[0] . "\n";
}

echo "\n=== 2. CEK TABEL YANG ADA DI DATABASE INI ===\n";
$tables = $conn->query("SHOW TABLES");
if ($tables->num_rows === 0) {
    echo ">> TIDAK ADA TABEL SAMA SEKALI di database ini.\n";
    echo ">> Berarti SQL kemarin belum berhasil dijalankan di database yang benar, atau database yang dipilih di koneksi.php salah.\n";
} else {
    while ($t = $tables->fetch_row()) {
        echo "- " . $t[0] . "\n";
    }
}

echo "\n=== 3. CEK STRUKTUR TABEL ulasan_rs ===\n";
$cek = $conn->query("SHOW TABLES LIKE 'ulasan_rs'");
if ($cek->num_rows === 0) {
    echo ">> Tabel 'ulasan_rs' TIDAK DITEMUKAN. Ini kemungkinan besar penyebab error 500-nya.\n";
    echo ">> Jalankan lagi skema_database_hosting.sql di database yang benar (pastikan database sudah dipilih di kiri phpMyAdmin sebelum klik Go).\n";
} else {
    echo "Tabel 'ulasan_rs' ADA. Kolom-kolomnya:\n";
    $cols = $conn->query("DESCRIBE ulasan_rs");
    while ($c = $cols->fetch_assoc()) {
        echo "- {$c['Field']} ({$c['Type']})\n";
    }

    echo "\n=== 4. TES INSERT LANGSUNG (data percobaan, boleh dihapus nanti) ===\n";
    $stmt = $conn->prepare("INSERT INTO ulasan_rs (nama_pasien, no_hp, jenis_layanan, sumber_info, rating, kritik_saran) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo ">> GAGAL prepare query: " . $conn->error . "\n";
        echo ">> Ini biasanya berarti nama kolom di tabel kamu BEDA dengan yang dipakai kode (cek daftar kolom di langkah 3 di atas).\n";
    } else {
        $nama = "TES DIAGNOSA"; $hp = "0800000000"; $layanan = "Poli Gigi Umum"; $sumber = "Lainnya"; $rating = 5; $kritik = "ini cuma tes, boleh dihapus";
        $stmt->bind_param("ssssis", $nama, $hp, $layanan, $sumber, $rating, $kritik);
        if ($stmt->execute()) {
            echo "BERHASIL insert data tes! Berarti struktur tabel & koneksi sudah benar.\n";
            echo ">> Kalau form ulasan asli masih error 500, kemungkinan masalahnya di proses_rating.php atau data yang dikirim dari form.\n";
        } else {
            echo ">> GAGAL insert: " . $stmt->error . "\n";
        }
        $stmt->close();
    }
}

echo "</pre>";
$conn->close();
?>