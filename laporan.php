<?php
include 'connection.php';
include 'admin-auth.php';
include 'header.php';

// Menyusun mengikut nama kategori dahulu, kemudian susun undi paling tinggi ke terendah
$result = $conn->query("
    SELECT 
        c.*, 
        k.Kategori AS nama_kategori_sebenar 
    FROM calon c
    INNER JOIN kategori k ON c.Id_Kategori = k.Id_Kategori
    ORDER BY k.Kategori ASC, c.Undi DESC
");

// Fallback jika ada isu perbezaan huruf kecil/besar pada nama kolum di database
if (!$result) {
    $result = $conn->query("
        SELECT 
            c.*, 
            k.Kategori AS nama_kategori_sebenar 
        FROM calon c
        INNER JOIN kategori k ON c.id_kategori = k.Id_Kategori
        ORDER BY k.Kategori ASC, c.undi DESC
    ");
}

if (!$result) {
    die("Ralat query laporan: " . $conn->error);
}
?>

<div class="card" style="padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h2 style="color: #2c3e50; margin-bottom: 20px; font-size: 1.5rem; border-bottom: 2px solid #16a085; padding-bottom: 10px;">
        📊 Laporan Keputusan Undian Mengikut Kategori
    </h2>

    <table class="table" style="width:100%; border-collapse: collapse; font-family: sans-serif; box-shadow: 0 2px 3px rgba(0,0,0,0.05);">
        <thead>
            <tr style="background-color: #2c3e50; color: white; text-align: left;">
                <th style="padding: 12px 15px; width: 20%; text-align: center;">Kedudukan</th>
                <th style="padding: 12px 15px; width: 60%;">Nama Calon</th>
                <th style="padding: 12px 15px; width: 20%; text-align: center;">Jumlah Undi</th>
            </tr>
        </thead>
        <tbody>

        <?php 
        $current_category = ""; 
        $rank = 1;

        while($row = $result->fetch_assoc()): 
            // Tukar key array kepada huruf kecil untuk mengelakkan ralat kesensitifan huruf
            $row_lower = array_change_key_case($row, CASE_LOWER); 
            $kategori_sekarang = $row['nama_kategori_sebenar'] ?? $row['Nama_Kategori_Sebenar'] ?? 'Tiada Kategori';

            // JIKA JUMPA KATEGORI BARU: Paparkan sub-header baru & reset kedudukan ranking
            if ($current_category !== $kategori_sekarang): 
                $current_category = $kategori_sekarang;
                $rank = 1; // Reset semula ke ranking 1st untuk kategori baru
        ?>
            <tr style="background-color: #eef2f3; font-weight: bold;">
                <td colspan="3" style="font-size: 1.05rem; color: #2c3e50; padding: 15px; border-bottom: 2px solid #bdc3c7;">
                    📂 Kategori: <span style="color: #16a085; text-transform: uppercase;"><?= htmlspecialchars($current_category) ?></span>
                </td>
            </tr>
        <?php endif; ?>

            <tr style="border-bottom: 1px solid #f1f1f1; transition: background 0.2s;">
                <td style="text-align: center; padding: 12px 15px; font-weight: bold;">
                    <?php 
                        if ($rank == 1) echo "<span style='color: #f1c40f; font-size: 1.15rem;'>🥇 1st</span>";
                        elseif ($rank == 2) echo "<span style='color: #7f8c8d; font-size: 1.1rem;'>🥈 2nd</span>";
                        elseif ($rank == 3) echo "<span style='color: #d35400; font-size: 1.05rem;'>🥉 3rd</span>";
                        else echo "<span style='color: #7f8c8d; font-weight: normal;'>" . $rank . "th</span>";
                    ?>
                </td>
                
                <td style="padding: 12px 15px; color: #34495e; font-weight: 500;">
                    <?= htmlspecialchars($row_lower['nama_calon'] ?? $row_lower['nama'] ?? '') ?>
                </td>
                
                <td style="text-align: center; padding: 12px 15px; font-weight: bold; color: #16a085; font-size: 1.1rem;">
                    <?= (int)($row_lower['undi'] ?? 0) ?>
                </td>
            </tr>

        <?php 
            $rank++; // Naikkan ranking (2nd, 3rd, 4th...) untuk calon seterusnya dalam kategori yang sama
        endwhile; 
        ?>

        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>