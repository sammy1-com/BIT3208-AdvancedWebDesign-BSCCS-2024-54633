<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (is_logged_in()) redirect('/index.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$password || !$confirm) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt2 = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')");
            $stmt2->bind_param('ssss', $name, $email, $phone, $hashed);
            if ($stmt2->execute()) {
                $success = 'Account created successfully. You can now log in.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
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
<title>Register — PitStop Parts</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo">Pit<span>Stop</span></div>
        <div class="auth-subtitle">Create your account</div>
        <?php if ($error): ?>
        <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
        <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
        <div class="auth-switch"><a href="/login.php">Proceed to Login</a></div>
        <?php else: ?>
        <form method="POST">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
           <label class="form-label">Password</label>
<div style="position:relative;display:flex;align-items:center;">
    <input type="password" name="password" id="reg-password" class="form-control" required>
    <button type="button" onclick="togglePassword('reg-password', 'toggle-icon-1')"
            style="position:absolute;right:0;top:0;bottom:0;width:45px;
                   background:#f1f1f1;border:1px solid #ccc;border-left:none;
                   border-radius:0 4px 4px 0;cursor:pointer;
                   display:flex;align-items:center;justify-content:center;">
        <i class="fa-regular fa-eye" id="toggle-icon-1" style="color:#000;"></i>
    </button>
</div>
<small id="strength-msg" style="display:block;margin-bottom:8px;font-size:12px;"></small>

<label class="form-label">Confirm Password</label>
<div style="position:relative;display:flex;align-items:center;">
    <input type="password" name="confirm_password" id="reg-confirm" class="form-control" required>
    <button type="button" onclick="togglePassword('reg-confirm', 'toggle-icon-2')"
            style="position:absolute;right:0;top:0;bottom:0;width:45px;
                   background:#f1f1f1;border:1px solid #ccc;border-left:none;
                   border-radius:0 4px 4px 0;cursor:pointer;
                   display:flex;align-items:center;justify-content:center;">
        <i class="fa-regular fa-eye" id="toggle-icon-2" style="color:#000;"></i>
    </button>
</div>
            <button type="submit" class="btn-submit">Create Account</button>
        </form>
        <div class="auth-switch">Already have an account? <a href="/login.php">Login</a></div>
        <?php endif; ?>
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

document.querySelector('form').addEventListener('submit', function(e) {
    var password = document.getElementById('reg-password').value;
    var msg = document.getElementById('strength-msg');
    var errors = [];

    if (password.length < 10) {
        errors.push('at least 10 characters');
    }
    if (!/[A-Z]/.test(password)) {
        errors.push('at least one uppercase letter');
    }
    if (!/[^a-zA-Z0-9]/.test(password)) {
        errors.push('at least one symbol');
    }

    if (errors.length > 0) {
        e.preventDefault();
        msg.textContent = 'Password must contain: ' + errors.join(', ') + '.';
        msg.style.color = '#c0392b';
    } else {
        msg.textContent = '';
    }
});
</script>
</body>
</html>
