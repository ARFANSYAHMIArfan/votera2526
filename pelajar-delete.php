<?php
include 'connection.php';
include 'admin-auth.php';
$id = $_GET['id'] ?? '';
if ($id !== '') {
    $stmt = $conn->prepare("DELETE FROM pelajar WHERE Id_Pelajar = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
}
header('Location: pelajar-senarai.php');
exit;
