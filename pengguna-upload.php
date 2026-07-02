<?php
include 'connection.php';
include 'header.php';
$msg = "";

if (isset($_POST['upload']) && isset($_FILES['csv_file'])) {
    $file = fopen($_FILES['csv_file']['tmp_name'], 'r');
    fgetcsv($file); // skip header

    while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
        $id = trim($data[0]);
        $nama = trim($data[1]);
        $password = password_hash(trim($data[2]), PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO pelajar (Id_Pelajar, Nama_Pelajar, Kata_Laluan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $id, $nama, $password);
        $stmt->execute();
    }
    fclose($file);
    $msg = "<div class='alert success'>Import CSV berjaya.</div>";
}
?>
<div class="card">
    <h2>Import Pelajar Dari CSV</h2>
    <?= $msg ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Pilih fail CSV</label>
            <input type="file" name="csv_file" accept=".csv" required>
        </div>
        <div class="form-group">
            <button type="submit" name="upload" class="btn">Upload CSV</button>
        </div>
    </form>
    <p>Format CSV: <strong>Id_Pelajar,Nama_Pelajar,Kata_Laluan</strong></p>
</div>
<?php include 'footer.php'; ?>
