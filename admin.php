<?php 
session_start();

// PROTEKSI: Jika belum login, tendang kembali ke halaman login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

require 'koneksi.php'; 

// --- HANDLER RESET DATA (dilindungi session admin + konfirmasi di sisi tombol) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'reset_data') {
    $conn->query("TRUNCATE TABLE ulasan_rs");
    header("Location: admin.php?reset=sukses");
    exit();
}

// --- MENGAMBIL DATA UNTUK STATISTIK (KPI) ---
$q_total = $conn->query("SELECT COUNT(id) as total FROM ulasan_rs");
$total_ulasan = $q_total->fetch_assoc()['total'];

$q_avg = $conn->query("SELECT AVG(rating) as rata FROM ulasan_rs");
$avg_rating = round($q_avg->fetch_assoc()['rata'], 1);
if(is_null($avg_rating)) $avg_rating = 0;

// --- MENGAMBIL DATA UNTUK GRAFIK LAYANAN ---
$q_layanan = $conn->query("SELECT jenis_layanan, COUNT(id) as jml FROM ulasan_rs GROUP BY jenis_layanan");
$label_layanan = []; $data_layanan = [];
while($r = $q_layanan->fetch_assoc()){
    $label_layanan[] = $r['jenis_layanan'];
    $data_layanan[] = (int)$r['jml']; // cast ke int biar json_encode menghasilkan angka, bukan teks
}

