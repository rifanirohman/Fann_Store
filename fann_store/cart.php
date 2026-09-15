<?php
session_start();
require 'config.php';

// Pastikan keranjang ada
if(!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

// Hapus item
if(isset($_GET['hapus'])){
    $hapus = (int)$_GET['hapus'];
    if(isset($_SESSION['cart'][$hapus])){
        unset($_SESSION['cart'][$hapus]);
    }
    header('Location: cart.php');
    exit;
}

// Bersihkan data rusak
$cart_clean = [];
foreach($_SESSION['cart'] as $item){
    if(
        is_array($item) &&
        isset($item['id_produk']) &&
        isset($item['qty']) &&
        isset($item['game_id']) &&
        isset($item['server'])
    ){
        $cart_clean[] = $item;
    }
}
$_SESSION['cart'] = $cart_clean;

$totalBayar = 0;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Keranjang Belanja</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background:#0f0f0f;
    color:#fff;
    font-family:Poppins, sans-serif;
}
.table td, .table th{color:#fff;}
.btn-danger{background:#ff4444;border:none;}
.btn-danger:hover{background:#cc0000;}
.btn-light{background:#e0e0e0;border:none;}
.btn-primary{background:#00aaff;border:none;}
.btn-primary:hover{background:#008bcc;}
</style>
</head>
<body>

<div class="container py-4">

<h2 class="mb-3">Keranjang Belanja</h2>

<a href="index.php" class="btn btn-light mb-3">Kembali belanja</a>

<?php if(empty($_SESSION['cart'])): ?>
    <div class="alert alert-warning">Keranjang masih kosong.</div>
<?php else: ?>

<table class="table table-dark table-bordered align-middle">
    <thead>
        <tr>
            <th>Produk</th>
            <th>ID Game</th>
            <th>Server</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

    <?php foreach($_SESSION['cart'] as $index => $item): ?>
        <?php
        // Ambil detail produk
        $stmt = $pdo->prepare("SELECT * FROM tb_produk WHERE id_produk = ?");
        $stmt->execute([$item['id_produk']]);
        $produk = $stmt->fetch();

        if(!$produk){
            continue;
        }

        $subTotal = $produk['harga'] * $item['qty'];
        $totalBayar += $subTotal;
        ?>

        <tr>
            <td><?php echo htmlspecialchars($produk['nama_produk']); ?></td>
            <td><?php echo htmlspecialchars($item['game_id']); ?></td>
            <td><?php echo htmlspecialchars($item['server']); ?></td>
            <td><?php echo (int)$item['qty']; ?></td>
            <td>Rp <?php echo number_format($produk['harga'],0,',','.'); ?></td>
            <td>Rp <?php echo number_format($subTotal,0,',','.'); ?></td>
            <td>
                <a href="cart.php?hapus=<?php echo $index; ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Hapus item ini?')">
                   Hapus
                </a>
            </td>
        </tr>

    <?php endforeach; ?>

    </tbody>
</table>

<h4 class="mt-3">Total Pembayaran: Rp <?php echo number_format($totalBayar,0,',','.'); ?></h4>

<!-- TOMBOL CHECKOUT YANG BENAR -->
<a href="checkout.php" class="btn btn-primary btn-lg mt-3">Checkout</a>

<?php endif; ?>

</div>

</body>
</html>
