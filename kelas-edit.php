<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

$id = $_GET['id'] ?? '';
$stmt = $conn->prepare("SELECT * FROM kelas WHERE Id_Kelas = ? LIMIT 1");
$stmt->bind_param("s", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<div class='card'><div class='alert error'>Kelas tidak dijumpai.</div></div>";
    include 'footer.php';
    exit;
}

$msg = '';
if (isset($_POST['update'])) {
    $nama = trim($_POST['Kelas']);
    $gambar = $data['Gambar_Kelas'];

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'gambar/' . $gambar);
    }

    $up = $conn->prepare("UPDATE kelas SET Kelas = ?, Gambar_Kelas = ? WHERE Id_Kelas = ?");
    $up->bind_param("sss", $nama, $gambar, $id);
    if ($up->execute()) {
        $msg = "<div class='alert success'>Kelas berjaya dikemaskini.</div>";
        $data['Kelas'] = $nama;
        $data['Gambar_Kelas'] = $gambar;
    } else {
        $msg = "<div class='alert error'>Ralat semasa kemaskini.</div>";
    }
}
?>
<div class="card">
    <h2>Edit Kelas</h2>
    <?= $msg ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>ID Kelas</label>
            <input type="text" value="<?= htmlspecialchars($data['Id_Kelas']) ?>" disabled>
        </div>
        <div class="form-group">
            <label>Nama Kelas</label>
            <input type="text" name="Kelas" value="<?= htmlspecialchars($data['Kelas']) ?>" required>
        </div>
        <div class="form-group">
            <label>Gambar Semasa</label><br>
            <img class="thumb" src="gambar/<?= htmlspecialchars($data['Gambar_Kelas']) ?>" alt="gambar">
        </div>
        <div class="form-group">
            <label>Upload Gambar Baru</label>
            <input type="file" name="gambar" accept="image/*">
        </div>
        <button type="submit" name="update" class="btn">Simpan Perubahan</button>
    </form>
</div>
<?php include 'footer.php'; ?>
