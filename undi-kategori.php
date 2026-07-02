<?php
include 'connection.php';
include 'header.php';

if (!isset($_SESSION['Id_Pelajar'])) {
    header('Location: login.php');
    exit;
}

$idPelajar = $_SESSION['Id_Pelajar'];
$kategori = $conn->query("SELECT * FROM kategori ORDER BY Kategori ASC");
?>
<div class="card">
    <h2>Pilih Kategori Undian</h2>
    <p>Setiap pelajar hanya boleh undi sekali bagi setiap kategori.</p>
</div>
<div class="grid">
<?php while($k = $kategori->fetch_assoc()):
    $check = $conn->prepare("SELECT COUNT(*) total FROM undi_kategori WHERE Id_Pelajar = ? AND Id_Kategori = ?");
    $check->bind_param("ss", $idPelajar, $k['Id_Kategori']);
    $check->execute();
    $done = $check->get_result()->fetch_assoc()['total'];
?>
    <div class="stat-card">
        <h3><?= htmlspecialchars($k['Kategori']) ?></h3>
        <p style="font-size:16px;"><?= $done ? 'Sudah Mengundi' : 'Belum Mengundi' ?></p>
        <div style="margin-top:15px;">
            <a href="undi-kategori-calon.php?id=<?= urlencode($k['Id_Kategori']) ?>">
                <button class="btn" <?= $done ? 'disabled' : '' ?>><?= $done ? 'SELESAI' : 'UNDI SEKARANG' ?></button>
            </a>
        </div>
    </div>
<?php endwhile; ?>
</div>
<?php include 'footer.php'; ?>
