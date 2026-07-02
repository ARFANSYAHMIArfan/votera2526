<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM calon WHERE id_calon = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<div class='card'><div class='alert error'>Calon tidak dijumpai.</div></div>";
    include 'footer.php';
    exit;
}

$kategori = $conn->query("SELECT * FROM kategori ORDER BY Kategori ASC");
$msg = '';
if (isset($_POST['update'])) {
    $idKategori = $_POST['id_kategori'];
    $nama = trim($_POST['nama_calon']);
    $kelas = trim($_POST['kelas_calon']);
    $gambar = $data['gambar_calon'];

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'gambar/' . $gambar);
    }

    $up = $conn->prepare("UPDATE calon SET id_kategori = ?, nama_calon = ?, kelas_calon = ?, gambar_calon = ? WHERE id_calon = ?");
    $up->bind_param("ssssi", $idKategori, $nama, $kelas, $gambar, $id);
    if ($up->execute()) {
        $msg = "<div class='alert success'>Calon berjaya dikemaskini.</div>";
        $data['id_kategori'] = $idKategori;
        $data['nama_calon'] = $nama;
        $data['kelas_calon'] = $kelas;
        $data['gambar_calon'] = $gambar;
    } else {
        $msg = "<div class='alert error'>Ralat semasa kemaskini.</div>";
    }
}
?>
<div class="card">
    <h2>Edit Calon</h2>
    <?= $msg ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Kategori</label>
            <select name="id_kategori" required>
                <?php while($k = $kategori->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($k['Id_Kategori']) ?>" <?= $data['id_kategori'] === $k['Id_Kategori'] ? 'selected' : '' ?>><?= htmlspecialchars($k['Kategori']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group"><label>Nama Calon</label><input type="text" name="nama_calon" value="<?= htmlspecialchars($data['nama_calon']) ?>" required></div>
        <div class="form-group"><label>Kelas Calon</label><input type="text" name="kelas_calon" value="<?= htmlspecialchars($data['kelas_calon']) ?>"></div>
        <div class="form-group"><label>Gambar Semasa</label><br><img class="thumb" src="gambar/<?= htmlspecialchars($data['gambar_calon']) ?>" alt="gambar"></div>
        <div class="form-group"><label>Upload Gambar Baru</label><input type="file" name="gambar" accept="image/*"></div>
        <button type="submit" name="update" class="btn">Simpan Perubahan</button>
    </form>
</div>
<?php include 'footer.php'; ?>
