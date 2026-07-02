<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

$msg = '';

if (isset($_POST['tambah'])) {
    $id = trim($_POST['Id_Kelas']);
    $nama = trim($_POST['Kelas']);
    $gambar = 'default.png';

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'gambar/' . $gambar);
    }

    $stmt = $conn->prepare("INSERT INTO kelas (Id_Kelas, Kelas, Gambar_Kelas) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $id, $nama, $gambar);

    if ($stmt->execute()) {
        $msg = "<div class='alert success'>Kelas berjaya ditambah.</div>";
    } else {
        $msg = "<div class='alert error'>Ralat insert: " . htmlspecialchars($stmt->error) . "</div>";
    }
}

$data = $conn->query("SELECT * FROM kelas ORDER BY Kelas ASC");

if (!$data) {
    die("Ralat query kelas: " . $conn->error);
}
?>

<div class="card">
    <h2>Urus Kelas / Calon Asas</h2>
    <?= $msg ?>
    <form method="post" enctype="multipart/form-data">
        <div class="grid">
            <div class="form-group">
                <label>ID Kelas</label>
                <input type="text" name="Id_Kelas" required>
            </div>
            <div class="form-group">
                <label>Nama Kelas</label>
                <input type="text" name="Kelas" required>
            </div>
            <div class="form-group">
                <label>Gambar</label>
                <input type="file" name="gambar" accept="image/*">
            </div>
        </div>
        <button type="submit" name="tambah" class="btn">Tambah Kelas</button>
    </form>
</div>

<div class="card">
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Kelas</th>
            <th>Jumlah Undi</th>
            <th>Tindakan</th>
        </tr>
        <?php while($row = $data->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['Id_Kelas']) ?></td>
            <td><?= htmlspecialchars($row['Kelas']) ?></td>
            <td><?= (int)$row['tot_undi_kelas'] ?></td>
            <td>
                <a href="kelas-edit.php?id=<?= urlencode($row['Id_Kelas']) ?>">Edit</a> |
                <a href="kelas-delete.php?id=<?= urlencode($row['Id_Kelas']) ?>" onclick="return confirm('Padam kelas ini?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>