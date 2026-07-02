<?php
include 'connection.php';
include 'header.php';

$msg = "";
if (isset($_POST['signup'])) {
    $id = trim($_POST['Id_Pelajar']);
    $nama = trim($_POST['Nama_Pelajar']);
    $password = password_hash($_POST['Kata_Laluan'], PASSWORD_DEFAULT);
    $gambar = "default.png";

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . basename($_FILES['gambar']['name']);
        $target = 'gambar/' . $gambar;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
    }

    $stmt = $conn->prepare("INSERT INTO pelajar (Id_Pelajar, Kata_Laluan, Nama_Pelajar, Gambar_Pelajar) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id, $password, $nama, $gambar);

    if ($stmt->execute()) {
        $msg = "<div class='alert success'>Signup berjaya. Sila login.</div>";
    } else {
        $msg = "<div class='alert error'>Ralat: " . htmlspecialchars($stmt->error) . "</div>";
    }
}
?>
<div class="card">
    <h2>Daftar Pelajar</h2>
    <?= $msg ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>ID Pelajar</label>
            <input type="text" name="Id_Pelajar" required>
        </div>
        <div class="form-group">
            <label>Nama Pelajar</label>
            <input type="text" name="Nama_Pelajar" required>
        </div>
        <div class="form-group">
            <label>Kata Laluan</label>
            <input type="password" name="Kata_Laluan" required>
        </div>
        <div class="form-group">
            <label>Upload Gambar</label>
            <input type="file" name="gambar" accept="image/*">
        </div>
        <div class="form-group">
            <button type="submit" name="signup" class="btn">Daftar</button>
        </div>
    </form>
</div>
<?php include 'footer.php'; ?>
