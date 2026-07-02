<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['Id_Pelajar'])) {
    header('Location: login.php');
    exit;
}

$idPelajar = $_SESSION['Id_Pelajar'];
$idKelas = $_POST['Id_Kelas'] ?? '';

$conn->begin_transaction();
try {
    $check = $conn->prepare("SELECT Status_Pelajar FROM pelajar WHERE Id_Pelajar = ? FOR UPDATE");
    $check->bind_param("s", $idPelajar);
    $check->execute();
    $status = $check->get_result()->fetch_assoc()['Status_Pelajar'] ?? 'belum';

    if ($status === 'sudah') {
        throw new Exception('Anda sudah mengundi.');
    }

    $insert = $conn->prepare("INSERT INTO undi (Id_Pelajar, Id_Kelas) VALUES (?, ?)");
    $insert->bind_param("ss", $idPelajar, $idKelas);
    $insert->execute();

    $updatePelajar = $conn->prepare("UPDATE pelajar SET Status_Pelajar = 'sudah' WHERE Id_Pelajar = ?");
    $updatePelajar->bind_param("s", $idPelajar);
    $updatePelajar->execute();

    $updateKelas = $conn->prepare("UPDATE kelas SET tot_undi_kelas = tot_undi_kelas + 1 WHERE Id_Kelas = ?");
    $updateKelas->bind_param("s", $idKelas);
    $updateKelas->execute();

    $conn->commit();
    header('Location: undi-calon.php?success=1');
    exit;
} catch (Exception $e) {
    $conn->rollback();
    header('Location: undi-calon.php?error=' . urlencode($e->getMessage()));
    exit;
}
