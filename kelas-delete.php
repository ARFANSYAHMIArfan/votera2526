<?php
include 'connection.php';
include 'admin-auth.php';
$id = $_GET['id'] ?? '';
if ($id !== '') {
    $stmt = $conn->prepare("DELETE FROM kelas WHERE Id_Kelas = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
}
header('Location: kelas-senarai.php');
exit;
