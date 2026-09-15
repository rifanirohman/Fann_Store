<?php
require '../config.php';
if(!isset($_SESSION['admin_id'])){header('Location: login.php');exit;}

if(isset($_POST['tambah'])){
$pdo->prepare("INSERT INTO tb_produk(nama_produk,harga,stok,IMG) VALUES(?,?,?,?)")
->execute([$_POST['nama'],$_POST['harga'],$_POST['stok'],$_POST['gambar']]);
}

$produk = $pdo->query("SELECT * FROM tb_produk")->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Kelola Produk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#0f0f0f;color:#fff;">
<div class="container py-4">
<h4>Kelola Produk</h4>

<form method="post" class="mb-3">
<input name="nama" class="form-control mb-2" placeholder="Nama Produk" required>
<input name="harga" class="form-control mb-2" placeholder="Harga" required>
<input name="stok" class="form-control mb-2" placeholder="Stok" required>
<input name="gambar" class="form-control mb-2" placeholder="Link Gambar" required>
<button name="tambah" class="btn btn-primary">Tambah</button>
</form>

<table class="table text-white">
<tr><th>Nama</th><th>Harga</th><th>Stok</th></tr>
<?php foreach($produk as $p): ?>
<tr>
<td><?php echo $p['nama_produk']; ?></td>
<td>Rp <?php echo number_format($p['harga'],0,',','.'); ?></td>
<td><?php echo $p['stok']; ?></td>
</tr>
<?php endforeach; ?>
</table>

</div>
</body>
</html>
