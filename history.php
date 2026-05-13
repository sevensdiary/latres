<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['login']) || !$_SESSION['login']) { //login bernilai false
    // redirect ke login
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$queryHistory = mysqli_prepare($koneksi, "SELECT b.*, l.nama_lab from bookings b join laboratories l on b.labID = l.labID where b.user_id = ? order by b.created_at desc");
mysqli_stmt_bind_param($queryHistory, "i", $user_id);
mysqli_stmt_execute($queryHistory);
$result = mysqli_stmt_get_result($queryHistory);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <style>
        .navbar-brand {
            background-color: #7b6cf6;
            color: white;
            font-weight: 700;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-link {
            color: #aaa;
            font-weight: 600;
        }

        .nav-link.active {
            color: #222;
        }

        .search-bar {
            border: 2px solid #7b6cf6;
            border-radius: 10px;
            padding: 6px 12px;
            width: 100%;
            outline: none;
            font-size: 13px;
            background-color: #f5c6c6;
            color: #222;
        }

        .search-bar::placeholder {
            color: #222;
            font-weight: bold;
        }

        .btn-filter {
            background-color: #f5c6c6;
            border: 2px solid #7b6cf6 !important;
            color: white;
            border-radius: 10px;
            padding: 6px 14px;
            font-weight: 600;
            color: #222;
            border: none;
            font-size: 13px;
            white-space: nowrap;
        }

        .section-title {
            color: #5a4fcf;
            font-weight: 700;
            text-align: center;
            margin: 20px 0 15px;
        }

        .table {
            border: 2px solid #7b6cf6;
            border-radius: 15px;
            overflow: hidden;
        }

        .table thead th {
            color: #7b6cf6;
            font-weight: 700;
            border-bottom: 2px solid #7b6cf6;
        }

        .table tbody td {
            color: #5a4fcf;
        }

        .table-bordered td,
        .table-bordered th {
            border: none;
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <nav class="d-flex align-items-center justify-content-between px-4 py-3">
        <div class="navbar-brand">C</div>
        <div class="d-flex gap-4">
            <a href="home.php" class="nav-link">Home</a>
            <a href="history.php" class="nav-link active">Riwayat</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <h5 class="section-title">Cek riwayat peminjamanmu disini</h5>

        <table class="table table-borderless">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Laboratorium</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama_lab']) ?></td>
                        <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?>     <?= $row['jam'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>