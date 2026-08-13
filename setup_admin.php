<?php
/**
 * FILE SEKALI-JALAN — untuk membuat/reset akun admin dengan password
 * yang ter-hash dengan BENAR (bukan teks polos).
 *
 * Cara pakai:
 * 1. Upload file ini ke folder yang sama dengan koneksi.php.
 * 2. Buka lewat browser, contoh: http://penilaian_rsgm.test/setup_admin.php
 * 3. Baca hasilnya di layar.
 * 4. Coba login pakai username & password di bawah ini.
 * 5. HAPUS file ini dari server setelah berhasil login — jangan dibiarkan
 *    nongkrong, karena siapa pun yang tahu URL-nya bisa reset password admin.
 */

require 'koneksi.php';

$username = 'adminmaster';
$password = 'adminmaster123';

$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<pre style='font-family: monospace; font-size: 14px; padding: 20px;'>";

// Cek dulu apakah usernya sudah ada
$stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Sudah ada -> update password-nya dengan hash yang benar
    $row = $result->fetch_assoc();
    $stmt->close();

    $update = $conn->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
    $update->bind_param("si", $hash, $row['id']);

    if ($update->execute()) {
        echo "BERHASIL: password untuk username '$username' sudah di-update dengan hash bcrypt yang benar.\n\n";
        echo "Silakan login pakai:\n";
        echo "Username: $username\n";
        echo "Password: $password\n";
    } else {
        echo "GAGAL update: " . $conn->error . "\n";
    }
    $update->close();
} else {
    // Belum ada -> buat baris baru
    $stmt->close();

    $insert = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
    $insert->bind_param("ss", $username, $hash);

    if ($insert->execute()) {
        echo "BERHASIL: akun admin baru sudah dibuat.\n\n";
        echo "Silakan login pakai:\n";
        echo "Username: $username\n";
        echo "Password: $password\n";
    } else {
        echo "GAGAL insert: " . $conn->error . "\n";
        echo "(Kalau errornya soal kolom, cek dulu nama kolom di tabel admin_users kamu sesuai belum: username, password)\n";
    }
    $insert->close();
}

echo "</pre>";
$conn->close();
?>