// --- MENGAMBIL DATA UNTUK GRAFIK SUMBER INFO ---
$q_sumber = $conn->query("SELECT sumber_info, COUNT(id) as jml FROM ulasan_rs GROUP BY sumber_info");
$label_sumber = []; $data_sumber = [];
while($r = $q_sumber->fetch_assoc()){
    $label_sumber[] = $r['sumber_info'];
    $data_sumber[] = (int)$r['jml']; // cast ke int juga
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Workspace - RSGM Unimus</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
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
            --sun-100: #fff6de;
            --bg: #f5faf7;
            --surface: #ffffff;
            --ink-900: #12211a;
            --ink-600: #55685f;
            --border: #dde8e2;
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--bg); color: var(--ink-900); }
        h1,h2,h3,h4,h5,.brand-font { font-family: 'Poppins', sans-serif; }

        .navbar-admin {
            background: linear-gradient(120deg, var(--forest-900), var(--forest-700));
            padding: 16px 30px;
            box-shadow: 0 4px 14px rgba(13,59,43,0.18);
        }
        .brand-logo { font-weight: 700; color: #fff; letter-spacing: -0.3px; text-decoration: none; font-size: 1.15rem; display: flex; align-items: center; }
        /* Logo file sudah versi putih (aset/Logo RSGM - Vector list putih.png), jadi tampil langsung tanpa kotak putih */
        .brand-logo img { height: 30px; width: auto; object-fit: contain; margin-right: 10px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.25)); }
        .navbar-admin .text-secondary { color: rgba(255,255,255,0.85) !important; }

        .btn-logout {
            background-color: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.25);
            color: #fff;
        }
        .btn-logout:hover { background-color: #fff; color: var(--forest-800); }

        .kpi-card {
            border: none; border-radius: 14px; box-shadow: 0 6px 16px rgba(13,59,43,0.06);
            background: var(--surface); padding: 22px; display: flex; align-items: center; justify-content: space-between;
        }
        .kpi-title { font-size: 0.8rem; font-weight: 600; color: var(--ink-600); text-transform: uppercase; letter-spacing: 0.5px; }
        .kpi-value { font-size: 1.8rem; font-weight: 700; color: var(--forest-900); margin-top: 5px; }
        .kpi-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        .kpi-icon.mint { background: var(--mint-100); color: var(--forest-700); }
        .kpi-icon.gold { background: var(--sun-100); color: var(--sun-500); }

        .dashboard-card { border: none; border-radius: 14px; box-shadow: 0 6px 16px rgba(13,59,43,0.06); background: var(--surface); margin-bottom: 24px; }
        .card-header-custom { background-color: transparent; border-bottom: 1px solid var(--border); padding: 18px 25px; font-weight: 600; color: var(--forest-900); }

        .table th { border-bottom: 2px solid var(--border); color: var(--ink-600); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 15px; background-color: var(--mint-100); }
        .table td { padding: 15px; vertical-align: middle; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        .badge-soft-primary { background-color: var(--mint-100); color: var(--forest-700); padding: 6px 10px; border-radius: 6px; font-weight: 500; }

        .page-heading { color: var(--forest-900); }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar-admin d-flex justify-content-between align-items-center mb-4">
        <a href="#" class="brand-logo">
            <img src="aset/Logo%20RSGM%20-%20Vector%20list%20putih.png" alt="Logo RSGM Unimus"> RSGM Workspace
        </a>
        <div>
            <span class="me-3 fw-semibold text-secondary"><i class="fa-solid fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php" class="btn btn-sm btn-logout rounded-pill px-3 fw-medium">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
            </a>
        </div>
    </nav>

    <div class="container-fluid px-4 pb-5">

        <div class="mb-4">
            <h4 class="fw-bold mb-1 page-heading">Overview Kinerja RSGM</h4>
            <p class="text-muted small">Ringkasan statistik ulasan dan kepuasan pasien.</p>
        </div>

        <div class="row g-4 mb-4 justify-content-center">
            <div class="col-md-6">
                <div class="kpi-card">
                    <div>
                        <div class="kpi-title">Total Ulasan Masuk</div>
                        <div class="kpi-value"><?= (int)$total_ulasan ?> <span class="fs-6 fw-normal text-muted">pasien</span></div>
                    </div>
                    <div class="kpi-icon mint">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="kpi-card">
                    <div>
                        <div class="kpi-title">Rata-Rata Kepuasan</div>
                        <div class="kpi-value"><?= htmlspecialchars($avg_rating) ?> <span class="fs-5" style="color: var(--sun-500);"><i class="fa-solid fa-star"></i></span></div>
                    </div>
                    <div class="kpi-icon gold">
                        <i class="fa-solid fa-face-smile"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="dashboard-card h-100">
                    <div class="card-header-custom">Distribusi Layanan Poli</div>
                    <div class="card-body">
                        <canvas id="chartLayanan" height="250"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="dashboard-card h-100">
                    <div class="card-header-custom">Sumber Informasi Pasien</div>
                    <div class="card-body">
                        <canvas id="chartSumber" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>Daftar Ulasan Pasien</span>
                <div>
                    <a href="export_csv.php" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="fa-solid fa-file-csv me-1"></i> Export CSV
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="konfirmasiReset()">
                        <i class="fa-solid fa-trash-can me-1"></i> Reset Data
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive p-4">
                    <table id="tabelAdmin" class="table table-hover w-100">
                        <thead>
                            <tr>
                                <th>Pasien & Kontak</th>
                                <th>Layanan & Sumber</th>
                                <th>Rating</th>
                                <th>Detail Ulasan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM ulasan_rs ORDER BY tanggal_dibuat DESC");
                            while($row = $result->fetch_assoc()) {
                                // Data hasil query di-escape SAAT DITAMPILKAN (bukan saat disimpan)
                                // supaya aman dari XSS tapi data asli di DB tetap bersih/utuh.
                                $ratingVal = max(0, min(5, (int)$row['rating']));
                                $stars = str_repeat("<i class='fa-solid fa-star' style='color: var(--sun-500);'></i>", $ratingVal);
                                $kontak = empty($row['no_hp']) ? "<span class='text-muted fst-italic'>Tidak ada</span>" : htmlspecialchars($row['no_hp']);

                                echo "<tr>
                                        <td>
                                            <div class='fw-bold text-dark'>".htmlspecialchars($row['nama_pasien'])."</div>
                                            <div class='text-muted small mt-1'><i class='fa-solid fa-phone me-1'></i> ".$kontak."</div>
                                        </td>
                                        <td>
                                            <span class='badge-soft-primary d-inline-block mb-2'>".htmlspecialchars($row['jenis_layanan'])."</span><br>
                                            <small class='text-muted'><i class='fa-solid fa-bullhorn me-1'></i> ".htmlspecialchars($row['sumber_info'])."</small>
                                        </td>
                                        <td class='text-nowrap'>".$stars."</td>
                                        <td style='max-width: 300px;'>
                                            <p class='mb-0 text-wrap'>".htmlspecialchars($row['kritik_saran'])."</p>
                                        </td>
                                        <td class='text-muted small'>".date('d M Y, H:i', strtotime($row['tanggal_dibuat']))."</td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-center text-muted small mt-4">
            &copy; 2026 Sistem Layanan RSGM. Developed by Risyaq Fardan.
        </div>

    </div>

    <!-- Form tersembunyi untuk aksi reset data (dikirim via POST, bukan link biasa,
         supaya tidak bisa ke-trigger cuma dengan klik/scan link) -->
    <form id="formReset" action="admin.php" method="POST" class="d-none">
        <input type="hidden" name="aksi" value="reset_data">
    </form>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#tabelAdmin').DataTable({
                "language": {
                    "search": "Cari data:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "paginate": { "first": "Pertama", "last": "Terakhir", "next": "Selanjutnya", "previous": "Sebelumnya" }
                },
                "order": [[ 4, "desc" ]]
            });

            const labelLayanan = <?= json_encode($label_layanan) ?>;
            const dataLayanan = <?= json_encode($data_layanan) ?>;
            const labelSumber = <?= json_encode($label_sumber) ?>;
            const dataSumber = <?= json_encode($data_sumber) ?>;

            // Palet warna mengikuti tema logo: hijau tua, hijau mint, kuning matahari,
            // ditambah beberapa warna pelengkap. generateColors() otomatis mengulang
            // paletnya dengan variasi shade kalau kategorinya lebih banyak dari palet dasar,
            // jadi warnanya tidak akan pernah "nabrak" sama persis lagi.
            const brandPalette = [
                '#186a49', '#4caf7d', '#f5b301', '#a7e8c4', '#0d3b2b',
                '#f4863a', '#2f9a6d', '#ffdc73', '#8fd9c4', '#b5651d'
            ];
            function generateColors(n) {
                const colors = [];
                for (let i = 0; i < n; i++) {
                    if (i < brandPalette.length) {
                        colors.push(brandPalette[i]);
                    } else {
                        // kalau kategorinya lebih banyak dari palet dasar, buat variasi
                        // shade baru (lebih terang/gelap) supaya tetap beda warna
                        const base = brandPalette[i % brandPalette.length];
                        colors.push(shadeColor(base, (Math.floor(i / brandPalette.length) % 2 === 0 ? -15 : 15)));
                    }
                }
                return colors;
            }
            function shadeColor(hex, percent) {
                let r = parseInt(hex.substring(1,3), 16);
                let g = parseInt(hex.substring(3,5), 16);
                let b = parseInt(hex.substring(5,7), 16);
                r = Math.min(255, Math.max(0, Math.round(r + (percent/100)*255)));
                g = Math.min(255, Math.max(0, Math.round(g + (percent/100)*255)));
                b = Math.min(255, Math.max(0, Math.round(b + (percent/100)*255)));
                return "#" + [r,g,b].map(v => v.toString(16).padStart(2,'0')).join('');
            }

            new Chart(document.getElementById('chartLayanan'), {
                type: 'doughnut',
                data: {
                    labels: labelLayanan,
                    datasets: [{
                        data: dataLayanan,
                        backgroundColor: generateColors(labelLayanan.length),
                        borderWidth: 0
                    }]
                },
                plugins: [ChartDataLabels],
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            color: '#fff',
                            font: { weight: '700', size: 12 },
                            formatter: function(value, ctx) {
                                const data = ctx.chart.data.datasets[0].data;
                                const total = data.reduce((a, b) => Number(a) + Number(b), 0);
                                if (total === 0) return '0%';
                                const persen = (Number(value) / total) * 100;
                                return persen.toFixed(1) + '%';
                            }
                        }
                    }
                }
            });

            new Chart(document.getElementById('chartSumber'), {
                type: 'bar',
                data: {
                    labels: labelSumber,
                    datasets: [{
                        label: 'Jumlah Pasien',
                        data: dataSumber,
                        backgroundColor: generateColors(labelSumber.length),
                        borderRadius: 6
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
            });
        });

        // Konfirmasi dulu sebelum benar-benar reset (tidak langsung jalan sekali klik)
        function konfirmasiReset() {
            Swal.fire({
                title: 'Hapus semua data ulasan?',
                text: 'Semua ulasan pasien yang sudah masuk akan terhapus permanen. Aksi ini tidak bisa dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#186a49',
                confirmButtonText: 'Ya, hapus semua',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formReset').submit();
                }
            });
        }

        <?php if (isset($_GET['reset']) && $_GET['reset'] === 'sukses'): ?>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Semua data ulasan sudah direset.',
            icon: 'success',
            confirmButtonColor: '#186a49'
        });
        window.history.replaceState(null, null, window.location.pathname);
        <?php endif; ?>
    </script>
</body>
</html>
