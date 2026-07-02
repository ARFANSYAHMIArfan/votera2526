<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

$id = $_GET['id'] ?? '';
$stmt = $conn->prepare("SELECT * FROM pelajar WHERE Id_Pelajar = ? LIMIT 1");
$stmt->bind_param("s", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<div class='card'><div class='alert error'>Pelajar tidak dijumpai.</div></div>";
    include 'footer.php';
    exit;
}

$msg = '';
if (isset($_POST['update'])) {
    $nama = trim($_POST['Nama_Pelajar']);
    $status = trim($_POST['Status_Pelajar']);
    $gambar = $data['Gambar_Pelajar'];

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'gambar/' . $gambar);
    }

    if (!empty($_POST['Kata_Laluan'])) {
        $password = password_hash($_POST['Kata_Laluan'], PASSWORD_DEFAULT);
        $up = $conn->prepare("UPDATE pelajar SET Nama_Pelajar = ?, Status_Pelajar = ?, Gambar_Pelajar = ?, Kata_Laluan = ? WHERE Id_Pelajar = ?");
        $up->bind_param("sssss", $nama, $status, $gambar, $password, $id);
    } else {
        $up = $conn->prepare("UPDATE pelajar SET Nama_Pelajar = ?, Status_Pelajar = ?, Gambar_Pelajar = ? WHERE Id_Pelajar = ?");
        $up->bind_param("ssss", $nama, $status, $gambar, $id);
    }

    if ($up->execute()) {
        $msg = "<div class='alert success'>Pelajar berjaya dikemaskini.</div>";
        $data['Nama_Pelajar'] = $nama;
        $data['Status_Pelajar'] = $status;
        $data['Gambar_Pelajar'] = $gambar;
    } else {
        $msg = "<div class='alert error'>Ralat semasa kemaskini.</div>";
    }
}
?>
<div class="card">
    <h2>Edit Pelajar</h2>
    <?= $msg ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group"><label>ID Pelajar</label><input type="text" value="<?= htmlspecialchars($data['Id_Pelajar']) ?>" disabled></div>
        <div class="form-group"><label>Nama Pelajar</label><input type="text" name="Nama_Pelajar" value="<?= htmlspecialchars($data['Nama_Pelajar']) ?>" required></div>
        <div class="form-group"><label>Password Baru (optional)</label><input type="password" name="Kata_Laluan"></div>
        <div class="form-group"><label>Status Mengundi</label>
            <select name="Status_Pelajar" required>
                <option value="belum" <?= $data['Status_Pelajar'] === 'belum' ? 'selected' : '' ?>>Belum</option>
                <option value="sudah" <?= $data['Status_Pelajar'] === 'sudah' ? 'selected' : '' ?>>Sudah</option>
            </select>
        </div>
        <div class="form-group"><label>Gambar Semasa</label><br><img class="thumb" src="gambar/<?= htmlspecialchars($data['Gambar_Pelajar']) ?>" alt="gambar"></div>
        <div class="form-group"><label>Upload Gambar Baru</label><input type="file" name="gambar" accept="image/*"></div>
        <button type="submit" name="update" class="btn">Simpan Perubahan</button>
    </form>
</div>
<?php include 'footer.php'; ?>
