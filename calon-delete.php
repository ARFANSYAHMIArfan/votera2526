<?php
include 'connection.php';
include 'admin-auth.php';
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM calon WHERE id_calon = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header('Location: calon-senarai.php');
exit;
