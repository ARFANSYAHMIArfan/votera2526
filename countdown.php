<?php include 'header.php'; ?>
<div class="card">
    <h2>Undian Tamat Dalam</h2>
    <div class="timer" style="margin-top:20px;">
        <div class="timer-box"><span id="days">0</span>Hari</div>
        <div class="timer-box"><span id="hours">0</span>Jam</div>
        <div class="timer-box"><span id="minutes">0</span>Minit</div>
        <div class="timer-box"><span id="seconds">0</span>Saat</div>
    </div>
</div>
<script>
const endTime = new Date('2026-12-31T23:59:59').getTime();
setInterval(() => {
    const now = new Date().getTime();
    let distance = endTime - now;
    if (distance < 0) distance = 0;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById('days').innerText = days;
    document.getElementById('hours').innerText = hours;
    document.getElementById('minutes').innerText = minutes;
    document.getElementById('seconds').innerText = seconds;
}, 1000);
</script>
<?php include 'footer.php'; ?>
