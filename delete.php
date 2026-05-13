<?php
session_start();
require "koneksi.php";

if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

$delete = mysqli_prepare($koneksi, "DELETE FROM bookings WHERE id = ?");
mysqli_stmt_bind_param($delete, "i", $id);
mysqli_stmt_execute($delete);

header("Location: home.php");
exit;
?>