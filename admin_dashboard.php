<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['username'];

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'approve') {
        mysqli_query($conn, "UPDATE reservations SET status = 'approved' WHERE id = $id");
    } elseif ($action === 'delete') {
        mysqli_query($conn, "DELETE FROM reservations WHERE id = $id");
    }
    header("Location: admin_dashboard.php");
    exit();
}

$sql = "SELECT r.*, u.username 
        FROM reservations r 
        JOIN users u ON r.user_id = u.id 
        ORDER BY r.waktu_submit DESC";
$query = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SMART2 STUDIO</title>
    <style>
        :root {
            --bg-dark: #121212;
            --card-bg: #1e1e1e;
            --accent: #007bff;
            --text-main: #e0e0e0;
            --text-dim: #bbb;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #1a1a1a;
            height: 100vh;
            position: fixed;
            border-right: 1px solid #333;
        }

        .sidebar-header {
            padding: 30px 20px;
            font-size: 20px;
            font-weight: bold;
            color: var(--accent);
            text-align: center;
            letter-spacing: 1px;
            border-bottom: 1px solid #333;
        }

        .nav-links {
            padding: 20px 0;
        }

        .nav-item {
            padding: 15px 25px;
            display: block;
            color: var(--text-dim);
            text-decoration: none;
            transition: 0.3s;
            font-size: 14px;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(0, 123, 255, 0.1);
            color: white;
            border-left: 4px solid var(--accent);
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 40px;
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* Table Inbox Style */
        .table-container {
            background: var(--card-bg);
            border-radius: 12px;
            border: 1px solid #333;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #252525;
            padding: 15px 20px;
            text-align: left;
            font-size: 13px;
            color: var(--accent);
            text-transform: uppercase;
        }

        td {
            padding: 15px 20px;
            border-bottom: 1px solid #2a2a2a;
            font-size: 14px;
        }

        tr:hover {
            background: #252525;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-pending { background: #5a4a00; color: #ffc107; }
        .badge-approved { background: #0e3a1a; color: #2ecc71; }

        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            transition: 0.2s;
        }
        .btn-approve { background: var(--accent); color: white; margin-right: 5px; }
        .btn-approve:hover { background: #0056b3; }
        .btn-delete { background: #333; color: #ff4d4d; border: 1px solid #444; }
        .btn-delete:hover { background: #ff4d4d; color: white; }

        .logout { color: #ff4d4d; font-weight: bold; text-decoration: none; border: 1px solid #ff4d4d; padding: 5px 15px; border-radius: 4px; }
        .logout:hover { background: #ff4d4d; color: white; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">SMART2 ADMIN</div>
        <div class="nav-links">
            <a href="admin_dashboard.php" class="nav-item active">📥 Inbox Reservasi</a>
            <a href="#" class="nav-item">📅 Kalender Studio</a>
            <a href="#" class="nav-item">👥 Daftar User</a>
            <a href="logout.php" class="nav-item" style="margin-top: 30px; color: #ff4d4d;">🚪 Keluar</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header-flex">
            <h2>Permintaan Reservasi</h2>
            <div>
                <span style="margin-right: 20px; color: var(--text-dim);">Admin: <strong><?= $admin_name ?></strong></span>
                <a href="logout.php" class="logout">Logout</a>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Pemesan</th>
                        <th>Kelas</th>
                        <th>Tanggal & Sesi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($row['username']) ?></strong><br>
                                <small style="color: #666;"><?= $row['waktu_submit'] ?></small>
                            </td>
                            <td><?= htmlspecialchars($row['kelas']) ?> (<?= $row['jumlah_orang'] ?> org)</td>
                            <td>
                                <div><?= date('d M Y', strtotime($row['tanggal'])) ?></div>
                                <div style="font-size: 12px; color: var(--accent);"><?= $row['jam_slot'] ?></div>
                            </td>
                            <td>
                                <?php if($row['status'] == 'pending'): ?>
                                    <span class="badge badge-pending">Pending</span>
                                <?php else: ?>
                                    <span class="badge badge-approved">Approved</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($row['status'] == 'pending'): ?>
                                    <a href="admin_dashboard.php?action=approve&id=<?= $row['id'] ?>" class="btn btn-approve" onclick="return confirm('Setujui reservasi ini?')">Approve</a>
                                <?php endif; ?>
                                <a href="admin_dashboard.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-delete" onclick="return confirm('Hapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #666;">Belum ada data reservasi masuk.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>