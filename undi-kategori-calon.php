<?php
include 'connection.php';
include 'header.php';

if (!isset($_SESSION['Id_Pelajar'])) {
    header('Location: login.php');
    exit;
}

$idPelajar = $_SESSION['Id_Pelajar'];
$idKategori = $_GET['id'] ?? '';

$check = $conn->prepare("SELECT COUNT(*) total FROM undi_kategori WHERE Id_Pelajar = ? AND Id_Kategori = ?");
$check->bind_param("ss", $idPelajar, $idKategori);
$check->execute();
$done = $check->get_result()->fetch_assoc()['total'];

$stmt = $conn->prepare("SELECT * FROM calon WHERE id_kategori = ? ORDER BY nama_calon ASC");
$stmt->bind_param("s", $idKategori);
$stmt->execute();
$result = $stmt->get_result();
?>
<div class="card">
    <h2>Senarai Calon</h2>
    <?php if ($done): ?>
        <div class="alert success">Anda sudah mengundi untuk kategori ini.</div>
    <?php endif; ?>
</div>
<div class="candidate-grid">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="candidate-card">
        <img src="gambar/<?= htmlspecialchars($row['gambar_calon'] ?: 'default.png') ?>" alt="<?= htmlspecialchars($row['nama_calon']) ?>">
        <h3><?= htmlspecialchars($row['nama_calon']) ?></h3>
        <p><?= htmlspecialchars($row['kelas_calon']) ?></p>
        <form method="post" action="undi-kategori-proses.php" style="margin-top:15px;">
            <input type="hidden" name="id_kategori" value="<?= htmlspecialchars($idKategori) ?>">
            <input type="hidden" name="id_calon" value="<?= (int)$row['id_calon'] ?>">
            <button type="submit" class="btn" <?= $done ? 'disabled' : '' ?>>UNDI</button>
        </form>
    </div>
<?php endwhile; ?>
</div>
<?php include 'footer.php'; ?>
