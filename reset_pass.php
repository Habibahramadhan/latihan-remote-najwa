<?php
include 'config.php';
$pass_baru = password_hash("456", PASSWORD_DEFAULT); 
mysqli_query($conn, "UPDATE users SET password = '$pass_baru'");
echo "Selesai! Sekarang password semua user adalah: 456";
?>