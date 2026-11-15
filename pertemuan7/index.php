<?php
require_once __DIR__ . '/Buku.php';

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$buku = new Buku();
$errors = [];
$searchInput = $_GET['q'] ?? '';
$search = is_string($searchInput) ? trim($searchInput) : '';
$editId = '';
$judulForm = '';
$penulisForm = '';
$stokForm = '';
$statusInput = $_GET['status'] ?? '';
$status = is_string($statusInput) ? $statusInput : '';

if (isset($_GET['q']) && !is_string($_GET['q'])) {
    $errors[] = 'Kata pencarian tidak valid.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $idInput = $_POST['id'] ?? '';
        $judulInput = $_POST['judul'] ?? '';
        $penulisInput = $_POST['penulis'] ?? '';
        $editId = is_string($idInput) ? trim($idInput) : '';
        $judulForm = is_string($judulInput) ? trim($judulInput) : '';
        $penulisForm = is_string($penulisInput) ? trim($penulisInput) : '';
        $stokInput = $_POST['stok'] ?? '';
        $stokForm = is_string($stokInput) ? $stokInput : '';

        try {
            if (!is_string($idInput)) {
                throw new InvalidArgumentException('ID buku tidak valid.');
            } elseif ($editId === '') {
                $buku->create($judulForm, $penulisForm, $stokForm);
                header('Location: index.php?status=created');
            } else {
                $buku->update($editId, $judulForm, $penulisForm, $stokForm);
                header('Location: index.php?status=updated');
            }
            exit;
        } catch (InvalidArgumentException $error) {
            $errors[] = $error->getMessage();
        }
    } elseif ($action === 'delete') {
        try {
            $buku->delete($_POST['id'] ?? '');
            header('Location: index.php?status=deleted');
            exit;
        } catch (InvalidArgumentException $error) {
            $errors[] = $error->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && isset($_GET['edit'])) {
    try {
        $editId = $_GET['edit'];
        $bukuEdit = $buku->find($editId);
        if ($bukuEdit === null) {
            $errors[] = 'Buku tidak ditemukan.';
            $editId = '';
        } else {
            $judulForm = $bukuEdit['judul'];
            $penulisForm = $bukuEdit['penulis'] ?? '';
            $stokForm = $bukuEdit['stok'];
        }
    } catch (InvalidArgumentException $error) {
        $errors[] = $error->getMessage();
        $editId = '';
    }
}

$daftarBuku = $buku->all($search);
$pesanStatus = [
    'created' => 'Buku berhasil ditambahkan.',
    'updated' => 'Buku berhasil diperbarui.',
    'deleted' => 'Buku berhasil dihapus.',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perpustakaan — Apple Style</title>
    <style>
        :root {
            --bg-body: #f5f5f7;
            --bg-card: rgba(255, 255, 255, 0.85);
            --bg-card-solid: #ffffff;
            --text-primary: #1d1d1f;
            --text-secondary: #86868b;
            --text-tertiary: #a1a1a6;
            --accent: #0071e3;
            --accent-hover: #0077ed;
            --accent-active: #005bb5;
            --accent-tint: rgba(0, 113, 227, 0.08);
            --danger: #ff3b30;
            --danger-hover: #e03126;
            --danger-tint: rgba(255, 59, 48, 0.1);
            --success: #34c759;
            --success-tint: rgba(52, 199, 89, 0.12);
            --warning: #ff9500;
            --warning-tint: rgba(255, 149, 0, 0.12);
            --border-subtle: rgba(0, 0, 0, 0.08);
            --border-focus: rgba(0, 113, 227, 0.4);
            --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            --shadow-elevated: 0 12px 32px rgba(0, 0, 0, 0.08), 0 2px 6px rgba(0, 0, 0, 0.04);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 18px;
            --radius-pill: 9999px;
            --font-stack: -apple-system, BlinkMacSystemFont, "SF Pro Display", "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: var(--font-stack);
            background-color: var(--bg-body);
            color: var(--text-primary);
            line-height: 1.5;
            padding: 32px 20px 60px;
            min-height: 100vh;
        }

        .container {
            max-width: 1080px;
            margin: 0 auto;
        }

        /* Top Bar / Header */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            padding: 0 4px;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #0071e3 0%, #42a5f5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 113, 227, 0.25);
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }

        .header-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .header-meta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: var(--radius-pill);
            background: rgba(0, 0, 0, 0.04);
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        /* Alerts & Toasts */
        .alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            animation: fadeIn 0.25s ease-out;
        }

        .alert-success {
            background-color: var(--success-tint);
            color: #1a7431;
            border: 1px solid rgba(52, 199, 89, 0.25);
        }

        .alert-danger {
            background-color: var(--danger-tint);
            color: #c92a2a;
            border: 1px solid rgba(255, 59, 48, 0.25);
        }

        .alert svg {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
        }

        /* Layout Grid */
        .layout-grid {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 860px) {
            .layout-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Card System */
        .card {
            background: var(--bg-card-solid);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-subtle);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-elevated);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            letter-spacing: -0.01em;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-body {
            padding: 24px;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 8px;
            letter-spacing: -0.005em;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text-primary);
            background: #fbfbfd;
            border: 1px solid #d2d2d7;
            border-radius: var(--radius-sm);
            outline: none;
            transition: all 0.18s ease;
        }

        .form-control:hover {
            border-color: #a1a1a6;
        }

        .form-control:focus {
            background: #ffffff;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0, 113, 227, 0.16);
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 24px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 18px;
            font-size: 13.5px;
            font-weight: 500;
            font-family: inherit;
            border-radius: var(--radius-pill);
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.16s ease;
            white-space: nowrap;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .btn-primary {
            background-color: var(--accent);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--accent-hover);
            box-shadow: 0 2px 8px rgba(0, 113, 227, 0.3);
        }

        .btn-secondary {
            background-color: #ebebeb;
            color: var(--text-primary);
        }

        .btn-secondary:hover {
            background-color: #e0e0e0;
        }

        .btn-danger {
            background-color: transparent;
            color: var(--danger);
            border-color: rgba(255, 59, 48, 0.2);
            padding: 5px 12px;
            font-size: 12.5px;
        }

        .btn-danger:hover {
            background-color: var(--danger);
            color: #ffffff;
            border-color: var(--danger);
        }

        .btn-edit {
            background-color: var(--accent-tint);
            color: var(--accent);
            padding: 5px 12px;
            font-size: 12.5px;
        }

        .btn-edit:hover {
            background-color: var(--accent);
            color: #ffffff;
        }

        /* Search Bar */
        .search-box {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .search-input-wrap {
            position: relative;
            flex: 1;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--text-tertiary);
            pointer-events: none;
        }

        .search-input {
            padding-left: 36px;
            background: #ffffff;
            border-radius: var(--radius-pill);
        }

        /* Table Design */
        .table-wrapper {
            overflow-x: auto;
            border-radius: var(--radius-md);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13.5px;
        }

        th {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            font-weight: 600;
            padding: 12px 16px;
            background: #fafafa;
            border-bottom: 1px solid var(--border-subtle);
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-primary);
            vertical-align: middle;
        }

        tbody tr {
            transition: background-color 0.15s ease;
        }

        tbody tr:hover {
            background-color: #fafafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* Stock Badges */
        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: var(--radius-pill);
            font-size: 12px;
            font-weight: 600;
        }

        .stock-in {
            background-color: var(--success-tint);
            color: #1a7431;
        }

        .stock-low {
            background-color: var(--warning-tint);
            color: #b36200;
        }

        .stock-out {
            background-color: var(--danger-tint);
            color: #c92a2a;
        }

        .book-id-badge {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 12px;
            color: var(--text-tertiary);
            background: #f0f0f2;
            padding: 2px 7px;
            border-radius: 6px;
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-secondary);
        }

        .empty-state-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 12px;
            color: var(--text-tertiary);
        }

        .empty-state-text {
            font-size: 14px;
            font-weight: 500;
        }

        /* Editing indicator pill */
        .editing-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-tint);
            color: var(--accent);
            padding: 3px 10px;
            border-radius: var(--radius-pill);
            font-size: 11.5px;
            font-weight: 600;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="header-brand">
                <div class="header-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="header-title">Perpustakaan</h1>
                    <p class="header-subtitle">Manajemen Koleksi & Inventaris Buku</p>
                </div>
            </div>
            <div class="header-meta">
                <span><?= count($daftarBuku) ?> Koleksi Terdaftar</span>
            </div>
        </header>

        <!-- Status Alerts -->
        <?php if (isset($pesanStatus[$status])): ?>
            <div class="alert alert-success" role="status">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span><?= h($pesanStatus[$status]) ?></span>
            </div>
        <?php endif; ?>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span><?= h($error) ?></span>
            </div>
        <?php endforeach; ?>

        <!-- Layout Grid -->
        <div class="layout-grid">
            <!-- Left Side: Form -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <span><?= $editId !== '' ? 'Edit Buku' : 'Tambah Buku Baru' ?></span>
                        <?php if ($editId !== ''): ?>
                            <span class="editing-indicator">Mode Edit</span>
                        <?php endif; ?>
                    </h2>
                </div>
                <div class="card-body">
                    <form method="post" action="index.php">
                        <input type="hidden" name="action" value="save">
                        <input type="hidden" name="id" value="<?= h(is_string($editId) ? $editId : '') ?>">

                        <div class="form-group">
                            <label class="form-label" for="judul">Judul Buku</label>
                            <input class="form-control" id="judul" name="judul" type="text" placeholder="Contoh: Pemrograman Modern" value="<?= h($judulForm) ?>" required autofocus>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="penulis">Nama Penulis</label>
                            <input class="form-control" id="penulis" name="penulis" type="text" placeholder="Contoh: Budi Raharjo" value="<?= h($penulisForm) ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="stok">Jumlah Stok</label>
                            <input class="form-control" id="stok" name="stok" type="number" min="0" step="1" placeholder="0" value="<?= h($stokForm) ?>" required>
                        </div>

                        <div class="form-actions">
                            <button class="btn btn-primary" type="submit" style="flex: 1;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                <span><?= $editId !== '' ? 'Simpan Perubahan' : 'Tambah Buku' ?></span>
                            </button>
                            <?php if ($editId !== ''): ?>
                                <a class="btn btn-secondary" href="index.php">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side: Search & Table -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <span>Koleksi Buku</span>
                    </h2>
                </div>
                <div class="card-body">
                    <!-- Search Bar -->
                    <form class="search-box" method="get" action="index.php">
                        <div class="search-input-wrap">
                            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input class="form-control search-input" id="q" name="q" type="search" placeholder="Cari berdasarkan judul atau penulis..." value="<?= h($search) ?>">
                        </div>
                        <button class="btn btn-secondary" type="submit">Cari</button>
                        <?php if ($search !== ''): ?>
                            <a class="btn btn-secondary" href="index.php" title="Hapus Filter">Reset</a>
                        <?php endif; ?>
                    </form>

                    <!-- Table List -->
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 50px;">ID</th>
                                    <th>Judul Buku</th>
                                    <th style="width: 150px;">Penulis</th>
                                    <th style="width: 110px;">Stok</th>
                                    <th style="width: 130px; text-align: right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!$daftarBuku): ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="11" cy="11" r="8"></circle>
                                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                    <line x1="8" y1="11" x2="14" y2="11"></line>
                                                </svg>
                                                <p class="empty-state-text">
                                                    <?= $search !== '' ? 'Tidak ditemukan buku dengan kata kunci "' . h($search) . '"' : 'Belum ada koleksi buku yang tersimpan.' ?>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($daftarBuku as $item): ?>
                                        <?php
                                            $stokNum = (int) $item['stok'];
                                            if ($stokNum <= 0) {
                                                $stockClass = 'stock-out';
                                                $stockLabel = 'Habis (0)';
                                            } elseif ($stokNum <= 5) {
                                                $stockClass = 'stock-low';
                                                $stockLabel = 'Sisa ' . $stokNum;
                                            } else {
                                                $stockClass = 'stock-in';
                                                $stockLabel = $stokNum . ' Tersedia';
                                            }
                                        ?>
                                        <tr>
                                            <td><span class="book-id-badge">#<?= h($item['id']) ?></span></td>
                                            <td style="font-weight: 500; color: var(--text-primary);"><?= h($item['judul']) ?></td>
                                            <td style="color: var(--text-secondary);"><?= h($item['penulis'] ?? '-') ?></td>
                                            <td>
                                                <span class="stock-badge <?= $stockClass ?>">
                                                    <?= h($stockLabel) ?>
                                                </span>
                                            </td>
                                            <td style="text-align: right;">
                                                <div class="table-actions" style="justify-content: flex-end;">
                                                    <a class="btn btn-edit" href="index.php?edit=<?= h($item['id']) ?>">
                                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                        <span>Edit</span>
                                                    </a>
                                                    <form method="post" action="index.php" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?');" style="margin: 0;">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?= h($item['id']) ?>">
                                                        <button class="btn btn-danger" type="submit">
                                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                            <span>Hapus</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>