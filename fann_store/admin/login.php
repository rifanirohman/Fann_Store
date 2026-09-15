<?php
require '../config.php'; // session_start() sudah ada di config

// Jika sudah login, langsung ke dashboard
if(!empty($_SESSION['admin_id'])){
    header("Location: dashboard.php");
    exit;
}

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM tb_admin WHERE username=? LIMIT 1");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if($admin && password_verify($password, $admin['password'])){
        $_SESSION['admin_id'] = $admin['id_admin'];
        $_SESSION['admin_nama'] = $admin['nama'];

        // 🔥 PERBAIKAN PALING PENTING
        // agar dashboard.php bisa membaca role dan tidak error lagi
        $_SESSION['role'] = $admin['role'];

        header("Location: dashboard.php");
        exit;
    } else {
        $error = 'Username atau password salah';
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {background:#111;color:#fff;}
.container {max-width:400px;margin-top:100px;}
</style>
</head>
<body>
<div class="container">
<div class="card p-4">
<h3 class="text-center mb-3">Login Admin</h3>

<?php if($error): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
<input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
<input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
<button type="submit" class="btn btn-primary w-100">Masuk</button>
</form>
</div>
</div>
</body>
</html>
