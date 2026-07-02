<?php
include 'connection.php';
include 'header.php';

if (!isset($_SESSION['Id_Pelajar'])) {
    header('Location: login.php');
    exit;
}

$total_pelajar = $conn->query("SELECT COUNT(*) AS total FROM pelajar")->fetch_assoc()['total'];
$total_undi    = $conn->query("SELECT COUNT(*) AS total FROM undi_kategori")->fetch_assoc()['total'];
$total_kategori= $conn->query("SELECT COUNT(*) AS total FROM kategori")->fetch_assoc()['total'];
$percent = $total_pelajar > 0 ? round(($total_undi / $total_pelajar) * 100, 2) : 0;
?>
<div class="card">
    <h2>Selamat Kembali, <?= htmlspecialchars($_SESSION['Nama_Pelajar']) ?></h2>
</div>
<div class="grid">
    <div class="stat-card">
        <h3>Jumlah Pelajar</h3>
        <p><?= $total_pelajar ?></p>
    </div>
    <div class="stat-card">
        <h3>Jumlah Undian</h3>
        <p><?= $total_undi ?></p>
    </div>
    <div class="stat-card">
        <h3>Jumlah Kategori</h3>
        <p><?= $total_kategori ?></p>
    </div>
</div>
<div class="card">
    <h2>Progress Mengundi</h2>
    <div class="progress-wrap">
        <div class="progress-bar" style="width:<?= $percent ?>%;"><?= $percent ?>%</div>
    </div>
</div>
<div class="card">
    <h2>Quick Access</h2>
    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:15px;">
        <a href="countdown.php"><button class="btn" style="width:220px;">Live Countdown</button></a>
        <a href="undi-kategori.php"><button class="btn" style="width:220px;">Undi Sekarang</button></a>
        <a href="laporan.php"><button class="btn" style="width:220px;">Lihat Laporan</button></a>
    </div>
</div>
<?php include 'footer.php'; ?>
