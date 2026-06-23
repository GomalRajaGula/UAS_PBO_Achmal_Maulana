<?php
require_once 'config/database.php';

// Memuat seluruh class turunan. Aturan OOP: Karyawan (Abstract) DILARANG di-instantiate.
require_once 'classes/KaryawanKontrak.php';
require_once 'classes/KaryawanTetap.php';
require_once 'classes/KaryawanMagang.php';

$database = new Database();
$db = $database->connect();

$errorMessage = "";

// 3. GROUPING DATA: Array penampung terpisah berdasarkan tipe
$listKontrak = [];
$listTetap = [];
$listMagang = [];

if ($db) {
    try {
        // 1. Ambil data karyawan dari database menggunakan PDO
        $query = "SELECT * FROM tabel_karyawan ORDER BY id_karyawan ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 2 & 10. Looping data array, mapping object ke subclass (Polymorphism)
        foreach ($results as $row) {
            if ($row['jenis_karyawan'] === 'Kontrak') {
                $listKontrak[] = new KaryawanKontrak($row);
            } elseif ($row['jenis_karyawan'] === 'Tetap') {
                $listTetap[] = new KaryawanTetap($row);
            } elseif ($row['jenis_karyawan'] === 'Magang') {
                $listMagang[] = new KaryawanMagang($row);
            }
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
            --bg-card-hover: #1f2937;
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

        /* 6. SIDEBAR */
        .sidebar {
            width: 260px;
            background-color: var(--bg-card);
            border-right: 1px solid var(--border-color);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
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

        /* 7. HEADER */
        .header {
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .header p {
            font-size: 1.05rem;
            color: var(--text-muted);
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

        /* 4 & 5. CARD SLIP GAJI */
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
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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

        /* Highlight Salary Area */
        .slip-salary {
            margin-top: 5px;
            background-color: #0f172a; /* Warna lebih gelap untuk kontras gaji */
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

        /* 9. BADGES */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-kontrak { background-color: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); } /* Blue */
        .badge-tetap { background-color: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); } /* Green */
        .badge-magang { background-color: rgba(249, 115, 22, 0.15); color: #fb923c; border: 1px solid rgba(249, 115, 22, 0.3); } /* Orange */

        /* Error / Empty State */
        .error-message {
            background-color: rgba(239, 68, 68, 0.1);
            color: #f87171;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

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
    </style>
</head>
<body>

    <!-- 6. SIDEBAR -->
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
        
        <!-- 7. HEADER -->
        <header class="header">
            <h1>Slip Gaji Karyawan</h1>
            <p>Sistem OOP PHP Native</p>
        </header>

        <?php if (!empty($errorMessage)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <!-- 3A. SECTION KONTRAK -->
        <section id="kontrak" class="section-group">
            <h2 class="section-title">Karyawan Kontrak</h2>
            <div class="card-grid">
                <?php 
                if (empty($listKontrak)) {
                    echo "<div class='empty-state'>Tidak ada data karyawan kontrak.</div>";
                } else {
                    foreach ($listKontrak as $karyawan) {
                        // Render UI untuk Karyawan Kontrak
                        renderSlipGajiCard(
                            $karyawan, 
                            "badge-kontrak", 
                            "Kontrak", 
                            "Gaji Dasar * Hari Kerja" // Optional Note
                        );
                    }
                }
                ?>
            </div>
        </section>

        <!-- 3B. SECTION TETAP -->
        <section id="tetap" class="section-group">
            <h2 class="section-title" style="--primary: #10b981;">Karyawan Tetap</h2>
            <div class="card-grid">
                <?php 
                if (empty($listTetap)) {
                    echo "<div class='empty-state'>Tidak ada data karyawan tetap.</div>";
                } else {
                    foreach ($listTetap as $karyawan) {
                        // Render UI untuk Karyawan Tetap
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

        <!-- 3C. SECTION MAGANG -->
        <section id="magang" class="section-group">
            <h2 class="section-title" style="--primary: #f97316;">Karyawan Magang</h2>
            <div class="card-grid">
                <?php 
                if (empty($listMagang)) {
                    echo "<div class='empty-state'>Tidak ada data karyawan magang.</div>";
                } else {
                    foreach ($listMagang as $karyawan) {
                        // Render UI untuk Karyawan Magang
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
