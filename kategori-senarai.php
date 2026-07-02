<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

if (isset($_POST['tambah'])) {
    $id = trim($_POST['Id_Kategori']);
    $nama = trim($_POST['Kategori']);
    $stmt = $conn->prepare("INSERT INTO kategori (Id_Kategori, Kategori) VALUES (?, ?)");
    $stmt->bind_param("ss", $id, $nama);
    $stmt->execute();
}

$data = $conn->query("SELECT * FROM kategori ORDER BY id_rekod_kategori DESC");
?>
<div class="card">
    <h2>Urus Kategori</h2>
    <form method="post" style="margin-top:15px;">
        <div class="grid">
            <div class="form-group">
                <label>ID Kategori</label>
                <input type="text" name="Id_Kategori" required>
            </div>
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="Kategori" required>
            </div>
        </div>
        <button type="submit" name="tambah" class="btn">Tambah Kategori</button>
    </form>
</div>
<div class="card">
    <table class="table">
        <tr><th>ID</th><th>Kategori</th><th>Tindakan</th></tr>
        <?php while($row = $data->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['Id_Kategori']) ?></td>
            <td><?= htmlspecialchars($row['Kategori']) ?></td>
            <td class="action-links">
                <a href="kategori-edit.php?id=<?= urlencode($row['Id_Kategori']) ?>">Edit</a>
                <a href="kategori-delete.php?id=<?= urlencode($row['Id_Kategori']) ?>" onclick="return confirm('Padam kategori ini?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include 'footer.php'; ?>
