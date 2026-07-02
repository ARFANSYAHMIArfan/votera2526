<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['Id_Pelajar'])) {
    header('Location: login.php');
    exit;
}

$idPelajar = $_SESSION['Id_Pelajar'];
$idKategori = $_POST['id_kategori'] ?? '';
$idCalon = (int)($_POST['id_calon'] ?? 0);

$conn->begin_transaction();
try {
    $check = $conn->prepare("SELECT COUNT(*) total FROM undi_kategori WHERE Id_Pelajar = ? AND Id_Kategori = ? FOR UPDATE");
    $check->bind_param("ss", $idPelajar, $idKategori);
    $check->execute();
    $done = $check->get_result()->fetch_assoc()['total'];

    if ($done > 0) {
        throw new Exception('Anda sudah mengundi untuk kategori ini.');
    }

    $insert = $conn->prepare("INSERT INTO undi_kategori (Id_Pelajar, Id_Kategori, id_calon) VALUES (?, ?, ?)");
    $insert->bind_param("ssi", $idPelajar, $idKategori, $idCalon);
    $insert->execute();

    $update = $conn->prepare("UPDATE calon SET undi = undi + 1 WHERE id_calon = ?");
    $update->bind_param("i", $idCalon);
    $update->execute();

    $conn->commit();
    header('Location: undi-kategori.php?success=1');
    exit;
} catch (Exception $e) {
    $conn->rollback();
    header('Location: undi-kategori.php?error=' . urlencode($e->getMessage()));
    exit;
}
