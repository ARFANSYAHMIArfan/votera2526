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
    <div class="nav-links" style="display: inline-block;">
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

        <?php 
        // 1. Ambil nama fail semasa
        $current_page = basename($_SERVER['PHP_SELF']); 
        
        // 2. Senarai halaman pengecualian (Butang kembali TIDAK akan muncul di sini)
        $excluded_pages = ['index.php', 'login.php', 'admin-login.php', 'signup.php', 'register.php'];
        
        // 3. Tetapkan logik hala tuju butang kembali
        $back_url = "javascript:history.back()";
        if ($current_page === 'calon-edit.php') {
            $back_url = "calon-senarai.php";
        }
        
        // 4. Paparkan butang jika bukan dalam senarai pengecualian
        if (!in_array($current_page, $excluded_pages)): 
        ?>
            <span class="back-button-wrap" style="margin-left: 15px; display: inline-block;">
                <a href="<?= $back_url ?>" class="back-btn">
                    ← Kembali
                </a>    
            </span>
        <?php endif; ?>
    </div>
</div>
<div class="container">