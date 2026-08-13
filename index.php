<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Pelanggan - RSGM Unimus</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --forest-900: #0d3b2b;
            --forest-800: #124a37;
            --forest-700: #186a49;
            --forest-600: #1f7d57;
            --mint-500: #4caf7d;
            --mint-300: #a7e8c4;
            --mint-100: #eafbf1;
            --sun-500: #f5b301;
            --sun-400: #ffc93c;
            --bg: #f5faf7;
            --surface: #ffffff;
            --ink-900: #12211a;
            --ink-600: #55685f;
            --border: #dde8e2;
        }

        * { box-sizing: border-box; }

        html {
            background-color: var(--bg); /* biar overscroll/bounce di HP nggak nampilin putih polos */
            overscroll-behavior: none;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--ink-900);
            margin: 0;
            overscroll-behavior: none;
        }

        h1, h2, h3, h4, h5, .brand-font {
            font-family: 'Poppins', sans-serif;
        }

        /* ===== HERO ===== */
        .hero-section {
            position: relative;
            background:
                radial-gradient(120% 140% at 85% 0%, rgba(255,201,60,0.10) 0%, rgba(255,201,60,0) 55%),
                linear-gradient(155deg, #0f4531 0%, var(--forest-900) 55%, #0a2e21 100%);
            padding: 64px 0 150px;
            overflow: hidden;
            isolation: isolate;
        }

        /* Pola titik halus sebagai tekstur, pengganti sementara foto gedung asli */
        .hero-texture {
            position: absolute; inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.10) 1.5px, transparent 1.5px);
            background-size: 26px 26px;
            mask-image: linear-gradient(to bottom, rgba(0,0,0,0.9), rgba(0,0,0,0));
            z-index: -1;
        }

        .hero-glow {
            position: absolute;
            top: -120px; right: -80px;
            width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(255,201,60,0.18) 0%, rgba(255,201,60,0) 70%);
            z-index: -1;
        }

        /* Link kecil ke halaman login admin, pojok kanan atas hero */
        .admin-login-link {
            position: absolute;
            top: 20px; right: 20px;
            z-index: 3;
            display: inline-flex; align-items: center;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.22);
            color: rgba(255,255,255,0.9);
            font-size: 0.8rem; font-weight: 500;
            padding: 8px 14px;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .admin-login-link:hover {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }
        @media (max-width: 576px) {
            .admin-login-link { top: 14px; right: 14px; font-size: 0.7rem; padding: 6px 10px; }
        }

        .hero-wave {
            position: absolute;
            left: 0; right: 0; bottom: -2px;
            line-height: 0;
        }

        /*
          LOGO: memakai file putih (aset/Logo RSGM - Vector list putih.png),
          jadi ditaruh langsung di atas latar gelap tanpa kotak putih lagi.
          Kalau nanti mau pakai versi logo full color di area putih (mis.
          di dalam form-card), tinggal ganti src-nya ke file versi warna.
        */
        .logo-mark {
            height: 110px;
            width: auto;
            filter: drop-shadow(0 6px 14px rgba(0,0,0,0.35));
            margin-bottom: 22px;
        }

        .hero-eyebrow-plain {
            font-size: 0.8rem; font-weight: 600; letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--mint-300);
            margin-bottom: 6px;
        }

        .hero-title {
            font-weight: 800;
            color: #fff;
            letter-spacing: -1px;
            font-size: clamp(1.7rem, 5.5vw, 3.2rem);
            line-height: 1.18;
            max-width: 720px;
            margin-left: auto;
            margin-right: auto;
            text-wrap: balance;
        }
        .hero-title .accent { color: var(--sun-400); }
        .hero-subtitle { color: rgba(255,255,255,0.72); font-weight: 300; max-width: 560px; margin-left: auto; margin-right: auto; }

        /* Badge kecil di hero: Bootstrap .badge default-nya white-space:nowrap,
           jadi kita override supaya boleh membungkus baris di layar sempit */
        .hero-tagline {
            display: inline-block;
            white-space: normal;
            max-width: 92%;
            line-height: 1.5;
        }

        /* ===== FORM CARD ===== */
        .form-card {
            background: var(--surface);
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 24px 48px rgba(13, 59, 43, 0.14);
            margin-top: -90px;
            padding: 44px;
            position: relative;
            z-index: 2;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 13px 16px;
            border: 1.5px solid var(--border);
            background-color: #fbfdfc;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: var(--mint-500);
            box-shadow: 0 0 0 4px rgba(76, 175, 125, 0.15);
        }
        .form-label { font-weight: 600; color: var(--ink-900); margin-bottom: 8px; font-size: 0.9rem; }

        .btn-submit {
            background: linear-gradient(135deg, var(--forest-700), var(--forest-800));
            color: white; border-radius: 10px; padding: 14px;
            font-weight: 600; transition: all 0.25s; border: none;
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, var(--forest-800), var(--forest-900));
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(24, 106, 73, 0.3);
            color: #fff;
        }

        .input-group-text { background-color: transparent; border-right: none; border-color: var(--border); color: #9db3a9; }
        .input-group .form-control { border-left: none; padding-left: 0; }
        .input-group:focus-within .input-group-text { border-color: var(--mint-500); color: var(--forest-700); }

        /* ===== RATING ===== */
        /* Lebar & tinggi kartu dibuat TETAP (bukan flex-grow) supaya semua
           kartu ukurannya identik, baik yang di baris pertama maupun yang
           "sisa" di baris kedua. justify-content:center otomatis merapikan
           baris yang jumlah kartunya lebih sedikit (mis. baris ke-2 isi 2). */
        .rating-wrapper { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; margin-top: 10px; }
        .rating-input { display: none; }
        .rating-option {
            flex: 0 0 108px; width: 108px; min-height: 136px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; cursor: pointer; padding: 12px 6px;
            border: 2px solid var(--border); border-radius: 14px;
            transition: all 0.2s ease; background-color: #ffffff; user-select: none;
        }
        .rating-option:hover { background-color: var(--mint-100); border-color: var(--mint-300); transform: translateY(-3px); }

        .rating-input:checked + .rating-option {
            border-color: var(--forest-600);
            background-color: var(--mint-100);
            box-shadow: 0 6px 16px rgba(31, 125, 87, 0.18);
            transform: translateY(-3px);
        }

        .rating-emoji {
            display: flex; align-items: center; justify-content: center;
            height: 52px; font-size: 2.4rem; line-height: 1;
            margin-bottom: 8px; transition: all 0.2s;
        }
        .rating-input:checked + .rating-option .rating-emoji { transform: scale(1.15); }

        .color-5 { color: var(--forest-600); }
        .color-4 { color: var(--mint-500); }
        .color-3 { color: var(--sun-500); }
        .color-2 { color: #f4863a; }
        .color-1 { color: #e6543f; }

        .rating-stars { color: var(--sun-500); font-size: 0.75rem; margin-bottom: 5px; letter-spacing: 1px; white-space: nowrap; }
        .rating-text { font-size: 0.8rem; font-weight: 700; color: var(--ink-900); line-height: 1.2; }

        .site-footer { color: var(--ink-600); }
        .site-footer i { color: var(--mint-500); }

        /* ===== RESPONSIVE: HP & TABLET ===== */
        @media (max-width: 576px) {
            .form-card { padding: 30px 22px; }
            .logo-mark { height: 82px; }
            .rating-wrapper { gap: 8px; }
            .rating-option { flex-basis: 90px; width: 90px; min-height: 116px; padding: 10px 4px; }
            .rating-emoji { height: 42px; font-size: 1.9rem; }
            .rating-text { font-size: 0.7rem; }
        }

        @media (min-width: 577px) and (max-width: 991px) {
            .form-card { padding: 38px; }
        }
    </style>
</head>
<body>

    <div class="hero-section text-center">
        <div class="hero-texture"></div>
        <div class="hero-glow"></div>

        <a href="login.php" class="admin-login-link">
            <i class="fa-solid fa-user-shield me-1"></i> Login Admin
        </a>

        <div class="container position-relative pt-4">
            <img class="logo-mark" src="aset/Logo%20RSGM%20-%20Vector%20list%20putih.png" alt="Logo RSGM Unimus">
            <div class="hero-eyebrow-plain">Care for The Excellence</div>
            <h2 class="hero-title mb-3">Perawatan Gigi Terbaik untuk Senyum <span class="accent">Anda</span></h2>
            <p class="hero-subtitle lead mb-0 mx-auto">Bantu kami terus meningkatkan mutu layanan RSGM Unimus lewat ulasan jujur Anda.</p>

            <span class="badge hero-tagline rounded-pill px-3 py-2 mt-4" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18); color: #fff; font-weight: 500;">
                <i class="fa-solid fa-tooth me-1"></i> Rumah Sakit Gigi dan Mulut<br>Universitas Muhammadiyah Semarang
            </span>
        </div>

        <div class="hero-wave">
            <svg viewBox="0 0 1440 90" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="width:100%; height:90px; display:block;">
                <path d="M0,40 C240,90 480,0 720,30 C960,60 1200,90 1440,40 L1440,100 L0,100 Z" fill="#f5faf7"></path>
            </svg>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="form-card">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold" style="color: var(--forest-900);">Bagaimana pengalaman Anda?</h4>
                        <p class="text-muted small">Setiap ulasan sangat berharga untuk pelayanan kami.</p>
                    </div>

                    <form action="proses_rating.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                                    <input type="text" name="nama" class="form-control" placeholder="Nama Anda" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">No. HP / WhatsApp <span class="text-muted fw-normal">(Opsional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-mobile-screen"></i></span>
                                    <input type="text" name="no_hp" class="form-control" placeholder="08xx-xxxx-xxxx">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Jenis Layanan</label>
                                <select name="jenis_layanan" class="form-select" required>
                                    <option value="" disabled selected>Pilih poli layanan</option>
                                    <option value="IGD & 24 Jam">IGD & 24 Jam</option>
                                    <option value="Poli Dokter Umum">Poli Dokter Umum</option>
                                    <option value="Poli Gigi Umum">Poli Gigi Umum</option>
                                    <option value="Sp. Konservasi Gigi (KG)">Sp. Konservasi Gigi (KG)</option>
                                    <option value="Sp. Gigi Anak (KGA)">Sp. Gigi Anak (KGA)</option>
                                    <option value="Sp. Bedah Mulut (BM)">Sp. Bedah Mulut (BM)</option>
                                    <option value="Spesialis Lain & Radiologi">Spesialis Lain & Radiologi</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Sumber Informasi</label>
                                <select name="sumber_info" class="form-select" required>
                                    <option value="" disabled selected>Pilih sumber informasi</option>
                                    <option value="Media Sosial">Media Sosial (IG/TikTok)</option>
                                    <option value="Teman / Keluarga">Rekomendasi Teman / Keluarga</option>
                                    <option value="Website Resmi">Website Resmi Unimus</option>
                                    <option value="Spanduk / Brosur">Spanduk / Brosur</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <!-- Rating Visual -->
                        <div class="mb-5 mt-2">
                            <label class="form-label d-block text-center fs-5 mb-3">Tingkat Kepuasan Anda</label>
                            <div class="rating-wrapper">

                                <input type="radio" name="rating" id="rate1" value="1" class="rating-input" required>
                                <label for="rate1" class="rating-option">
                                    <div class="rating-emoji color-1"><i class="fa-solid fa-face-sad-tear"></i></div>
                                    <div class="rating-stars"><i class="fa-solid fa-star"></i></div>
                                    <div class="rating-text">Sangat Kurang</div>
                                </label>

                                <input type="radio" name="rating" id="rate2" value="2" class="rating-input" required>
                                <label for="rate2" class="rating-option">
                                    <div class="rating-emoji color-2"><i class="fa-solid fa-face-frown"></i></div>
                                    <div class="rating-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                                    <div class="rating-text">Kurang</div>
                                </label>

                                <input type="radio" name="rating" id="rate3" value="3" class="rating-input" required>
                                <label for="rate3" class="rating-option">
                                    <div class="rating-emoji color-3"><i class="fa-solid fa-face-meh"></i></div>
                                    <div class="rating-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                                    <div class="rating-text">Cukup</div>
                                </label>

                                <input type="radio" name="rating" id="rate4" value="4" class="rating-input" required>
                                <label for="rate4" class="rating-option">
                                    <div class="rating-emoji color-4"><i class="fa-solid fa-face-smile"></i></div>
                                    <div class="rating-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                                    <div class="rating-text">Memuaskan</div>
                                </label>

                                <input type="radio" name="rating" id="rate5" value="5" class="rating-input" required>
                                <label for="rate5" class="rating-option">
                                    <div class="rating-emoji color-5"><i class="fa-solid fa-face-laugh-beam"></i></div>
                                    <div class="rating-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                                    <div class="rating-text">Sangat<br>Memuaskan</div>
                                </label>

                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Ulasan, Kritik, atau Saran</label>
                            <textarea name="kritik_saran" class="form-control" rows="4" placeholder="Ceritakan pengalaman Anda secara detail di sini..." required></textarea>
                        </div>

                        <button type="submit" class="btn-submit w-100 d-flex justify-content-center align-items-center">
                            Kirim Ulasan <i class="fa-solid fa-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>

                <div class="text-center site-footer small mt-4">
                    <div>Rumah Sakit Gigi dan Mulut</div>
                    <div>Universitas Muhammadiyah Semarang</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
    <script>
        Swal.fire({
            title: 'Terkirim!',
            text: 'Terima kasih, ulasan Anda sangat berarti untuk RSGM Unimus.',
            icon: 'success',
            confirmButtonColor: '#186a49'
        });
        window.history.replaceState(null, null, window.location.pathname);
    </script>
    <?php elseif(isset($_GET['status']) && $_GET['status'] == 'gagal'): ?>
    <script>
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat mengirim data. Pastikan semua kolom sudah terisi dengan benar.',
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
        window.history.replaceState(null, null, window.location.pathname);
    </script>
    <?php endif; ?>

</body>
</html>