<?php
$servername = "localhost";
$username = "root";    // Default XAMPP
$password = "";        // Default XAMPP (kosong)
$dbname = "votera2526"; // Nama baru yang kita set tadi
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Gagal sambung ke database: " . mysqli_connect_error());
}
?>
