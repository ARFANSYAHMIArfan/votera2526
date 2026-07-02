<?php
include 'connection.php';
include 'header.php';

if (!isset($_SESSION['Id_Pelajar'])) {
    header('Location: login.php');
    exit;
}

$idPelajar = $_SESSION['Id_Pelajar'];
$stmt = $conn->prepare("SELECT Status_Pelajar FROM pelajar WHERE Id_Pelajar = ?");
$stmt->bind_param("s", $idPelajar);
$stmt->execute();
$status = $stmt->get_result()->fetch_assoc()['Status_Pelajar'] ?? 'belum';

$result = $conn->query("SELECT * FROM kelas ORDER BY Kelas ASC");
?>
<div class="card">
    <h2>Senarai Calon / Kelas</h2>
    <?php if ($status === 'sudah'): ?>
        <div class="alert success">Anda telah selesai mengundi. Terima kasih!</div>
    <?php endif; ?>
</div>
<div class="candidate-grid">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="candidate-card">
            <img src="gambar/<?= htmlspecialchars($row['Gambar_Kelas'] ?: 'default.png') ?>" alt="<?= htmlspecialchars($row['Kelas']) ?>">
            <h3><?= htmlspecialchars($row['Kelas']) ?></h3>
            <div class="badge">ID: <?= htmlspecialchars($row['Id_Kelas']) ?></div>
            <form method="post" action="undi-proses.php" style="margin-top:15px;">
                <input type="hidden" name="Id_Kelas" value="<?= htmlspecialchars($row['Id_Kelas']) ?>">
                <button type="submit" class="btn" <?= $status === 'sudah' ? 'disabled' : '' ?>>UNDI</button>
            </form>
        </div>
    <?php endwhile; ?>
</div>
<?php include 'footer.php'; ?>
