<?php
session_start();

/*
|--------------------------------------------------------------------------
| Intro hanya keluar sekali sahaja
|--------------------------------------------------------------------------
*/

if (!isset($_COOKIE['intro_seen'])) {
    setcookie("intro_seen", "yes", time() + (30 * 24 * 60 * 60), "/");
    header("Location: intro.php");
    exit;
}

$pageTitle = "VOTERA2526";
include 'header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- OFFLINE BANNER -->
<div id="offlineBanner" class="offline-banner">
    ⚠️ Anda sedang offline. Sila sambung internet untuk teruskan.
</div>

<div class="hero-box">
    <div class="hero-badge">Sistem Undian Digital Pintar</div>

    <h1>Selamat Datang ke VOTERA 2526: Undian Kelas Pintar 🔥</h1>

    <p class="hero-subtitle">
        Sistem undian digital moden untuk keunggulan kelas, pantas, selamat dan mudah digunakan.
    </p>

    <!-- BUTTONS -->
    <div class="hero-buttons">

        <a href="login.php" class="btn hero-btn" title="Log Masuk Pelajar">
            <i class="fa-solid fa-user-graduate"></i>
            <span class="btn-text">Pelajar</span>
        </a>

        <a href="admin-login.php" class="btn hero-btn" title="Log Masuk Pentadbir">
            <i class="fa-solid fa-user-gear"></i>
            <span class="btn-text">Admin</span>
        </a>

        <a href="live-result.php" class="btn hero-btn" title="Lihat Kiraan Langsung">
            <i class="fa-solid fa-chart-column"></i>
            <span class="btn-text">Live</span>
        </a>

    </div>
</div>

<style>
.hero-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 25px;
}

.hero-btn {
    width: 60px;
    height: 60px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    border-radius: 15px;
    font-size: 22px;
    background: #2d6cdf;
    color: white;
    text-decoration: none;
    transition: 0.25s ease;
}

/* TEXT HIDDEN NORMAL MODE */
.btn-text {
    font-size: 10px;
    display: none;
}

/* hover effect */
.hero-btn:hover {
    transform: translateY(-5px);
    background: #1f4ea8;
}

/* OFFLINE MODE */
.offline .btn-text {
    display: block;
}

.offline .hero-btn {
    width: 80px;
    height: 80px;
}

/* OFFLINE BANNER */
.offline-banner {
    display: none;
    background: #ff4d4d;
    color: white;
    text-align: center;
    padding: 12px;
    font-weight: bold;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
}
</style>

<script>
function showOfflineBanner() {
    const banner = document.getElementById("offlineBanner");

    banner.style.display = "block";

    // auto hilang selepas 3 saat
    setTimeout(() => {
        banner.style.display = "none";
    }, 3000);
}

function updateOnlineStatus() {
    if (!navigator.onLine) {
        document.body.classList.add("offline");
        showOfflineBanner();
    } else {
        document.body.classList.remove("offline");
        document.getElementById("offlineBanner").style.display = "none";
    }
}

window.addEventListener("load", updateOnlineStatus);
window.addEventListener("online", updateOnlineStatus);
window.addEventListener("offline", updateOnlineStatus);
</script>
<?php include 'footer.php'; ?>  