<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$query_notif = mysqli_query($conn, "SELECT * FROM reservations WHERE user_id = '$user_id' AND status = 'approved' ORDER BY id DESC LIMIT 1");
$data_notif = mysqli_fetch_assoc($query_notif);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART2 STUDIO - User Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background-color: #121212; 
            color: #e0e0e0;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #1f1f1f;
            padding: 15px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #007bff;
        }

        .navbar h1 { margin: 0; color: #007bff; font-size: 24px; letter-spacing: 2px; }

        .container { width: 85%; margin: 30px auto; }

        .notif-bar {
            background: #9fa728; //Najwa edit warna di sini
            color: white;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        .grid-layout {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }

        .card {
            background: #1e1e1e;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #333;
            box-shadow: 0 8px 16px rgba(0,0,0,0.5);
        }

        h3 { color: #007bff; border-bottom: 1px solid #333; padding-bottom: 10px; }

        label { display: block; margin-top: 15px; font-size: 14px; color: #bbb; }

        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            background: #2c2c2c;
            border: 1px solid #444;
            color: white;
            border-radius: 6px;
            box-sizing: border-box;
        }

        input:focus { border-color: #007bff; outline: none; }

        .btn-reserve {
            background: #007bff;
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            margin-top: 25px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
        }

        .btn-reserve:hover { background: #0056b3; transform: scale(1.02); }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-top: 15px;
        }

        .day-box {
            aspect-ratio: 1 / 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            transition: 0.3s;
        }

        .white { background: #ffffff; color: #333; }
        .green { background: #28a745; color: white; box-shadow: 0 0 10px rgba(40, 167, 69, 0.5); }

        .legend { display: flex; gap: 20px; margin-top: 20px; font-size: 13px; }
        .legend-item { display: flex; align-items: center; gap: 5px; }
        .dot { width: 12px; height: 12px; border-radius: 50%; }

        .logout { color: #ff4d4d; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>SMART2 STUDIO</h1>
        <div>
            <span>Halo, <strong><?= $username ?></strong></span> | 
            <a href="logout.php" class="logout">Keluar</a>
        </div>
    </div>

    <div class="container">
        
        <?php if ($data_notif): ?>
        <div class="notif-bar">
            NOTIFIKASI: Reservasi Anda pada tanggal <?= date('d M Y', strtotime($data_notif['tanggal'])) ?> Jam <?= $data_notif['jam_slot'] ?> telah DISETUJUI. Silakan datang tepat waktu!
        </div>
        <?php endif; ?>

        <div class="grid-layout">
            <div class="card">
                <h3>Form Reservasi</h3>
                <form action="proses_reservasi.php" method="POST">
                    <label>Pilih Tanggal</label>
                    <input type="date" name="tanggal" min="<?= date('Y-m-d') ?>" required>

                    <label>Pilih Sesi Jam</label>
                    <select name="jam_slot" required>
                        <option value="09.00-11.00">Jam 1 (09.00 - 11.00)</option>
                        <option value="13.00-14.00">Jam 2 (13.00 - 14.00)</option>
                    </select>

                    <label>Kelas</label>
                    <input type="text" name="kelas" placeholder="Contoh: XI MIPA 2" required>

                    <label>Jumlah Orang (Maks 30)</label>
                    <input type="number" name="jumlah_orang" min="1" max="30" placeholder="0" required>

                    <button type="submit" name="submit" class="btn-reserve">Reservasi Sekarang</button>
                </form>
            </div>

            <div class="card">
                <h3>Jadwal Studio (7 Hari Kedepan)</h3>
                <div class="calendar-grid">
                    <?php
                    for ($i = 0; $i < 14; $i++) {
                        $tgl_cek = date('Y-m-d', strtotime("+$i days"));
                        $hari_nama = date('D', strtotime($tgl_cek));
                        $tgl_nomer = date('d', strtotime($tgl_cek));

                        $cek_db = mysqli_query($conn, "SELECT * FROM reservations WHERE tanggal = '$tgl_cek' AND status = 'approved'");
                        $is_full = mysqli_num_rows($cek_db) >= 2; 
                        $is_booked = mysqli_num_rows($cek_db) > 0; 

                        $status_class = $is_booked ? 'green' : 'white';
                        
                        echo "<div class='day-box $status_class'>
                                <span>$hari_nama</span>
                                <span style='font-size:18px'>$tgl_nomer</span>
                              </div>";
                    }
                    ?>
                </div>
                
                <div class="legend">
                    <div class="legend-item"><div class="dot" style="background:white"></div> Tersedia</div>
                    <div class="legend-item"><div class="dot" style="background:#28a745"></div> Sudah Terisi / Booking</div>
                </div>

                <p style="font-size: 12px; color: #777; margin-top: 20px;">
                    *Jika kotak berwarna hijau, salah satu atau semua jam pada tanggal tersebut sudah dipesan.
                </p>
            </div>
        </div>
    </div>

</body>
</html>