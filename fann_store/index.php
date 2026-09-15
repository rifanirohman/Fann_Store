<?php
require 'config.php';

$kategoriFilter = $_GET['kategori'] ?? '';
$sql = "SELECT id_produk, nama_produk, harga, stok, img FROM tb_produk";
if($kategoriFilter){
    $sql .= " WHERE kategori = :kategori";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['kategori' => $kategoriFilter]);
} else {
    $stmt = $pdo->query($sql);
}
$products = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Fann Store</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background:#0f0f0f;
    color:#e7e7e7;
    font-family:'Urbanist', sans-serif;
}
.navbar {
    background:#141414;
    border-bottom:1px solid #222;
}
.navbar-brand {
    font-weight:700;
    font-size:24px;
    color:#12bafc !important;
}
.banner {
    background:url('img/banner store.jpg') center/cover no-repeat;
    height:260px;
    border-radius:14px;
    margin-top:20px;
    position:relative;
    overflow:hidden;
}
.banner:after {
    content:"";
    position:absolute;
    inset:0;
    background:rgba(0,0,0,0.55);
}
.banner-text {
    position:absolute;
    bottom:25px;
    left:30px;
    font-size:30px;
    font-weight:700;
    color:#ffffff;
}
.card {
    background:#161616;
    border:1px solid #1f1f1f;
    border-radius:14px;
    transition:0.25s;
}
.card:hover {
    transform:translateY(-6px);
    border-color:#12bafc;
}
.card-img-top {
    border-radius:12px;
}
.card-title {
    font-size:18px;
    font-weight:600;
    margin-bottom:6px;
    color:#f4f4f4;
}
.price {
    font-size:17px;
    color:#12bafc;
    font-weight:700;
}
.btn-buy {
    background:#12bafc;
    border:none;
    color:#000;
    font-weight:700;
    border-radius:10px;
    padding:7px 0;
}
.btn-buy:hover {
    background:#0da3db;
}
.btn-category {
    background: linear-gradient(135deg, #12bafc, #0da3db);
    color: #000;
    font-weight: 600;
    border-radius: 12px;
    padding: 8px 20px;
    text-decoration: none;
    transition: 0.3s;
    box-shadow: 0 4px 6px rgba(0,0,0,0.2);
    display: inline-block;
    margin-bottom: 10px;
}
.btn-category:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 10px rgba(0,0,0,0.25);
    background: linear-gradient(135deg, #0da3db, #12bafc);
    color: #fff;
}
</style>
</head>

<body>

<nav class="navbar navbar-dark px-3 py-2">
    <a class="navbar-brand" href="index.php">Fann Store</a>
    <div class="d-flex gap-2">
        <!-- Tombol Login -->
        <a href="admin/login.php" class="btn btn-sm btn-outline-light d-flex align-items-center justify-content-center" style="font-size:1.5rem; width:45px; height:45px; padding:0;">
            <i class="bi bi-person-circle"></i>
        </a>
        <!-- Tombol Keranjang -->
        <a href="cart.php" class="btn btn-sm btn-outline-light d-flex align-items-center justify-content-center" style="font-size:1.5rem; width:45px; height:45px; padding:0;">
            <i class="bi bi-cart"></i>
        </a>
    </div>
</nav>

<div class="container">

    <div class="banner">
        <div class="banner-text">Top Up Diamond ML</div>
    </div>

    <h4 class="mt-4 mb-3" style="color:#12bafc;font-weight:700;">Pilih Paket Diamond</h4>

    <!-- Pintasan kategori profesional -->
    <div class="d-flex gap-2 mb-3 flex-wrap">
        <a href="index.php?kategori=wdp" class="btn-category">WEEKKLY PASS</a>
        <a href="index.php?kategori=starlight" class="btn-category">STARLIGHT</a>
        <a href="index.php?kategori=dm_kecil" class="btn-category">PAKET HEMAT</a>
        <a href="index.php?kategori=dm_besar" class="btn-category">PAKET SULTAN</a>
    </div>

    <div class="row">
        <?php foreach($products as $p): ?>
            <div class="col-md-4 mb-4">
                <div class="card p-3">
                    <img src="img/<?php echo $p['img']; ?>" 
                         class="card-img-top"
                         onerror="this.src='https://via.placeholder.com/350x150/333/fff'">

                    <div class="card-body px-0">
                        <div class="card-title"><?php echo $p['nama_produk']; ?></div>
                        <div class="price">Rp <?php echo number_format($p['harga'], 0, ',', '.'); ?></div>

                        <a href="product.php?id=<?php echo $p['id_produk']; ?>" 
                           class="btn btn-buy w-100 mt-3">
                            Beli
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
</body>
</html>
