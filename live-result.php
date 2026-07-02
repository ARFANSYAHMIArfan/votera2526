<?php
include 'connection.php';
include 'header.php';

// 1. Dapatkan jumlah undi keseluruhan bagi setiap kategori untuk kira peratusan calon
$total_kategori = [];
$count_total = $conn->query("SELECT Id_Kategori, SUM(Undi) AS total FROM calon GROUP BY Id_Kategori");
if (!$count_total) {
    $count_total = $conn->query("SELECT id_kategori, SUM(undi) AS total FROM calon GROUP BY id_kategori");
}
if ($count_total) {
    while ($t_row = $count_total->fetch_assoc()) {
        $t_row_lower = array_change_key_case($t_row, CASE_LOWER);
        $total_kategori[$t_row_lower['id_kategori']] = (int)$t_row_lower['total'];
    }
}

// 2. Ambil data calon dan join dengan kategori, susun mengikut kategori & undi tertinggi
$result = $conn->query("
    SELECT 
        c.*, 
        k.Kategori AS nama_kategori_sebenar 
    FROM calon c
    INNER JOIN kategori k ON c.Id_Kategori = k.Id_Kategori
    ORDER BY k.Kategori ASC, c.Undi DESC
");

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
    die("Ralat query live-result: " . $conn->error);
}
?>

<div class="container">
    <!-- Meta auto-refresh setiap 10 saat kekal berfungsi -->
    <script>
        setTimeout(function(){
            window.location.reload();
        }, 10000);
    </script>

    <a href="index.php" class="back-btn">⬅️ Kembali</a>

    <div class="card" style="padding: 25px; background: #fff; border-radius: 18px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
        <h2 style="color: #2c3e50; margin-bottom: 5px; font-size: 1.6rem;">📢 LIVE RESULT SCREEN</h2>
        <h3 style="color: #16a085; margin-bottom: 5px; font-size: 1.25rem;">Keputusan Semasa Undian Calon</h3>
        <p style="color: #7f8c8d; margin-bottom: 25px; font-size: 0.85rem; font-style: italic;">Auto refresh setiap 10 saat.</p>

        <div style="display: flex; flex-direction: column; gap: 25px;">
        <?php 
        $current_category = ""; 
        $rank = 1;

        while($row = $result->fetch_assoc()): 
            $row_lower = array_change_key_case($row, CASE_LOWER); 
            $kategori_sekarang = $row['nama_kategori_sebenar'] ?? $row['Nama_Kategori_Sebenar'] ?? 'Tiada Kategori';
            $id_kat = $row_lower['id_kategori'] ?? '';
            $undi_calon = (int)($row_lower['undi'] ?? 0);
            
            // Ambil jumlah undi keseluruhan kategori ini
            $total_undi_kat = $total_kategori[$id_kat] ?? 0;
            
            // Kira peratus asal
            $peratus = $total_undi_kat > 0 ? round(($undi_calon / $total_undi_kat) * 100, 1) : 0;

            // FIX: Sekat peratusan supaya TIDAK BOLEH lebih daripada 100%
            if ($peratus > 100) {
                $peratus = 100;
            }
            // Jika undian maksimum tapi data pelik, kita default kan ke 100%
            if ($undi_calon > 0 && $total_undi_kat == 0) {
                $peratus = 100;
            }

            // Jika masuk Kategori Baru, paparkan nama Kategori tersebut
            if ($current_category !== $kategori_sekarang): 
                $current_category = $kategori_sekarang;
                $rank = 1; 
        ?>
            <!-- Header Pembagi Kategori -->
            <div style="background: #2c3e50; color: #fff; padding: 12px 20px; border-radius: 10px; font-weight: bold; font-size: 1.1rem; margin-top: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                📂 KATEGORI: <?= htmlspecialchars($current_category) ?>
            </div>
        <?php endif; ?>

            <!-- Baris Visual Bar bagi Setiap Calon -->
            <div class="bar-row" style="background: #fdfdfd; padding: 15px; border-radius: 12px; border: 1px solid #edf2f7; margin: -5px 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 10px;">
                    <!-- Nama & Kedudukan Calon -->
                    <span style="font-weight: 600; color: #2c3e50;">
                        <?php 
                            if ($rank == 1) echo "<span style='color: #f1c40f;'>🥇 1st</span>";
                            elseif ($rank == 2) echo "<span style='color: #7f8c8d;'>🥈 2nd</span>";
                            elseif ($rank == 3) echo "<span style='color: #d35400;'>🥉 3rd</span>";
                            else echo "<span style='color: #a0aec0;'>" . $rank . "th</span>";
                        ?>
                        <span style="margin-left: 8px; text-transform: uppercase;"><?= htmlspecialchars($row_lower['nama_calon'] ?? $row_lower['nama'] ?? '') ?></span>
                    </span>
                    
                    <!-- Bilangan Undi Semasa -->
                    <span style="font-weight: bold; color: #16a085; background: #e6f4ea; padding: 4px 12px; border-radius: 20px; font-size: 0.9rem;">
                        <?= $undi_calon ?> Undi
                    </span>
                </div>

                <!-- Progress Bar Grafik Bergerak -->
                <div class="bar-track" style="height: 20px; background: #edf2f7; border-radius: 999px;">
                    <!-- CSS width dikawal oleh $peratus yang dah dilaunkan max 100% -->
                    <div class="bar-fill" style="width: <?= $peratus ?>%; height: 100%; border-radius: 999px; font-size: 11px; line-height: 20px; text-align: right; padding-right: 15px; font-weight: bold; transition: width 0.6s ease-in-out;">
                        <?= $peratus > 8 ? $peratus . '%' : '' ?>
                    </div>
                </div>
            </div>

        <?php 
            $rank++;
        endwhile; 
        ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>