<?php
session_start();
require '../config.php';

// CEK LOGIN
if(empty($_SESSION['admin_id'])){
    header("Location: login.php");
    exit;
}

// AMBIL ROLE
$role = $_SESSION['role']; // admin atau pegawai


// ======================================================
// PEMBATASAN AKSI UNTUK PEGAWAI
// ======================================================
if($role == 'pegawai' && isset($_GET['kirim'])){
    echo "<script>alert('Anda tidak memiliki akses untuk fitur ini!');window.location='dashboard.php';</script>";
    exit;
}


// ======================================================
// ADMIN BOLEH MENGUBAH STATUS TRANSAKSI
// ======================================================
if($role == 'admin' && isset($_GET['kirim'])){
    $id_transaksi = (int)$_GET['kirim'];

    $stmt = $pdo->prepare("UPDATE tb_transaksi SET status='sukses' WHERE id_transaksi=?");
    $stmt->execute([$id_transaksi]);

    header("Location: dashboard.php");
    exit;
}


// ======================================================
// AMBIL DATA TRANSAKSI
// ======================================================
$t = $pdo->query("SELECT t.*, p.nama_produk 
                  FROM tb_transaksi t 
                  JOIN tb_produk p ON t.id_produk=p.id_produk 
                  ORDER BY t.tanggal DESC")->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background:#0f0f0f; color:#fff; font-family:Poppins, sans-serif;}
.table td, .table th {color:#fff;}
.btn-success {background:#00cc44; border:none;}
.btn-success:hover {background:#009933;}
.btn-warning {background:#ffaa00; border:none;}
.btn-warning:hover {background:#cc8800;}
</style>
</head>
<body>
<div class="container py-4">

<h4>Dashboard Fann Store</h4>

<!-- TOMBOL MENU KHUSUS ADMIN -->
<?php if($role == 'admin'): ?>
<a href="products_crud.php" class="btn btn-primary mb-3">Kelola Produk</a>
<a href="keuangan.php" class="btn btn-info mb-3">Keuangan</a>
<?php endif; ?>

<!-- MENU YANG BISA DIAKSES ADMIN & PEGAWAI -->
<a href="pengeluaran.php" class="btn btn-warning mb-3">Restock</a>
<a href="logout.php" class="btn btn-danger mb-3">Logout</a>

<table class="table table-dark table-bordered align-middle">
<tr>
<th>Produk</th>
<th>Jumlah</th>
<th>Total</th>
<th>Tanggal</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php foreach($t as $d): ?>
<tr>
<td><?php echo $d['nama_produk']; ?></td>
<td><?php echo $d['jumlah']; ?></td>
<td>Rp <?php echo number_format($d['total'],0,',','.'); ?></td>
<td><?php echo $d['tanggal']; ?></td>
<td><?php echo $d['status']; ?></td>

<td>
<?php if($d['status']=='pending'): ?>

    <?php if($role == 'admin'): ?>
        <!-- ADMIN BISA KIRIM -->
        <a href="dashboard.php?kirim=<?php echo $d['id_transaksi']; ?>" 
           class="btn btn-success btn-sm"
           onclick="return confirm('Kirim diamond untuk transaksi ini?')">Kirim</a>
    <?php else: ?>
        <!-- PEGAWAI TIDAK BISA -->
        <span class="btn btn-secondary btn-sm disabled">Tidak Ada Akses</span>
    <?php endif; ?>

<?php else: ?>
    <span class="btn btn-warning btn-sm disabled">Sukses</span>
<?php endif; ?>
</td>

</tr>
<?php endforeach; ?>
</table>

</div>
</body>
</html>
