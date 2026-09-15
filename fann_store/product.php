<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM tb_produk WHERE id_produk = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if(!$product){
    exit('Produk tidak ditemukan');
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $qty = max(1, (int)$_POST['qty']);
    if($qty > $product['stok']) $qty = $product['stok'];

    $game_id = trim($_POST['game_id']);
    $server  = trim($_POST['server']);

    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        'id_produk' => $id,
        'qty'       => $qty,
        'game_id'   => $game_id,
        'server'    => $server
    ];

    header('Location: cart.php');
    exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($product['nama_produk']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background:#0f0f0f;color:#fff;font-family:Poppins,sans-serif;}
.btn-primary {background:#00aaff;border:none;}
.btn-primary:hover {background:#008bcc;}
.input-label {font-weight:600;margin-top:10px;}
</style>
</head>
<body>

<div class="container py-4">

<a href="index.php" class="btn btn-light mb-3">Kembali</a>

<div class="row">

    <div class="col-md-6">
        <img src="img/<?php echo htmlspecialchars($product['img']); ?>" 
             class="img-fluid"
             onerror="this.src='https://via.placeholder.com/400x200'">
    </div>

    <div class="col-md-6">
        <h3><?php echo htmlspecialchars($product['nama_produk']); ?></h3>
        <p>Rp <?php echo number_format($product['harga'],0,',','.'); ?></p>
        <p>Stok tersedia: <?php echo (int)$product['stok']; ?></p>

        <form method="post">

            <label class="input-label">ID Game</label>
            <input type="text" name="game_id" required placeholder="Masukkan ID game"
                   class="form-control mb-2" style="max-width:280px">

            <label class="input-label">Server</label>
            <input type="text" name="server" required placeholder="Masukkan server"
                   class="form-control mb-2" style="max-width:280px">

            <label class="input-label">Jumlah</label>
            <input type="number" name="qty" value="1" min="1"
                   max="<?php echo (int)$product['stok']; ?>"
                   class="form-control mb-3" style="width:120px">

            <button class="btn btn-primary">Tambah ke Keranjang</button>

        </form>
    </div>

</div>

</div>

</body>
</html>
