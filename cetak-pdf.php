<?php
// Mesti autoload.inc.php, bukan yang lain
require_once 'dompdf/autoload.inc.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

include 'connection.php';
include 'admin-auth.php';

// Ambil data undi yang sama dengan senarai-undi.php
$sql = "
    SELECT 
        uk.id_undi, uk.Id_Pelajar, p.Nama_Pelajar, 
        k.Kategori, c.nama_calon, c.kelas_calon, uk.Tarikh_Undi
    FROM undi_kategori uk
    JOIN pelajar p ON uk.Id_Pelajar = p.Id_Pelajar
    JOIN kategori k ON uk.Id_Kategori = k.Id_Kategori
    JOIN calon c ON uk.id_calon = c.id_calon
    ORDER BY uk.Tarikh_Undi DESC
";
$result = $conn->query($sql);

// Mulakan pembinaan HTML untuk PDF
$html = '
<style>
    body { font-family: sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .header { text-align: center; margin-bottom: 20px; }
</style>

<div class="header">
    <h1>LAPORAN RASMI UNDIAN VOTERA 2526</h1>
    <p>Tarikh Dijana: ' . date("d-m-Y H:i:s") . '</p>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Pelajar (Pengundi)</th>
            <th>Kategori</th>
            <th>Calon Dipilih</th>
            <th>Tarikh Undian</th>
        </tr>
    </thead>
    <tbody>';

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $html .= '
        <tr>
            <td>' . $row['id_undi'] . '</td>
            <td>' . htmlspecialchars($row['Nama_Pelajar']) . '</td>
            <td>' . htmlspecialchars($row['Kategori']) . '</td>
            <td>' . htmlspecialchars($row['nama_calon']) . ' (' . htmlspecialchars($row['kelas_calon']) . ')</td>
            <td>' . $row['Tarikh_Undi'] . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="5" style="text-align:center;">Tiada rekod dijumpai</td></tr>';
}

$html .= '</tbody></table>';

// Konfigurasi Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); // Guna landscape supaya muat banyak kolum
$dompdf->render();

// Output fail PDF
$dompdf->stream("Laporan_Undian_Votera2526.pdf", array("Attachment" => 0)); // 0 = Preview, 1 = Download
?>