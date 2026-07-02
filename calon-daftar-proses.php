<?php
session_start();
include('connection.php');
include('kawalan-admin.php');

/* Pastikan borang dihantar */
if (!empty($_POST)) {

    $Id_Kelas = mysqli_real_escape_string($condb, $_POST['Id_Kelas']);
    $Kelas = mysqli_real_escape_string($condb, $_POST['Nama_Kelas']);
    $Undi_Kelas = mysqli_real_escape_string($condb, $_POST['Undi_Kelas']);

    $target_dir = "gambar/";
    $image_file_type = strtolower(pathinfo($_FILES['Gambar_Kelas']['name'], PATHINFO_EXTENSION));

    /* Validasi jenis fail */
    if (!in_array($image_file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "<script>
                alert('Hanya fail gambar (JPG, JPEG, PNG, GIF) dibenarkan');
                window.history.back();
              </script>";
        exit;
    }

    /* Nama fail unik */
    $nama_fail_baru = date("Ymd_His") . '.' . $image_file_type;
    $target_file = $target_dir . $nama_fail_baru;

    if (move_uploaded_file($_FILES['Gambar_Kelas']['tmp_name'], $target_file)) {

        $arahan = "INSERT INTO kelas 
                   (Id_Kelas, Kelas, tot_undi_kelas, Undi_Kelas, Gambar_Kelas)
                   VALUES 
                   ('$Id_Kelas', '$Kelas', 0, '$Undi_Kelas', '$nama_fail_baru')";

        if (mysqli_query($condb, $arahan)) {

            echo "<script>
                    alert('Kelas berjaya didaftarkan');
                    window.location.href='kelas-senarai.php';
                  </script>";

        } else {

            echo "<script>
                    alert('Pendaftaran kelas gagal');
                    window.location.href='kelas-senarai.php';
                  </script>";
        }

    } else {

        echo "<script>
                alert('Gagal memuat naik gambar');
                window.history.back();
              </script>";
    }

} else {

    echo "<script>
            alert('Sila isi semua maklumat');
            window.location.href='kelas-senarai.php';
          </script>";
}
?>