<?php
session_start();
include('kawalan-admin.php');
include('connection.php');

/* Pastikan parameter Id_Pelajar wujud */
if (isset($_GET['Id_Pelajar']) && !empty($_GET['Id_Pelajar'])) {

    $id_pelajar = mysqli_real_escape_string($condb, $_GET['Id_Pelajar']);

    /* Elak padam akaun admin */
    $semak_admin = mysqli_query($condb,
        "SELECT Status_Pelajar FROM pelajar 
         WHERE Id_Pelajar='$id_pelajar'"
    );

    if (mysqli_num_rows($semak_admin) == 1) {

        $data = mysqli_fetch_array($semak_admin);

        if ($data['Status_Pelajar'] == 'Admin') {

            echo "<script>
                    alert('Akaun Admin tidak boleh dipadam');
                    window.location.href='pelajar-senarai.php';
                  </script>";
            exit;
        }

        /* Proses padam */
        $arahan = "DELETE FROM pelajar 
                   WHERE Id_Pelajar='$id_pelajar'";

        if (mysqli_query($condb, $arahan)) {

            echo "<script>
                    alert('Padam data berjaya');
                    window.location.href='pelajar-senarai.php';
                  </script>";

        } else {

            echo "<script>
                    alert('Padam data gagal');
                    window.location.href='pelajar-senarai.php';
                  </script>";
        }

    } else {

        echo "<script>
                alert('Rekod tidak dijumpai');
                window.location.href='pelajar-senarai.php';
              </script>";
    }

} else {

    echo "<script>
            alert('Ralat! Akses tidak sah');
            window.location.href='pelajar-senarai.php';
          </script>";
}
?>