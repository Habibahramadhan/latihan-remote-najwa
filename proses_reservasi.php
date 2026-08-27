<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $jam_slot = mysqli_real_escape_string($conn, $_POST['jam_slot']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $jumlah_orang = intval($_POST['jumlah_orang']);

    $query = "INSERT INTO reservations (user_id, kelas, jumlah_orang, tanggal, jam_slot) 
              VALUES ('$user_id', '$kelas', '$jumlah_orang', '$tanggal', '$jam_slot')";

    if (mysqli_query($conn, $query)) {
        header("Location: waiting.php");
        exit();
    } else {
        echo "Wah, ada error nih: " . mysqli_error($conn);
    }
} else {
    header("Location: user_dashboard.php");
    exit();
}
?>