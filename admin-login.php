<?php
include 'connection.php';
$pageTitle = "Log Masuk Pentadbir";
include 'header.php';

$msg = "";

if (isset($_POST['login_admin'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['nama'];
            $_SESSION['admin_role'] = $admin['role'];

            header("Location: admin-dashboard.php");
            exit;
        }
    }

    $msg = "<div class='alert error login-alert'>Nama Pengguna atau Kata Laluan tidak sah.</div>";
}
?>

<div class="auth-page auth-page-admin">
    <div class="auth-particles"></div>

    <div class="auth-card auth-card-admin">
        <div class="auth-glow-border"></div>

        <div class="auth-inner">
            <h2 class="auth-title">🧑‍💼 Log Masuk Pentadbir</h2>

            <?= $msg ?>

            <form method="post" class="auth-form">
                <div class="auth-input-group">
                    <span class="auth-icon">👤</span>
                    <input type="text" name="username" placeholder="Nama Pengguna" required>
                </div>

                <div class="auth-input-group">
                    <span class="auth-icon">🔒</span>
                    <input type="password" name="password" id="adminPassword" placeholder="Kata Laluan" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('adminPassword', this)">👁</button>
                </div>

                <button type="submit" name="login_admin" class="btn auth-login-btn">Log Masuk</button>
            </form>
        </div>
    </div>
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