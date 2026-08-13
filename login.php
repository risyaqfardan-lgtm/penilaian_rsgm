<?php
session_start();
require 'koneksi.php';

// Jika sudah login, langsung arahkan ke admin.php
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

$error = false;

// Logika ketika tombol login ditekan
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['username'] = $admin['username'];
            header("Location: admin.php");
            exit();
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - RSGM Unimus</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --forest-900: #0d3b2b;
            --forest-800: #124a37;
            --forest-700: #186a49;
            --mint-500: #4caf7d;
            --mint-100: #eafbf1;
            --sun-500: #f5b301;
            --border: #dde8e2;
            --ink-900: #12211a;
        }

        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            background-color: var(--forest-900); /* biar overscroll/bounce di HP nggak nampilin warna putih asli device */
            overscroll-behavior: none; /* matikan efek "mantul" pas discroll kelewat ujung */
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            min-height: 100dvh; /* viewport dinamis: lebih akurat di HP (address bar naik-turun) */
            background: radial-gradient(120% 140% at 15% 0%, #175a40 0%, var(--forest-900) 65%);
            position: relative;
            overflow-x: hidden; /* cegah scroll horizontal, tapi tetap boleh scroll vertikal kalau layarnya pendek */
            margin: 0;
        }

        body::before {
            content: "";
            position: absolute; top: -140px; right: -100px;
            width: 380px; height: 380px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,201,60,0.16) 0%, rgba(255,201,60,0) 70%);
        }

        h4, .brand-font { font-family: 'Poppins', sans-serif; }

        /*
          Teknik centering paling stabil: .login-wrap di-absolute-kan lalu
          ditarik pakai transform translate(-50%, -50%) berdasarkan UKURAN
          WRAP ITU SENDIRI. Karena logo diposisikan absolute (dikeluarkan
          dari alur normal), tinggi .login-wrap = tinggi .login-card SAJA,
          jadi yang di-center murni kartunya, bukan gabungan logo+kartu.
        */
        .login-wrap {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 100%; max-width: 400px;
            padding: 0 16px;
            box-sizing: border-box;
            z-index: 2;
        }

        .login-header {
            position: absolute;
            left: 0; right: 0; bottom: 100%; /* nempel persis di atas tepi atas .login-wrap (= atas kartu) */
            margin-bottom: 20px;
            display: flex; justify-content: center;
            padding: 0 16px;
            box-sizing: border-box;
        }

        .logo-mark { height: 56px; width: auto; filter: drop-shadow(0 6px 14px rgba(0,0,0,0.35)); }

        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
            width: 100%;
            box-sizing: border-box;
            text-align: left;
        }

        .form-control { padding: 12px 15px; border-radius: 10px; border: 1.5px solid var(--border); }
        .form-control:focus { border-color: var(--mint-500); box-shadow: 0 0 0 4px rgba(76, 175, 125, 0.15); }

        .btn-login {
            background: linear-gradient(135deg, var(--forest-700), var(--forest-800));
            color: white; padding: 12px; border-radius: 10px; font-weight: 600; transition: 0.25s; border: none;
        }
        .btn-login:hover { background: linear-gradient(135deg, var(--forest-800), var(--forest-900)); color: white; transform: translateY(-1px); }

        .back-link { color: var(--ink-900); opacity: 0.6; }
        .back-link:hover { opacity: 1; color: var(--forest-700); }
    </style>
</head>
<body>

    <div class="login-wrap">
        <div class="login-header">
            <img class="logo-mark" src="aset/Logo%20RSGM%20-%20Vector%20list%20putih.png" alt="Logo RSGM Unimus">
        </div>

        <div class="login-card">
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-1" style="color: var(--forest-900);">RSGM Admin</h4>
                <p class="text-muted small mb-0">Silakan login untuk mengakses dashboard</p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger py-2 text-center small fw-semibold">
                    Username atau Password salah!
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-user text-muted"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>
                <button type="submit" name="login" class="btn btn-login w-100 mb-3">Login ke Dashboard</button>
                <a href="index.php" class="back-link d-block text-center text-decoration-none small"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Form Pasien</a>
            </form>
        </div>
    </div>

</body>
</html>
