<?php
session_start();
require 'config.php';

// Pastikan keranjang tidak kosong
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$totalBayar = 0;
foreach ($_SESSION['cart'] as $item) {
    $stmt = $pdo->prepare("SELECT * FROM tb_produk WHERE id_produk = ?");
    $stmt->execute([$item['id_produk']]);
    $produk = $stmt->fetch();
    if (!$produk) continue;

    $totalBayar += $produk['harga'] * $item['qty'];
}

// Proses checkout
if (isset($_POST['checkout'])) {
    $id_pelanggan = 1; // ganti dengan session user jika login
    $tanggal = date('Y-m-d');

    foreach ($_SESSION['cart'] as $item) {

        // Ambil produk
        $stmtProduk = $pdo->prepare("SELECT * FROM tb_produk WHERE id_produk = ?");
        $stmtProduk->execute([$item['id_produk']]);
        $produk = $stmtProduk->fetch();
        if (!$produk) continue;

        $jumlah = (int)$item['qty'];
        $total = $produk['harga'] * $jumlah;

        // Masukkan ke tabel transaksi
        $stmt = $pdo->prepare("
            INSERT INTO tb_transaksi (id_pelanggan, id_produk, tanggal, jumlah, total) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$id_pelanggan, $item['id_produk'], $tanggal, $jumlah, $total]);


        // ======================
        // INSERT PEMASUKAN KE tb_keuangan
        // ======================
        $stmtK = $pdo->prepare("
            INSERT INTO tb_keuangan (tanggal, jenis, keterangan, nominal)
            VALUES (?, 'pemasukan', ?, ?)
        ");
        $stmtK->execute([
            $tanggal,
            "Penjualan ". $produk['nama_produk'],
            $total
        ]);
    }

    // Kosongkan keranjang
    $_SESSION['cart'] = [];

    // Redirect ke halaman sukses
    header('Location: sukses.php');
    exit;
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #0f0f0f;
            color: #fff;
            font-family: Poppins, sans-serif;
        }
        .table td, .table th {
            color: #fff;
        }
        .btn-primary {
            background: #00aaff;
            border: none;
        }
        .btn-primary:hover {
            background: #008bcc;
        }
    </style>
</head>
<body>
<div class="container py-4">

    <h2 class="mb-3">Checkout</h2>

    <a href="cart.php" class="btn btn-light mb-3">Kembali ke keranjang</a>

    <h4>Ringkasan Pesanan</h4>
    <table class="table table-dark table-bordered align-middle">
        <thead>
        <tr>
            <th>Produk</th>
            <th>ID Game</th>
            <th>Server</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM tb_produk WHERE id_produk = ?");
            $stmt->execute([$item['id_produk']]);
            $produk = $stmt->fetch();
            if (!$produk) continue;

            $subTotal = $produk['harga'] * $item['qty'];
            ?>
            <tr>
                <td><?php echo htmlspecialchars($produk['nama_produk']); ?></td>
                <td><?php echo htmlspecialchars($item['game_id']); ?></td>
                <td><?php echo htmlspecialchars($item['server']); ?></td>
                <td><?php echo (int)$item['qty']; ?></td>
                <td>Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></td>
                <td>Rp <?php echo number_format($subTotal, 0, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h4>Total Bayar: Rp <?php echo number_format($totalBayar, 0, ',', '.'); ?></h4>

    <form method="post">
        <button type="submit" name="checkout" class="btn btn-primary btn-lg mt-3">Bayar Sekarang</button>
    </form>

</div>
</body>
</html>
