<?php
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simpan data APA ADANYA (trim saja). Jangan htmlspecialchars sebelum
    // masuk DB -> proses "escape untuk tampilan" itu tugasnya saat OUTPUT
    // di admin.php, bukan saat data disimpan. Kalau di-escape dari awal,
    // datanya di DB jadi "kotor" (mis. tanda kutip berubah jadi &#039;).
    $nama          = trim($_POST['nama'] ?? '');
    $no_hp         = trim($_POST['no_hp'] ?? '');
    $jenis_layanan = trim($_POST['jenis_layanan'] ?? '');
    $sumber_info   = trim($_POST['sumber_info'] ?? '');
    $rating        = (int)($_POST['rating'] ?? 0);
    $kritik_saran  = trim($_POST['kritik_saran'] ?? '');

    // Validasi di server (jangan hanya andalkan "required" di HTML,
    // karena itu bisa dilewati/dimanipulasi dari luar form)
    if ($nama === '' || $jenis_layanan === '' || $sumber_info === '' || $kritik_saran === '' || $rating < 1 || $rating > 5) {
        header("Location: index.php?status=gagal");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO ulasan_rs (nama_pasien, no_hp, jenis_layanan, sumber_info, rating, kritik_saran) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $nama, $no_hp, $jenis_layanan, $sumber_info, $rating, $kritik_saran);

    if ($stmt->execute()) {
        header("Location: index.php?status=sukses");
    } else {
        header("Location: index.php?status=gagal");
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>
