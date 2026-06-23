<?php
require_once 'config/database.php';

// Memuat seluruh class turunan.
require_once 'classes/KaryawanKontrak.php';
require_once 'classes/KaryawanTetap.php';
require_once 'classes/KaryawanMagang.php';

$database = new Database();
$db = $database->connect();

$daftarKaryawan = [];
$connectionStatus = false;
$errorMessage = "";

if ($db) {
    $connectionStatus = true;
    try {
        // Mengambil semua data dari tabel_karyawan menggunakan PDO
        $query = "SELECT * FROM tabel_karyawan ORDER BY id_karyawan ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Looping data array, mapping object sesuai jenis_karyawan (Polymorphism)
        foreach ($results as $row) {
            if ($row['jenis_karyawan'] === 'Kontrak') {
                $daftarKaryawan[] = new KaryawanKontrak($row);
            } elseif ($row['jenis_karyawan'] === 'Tetap') {
                $daftarKaryawan[] = new KaryawanTetap($row);
            } elseif ($row['jenis_karyawan'] === 'Magang') {
                $daftarKaryawan[] = new KaryawanMagang($row);
            }
        }
    } catch (Exception $e) {
        $errorMessage = "Terjadi kesalahan pada query. Detail: " . $e->getMessage();
    }
} else {
    $errorMessage = "Koneksi database gagal.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Karyawan - UAS PBO</title>
    
    <!-- Meta Tags SEO -->
    <meta name="description" content="Dashboard Sistem Informasi Karyawan - UAS PBO TRPL 1B Achmal Maulana">
    <meta name="author" content="Achmal Maulana">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(17, 24, 39, 0.7);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-main: #f3f4f6;
            --text-secondary: #9ca3af;
            --success-color: #10b981;
            --danger-color: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 10% 10%, rgba(59, 130, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 90% 90%, rgba(139, 92, 246, 0.15) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1.5rem;
        }

        header {
            width: 100%;
            max-width: 1200px;
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .header-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .header-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 300;
        }

        main {
            width: 100%;
            max-width: 1200px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 992px) {
            main {
                grid-template-columns: 280px 1fr;
            }
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--card-border);
            padding-bottom: 0.75rem;
            color: #fff;
        }

        .status-badge {
            display: inline-block;
            font-weight: 500;
            font-size: 0.85rem;
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            margin-top: 0.5rem;
        }

        .status-badge.connected {
            background-color: rgba(16, 185, 129, 0.15);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-badge.disconnected {
            background-color: rgba(239, 68, 68, 0.15);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .meta-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            margin-top: 1rem;
        }

        .meta-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--text-secondary);
            display: block;
            margin-bottom: 0.25rem;
        }

        .meta-value {
            font-size: 0.95rem;
            font-weight: 500;
        }

        /* CSS Tabel */
        .table-container {
            overflow-x: auto;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            margin-top: 0.5rem;
        }

        th {
            padding: 1.2rem 1rem;
            font-weight: 600;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--card-border);
            font-size: 0.875rem;
            text-transform: uppercase;
        }

        td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.95rem;
            color: var(--text-main);
            transition: background-color 0.2s ease;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: rgba(255, 255, 255, 0.04);
        }

        /* Badge Warna Karyawan Spesifik */
        .badge-jabatan {
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-kontrak {
            background-color: rgba(59, 130, 246, 0.15);
            color: #93c5fd; /* Biru */
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .badge-tetap {
            background-color: rgba(16, 185, 129, 0.15);
            color: #6ee7b7; /* Hijau */
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-magang {
            background-color: rgba(249, 115, 22, 0.15);
            color: #fdba74; /* Oranye */
            border: 1px solid rgba(249, 115, 22, 0.3);
        }

        footer {
            margin-top: auto;
            padding-top: 3rem;
            color: var(--text-secondary);
            font-size: 0.85rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1 class="header-title">Sistem Manajemen Karyawan</h1>
        <p class="header-subtitle">UAS Pemrograman Berorientasi Objek - Achmal Maulana</p>
    </header>

    <main>
        <!-- Sidebar Meta Info -->
        <section class="card">
            <h2 class="card-title">Status Sistem</h2>
            
            <div>
                <span class="meta-label">Koneksi Database</span>
                <?php if ($connectionStatus): ?>
                    <span class="status-badge connected">Terhubung</span>
                <?php else: ?>
                    <span class="status-badge disconnected">Terputus</span>
                <?php endif; ?>
            </div>

            <ul class="meta-list">
                <li>
                    <span class="meta-label">Nama Database</span>
                    <span class="meta-value" style="font-family: monospace;">DB_UAS_PBO_TRPL1B_Achmal_Maulana</span>
                </li>
                <li>
                    <span class="meta-label">Mahasiswa</span>
                    <span class="meta-value">Achmal Maulana (TRPL 1B)</span>
                </li>
            </ul>
        </section>

        <!-- Main Content (Karyawan List) -->
        <section class="card" style="overflow: hidden;">
            <h2 class="card-title">Daftar Karyawan</h2>

            <?php if (!empty($errorMessage)): ?>
                <div style="color: #ef4444; padding: 1rem; border: 1px solid #ef4444; background: rgba(239, 68, 68, 0.08); border-radius: 8px; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
            <?php endif; ?>

            <div class="table-container">
                <?php if ($connectionStatus && !empty($daftarKaryawan)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Karyawan</th>
                                <th>Departemen</th>
                                <th>Jenis Karyawan</th>
                                <th>Gaji Bersih</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($daftarKaryawan as $karyawan): 
                                // Menentukan class object dan warna badge
                                $className = get_class($karyawan);
                                $badgeClass = 'badge-kontrak'; // default
                                $jenisText = 'Kontrak';

                                if ($className === 'KaryawanTetap') {
                                    $badgeClass = 'badge-tetap';
                                    $jenisText = 'Tetap';
                                } elseif ($className === 'KaryawanMagang') {
                                    $badgeClass = 'badge-magang';
                                    $jenisText = 'Magang';
                                }
                            ?>
                                <tr>
                                    <td style="color: #9ca3af; font-weight: 500;">
                                        #<?php echo htmlspecialchars($karyawan->getIdKaryawan()); ?>
                                    </td>
                                    
                                    <td style="font-weight: 600; color: #f3f4f6;">
                                        <?php echo htmlspecialchars($karyawan->getNamaKaryawan()); ?>
                                    </td>
                                    
                                    <td>
                                        <?php echo htmlspecialchars($karyawan->getDepartemen()); ?>
                                    </td>
                                    
                                    <td>
                                        <span class="badge-jabatan <?php echo $badgeClass; ?>">
                                            <?php echo htmlspecialchars($jenisText); ?>
                                        </span>
                                    </td>
                                    
                                    <td style="font-family: monospace; font-size: 1.05rem; font-weight: 500; color: #10b981;">
                                        Rp <?php echo number_format($karyawan->hitungGajiBersih(), 0, ',', '.'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: #9ca3af; padding: 2rem;">Tidak ada data karyawan.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Achmal Maulana. UAS Pemrograman Berorientasi Objek - Kelas TRPL 1B.</p>
    </footer>
</body>
</html>
