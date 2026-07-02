<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

$msg = '';

// PADAM UNDI
if (isset($_GET['padam'])) {
    $idUndi = (int)$_GET['padam'];

    if ($idUndi > 0) {
        $conn->begin_transaction();

        try {
            // Ambil rekod undi
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

            $idPelajar = $vote['Id_Pelajar'];
            $idCalon   = (int)$vote['id_calon'];

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

            // Semak baki undi pelajar
            $check = $conn->prepare("
                SELECT COUNT(*) AS total
                FROM undi_kategori
                WHERE Id_Pelajar = ?
            ");
            $check->bind_param("s", $idPelajar);
            $check->execute();
            $remaining = $check->get_result()->fetch_assoc()['total'] ?? 0;

            // Kalau tiada lagi undi, reset status pelajar
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
            $msg = "<div class='alert success'>Rekod undi berjaya dipadam.</div>";

        } catch (Exception $e) {
            $conn->rollback();
            $msg = "<div class='alert error'>Ralat: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}

// PAPAR SENARAI UNDI
$sql = "
    SELECT 
        uk.id_undi,
        uk.Id_Pelajar,
        p.Nama_Pelajar,
        uk.Id_Kategori,
        k.Kategori,
        uk.id_calon,
        c.nama_calon,
        c.kelas_calon,
        uk.Tarikh_Undi
    FROM undi_kategori uk
    JOIN pelajar p ON uk.Id_Pelajar = p.Id_Pelajar
    JOIN kategori k ON uk.Id_Kategori = k.Id_Kategori
    JOIN calon c ON uk.id_calon = c.id_calon
    ORDER BY uk.Tarikh_Undi DESC, uk.id_undi DESC
";

$result = $conn->query($sql);

if (!$result) {
    die("Ralat query undi: " . $conn->error);
}
?>

<div class="card" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
    <div>
        <h2 style="margin: 0;">Senarai Semua Undi</h2>
        <p style="margin: 5px 0 0 0;">Admin boleh mengurus dan memantau rekod undian pelajar.</p>
    </div>
    
    <div class="action-buttons">
        <a href="cetak-pdf.php" target="_blank" class="btn" style="background: #e74c3c; text-decoration: none;">
             📄 Cetak Laporan PDF
        </a>
    </div>
</div>

<?= $msg ?>

<div class="card" style="overflow-x: auto;">
    <table class="table">
        <thead>
            <tr>
                <th>ID Undi</th>
                <th>ID Pelajar</th>
                <th>Nama Pelajar</th>
                <th>Kategori</th>
                <th>Calon Dipilih</th>
                <th>Kelas Calon</th>
                <th>Tarikh</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$row['id_undi'] ?></td>
                    <td><?= htmlspecialchars($row['Id_Pelajar']) ?></td>
                    <td><?= htmlspecialchars($row['Nama_Pelajar']) ?></td>
                    <td><?= htmlspecialchars($row['Kategori']) ?></td>
                    <td><?= htmlspecialchars($row['nama_calon']) ?></td>
                    <td><?= htmlspecialchars($row['kelas_calon']) ?></td>
                    <td><?= htmlspecialchars($row['Tarikh_Undi']) ?></td>
                    <td>
                        <a href="senarai-undi.php?padam=<?= (int)$row['id_undi'] ?>" 
                           style="color: #e74c3c; font-weight: bold; text-decoration: none;"
                           onclick="return confirm('Adakah anda pasti mahu memadam rekod undi ini? Jumlah undi calon akan ditolak secara automatik.')">
                           Padam
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center;">Tiada rekod undian dijumpai.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>