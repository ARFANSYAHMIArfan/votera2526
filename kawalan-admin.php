<?php

if(!isset($_SESSION['jenis_pengguna']) || $_SESSION['jenis_pengguna'] != "Admin"){

echo "<script>
alert('Akses tidak dibenarkan');
window.location.href='index.php';
</script>";

exit();

}

?>