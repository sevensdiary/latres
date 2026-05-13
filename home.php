<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$slotWaktu = ['08:00', '10:30', '13:00', '15:30'];

$search = isset($_GET['search']) ? $_GET['search'] : '';
$f_jam = isset($_GET['filter_jam']) ? $_GET['filter_jam'] : '';

$sqlLab = "SELECT * FROM laboratories WHERE nama_lab LIKE ?";
$stmtLab = mysqli_prepare($koneksi, $sqlLab);
$searchParam = "%$search%";
mysqli_stmt_bind_param($stmtLab, "s", $searchParam);
mysqli_stmt_execute($stmtLab);
$queryLab = mysqli_stmt_get_result($stmtLab);

$queryBooking = mysqli_prepare($koneksi, "SELECT b.*, l.nama_lab from bookings b join laboratories l on b.labID = l.labID where b.user_id = ? order by b.created_at DESC limit 5");
mysqli_stmt_bind_param($queryBooking, "i", $user_id);
mysqli_stmt_execute($queryBooking);
$resulBooking = mysqli_stmt_get_result($queryBooking);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
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
            color: #e7a8a8;
            font-weight: 600;
            text-decoration: none;
        }

        .nav-link.active {
            color: #7b6cf6;
        }

        .search-bar {
            border: 2px solid #7b6cf6;
            border-radius: 12px;
            padding: 10px 15px;
            width: 100%;
            outline: none;
            background-color: #f5c6c6;
            color: #222;
            font-weight: 500;
        }

        .btn-filter {
            background-color: white;
            border: 2px solid #7b6cf6;
            color: #7b6cf6;
            border-radius: 12px;
            padding: 0 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title {
            color: #5a4fcf;
            font-weight: 700;
            text-align: center;
            margin: 30px 0 20px;
        }

        .section-title-left {
            color: #5a4fcf;
            font-weight: 700;
            margin: 30px 0 15px;
        }

        .lab-card {
            background-color: #7b6cf6;
            border-radius: 18px;
            padding: 20px;
            color: white;
            height: 100%;
        }

        .lab-name {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .slot-badge {
            background-color: transparent;
            border: 1px solid #e7a8a8;
            color: white;
            border-radius: 8px;
            padding: 4px 10px;
            font-size: 12px;
            margin-right: 5px;
        }

        .booking-card {
            background-color: #7b6cf6;
            border-radius: 18px;
            padding: 20px;
            color: white;
        }

        .btn-hapus {
            background-color: #7b6cf6;
            color: white;
            border: 2px solid white;
            border-radius: 25px;
            padding: 6px 20px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #e7a8a8;
            color: white;
            border-radius: 25px;
            padding: 6px 20px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
        }

        .fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #f5c6c6;
            border: 2px solid #7b6cf6;
            color: #7b6cf6;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav class="d-flex align-items-center justify-content-between px-4 py-3">
        <div class="navbar-brand">C</div>
        <div class="d-flex gap-4">
            <a href="home.php" class="nav-link active">Home</a>
            <a href="history.php" class="nav-link">Riwayat</a>
            <a href="logout.php" class="nav-link" style="color: #f5c6c6;">Logout</a>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <form method="GET" action="home.php" class="d-flex gap-2 mb-4">
            <input type="text" name="search" class="search-bar" placeholder="Cari laboratorium"
                value="<?= htmlspecialchars($search) ?>">
            <button type="button" class="btn-filter" data-bs-toggle="modal" data-bs-target="#modalFilter">
                filter
            </button>
        </form>

        <h5 class="section-title">Laboratorium yang tersedia hari ini</h5>
        <div class="row g-4 mb-5 justify-content-center">
            <?php
            while ($lab = mysqli_fetch_assoc($queryLab)):
                $labID = $lab['labID'];
                $qCek = mysqli_prepare($koneksi, "SELECT jam FROM bookings WHERE labID = ? AND tanggal = CURDATE()");
                mysqli_stmt_bind_param($qCek, 'i', $labID);
                mysqli_stmt_execute($qCek);
                $resCek = mysqli_stmt_get_result($qCek);
                $terpakai = [];
                while ($s = mysqli_fetch_assoc($resCek)) {
                    $terpakai[] = $s['jam'];
                }
                $tersedia = array_diff($slotWaktu, $terpakai);

                if ($f_jam != '') {
                    if (!in_array($f_jam, $tersedia))
                        continue;
                    $tersedia = [$f_jam];
                }

                if (empty($tersedia))
                    continue;
                ?>
                <div class="col-md-5 col-lg-4">
                    <div class="lab-card">
                        <div class="lab-name">□
                            <?= htmlspecialchars($lab['nama_lab']) ?>
                        </div>
                        <div class="d-flex flex-wrap gap-1">
                            <?php foreach ($tersedia as $slot): ?>
                                <span class="slot-badge">
                                    <?= $slot ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <h5 class="section-title-left">Ajuan pinjaman saat ini</h5>
        <div class="row g-4">
            <?php while ($booking = mysqli_fetch_assoc($resulBooking)): ?>
                <div class="col-md-5 col-lg-4">
                    <div class="booking-card">
                        <div class="lab-name">□
                            <?= htmlspecialchars($booking['nama_lab']) ?>
                        </div>

                        <div class="booking-info">
                            <?= date('d/m/Y', strtotime($booking['tanggal'])) ?>

                            <span>
                                <?= date('H:i', strtotime($booking['created_at'])) ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <span class="slot-badge" style="border-color: white; background-color: rgba(255,255,255,0.2);">
                                <?= $booking['jam'] ?>
                            </span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="delete.php?id=<?= $booking['id'] ?>" class="btn-hapus">HAPUS</a>
                            <a href="edit.php?id=<?= $booking['id'] ?>" class="btn-edit">EDIT</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="modal fade" id="modalFilter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 20px; border: 2px solid #7b6cf6;">
                <form method="GET" action="home.php">
                    <div class="modal-body p-4">
                        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                        <h6 class="fw-bold mb-3">Filter Waktu</h6>
                        <select name="filter_jam" class="form-select mb-3">
                            <option value="">Semua Jam</option>
                            <?php foreach ($slotWaktu as $w): ?>
                                <option value="<?= $w ?>" <?= $f_jam == $w ? 'selected' : '' ?>>Jam
                                    <?= $w ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn w-100"
                            style="background-color: #7b6cf6; color: white;">Terapkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <a href="add.php" class="fab">+</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>