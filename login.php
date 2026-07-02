<?php
include 'connection.php';
$pageTitle = "Log Masuk Pelajar";
include 'header.php';

$msg = "";

if (isset($_POST['login'])) {
    $id = trim($_POST['Id_Pelajar']);
    $pass = $_POST['Kata_Laluan'];

    $stmt = $conn->prepare("SELECT * FROM pelajar WHERE Id_Pelajar = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($pass, $row['Kata_Laluan'])) {
            session_regenerate_id(true);
            $_SESSION['Id_Pelajar'] = $row['Id_Pelajar'];
            $_SESSION['Nama_Pelajar'] = $row['Nama_Pelajar'];
            header("Location: dashboard.php");
            exit;
        }
    }

    $msg = "<div class='alert error login-alert'>ID Pelajar atau Kata Laluan tidak sah.</div>";
}
?>

<div class="auth-page auth-page-student">
    <div class="auth-particles"></div>

    <div class="auth-card auth-card-student">
        <div class="auth-glow-border"></div>

        <div class="auth-inner">
            <h2 class="auth-title">🎓 Log Masuk Pelajar</h2>

            <?= $msg ?>

            <form method="post" class="auth-form">
                <div class="auth-input-group">
                    <span class="auth-icon">🪪</span>
                    <input type="text" name="Id_Pelajar" placeholder="ID Pelajar" required>
                </div>

                <div class="auth-input-group">
                    <span class="auth-icon">🔒</span>
                    <input type="password" name="Kata_Laluan" id="studentPassword" placeholder="Kata Laluan" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('studentPassword', this)">👁</button>
                </div>

                <button type="submit" name="login" class="btn auth-login-btn">Log Masuk</button>
            </form>
        </div>
    </div>
</div>

<div class="auth-register-link">
    Belum ada akaun?
    <a href="register.php">Daftar di sini</a>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);

    if (input.type === "password") {
        input.type = "text";
        btn.textContent = "🙈";
    } else {
        input.type = "password";
        btn.textContent = "👁";
    }
}
</script>

<?php include 'footer.php'; ?>