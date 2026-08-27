<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5;url=user_dashboard.php">
    <title>Mohon Tunggu - SMART2 STUDIO</title>
    <style>
        body {
            background-color: #121212; 
            color: #e0e0e0;
            font-family: 'Segoe UI', Roboto, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: #1e1e1e;
            padding: 40px;
            border-radius: 15px;
            border: 1px solid #007bff;
            text-align: center;
            box-shadow: 0 0 25px rgba(0, 123, 255, 0.2);
            max-width: 400px;
        }

        .loader {
            border: 5px solid #333;
            border-top: 5px solid #007bff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h2 { color: #007bff; margin-top: 0; }
        p { color: #bbb; line-height: 1.6; }

        .btn-manual {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="loader"></div>
        <h2>Harap Tunggu...</h2>
        <p>Reservasi Anda sudah kami terima. <br> Mohon tunggu admin menyetujui jadwal Anda.</p>
        <p style="font-size: 12px; color: #666;">Halaman ini akan otomatis kembali ke Dashboard dalam 5 detik.</p>
        
        <a href="user_dashboard.php" class="btn-manual">Klik di sini jika tidak berpindah</a>
    </div>

</body>
</html>