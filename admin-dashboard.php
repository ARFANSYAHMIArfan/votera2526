<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

$total_pelajar = $conn->query("SELECT COUNT(*) total FROM pelajar")->fetch_assoc()['total'];
$total_calon   = $conn->query("SELECT COUNT(*) total FROM calon")->fetch_assoc()['total'];
$total_kategori= $conn->query("SELECT COUNT(*) total FROM kategori")->fetch_assoc()['total'];
$total_undi    = $conn->query("SELECT COUNT(*) total FROM undi_kategori")->fetch_assoc()['total'];
?>
<div class="card">
    <h1>Admin Dashboard</h1>
    <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['admin_name']) ?></strong></p>
</div>
<div class="grid">
    <div class="stat-card"><h3>Pelajar</h3><p><?= $total_pelajar ?></p></div>
    <div class="stat-card"><h3>Calon</h3><p><?= $total_calon ?></p></div>
    <div class="stat-card"><h3>Kategori</h3><p><?= $total_kategori ?></p></div>
    <div class="stat-card"><h3>Jumlah Undi</h3><p><?= $total_undi ?></p></div>
</div>
<div class="card">
    <h2>Menu Admin</h2>
    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a href="kategori-senarai.php"><button class="btn" style="width:200px;">Kategori</button></a>
        <a href="kelas-senarai.php"><button class="btn" style="width:200px;">Kelas</button></a>
        <a href="calon-senarai.php"><button class="btn" style="width:200px;">Calon</button></a>
        <a href="pelajar-senarai.php"><button class="btn" style="width:200px;">Pelajar</button></a>
        <a href="senarai-undi.php"><button class="btn" style="width:200px;">Senarai Undi</button></a>
        <a href="laporan-penuh.php"><button class="btn" style="width:200px;">Laporan</button></a>
    </div>
</div>
<?php include 'footer.php'; ?>
