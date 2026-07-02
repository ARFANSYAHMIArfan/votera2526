<?php
session_start();
include('kawalan-admin.php');
include('connection.php');

// Semak kewujudan data POST dan GET
if (!empty($_POST) && !empty($_GET['id_lama'])) {

    // Mengambil dan menapis data POST
    $id_pelajar      = mysqli_real_escape_string($condb, $_POST['id_pelajar']);
    $nama_pelajar    = mysqli_real_escape_string($condb, $_POST['nama_pelajar']);
    $kata_laluan     = mysqli_real_escape_string($condb, $_POST['kata_laluan']);
    $status_pelajar  = mysqli_real_escape_string($condb, $_POST['status_pelajar']);
    $gambar_pelajar  = mysqli_real_escape_string($condb, $_POST['gambar_pelajar']);
    
    // ID asal dari URL untuk tujuan WHERE clause
    $id_lama         = mysqli_real_escape_string($condb, $_GET['id_lama']);

    // Arahan SQL untuk kemaskini maklumat pelajar
    $arahan = "UPDATE pelajar SET
               Id_Pelajar     = '$id_pelajar',
               Nama_Pelajar   = '$nama_pelajar',
               Kata_Laluan    = '$kata_laluan',
               Status_Pelajar = '$status_pelajar',
               Gambar_Pelajar = '$gambar_pelajar'
               WHERE Id_Pelajar = '$id_lama'";

    // Laksanakan arahan SQL
    if (mysqli_query($condb, $arahan)) {
        echo "<script>
                alert('Kemaskini Maklumat Pelajar Berjaya');
                window.location.href='pelajar-senarai.php';
              </script>";
    } else {
        // Jika gagal (contoh: ID baru sudah wujud dalam database)
        echo "<script>
                alert('Kemaskini Gagal: Ralat pada sistem atau ID telah digunakan.');
                window.history.back();
              </script>";
    }

} else {
    // Jika fail diakses tanpa data POST
    die("<script>
            alert('Sila lengkapkan data terlebih dahulu');
            window.location.href='pelajar-senarai.php';
        </script>");
}
?>