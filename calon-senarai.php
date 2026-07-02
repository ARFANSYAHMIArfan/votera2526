<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

if (isset($_POST['tambah'])) {
    $idKategori = $_POST['id_kategori'];
    $nama = trim($_POST['nama_calon']);
    $kelas = trim($_POST['kelas_calon']);
    $gambar = 'default.png';

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'gambar/' . $gambar);
    }

    $stmt = $conn->prepare("INSERT INTO calon (id_kategori, nama_calon, kelas_calon, gambar_calon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $idKategori, $nama, $kelas, $gambar);
    $stmt->execute();
}

$kategori = $conn->query("SELECT * FROM kategori ORDER BY Kategori ASC");
$data = $conn->query("SELECT c.*, k.Kategori FROM calon c JOIN kategori k ON c.id_kategori = k.Id_Kategori ORDER BY c.id_calon DESC");
?>
<div class="card">
    <h2>Urus Calon</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="grid">
            <div class="form-group">
                <label>Kategori</label>
                <select name="id_kategori" required>
                    <option value="">-- Pilih --</option>
                    <?php while($k = $kategori->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($k['Id_Kategori']) ?>"><?= htmlspecialchars($k['Kategori']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group"><label>Nama Calon</label><input type="text" name="nama_calon" required></div>
            <div class="form-group"><label>Kelas Calon</label><input type="text" name="kelas_calon"></div>
            <div class="form-group"><label>Gambar</label><input type="file" name="gambar" accept="image/*"></div>
        </div>
        <button type="submit" name="tambah" class="btn">Tambah Calon</button>
    </form>
</div>
<div class="card">
    <table class="table">
        <tr><th>Kategori</th><th>Nama Calon</th><th>Kelas</th><th>Undi</th><th>Tindakan</th></tr>
        <?php while($row = $data->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['Kategori']) ?></td>
            <td><?= htmlspecialchars($row['nama_calon']) ?></td>
            <td><?= htmlspecialchars($row['kelas_calon']) ?></td>
            <td><?= (int)$row['undi'] ?></td>
            <td class="action-links">
                <a href="calon-edit.php?id=<?= (int)$row['id_calon'] ?>">Edit</a>
                <a href="calon-delete.php?id=<?= (int)$row['id_calon'] ?>" onclick="return confirm('Padam calon ini?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php include 'footer.php'; ?>
