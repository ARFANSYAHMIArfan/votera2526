<?php
include 'connection.php';
$type = $_GET['type'] ?? 'csv';

if ($type === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="laporan_undi_2026.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Kategori', 'Calon', 'Jumlah Undi']);

    $result = $conn->query("SELECT k.Kategori, c.nama_calon, c.undi FROM calon c JOIN kategori k ON c.id_kategori = k.Id_Kategori ORDER BY k.Kategori ASC, c.nama_calon ASC");
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

echo 'Format belum disediakan.';
