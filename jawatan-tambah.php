<?php
session_start();
include('header.php');
include('connection.php');
include('kawalan-admin.php');

// Proses Tambah Kategori Baharu
if (!empty($_POST['id_kategori_baru']) && !empty($_POST['nama_kategori_baru'])) {

    $id_kategori = mysqli_real_escape_string($condb, $_POST['id_kategori_baru']);
    $nama_kategori = mysqli_real_escape_string($condb, $_POST['nama_kategori_baru']);

    // Proses tambah ke jadual kategori
    $arahan_tambah = "INSERT INTO kategori (Id_Kategori, Kategori) 
                      VALUES ('$id_kategori', '$nama_kategori')";
                      
    if (mysqli_query($condb, $arahan_tambah)) {
        echo "<script>
                alert('Kategori berjaya ditambah'); 
                window.location.href='kategori-tambah.php';
              </script>";
    } else {
        echo "<script>alert('Tambah kategori gagal: " . mysqli_error($condb) . "');</script>";
    }
}

// Proses Padam Kategori
if (!empty($_GET['id_rekod_kategori'])) {

    $id_rekod = mysqli_real_escape_string($condb, $_GET['id_rekod_kategori']);
    
    // Proses padam berdasarkan id_rekod_kategori
    $arahan_padam = "DELETE FROM kategori WHERE id_rekod_kategori='$id_rekod'";

    if (mysqli_query($condb, $arahan_padam)) {
        echo "<script>
                alert('Kategori berjaya dipadam'); 
                window.location.href='kategori-tambah.php';
              </script>";
    } else {
        echo "<script>
                alert('Padam kategori gagal'); 
                window.location.href='kategori-tambah.php';
              </script>";
    }
}
?>

<h3 align='center'>Pengurusan Kategori Undian</h3>

<table align='center' width='60%' border='1' style="border-collapse: collapse; text-align: center;">
    <caption>
        <form action='kategori-tambah.php' method='POST' style="margin-bottom: 20px;">
            <input type='text' name='id_kategori_baru' placeholder='Kod (Contoh: KB)' required>
            <input type='text' name='nama_kategori_baru' placeholder='Nama Kategori (Contoh: Kelas Bersih)' required style="width: 250px;">
            <input type='submit' value='Tambah Kategori'>
        </form>
    </caption>
    <tr bgcolor='#AF2413' style="color: blue;">
        <th>Kod Kategori</th>
        <th>Nama Kategori</th>
        <th>Tindakan</th>
    </tr>
    
    <?php
    // Paparkan semua kategori dari database
    $arahan_papar = "SELECT * FROM kategori ORDER BY id_rekod_kategori DESC";
    $laksana = mysqli_query($condb, $arahan_papar);
    
    while ($data = mysqli_fetch_array($laksana)) {
        echo "<tr>
                <td>" . $data['Id_Kategori'] . "</td>
                <td>" . $data['Kategori'] . "</td>
                <td>
                    <a href='kategori-tambah.php?id_rekod_kategori=" . $data['id_rekod_kategori'] . "' 
                       onClick=\"return confirm('Anda pasti ingin memadam kategori " . $data['Kategori'] . "?')\" 
                       style='color: red; text-decoration: none;'>
                       [ Hapus ]
                    </a>
                </td>
              </tr>";
    } 
    ?>
</table>

<?php include('footer.php'); ?>