<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (is_logged_in()) redirect('/index.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            if ($user['role'] === 'admin') {
                redirect('/admin/index.php');
            } else {
                redirect('/index.php');
            }
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — PitStop Parts</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo">Pit<span>Stop</span></div>
        <div class="auth-subtitle">Sign in to your account</div>
        <?php if ($error): ?>
        <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
    <label class="form-label">Full Name</label>
    <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
    
    <label class="form-label">Email Address</label>
    <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
    
    <label class="form-label">Phone Number</label>
    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
    
    <label class="form-label">Password</label>
    <div style="display:flex;margin-bottom:20px;">
        <input type="password" name="password" id="reg-password" class="form-control" required 
               style="width:auto;flex:1;margin-bottom:0;border-right:none;border-radius:2px 0 0 2px;">
        <button type="button" onclick="togglePassword('reg-password', 'toggle-icon-1')"
                style="width:45px;background:var(--white);border:1px solid var(--linen);border-left:none;
                       border-radius:0 2px 2px 0;cursor:pointer;
                       display:flex;align-items:center;justify-content:center;">
            <i class="fa-regular fa-eye" id="toggle-icon-1" style="color:#000;"></i>
        </button>
    </div>
    <small id="strength-msg" style="display:block;margin-bottom:8px;font-size:12px;"></small>

    <label class="form-label">Confirm Password</label>
    <div style="display:flex;margin-bottom:20px;">
        <input type="password" name="confirm_password" id="reg-confirm" class="form-control" required 
               style="width:auto;flex:1;margin-bottom:0;border-right:none;border-radius:2px 0 0 2px;">
        <button type="button" onclick="togglePassword('reg-confirm', 'toggle-icon-2')"
                style="width:45px;background:var(--white);border:1px solid var(--linen);border-left:none;
                       border-radius:0 2px 2px 0;cursor:pointer;
                       display:flex;align-items:center;justify-content:center;">
            <i class="fa-regular fa-eye" id="toggle-icon-2" style="color:#000;"></i>
        </button>
    </div>

    <button type="submit" class="btn-submit">Create Account</button>
</form>
<div class="auth-switch">Already have an account? <a href="/login.php">Login</a></div>
        <div class="auth-switch" style="margin-top:12px;">
            <a href="/index.php" style="color:var(--taupe);">Back to Store</a>
        </div>
    </div>
</div>
    <script>
function togglePassword(inputId, iconId) {
    var input = document.getElementById(inputId);
    var icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>
