<?php
session_start(); 
include('header.php');             
include('kawalan-admin.php');      
include('connection.php');         

// Semak kewujudan data GET (id_pelajar)
if (empty($_GET['id_pelajar'])) { 
    die("<script>window.location.href='pelajar-senarai.php';</script>"); 
}

// Dapatkan maklumat pelajar berdasarkan Id_Pelajar
$id_pelajar = mysqli_real_escape_string($condb, $_GET['id_pelajar']);
$sql = "SELECT * FROM pelajar WHERE Id_Pelajar = '$id_pelajar'";
$laksana = mysqli_query($condb, $sql);
$m = mysqli_fetch_array($laksana);

// Jika data tidak dijumpai
if (!$m) {
    die("<script>alert('Data pelajar tidak wujud'); window.location.href='pelajar-senarai.php';</script>");
}
?>

<h3 align="center">Kemaskini Maklumat Pelajar</h3>

<form action='pelajar-kemaskini-proses.php?id_lama=<?= $id_pelajar ?>' method='POST' align="center">
    <table border="0" align="center" cellpadding="5">
        <tr>
            <td><label>ID Pelajar:</label></td>
            <td><input type='text' name='id_pelajar' value='<?= $m['Id_Pelajar'] ?>' required></td>
        </tr>
        <tr>
            <td><label>Nama Pelajar:</label></td>
            <td><input type='text' name='nama_pelajar' value='<?= $m['Nama_Pelajar'] ?>' required style="width: 250px;"></td>
        </tr>
        <tr>
            <td><label>Kata Laluan:</label></td>
            <td><input type='text' name='kata_laluan' value='<?= $m['Kata_Laluan'] ?>' required></td>
        </tr>
        <tr>
            <td><label>Status (Admin/Murid):</label></td>
            <td>
                <select name='status_pelajar' required>
                    <option value='<?= $m['Status_Pelajar'] ?>'><?= $m['Status_Pelajar'] ?></option>
                    <?php 
                    // Pilihan status selain yang sedia ada
                    $pilihan = ($m['Status_Pelajar'] == 'Murid') ? 'Admin' : 'Murid';
                    echo "<option value='$pilihan'>$pilihan</option>";
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><label>Nama Fail Gambar:</label></td>
            <td><input type='text' name='gambar_pelajar' value='<?= $m['Gambar_Pelajar'] ?>' placeholder="contoh: johan.png"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <input type='submit' value='Simpan Perubahan' style="padding: 5px 20px;">
            </td>
        </tr>
    </table>
</form>

<?php include('footer.php'); ?>