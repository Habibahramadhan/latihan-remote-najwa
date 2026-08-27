<?php
include 'config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit();
} //Teks ini di edit langsung dari web github

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($query) === 1) {
        $data = mysqli_fetch_assoc($query);

        if (password_verify($password, $data['password'])) {
            
            $_SESSION['user_id']  = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role']     = $data['role'];

            if ($data['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SMART2 STUDIO</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #121212; 
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #1e1e1e;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #333;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 350px;
            text-align: center;
        }

        h1 {
            color: #007bff;
            margin-bottom: 10px;
            font-size: 24px;
            letter-spacing: 2px;
        }

        p.subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .error-box {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #dc3545;
            margin-bottom: 20px;
            font-size: 13px;
        }

        label {
            display: block;
            text-align: left;
            font-size: 13px;
            color: #bbb;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            background: #2c2c2c;
            border: 1px solid #444;
            color: white;
            border-radius: 6px;
            box-sizing: border-box;
            transition: 0.3s;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn-login {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
        }

        .btn-login:hover {
            background: #0056b3;
            transform: scale(1.02);
        }

        .footer-text {
            margin-top: 25px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h1>SMART2 STUDIO</h1>
        <p class="subtitle">Sistem Reservasi Musik Sekolah</p>

        <?php if ($error !== ""): ?>
            <div class="error-box"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>

            <button type="submit" name="login" class="btn-login">Masuk ke Sistem</button>
        </form>

        <div class="footer-text">
            &copy; 2026 SMART2 Studio Management
        </div>
    </div>

</body>
</html>
