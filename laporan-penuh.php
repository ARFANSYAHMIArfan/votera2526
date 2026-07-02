<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

$kategori = $conn->query("SELECT * FROM kategori ORDER BY Kategori ASC");
?>
<div class="card">
    <h1>Laporan Penuh Keputusan</h1>
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:15px;">
        <a href="export-laporan.php?type=csv"><button class="btn" style="width:180px;">Export fail ke CSV</button></a>
        <a href="live-result.php" target="_blank"><button class="btn" style="width:220px;">Paparkan Undian Langsung</button></a>
    </div>
</div>
<?php while($k = $kategori->fetch_assoc()):
    $stmt = $conn->prepare("SELECT nama_calon, kelas_calon, undi FROM calon WHERE id_kategori = ? ORDER BY undi DESC, nama_calon ASC");
    $stmt->bind_param("s", $k['Id_Kategori']);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    $max = 1;
    while($r = $result->fetch_assoc()) {
        $rows[] = $r;
        if ($r['undi'] > $max) { $max = $r['undi']; }
    }
?>
<div class="card">
    <h2><?= htmlspecialchars($k['Kategori']) ?></h2>
    <?php foreach($rows as $row):
        $width = $max > 0 ? ($row['undi'] / $max) * 100 : 0;
    ?>
    <div class="bar-row">
        <div class="bar-label"><?= htmlspecialchars($row['nama_calon']) ?> (<?= htmlspecialchars($row['kelas_calon']) ?>)</div>
        <div class="bar-track">
            <div class="bar-fill" style="width:<?= $width ?>%;"><?= (int)$row['undi'] ?> undi</div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endwhile; ?>
<?php include 'footer.php'; ?>
