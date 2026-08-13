<?php
/**
 * FILE SEMENTARA — jalankan sekali untuk membuat hash password baru,
 * lalu HAPUS file ini dari server setelah selesai dipakai.
 *
 * Cara pakai:
 * 1. Buka file ini lewat browser (mis. http://localhost/generate_hash.php).
 * 2. Copy hasil hash yang muncul (diawali $2y$...).
 * 3. Update manual di database:
 *      UPDATE admin_users SET password = 'HASIL_HASH_DISINI' WHERE username = 'adminmaster';
 * 4. Hapus file generate_hash.php ini.
 */

$password_baru = 'adminmaster123';
echo password_hash($password_baru, PASSWORD_DEFAULT);
