<?php
require_once 'config/database.php';

// Memuat seluruh class turunan. Aturan OOP: Karyawan (Abstract) DILARANG di-instantiate.
require_once 'classes/KaryawanKontrak.php';
require_once 'classes/KaryawanTetap.php';
require_once 'classes/KaryawanMagang.php';

$database = new Database();
$db = $database->connect();

$errorMessage = "";

// GROUPING DATA: Array penampung terpisah berdasarkan tipe
$listKontrak = [];
$listTetap = [];
$listMagang = [];

// Menangkap keyword pencarian
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($db) {
    try {
        // Instantiate class untuk query (hanya passing db)
        $kontrak = new KaryawanKontrak($db);
        $tetap = new KaryawanTetap($db);
        $magang = new KaryawanMagang($db);

        // Eksekusi method search dari tiap object
        $resultsKontrak = $kontrak->search($searchKeyword);
        $resultsTetap = $tetap->search($searchKeyword);
        $resultsMagang = $magang->search($searchKeyword);
        
        // Looping data array, mapping object ke subclass (Polymorphism)
        foreach ($resultsKontrak as $row) {
            $listKontrak[] = new KaryawanKontrak($db, $row);
        }
        foreach ($resultsTetap as $row) {
            $listTetap[] = new KaryawanTetap($db, $row);
        }
        foreach ($resultsMagang as $row) {
            $listMagang[] = new KaryawanMagang($db, $row);
        }
    } catch (Exception $e) {
        $errorMessage = "Terjadi kesalahan pada query. Detail: " . $e->getMessage();
    }
} else {
    $errorMessage = "Koneksi database gagal.";
}

