<?php
include 'connection.php';
$pageTitle = "Daftar Akaun Pelajar";
include 'header.php';

$msg = "";

if (isset($_POST['register'])) {

    $nama = trim($_POST['Nama_Pelajar']);
    $password = $_POST['Kata_Laluan'];

    /* =========================
       AUTO GENERATE ID PELAJAR
    ========================= */

    $query = mysqli_query($conn, "
        SELECT Id_Pelajar 
        FROM pelajar
        ORDER BY Id_Pelajar DESC
        LIMIT 1
    ");

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        $lastId = $row['Id_Pelajar']; // contoh mz004
        $number = (int) substr($lastId, 2); // ambil 004 → 4
        $newNumber = $number + 1;

        $newId = "mz" . str_pad($newNumber, 3, "0", STR_PAD_LEFT);
    } else {
        $newId = "mz001";
    }

    /* =========================
       HASH PASSWORD
    ========================= */

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    /* =========================
       UPLOAD GAMBAR
    ========================= */

    $gambar = "default-user.png";

    if (!empty($_FILES['Gambar_Pelajar']['name'])) {

        $targetDir = "gambar/";
        $fileName = time() . "_" . basename($_FILES["Gambar_Pelajar"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["Gambar_Pelajar"]["tmp_name"], $targetFile)) {
            $gambar = $fileName;
        }
    }

    /* =========================
       INSERT DATABASE
    ========================= */

    $status = "Belum Mengundi";

    $stmt = $conn->prepare("
        INSERT INTO pelajar
        (Id_Pelajar, Kata_Laluan, Nama_Pelajar, Gambar_Pelajar, Status_Pelajar)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssss",
        $newId,
        $hashedPassword,
        $nama,
        $gambar,
        $status
    );

    if ($stmt->execute()) {
        // Menggunakan Alert Box JavaScript dan redirect ke login.php
        echo "<script>
                alert('Pendaftaran berjaya! ID Pelajar anda ialah: $newId');
                window.location.href = 'login.php';
              </script>";
        exit();
    } else {
        $msg = "<div class='alert error'>
                    Pendaftaran gagal. Sila cuba lagi.
                </div>";
    }
}
?>

<div class="container">
    <div class="card">

        <h1>Daftar Akaun Pelajar</h1>

        <?= $msg ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Nama Pelajar</label>
                <input type="text" name="Nama_Pelajar" required>
            </div>

            <div class="form-group">
                <label>Kata Laluan</label>
                <input type="password" name="Kata_Laluan" required>
            </div>

            <div class="form-group">
                <label>Gambar Murid</label>
                <input type="file" name="Gambar_Pelajar" accept="image/*">
            </div>

            <button type="submit" name="register" class="btn">
                Daftar Akaun
            </button>

        </form>

    </div>
</div>

<?php include 'footer.php'; ?>