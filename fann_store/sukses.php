<?php
session_start();
require 'config.php';

// Jika ada info transaksi terakhir di session (dari checkout.php)
$lastTransaksiIDs = $_SESSION['last_transaksi']['ids'] ?? [];

// Ambil data transaksi terakhir berdasarkan ID transaksi
if (!empty($lastTransaksiIDs)) {
    $placeholders = implode(',', array_fill(0, count($lastTransaksiIDs), '?'));
    $stmt = $pdo->prepare("SELECT * FROM tb_transaksi WHERE id_transaksi IN ($placeholders) ORDER BY id_transaksi DESC");
    $stmt->execute($lastTransaksiIDs);
    $transaksi = $stmt->fetchAll();
} else {
    $transaksi = [];
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Transaksi Berhasil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background:#0f0f0f; color:#fff; font-family:Poppins, sans-serif;}
.btn-primary{background:#00aaff;border:none;}
.btn-primary:hover{background:#008bcc;}
</style>
</head>
<body>

<div class="container py-4">

<h2 class="mb-3">Transaksi Berhasil</h2>

<div class="alert alert-success">
Transaksi Anda telah berhasil diproses.
</div>

<h4>Ringkasan Transaksi Terakhir</h4>

<?php if($transaksi): ?>
<table class="table table-dark table-bordered align-middle">
<thead>
<tr>
<th>ID Transaksi</th>
<th>ID Pelanggan</th>
<th>Produk</th>
<th>Qty</th>
<th>Total</th>
<th>Tanggal</th>
</tr>
</thead>
<tbody>
<?php foreach($transaksi as $t): ?>
<?php
$stmtProduk = $pdo->prepare("SELECT nama_produk FROM tb_produk WHERE id_produk = ?");
$stmtProduk->execute([$t['id_produk']]);
$produk = $stmtProduk->fetch();
$nama_produk = $produk ? $produk['nama_produk'] : '-';
?>
<tr>
<td><?php echo $t['id_transaksi']; ?></td>
<td><?php echo $t['id_pelanggan']; ?></td>
<td><?php echo htmlspecialchars($nama_produk); ?></td>
<td><?php echo $t['jumlah']; ?></td>
<td>Rp <?php echo number_format($t['total'],0,',','.'); ?></td>
<td><?php echo $t['tanggal']; ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php else: ?>
<div class="alert alert-warning">Tidak ada transaksi.</div>
<?php endif; ?>

<a href="index.php" class="btn btn-primary mt-3">Kembali ke Beranda</a>

</div>

<?php
// Hapus data transaksi terakhir dari session agar tidak muncul lagi
unset($_SESSION['last_transaksi']);
?>

</body>
</html>
