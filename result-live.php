<?php
include("connection.php");

$data = mysqli_query($condb,"
SELECT kelas.Kelas, tot_undi_kelas 
FROM kelas
");


while($row=mysqli_fetch_array($data)){
echo $row['Kelas']." : ".$row['tot_undi_kelas']." undi <br>";
}
?>