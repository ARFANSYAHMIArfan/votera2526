<?php

if(!isset($_SESSION['jenis_pengguna'])){

echo "<script>
alert('Sila login dahulu');
window.location.href='login.php';
</script>";

exit();

}

?>