<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Title dan CSS-->
    <title>VOTERA2526: UNDIAN PINTAR</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="topbar">
    <div class="brand">VOTERA2526</div>
    <div>
        <a href="index.php">Home</a>

        <?php if (isset($_SESSION['admin_id'])): ?>
            <a href="admin-dashboard.php">Papan Pemuka Pentadbir</a>
            <a href="laporan.php">Laporan</a>
            <a href="logout.php">Log Keluar</a>

        <?php elseif (isset($_SESSION['Id_Pelajar'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="undi-kategori.php">Undi</a>
            <a href="logout.php">Log Keluar</a>

        <?php else: ?>
            <a href="login.php">Log Masuk Pelajar</a>
            <a href="admin-login.php">Log Masuk Pentadbir</a>
        <?php endif; ?>


    </a>    
</div>
    </div>
</div>
<div class="container">