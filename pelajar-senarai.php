<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

$msg = '';

/*
=====================================
AUTO GENERATE ID PELAJAR
Format: mz001, mz002, mz003...
=====================================
*/
$getLast = $conn->query("
    SELECT Id_Pelajar 
    FROM pelajar 
    ORDER BY Id_Pelajar DESC 
    LIMIT 1
");

if ($getLast && $getLast->num_rows > 0) {
    $lastRow = $getLast->fetch_assoc();
    $lastId = $lastRow['Id_Pelajar'];

    $num = (int) substr($lastId, 2);
    $num++;

    $newId = 'mz' . str_pad($num, 3, '0', STR_PAD_LEFT);
} else {
    $newId = 'mz001';
}

/*
=====================================
TAMBAH PELAJAR
=====================================
*/
if (isset($_POST['tambah'])) {
    $id = $newId;
    $nama = trim($_POST['Nama_Pelajar']);
    $password = password_hash($_POST['Kata_Laluan'], PASSWORD_DEFAULT);
    $status = 'belum';

    $gambar = 'default.png';

    if (!empty($_FILES['Gambar_Pelajar']['name'])) {
        $targetDir = "uploads/pelajar/";
        $fileName = time() . "_" . basename($_FILES["Gambar_Pelajar"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["Gambar_Pelajar"]["tmp_name"], $targetFile)) {
            $gambar = $fileName;
        }
    }

    $stmt = $conn->prepare("
        INSERT INTO pelajar 
        (Id_Pelajar, Kata_Laluan, Nama_Pelajar, Status_Pelajar, Gambar_Pelajar)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssss", $id, $password, $nama, $status, $gambar);

    if ($stmt->execute()) {
        $msg = "<div class='alert success'>Pelajar berjaya ditambah.</div>";
    } else {
        $msg = "<div class='alert error'>Ralat: " . htmlspecialchars($stmt->error) . "</div>";
    }
}

/*
=====================================
RESET PASSWORD OLEH ADMIN
=====================================
*/
if (isset($_POST['reset_password'])) {
    $id = $_POST['reset_id'];
    $newPass = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        UPDATE pelajar 
        SET Kata_Laluan = ? 
        WHERE Id_Pelajar = ?
    ");

    $stmt->bind_param("ss", $newPass, $id);

    if ($stmt->execute()) {
        $msg = "<div class='alert success'>Password berjaya dikemaskini.</div>";
    }
}

$data = $conn->query("
    SELECT * FROM pelajar 
    ORDER BY Nama_Pelajar ASC
");
?>

<div class="card">
    <h2>Tambah Pelajar</h2>
    <?= $msg ?>

    <form method="post" enctype="multipart/form-data">
        <div class="grid">

            <div class="form-group">
                <label>ID Pelajar (Auto)</label>
                <input type="text" value="<?= $newId ?>" readonly>
            </div>

            <div class="form-group">
                <label>Nama Pelajar</label>
                <input type="text" name="Nama_Pelajar" required>
            </div>

            <div class="form-group">
                <label>Kata Laluan</label>
                <input type="text" name="Kata_Laluan" required>
            </div>

            <div class="form-group">
                <label>Gambar Pelajar</label>
                <input type="file" name="Gambar_Pelajar">
            </div>

        </div>

        <button type="submit" name="tambah" class="btn">
            Tambah Pelajar
        </button>
    </form>
</div>

<div class="card">
    <h2>Senarai Pelajar</h2>

    <table class="table">
        <tr>
            <th>Gambar</th>
            <th>ID Pelajar</th>
            <th>Nama</th>
            <th>Status Mengundi</th>
            <th>Tarikh Daftar</th>
            <th>Password Baru</th>
            <th>Tindakan</th>
        </tr>

        <?php while($row = $data->fetch_assoc()): ?>
        <tr>

            <td>
                <img 
                    src="uploads/pelajar/<?= htmlspecialchars($row['Gambar_Pelajar']) ?>" 
                    class="thumb"
                >
            </td>

            <td><?= htmlspecialchars($row['Id_Pelajar']) ?></td>

            <td><?= htmlspecialchars($row['Nama_Pelajar']) ?></td>

            <td><?= htmlspecialchars($row['Status_Pelajar']) ?></td>

            <td>
                <?= isset($row['tarikh_daftar']) ? $row['tarikh_daftar'] : '-' ?>
            </td>

            <td>
                <form method="post" style="display:flex; gap:10px;">
                    <input 
                        type="hidden" 
                        name="reset_id" 
                        value="<?= $row['Id_Pelajar'] ?>"
                    >

                    <input 
                        type="text" 
                        name="password_baru" 
                        placeholder="Password Baru"
                        required
                    >

                    <button 
                        type="submit" 
                        name="reset_password" 
                        class="btn"
                    >
                        Tukar
                    </button>
                </form>
            </td>

            <td>
                <a href="pelajar-edit.php?id=<?= urlencode($row['Id_Pelajar']) ?>">
                    Edit
                </a>
                |
                <a 
                    href="pelajar-delete.php?id=<?= urlencode($row['Id_Pelajar']) ?>"
                    onclick="return confirm('Padam pelajar ini?')"
                >
                    Delete
                </a>
            </td>

        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include 'footer.php'; ?>