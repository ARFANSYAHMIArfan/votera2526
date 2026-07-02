<?php
include 'connection.php';
include 'admin-auth.php';   // <-- letak sini
include 'header.php';

$result = $conn->query("SELECT Kelas, tot_undi_kelas FROM kelas ORDER BY tot_undi_kelas DESC");

if (!$result) {
    die("Ralat query laporan: " . $conn->error);
}
?>

<div class="card">
<h2>Laporan Keputusan Undian</h2>

<table class="table">
<tr>
<th>Kelas</th>
<th>Jumlah Undi</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['Kelas']) ?></td>
<td><?= (int)$row['tot_undi_kelas'] ?></td>
</tr>
<?php endwhile; ?>

</table>
</div>

<?php include 'footer.php'; ?>