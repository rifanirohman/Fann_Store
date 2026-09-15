<?php
session_start();
require '../config.php';

// Ambil semua produk dari database
$produk = $pdo->query("SELECT id_produk, nama_produk, harga, stok FROM tb_produk")->fetchAll(PDO::FETCH_ASSOC);

// Rumus harga beli reseller
function harga_reseller($harga){
    return floor($harga * 0.95);
}

// Proses ketika admin restock
if(isset($_POST['submit'])){

    $id_produk = $_POST['id_produk'];
    $jumlah    = (int)$_POST['jumlah'];
    $harga_beli= (int)$_POST['harga_beli'];
    $total     = (int)$_POST['total'];
    $catatan   = $_POST['catatan'];
    $tanggal   = date('Y-m-d');

    // 1. Tambah stok ke tb_produk
    $stmtStok = $pdo->prepare("UPDATE tb_produk SET stok = stok + ? WHERE id_produk = ?");
    $stmtStok->execute([$jumlah, $id_produk]);

    // 2. Catat pengeluaran di tb_keuangan
    $stmtKeu = $pdo->prepare("INSERT INTO tb_keuangan (tanggal, jenis, nominal, keterangan) VALUES (?, 'pengeluaran', ?, ?)");
    $stmtKeu->execute([$tanggal, $total, "Restock produk ID $id_produk. $catatan"]);

    // 3. Tampilkan info
    echo "<div style='padding:15px;background:#1e1e1e;color:#fff;border-radius:5px;width:350px;'>
            Restock berhasil.<br>
            Produk ID: $id_produk<br>
            Jumlah: $jumlah<br>
            Harga satuan: $harga_beli<br>
            Total: $total<br>
            Catatan: $catatan
          </div>";
    exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Pengeluaran Restock</title>

<style>
body{
    background:#0f0f0f;
    color:#fff;
    font-family:Arial;
}
form{
    background:#1b1b1b;
    padding:20px;
    width:350px;
    border-radius:8px;
}
input, select{
    width:100%;
    padding:8px;
    margin-top:5px;
    background:#2e2e2e;
    color:#fff;
    border:1px solid #444;
}
button{
    margin-top:15px;
    width:100%;
    padding:10px;
    background:#008cff;
    border:none;
    color:#fff;
    cursor:pointer;
}
button:hover{
    background:#006fcc;
}
</style>

</head>
<body>

<h2>Restock Barang</h2>

<form method="post">

    Pilih produk:
    <select name="id_produk" id="produk" onchange="updateProduk()">
        <option value="">Pilih produk</option>
        <?php foreach($produk as $p){ ?>
            <option 
                value="<?php echo $p['id_produk']; ?>" 
                data-harga="<?php echo $p['harga']; ?>"
                data-stok="<?php echo $p['stok']; ?>"
            >
                <?php echo $p['nama_produk']; ?> (Stok: <?php echo $p['stok']; ?>)
            </option>
        <?php } ?>
    </select>

    Harga beli:
    <input type="number" name="harga_beli" id="harga_beli" readonly>

    Jumlah restock:
    <input type="number" name="jumlah" id="jumlah" value="1" min="1" onchange="updateTotal()">

    Total harga:
    <input type="number" name="total" id="total" readonly>

    Catatan:
    <input type="text" name="catatan" placeholder="Opsional">

    <button type="submit" name="submit">Simpan Restock</button>

</form>

<script>
function updateProduk(){
    var select = document.getElementById("produk");
    var harga  = parseInt(select.options[select.selectedIndex].getAttribute("data-harga") || 0);
    var reseller = Math.floor(harga * 0.95);

    document.getElementById("harga_beli").value = reseller;

    updateTotal();
}

function updateTotal(){
    var harga  = parseInt(document.getElementById("harga_beli").value || 0);
    var jumlah = parseInt(document.getElementById("jumlah").value || 0);

    document.getElementById("total").value = harga * jumlah;
}
</script>

</body>
</html>