// Helper Function untuk Merender Card Slip Gaji di HTML
function renderSlipGajiCard($karyawan, $badgeClass, $jenisText, $detailText) {
    // Memanggil metode polymorphism
    $gajiBersih = number_format($karyawan->hitungGajiBersih(), 0, ',', '.');
    $nama = htmlspecialchars($karyawan->getNamaKaryawan());
    $id = htmlspecialchars($karyawan->getIdKaryawan());
    $dept = htmlspecialchars($karyawan->getDepartemen());
    
    echo "
    <div class='slip-card'>
        <div class='slip-header'>
            <div>
                <h4>$nama</h4>
                <span class='slip-id'>ID: #$id</span>
            </div>
            <span class='badge $badgeClass'>$jenisText</span>
        </div>
        <div class='slip-body'>
            <div class='slip-info'>
                <span class='info-label'>Departemen</span>
                <span class='info-value'>$dept</span>
            </div>
            <div class='slip-salary'>
                <span class='salary-label'>Gaji Bersih</span>
                <span class='salary-value'>Rp $gajiBersih</span>
                <span class='salary-detail'>$detailText</span>
            </div>
        </div>
    </div>
    ";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji Karyawan - Dark Admin Panel</title>
    
    <!-- Font Modern -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* CSS RESET & VARIABLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', Arial, sans-serif;
        }

        :root {
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --border-color: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #3b82f6;
            --accent-green: #10b981;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background-color: var(--bg-card);
            border-right: 1px solid var(--border-color);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 10;
        }

        .sidebar-brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 40px;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand span { color: var(--primary); }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-menu li a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            padding: 12px 16px;
            border-radius: 8px;
            display: block;
            transition: all 0.2s ease;
        }

        .sidebar-menu li a:hover, .sidebar-menu li a.active {
            background-color: var(--border-color);
            color: var(--text-main);
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-left: 260px; /* Offset for fixed sidebar */
            padding: 40px 50px;
            width: calc(100% - 260px);
        }

        /* HEADER & SEARCH FORM */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }

        .header-content h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .header-content p {
            font-size: 1.05rem;
            color: var(--text-muted);
        }

        /* FITUR SEARCH UI IMPROVEMENTS */
        .search-form {
            display: flex;
            align-items: center;
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            padding: 5px 5px 5px 15px;
            transition: border-color 0.2s ease;
            position: relative;
        }

        .search-form:focus-within {
            border-color: var(--primary);
        }

        .search-input {
            background: transparent;
            border: none;
            outline: none;
            color: var(--text-main);
            font-size: 0.95rem;
            padding: 8px 10px;
            width: 250px;
        }

        .search-input::placeholder {
            color: #64748b;
        }

        .clear-btn {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s ease, color 0.2s ease;
            text-decoration: none;
        }

        .clear-btn:hover {
            color: #f87171;
            background-color: rgba(248, 113, 113, 0.1);
        }

        .search-btn {
            background-color: var(--primary);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-left: 5px;
        }

        .search-btn:hover {
            background-color: #2563eb;
        }

        /* Teks Kecil Pencarian */
        .search-feedback {
            margin-bottom: 30px;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .search-feedback strong {
            color: var(--text-main);
        }

        /* SECTION LAYOUT */
        .section-group {
            margin-bottom: 60px;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 22px;
            background-color: var(--primary);
            border-radius: 4px;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        /* CARD SLIP GAJI */
        .slip-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .slip-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
            border-color: #475569;
        }

        .slip-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 1px dashed var(--border-color);
            padding-bottom: 15px;
        }

        .slip-header h4 {
            font-size: 1.15rem;
            color: var(--text-main);
            font-weight: 600;
            margin-bottom: 4px;
        }

        .slip-id {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-family: monospace;
        }

        .slip-body {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .slip-info, .slip-salary {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .info-label, .salary-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .info-value {
            font-size: 1rem;
            color: #e2e8f0;
            font-weight: 500;
        }

        .slip-salary {
            margin-top: 5px;
            background-color: #0f172a;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #1e293b;
        }

        .salary-value {
            font-size: 1.5rem;
            color: var(--accent-green);
            font-weight: 700;
            font-family: monospace;
            letter-spacing: -0.5px;
        }

        .salary-detail {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 4px;
            font-style: italic;
        }

        /* BADGES */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-kontrak { background-color: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
        .badge-tetap { background-color: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .badge-magang { background-color: rgba(249, 115, 22, 0.15); color: #fb923c; border: 1px solid rgba(249, 115, 22, 0.3); }

        /* Empty State */
        .empty-state {
            grid-column: 1 / -1;
            color: var(--text-muted);
            font-style: italic;
            padding: 30px;
            background-color: var(--bg-card);
            border-radius: 8px;
            border: 1px dashed var(--border-color);
            text-align: center;
        }

        /* Responsiveness */
        @media (max-width: 992px) {
            .header-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }
            .search-form {
                width: 100%;
            }
            .search-input {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            HR System<span>.</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#" class="active">Dashboard</a></li>
            <li><a href="#kontrak">Karyawan Kontrak</a></li>
            <li><a href="#tetap">Karyawan Tetap</a></li>
            <li><a href="#magang">Karyawan Magang</a></li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        
        <!-- HEADER & SEARCH FORM -->
        <header class="header-top">
            <div class="header-content">
                <h1>Slip Gaji Karyawan</h1>
                <p>Sistem OOP PHP Native</p>
            </div>
            
            <form method="GET" action="index.php" class="search-form">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="search" class="search-input" placeholder="Cari nama karyawan..." value="<?php echo htmlspecialchars($searchKeyword); ?>" autocomplete="off">
                
                <!-- UX: Tombol Clear Search (Silang) Muncul Jika Ada Keyword -->
                <?php if (!empty($searchKeyword)): ?>
                    <a href="index.php" class="clear-btn" title="Reset Pencarian">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </a>
                <?php endif; ?>
                
                <button type="submit" class="search-btn">Cari</button>
            </form>
        </header>

        <!-- UX: Teks Kecil Feedback Pencarian -->
        <?php if (!empty($searchKeyword)): ?>
            <div class="search-feedback">
                Menampilkan hasil pencarian untuk: <strong>'<?php echo htmlspecialchars($searchKeyword); ?>'</strong>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div style="background-color: rgba(239,68,68,0.1); color: #f87171; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid rgba(239,68,68,0.2);">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <!-- SECTION KONTRAK -->
        <section id="kontrak" class="section-group">
            <h2 class="section-title">Karyawan Kontrak</h2>
            <div class="card-grid">
                <?php 
                if (empty($listKontrak)) {
                    // UX: Empty State jika Karyawan Tidak Ditemukan
                    echo "<div class='empty-state'>Karyawan tidak ditemukan</div>";
                } else {
                    foreach ($listKontrak as $karyawan) {
                        renderSlipGajiCard(
                            $karyawan, 
                            "badge-kontrak", 
                            "Kontrak", 
                            "Gaji Dasar * Hari Kerja"
                        );
                    }
                }
                ?>
            </div>
        </section>

        <!-- SECTION TETAP -->
        <section id="tetap" class="section-group">
            <h2 class="section-title" style="--primary: #10b981;">Karyawan Tetap</h2>
            <div class="card-grid">
                <?php 
                if (empty($listTetap)) {
                    // UX: Empty State jika Karyawan Tidak Ditemukan
                    echo "<div class='empty-state'>Karyawan tidak ditemukan</div>";
                } else {
                    foreach ($listTetap as $karyawan) {
                        renderSlipGajiCard(
                            $karyawan, 
                            "badge-tetap", 
                            "Tetap", 
                            "(Gaji Dasar * Hari Kerja) + Tunj. Kesehatan"
                        );
                    }
                }
                ?>
            </div>
        </section>

        <!-- SECTION MAGANG -->
        <section id="magang" class="section-group">
            <h2 class="section-title" style="--primary: #f97316;">Karyawan Magang</h2>
            <div class="card-grid">
                <?php 
                if (empty($listMagang)) {
                    // UX: Empty State jika Karyawan Tidak Ditemukan
                    echo "<div class='empty-state'>Karyawan tidak ditemukan</div>";
                } else {
                    foreach ($listMagang as $karyawan) {
                        renderSlipGajiCard(
                            $karyawan, 
                            "badge-magang", 
                            "Magang", 
                            "(Gaji Dasar * Hari Kerja) * 80%"
                        );
                    }
                }
                ?>
            </div>
        </section>

    </main>

</body>
</html>
