<?php
session_start();
require '../config.php';

// pastikan admin login
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Hitung total pemasukan (pakai kolom nominal)
$qMasuk = $pdo->query("
    SELECT COALESCE(SUM(nominal),0) AS total_masuk
    FROM tb_keuangan
    WHERE jenis = 'pemasukan'
");
$rowMasuk = $qMasuk->fetch();
$total_masuk = $rowMasuk['total_masuk'] ?? 0;

// Hitung total pengeluaran (pakai kolom nominal)
$qKeluar = $pdo->query("
    SELECT COALESCE(SUM(nominal),0) AS total_keluar
    FROM tb_keuangan
    WHERE jenis = 'pengeluaran'
");
$rowKeluar = $qKeluar->fetch();
$total_keluar = $rowKeluar['total_keluar'] ?? 0;

// Profit / selisih
$profit = $total_masuk - $total_keluar;

// Ambil histori pemasukan (urut terbaru)
$histMasuk = $pdo->query("
    SELECT id_keuangan, tanggal, keterangan, jumlah, nominal
    FROM tb_keuangan
    WHERE jenis = 'pemasukan'
    ORDER BY tanggal DESC, id_keuangan DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Ambil histori pengeluaran (urut terbaru)
$histKeluar = $pdo->query("
    SELECT id_keuangan, tanggal, keterangan, jumlah, nominal
    FROM tb_keuangan
    WHERE jenis = 'pengeluaran'
    ORDER BY tanggal DESC, id_keuangan DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Keuangan - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #0d0d0d;
    color: #ffffff;
    font-family: Poppins, sans-serif;
}
.container { max-width: 1100px; }
.card {
    background: #141414;
    border: 1px solid #222;
    color: #fff;
}
.table th, .table td {
    color: #fff;
}
.badge-in { background:#198754; color:#fff; }
.badge-out { background:#dc3545; color:#fff; }
.value-big { font-size:1.6rem; font-weight:700; }
.small-muted { color:#cfcfcf; font-size:0.9rem; }
</style>
</head>
<body>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Keuangan</h3>
        <div>
            <a href="dashboard.php" class="btn btn-sm btn-light">Kembali</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-3">
                <div class="small-muted">Total Pemasukan</div>
                <div class="value-big">Rp <?php echo number_format($total_masuk,0,',','.'); ?></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <div class="small-muted">Total Pengeluaran</div>
                <div class="value-big">Rp <?php echo number_format($total_keluar,0,',','.'); ?></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <div class="small-muted">Profit / Selisih</div>
                <div class="value-big">Rp <?php echo number_format($profit,0,',','.'); ?></div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Kolom Pemasukan -->
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Histori Pemasukan</h5>
                    <span class="small-muted">Total: Rp <?php echo number_format($total_masuk,0,',','.'); ?></span>
                </div>

                <?php if(empty($histMasuk)): ?>
                    <div class="alert alert-secondary bg-dark text-white">Belum ada pemasukan tercatat.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($histMasuk as $r): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($r['tanggal']); ?></td>
                                        <td><?php echo htmlspecialchars($r['keterangan']); ?></td>
                                        <td class="text-end"><?php echo number_format((int)$r['jumlah'],0,',','.'); ?></td>
                                        <td class="text-end">Rp <?php echo number_format((int)$r['nominal'],0,',','.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Kolom Pengeluaran -->
        <div class="col-md-6 mb-4">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Histori Pengeluaran</h5>
                    <span class="small-muted">Total: Rp <?php echo number_format($total_keluar,0,',','.'); ?></span>
                </div>

                <?php if(empty($histKeluar)): ?>
                    <div class="alert alert-secondary bg-dark text-white">Belum ada pengeluaran tercatat.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($histKeluar as $r): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($r['tanggal']); ?></td>
                                        <td><?php echo htmlspecialchars($r['keterangan']); ?></td>
                                        <td class="text-end"><?php echo number_format((int)$r['jumlah'],0,',','.'); ?></td>
                                        <td class="text-end">Rp <?php echo number_format((int)$r['nominal'],0,',','.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
</body>
</html>
