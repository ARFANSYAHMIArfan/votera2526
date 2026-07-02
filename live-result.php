<?php
include 'connection.php';
include 'header.php';

// 1. Dapatkan jumlah keseluruhan undi dalam sistem untuk kira peratusan
$total_votes_query = $conn->query("SELECT SUM(tot_undi_kelas) AS total FROM kelas");
$total_data = $total_votes_query->fetch_assoc();
$grand_total_votes = (int)($total_data['total'] ?? 0);

// 2. Ambil data kelas dan susun mengikut jumlah undian tertinggi ke terendah
$result = $conn->query("
    SELECT * FROM kelas 
    ORDER BY tot_undi_kelas DESC
");

if (!$result) {
    die("Ralat query live-result: " . $conn->error);
}
?>

<div class="container">
    <!-- Butang Kembali yang Kemas -->
    <a href="index.php" class="back-btn">⬅️ Kembali ke Laman Utama</a>

    <div class="card" style="padding: 25px; background: #fff; border-radius: 18px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
        <h2 style="color: #2c3e50; margin-bottom: 8px; font-size: 1.6rem; display: flex; align-items: center; gap: 10px;">
            📊 Keputusan Live Undian Mengikut Kelas
        </h2>
        <p style="color: #7f8c8d; margin-bottom: 25px; font-size: 0.95rem;">
            Jumlah Keseluruhan Undi Semasa: <strong style="color: #2980b9; font-size: 1.1rem;"><?= $grand_total_votes ?> undi</strong>
        </p>

        <div style="display: flex; flex-direction: column; gap: 20px;">
            <?php 
            $rank = 1;
            while($row = $result->fetch_assoc()): 
                // Mengamankan nama kolum daripada isu huruf besar/kecil
                $row_lower = array_change_key_case($row, CASE_LOWER);
                $nama_kelas = $row_lower['kelas'] ?? 'Tiada Nama';
                $undi_kelas = (int)($row_lower['tot_undi_kelas'] ?? 0);

                // Kira peratusan undi untuk Progress Bar (Elakkan ralat devide by zero)
                $peratus = $grand_total_votes > 0 ? round(($undi_kelas / $grand_total_votes) * 100, 1) : 0;
            ?>
                <!-- Baris Keputusan Setiap Kelas -->
                <div class="bar-row" style="background: #f8f9fa; padding: 15px; border-radius: 12px; border-left: 5px solid #3498db;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 5px;">
                        <!-- Nama Kelas & Kedudukan -->
                        <span style="font-weight: bold; color: #2c3e50; font-size: 1.05rem;">
                            <?php 
                                if ($rank == 1) echo "🥇 1st | ";
                                elseif ($rank == 2) echo "🥈 2nd | ";
                                elseif ($rank == 3) echo "🥉 3rd | ";
                                else echo "🏅 " . $rank . "th | ";
                            ?>
                            Kelas: <span style="color: #2980b9; text-transform: uppercase;"><?= htmlspecialchars($nama_kelas) ?></span>
                        </span>
                        
                        <!-- Jumlah Undi & Peratusan -->
                        <span style="font-weight: bold; color: #16a085; font-size: 1rem;">
                            <?= $undi_kelas ?> Undi <span style="color: #7f8c8d; font-weight: normal; font-size: 0.9rem;">(<?= $peratus ?>%)</span>
                        </span>
                    </div>

                    <!-- Progress Bar Visual (Menggunakan class dari style.css anda) -->
                    <div class="bar-track" style="height: 22px; background: #e9ecef; border-radius: 999px;">
                        <div class="bar-fill" style="width: <?= $peratus ?>%; height: 100%; border-radius: 999px; font-size: 11px; line-height: 22px; text-align: right; padding-right: 15px; font-weight: bold; box-shadow: inset 0 -2px 5px rgba(0,0,0,0.1); transition: width 0.5s ease-in-out;">
                            <?= $peratus > 5 ? $peratus . '%' : '' ?>
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