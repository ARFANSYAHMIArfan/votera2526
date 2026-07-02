<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

$id = $_GET['id'] ?? '';
$stmt = $conn->prepare("SELECT * FROM kategori WHERE Id_Kategori = ? LIMIT 1");
$stmt->bind_param("s", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<div class='card'><div class='alert error'>Kategori tidak dijumpai.</div></div>";
    include 'footer.php';
    exit;
}

$msg = '';
if (isset($_POST['update'])) {
    $nama = trim($_POST['Kategori']);
    $up = $conn->prepare("UPDATE kategori SET Kategori = ? WHERE Id_Kategori = ?");
    $up->bind_param("ss", $nama, $id);
    if ($up->execute()) {
        $msg = "<div class='alert success'>Kategori berjaya dikemaskini.</div>";
        $data['Kategori'] = $nama;
    } else {
        $msg = "<div class='alert error'>Ralat semasa kemaskini.</div>";
    }
}
?>
<div class="card">
    <h2>Edit Kategori</h2>
    <?= $msg ?>
    <form method="post">
        <div class="form-group">
            <label>ID Kategori</label>
            <input type="text" value="<?= htmlspecialchars($data['Id_Kategori']) ?>" disabled>
        </div>
        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="Kategori" value="<?= htmlspecialchars($data['Kategori']) ?>" required>
        </div>
        <button type="submit" name="update" class="btn">Simpan Perubahan</button>
    </form>
</div>
<?php include 'footer.php'; ?>
