<?php
include 'connection.php';
$result = $conn->query("SELECT c.nama_calon, k.Kategori, c.undi FROM calon c JOIN kategori k ON c.id_kategori = k.Id_Kategori ORDER BY k.Kategori ASC, c.undi DESC");
?>

<div class="container">

    <a href="javascript:history.back()" class="back-btn">
        ← Kembali
    </a>


    <!-- table result -->
</div>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10">
    <title>Live Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <div class="live-badge">LIVE RESULT SCREEN</div>
        <h1>Keputusan Semasa Undian</h1>
        <p>Auto refresh setiap 10 saat.</p>
    </div>
    <div class="card">
        <table class="table">
            <tr><th>Kategori</th><th>Calon</th><th>Undi</th></tr>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['Kategori']) ?></td>
                <td><?= htmlspecialchars($row['nama_calon']) ?></td>
                <td><?= (int)$row['undi'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>
</body>
</html>
<?php include 'footer.php'; ?>