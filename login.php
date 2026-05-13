<?php
session_start();
require "koneksi.php";

// ● Jika username tidak ditemukan, tampilkan pesan: “Username tidak 
// ditemukan” 
// ● Jika password salah, tampilkan pesan: “Password tidak valid” 
// Sistem juga harus menerapkan mekanisme otorisasi: 
// ● Pengguna yang belum login tidak diperbolehkan mengakses halaman 
// dashboard 
// ● Jika pengguna mencoba mengakses tanpa login, sistem akan 
// mengarahkan kembali ke halaman Login

if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // ambil data dari db dulu kan?? trs dicompare sm inputan user
    $query = mysqli_prepare($koneksi, "SELECT * FROM users where username=?");
    mysqli_stmt_bind_param($query, "s", $username);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true; //tiket untuk ke page lain
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // redirect ke home
            header("Location: home.php");
            exit;
        } else {
            $_SESSION['error'] = "Password tidak valid!";
        }
    } else {
        $_SESSION['error'] = "Username tidak ditemukan";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container-fluid main-container">
        <div class="row w-100 align-items-center">

            <!-- form kiri -->
            <div class="col-md-6 text-center">
                <div class="form-section">

                    <h1 class="title">
                        LOGIN
                    </h1>

                    <p class="subtitle">
                        Selamat Datang Kembali
                    </p>

                    <!-- pesan validasiiii -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert py-1 small p-0 mb-2" style="background: none; border:none; color: red;">
                            <?= $_SESSION['error'] ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="login.php">
                        <input type="text" name="username" class="form-control mb-3" placeholder="Username">
                        <input type="password" name="password" class="form-control mb-4" placeholder="Password">
                        <button class="btn btn-purple w-100" name="login" style="color: #e7a8a8;">
                            Masuk
                        </button>
                    </form>

                    <p class="bottom-text">
                        Belum punya akun?
                        <a href="register.php">Register</a>
                    </p>
                </div>
            </div>

            <!-- gambar kanan -->
            <div class="col-md-6">
                <img src="assets/workspace.png" class="img-fluid illustration">
            </div>
        </div>

    </div>
</body>

</html>