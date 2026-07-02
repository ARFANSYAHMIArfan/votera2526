<?php
include 'connection.php';
include 'admin-auth.php';
$id = $_GET['id'] ?? '';
if ($id !== '') {
    $stmt = $conn->prepare("DELETE FROM kategori WHERE Id_Kategori = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
}
header('Location: kategori-senarai.php');
exit;
