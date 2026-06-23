<?php
require_once 'config/database.php';
require_once 'classes/Karyawan.php';

$database = new Database();
$db = $database->connect();

$karyawanList = [];
$connectionStatus = false;
$errorMessage = "";

if ($db) {
    $connectionStatus = true;
    $karyawan = new Karyawan($db);
    try {
        $stmt = $karyawan->read();
        $karyawanList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $errorMessage = "Tabel karyawan tidak ditemukan atau query bermasalah. Pastikan Anda telah mengimpor file SQL ke database Anda. Detail: " . $e->getMessage();
    }
} else {
    $errorMessage = "Koneksi database gagal. Silakan periksa kembali konfigurasi koneksi database Anda.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAS PBO - SIPERUKA (Achmal Maulana)</title>
    
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
            --accent-primary: #3b82f6;
            --accent-glow: rgba(59, 130, 246, 0.5);
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
            justify-content: flex-start;
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
                grid-template-columns: 320px 1fr;
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
            transition: all 0.3s ease;
        }

        .card:hover {
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 15px 35px -5px rgba(59, 130, 246, 0.1);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--card-border);
            padding-bottom: 0.75rem;
        }

        /* Status Badge Component */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
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

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .connected .status-dot {
            background-color: var(--success-color);
            box-shadow: 0 0 8px var(--success-color);
            animation: pulse 2s infinite;
        }

        .disconnected .status-dot {
            background-color: var(--danger-color);
            box-shadow: 0 0 8px var(--danger-color);
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .meta-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1rem;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .meta-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
        }

        .meta-value {
            font-size: 0.95rem;
            font-weight: 500;
        }

        /* Database Info Alert */
        .info-alert {
            background: rgba(59, 130, 246, 0.08);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 8px;
            padding: 1rem;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #93c5fd;
            margin-top: 1rem;
        }

        /* Table Styling */
        .table-container {
            overflow-x: auto;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            padding: 1rem;
            font-weight: 600;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--card-border);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            font-size: 0.95rem;
            color: var(--text-main);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .badge-jabatan {
            background-color: rgba(59, 130, 246, 0.15);
            color: #93c5fd;
            border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
        }

        /* Empty / Error state */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-secondary);
        }

        .error-message {
            color: var(--danger-color);
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .error-title {
            font-weight: 600;
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
        <h1 class="header-title">SIPERUKA</h1>
        <p class="header-subtitle">Sistem Informasi Karyawan - UAS Pemrograman Berorientasi Objek</p>
    </header>

    <main>
        <!-- Sidebar Meta Info -->
        <section class="card">
            <h2 class="card-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent-primary);"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Status Sistem
            </h2>
            
            <div class="meta-item">
                <span class="meta-label">Koneksi Database</span>
                <?php if ($connectionStatus): ?>
                    <span class="status-badge connected">
                        <span class="status-dot"></span> Terhubung
                    </span>
                <?php else: ?>
                    <span class="status-badge disconnected">
                        <span class="status-dot"></span> Terputus
                    </span>
                <?php endif; ?>
            </div>

            <ul class="meta-list">
                <li class="meta-item">
                    <span class="meta-label">Nama Database</span>
                    <span class="meta-value" style="font-family: monospace;">DB_UAS_PBO_TRPL1B_Achmal_Maulana</span>
                </li>
                <li class="meta-item">
                    <span class="meta-label">Nama Mahasiswa</span>
                    <span class="meta-value">Achmal Maulana</span>
                </li>
                <li class="meta-item">
                    <span class="meta-label">Kelas</span>
                    <span class="meta-value">TRPL 1B</span>
                </li>
            </ul>

            <div class="info-alert">
                Aplikasi ini mendemonstrasikan pemrograman berorientasi objek menggunakan PHP, PDO, dan arsitektur database relasional.
            </div>
        </section>

        <!-- Main Content (Karyawan List) -->
        <section class="card" style="overflow: hidden;">
            <h2 class="card-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent-primary);"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Daftar Karyawan
            </h2>

            <?php if (!empty($errorMessage)): ?>
                <div class="error-message">
                    <div class="error-title">Kesalahan Sistem:</div>
                    <div><?php echo htmlspecialchars($errorMessage); ?></div>
                </div>
            <?php endif; ?>

            <div class="table-container">
                <?php if ($connectionStatus && !empty($karyawanList)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama Lengkap</th>
                                <th>Jabatan</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($karyawanList as $row): ?>
                                <tr>
                                    <td style="font-family: monospace; font-weight: 500; color: #a78bfa;"><?php echo htmlspecialchars($row['nip']); ?></td>
                                    <td style="font-weight: 600;"><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><span class="badge-jabatan"><?php echo htmlspecialchars($row['jabatan']); ?></span></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_telp']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary); margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <p>Tidak ada data karyawan yang dapat ditampilkan.</p>
                        <p style="font-size: 0.85rem; margin-top: 0.5rem;">Pastikan database sudah didefinisikan dan tabel karyawan sudah terisi data.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Achmal Maulana. UAS Pemrograman Berorientasi Objek - Kelas TRPL 1B.</p>
    </footer>
</body>
</html>
