<?php
include 'connection.php';
include 'admin-auth.php';

$idUndi = (int)($_GET['id'] ?? 0);

if ($idUndi <= 0) {
    header("Location: senarai-undi.php");
    exit;
}

$conn->begin_transaction();

try {
    // Ambil rekod undi yang nak dipadam
    $stmt = $conn->prepare("
        SELECT id_undi, Id_Pelajar, Id_Kategori, id_calon
        FROM undi_kategori
        WHERE id_undi = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $idUndi);
    $stmt->execute();
    $vote = $stmt->get_result()->fetch_assoc();

    if (!$vote) {
        throw new Exception("Rekod undi tidak dijumpai.");
    }

    $idPelajar  = $vote['Id_Pelajar'];
    $idCalon    = (int)$vote['id_calon'];

    // Tolak jumlah undi calon
    $upCalon = $conn->prepare("
        UPDATE calon
        SET undi = GREATEST(undi - 1, 0)
        WHERE id_calon = ?
    ");
    $upCalon->bind_param("i", $idCalon);
    $upCalon->execute();

    // Padam rekod undi
    $delVote = $conn->prepare("
        DELETE FROM undi_kategori
        WHERE id_undi = ?
    ");
    $delVote->bind_param("i", $idUndi);
    $delVote->execute();

    // Semak sama ada pelajar masih ada undi lain
    $check = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM undi_kategori
        WHERE Id_Pelajar = ?
    ");
    $check->bind_param("s", $idPelajar);
    $check->execute();
    $remaining = $check->get_result()->fetch_assoc()['total'] ?? 0;

    // Kalau dah tiada undi lain, reset status pelajar
    if ((int)$remaining === 0) {
        $upPelajar = $conn->prepare("
            UPDATE pelajar
            SET Status_Pelajar = 'belum'
            WHERE Id_Pelajar = ?
        ");
        $upPelajar->bind_param("s", $idPelajar);
        $upPelajar->execute();
    }

    $conn->commit();
    header("Location: senarai-undi.php?success=1");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    header("Location: senarai-undi.php?error=" . urlencode($e->getMessage()));
    exit;
}