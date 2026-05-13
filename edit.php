<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

// ambil data booking lama
$queryBooking = mysqli_prepare($koneksi, "SELECT b.*, l.nama_lab FROM bookings b JOIN laboratories l ON b.labID = l.labID WHERE b.id = ?");
mysqli_stmt_bind_param($queryBooking, "i", $id);
mysqli_stmt_execute($queryBooking);
$booking = mysqli_fetch_assoc(mysqli_stmt_get_result($queryBooking));

if (isset($_POST['edit'])) {
    $tanggal = $_POST['tanggal'];
    $jam = $_POST['jam'];

    $cekBooking = mysqli_prepare($koneksi, "SELECT * FROM bookings WHERE labID = ? AND tanggal = ? AND jam = ? AND id != ?");
    mysqli_stmt_bind_param($cekBooking, "issi", $booking['labID'], $tanggal, $jam, $id);
    mysqli_stmt_execute($cekBooking);
    $hasil = mysqli_num_rows(mysqli_stmt_get_result($cekBooking));

    if ($hasil > 0) {
        $_SESSION['error'] = "Waktu yang dipilih sudah tidak tersedia";
        header("Location: edit.php?id=$id");
        exit;
    } else {
        $update = mysqli_prepare($koneksi, "UPDATE bookings SET tanggal=?, jam=? WHERE id=?");
        mysqli_stmt_bind_param($update, "ssi", $tanggal, $jam, $id);
        mysqli_stmt_execute($update);
        header("Location: home.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Booking</title>
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

        .add-card {
            border: 2px solid #7b6cf6;
            border-radius: 15px;
            padding: 25px;
            max-width: 600px;
            margin: 0 auto;
        }

        .btn-edit {
            background-color: #7b6cf6;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 4px 15px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
        }

        .btn-hapus {
            background-color: white;
            color: #7b6cf6;
            border: 2px solid #7b6cf6;
            border-radius: 20px;
            padding: 4px 15px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <nav class="d-flex align-items-center justify-content-between px-4 py-3">
        <div class="navbar-brand">C</div>
    </nav>

    <div class="container-fluid px-4">
        <h5 class="section-title">Silahkan Masukkan Data</h5>

        <div class="add-card">
            <?php if (isset($_SESSION['error'])): ?>
                <div style="background:none; border:none; color:red;" class="py-1 small p-0 mb-2">
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="edit.php?id=<?= $id ?>">
                <input type="text" name="nama_lab" class="form-control mb-3"
                    value="<?= htmlspecialchars($booking['nama_lab']) ?>">
                <input type="date" name="tanggal" class="form-control mb-3" value="<?= $booking['tanggal'] ?>">

                <p class="mb-2 fw-600">Jam Mulai</p>
                <div class="d-flex gap-3 mb-4">
                    <label><input type="radio" name="jam" value="08:00" <?= $booking['jam'] == '08:00' ? 'checked' : '' ?>>
                        08:00</label>
                    <label><input type="radio" name="jam" value="10:30" <?= $booking['jam'] == '10:30' ? 'checked' : '' ?>>
                        10:30</label>
                    <label><input type="radio" name="jam" value="13:00" <?= $booking['jam'] == '13:00' ? 'checked' : '' ?>>
                        13:00</label>
                    <label><input type="radio" name="jam" value="15:30" <?= $booking['jam'] == '15:30' ? 'checked' : '' ?>>
                        15:30</label>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" name="edit" class="btn-edit">UBAH PINJAMAN</button>
                    <a href="home.php" class="btn-hapus">BATALKAN</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>