<?php
session_start();
require "koneksi.php";

// ambil input
// cek email valid
// cek usn <= 20 char
// cek pass minim 6
// cek usn/email udh dipakai atau blm
// hash password
// insert db
// redirect ke login.php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ambil input kiriman user
    $email = htmlspecialchars($_POST['email']);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // cek email valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid";
    } elseif (strlen($username) > 20) {
        $_SESSION['error'] = "Username tidak boleh lebih dari 20 karakter.";
    } elseif (strlen($password) < 6) {
        $_SESSION['error'] = "Password minimal terdiri dari 6 karakter";
    } else {
        // cek usn/email udh ada yg pake blm 
        $cek = mysqli_prepare($koneksi, "SELECT * FROM users WHERE username = ? OR email = ?");
        mysqli_stmt_bind_param($cek, "ss", $username, $email);
        mysqli_stmt_execute($cek);

        $result = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['error'] = "Username atau email sudah digunakan";
        } else {
            // lakukan hash password
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);

            // insert pass ke db
            $insert = mysqli_prepare($koneksi, "INSERT INTO users(username, email, password) VALUES(?, ?, ?)");
            mysqli_stmt_bind_param($insert, "sss", $username, $email, $hashPassword);
            mysqli_stmt_execute($insert);

            // redirect ke login.php
            header("Location: login.php");
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">

    <title>Register</title>
</head>

<body>
    <div class="container-fluid main-container">
        <div class="row w-100 align-items-center">

            <!-- gambar -->
            <div class="col-md-6 text-center">
                <img src="assets/workspace.png" class="img-fluid illustration">
            </div>

            <!-- form -->
            <div class="col-md-6">
                <div class="form-section">

                    <h1 class="title">
                        REGISTER
                    </h1>

                    <p class="subtitle">
                        Mulai ajukan peminjaman lab
                    </p>

                    <!-- pesan validasiiii -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert py-1 small p-0 mb-2" style="background: none; border:none; color: red;">
                            <?= $_SESSION['error'] ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" action="register.php">
                        <input type="email" name="email" class="form-control mb-3" placeholder="email">
                        <input type="text" name="username" class="form-control mb-3" placeholder="Username">
                        <input type="password" name="password" class="form-control mb-4" placeholder="Password">
                        <button class="btn btn-purple w-100" name="regist" style="color: #e7a8a8;">
                            Buat Akun
                        </button>
                    </form>

                    <p class="bottom-text">
                        Sudah punya akun?
                        <a href="login.php">Login</a>
                    </p>
                </div>
            </div>
        </div>

    </div>

</body>

</html>