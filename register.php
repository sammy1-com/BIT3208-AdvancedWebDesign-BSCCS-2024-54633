<?php
// WEEK 4 - Register: Full DB-backed registration with password hashing
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (is_logged_in()) redirect('/pitstop/index.php');

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$password || !$confirm) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // WEEK 4: Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            // WEEK 4: Hash password with PHP's password_hash
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt2  = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')");
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
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — PitStop Parts</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/pitstop/assets/css/style.css">
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
        <div class="auth-switch"><a href="login.php">Proceed to Login</a></div>
        <?php else: ?>
        <form method="POST">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
            <label class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <small id="strength-msg" style="display:block;margin-bottom:8px;font-size:12px;"></small>
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
            <button type="submit" class="btn-submit">Create Account</button>
        </form>
        <div class="auth-switch">Already have an account? <a href="login.php">Login</a></div>
        <?php endif; ?>
    </div>
</div>
<script>
document.getElementById('password').addEventListener('input', function () {
    var val = this.value;
    var msg = document.getElementById('strength-msg');
    if (val.length === 0) { msg.textContent = ''; }
    else if (val.length < 6) { msg.textContent = 'Too short'; msg.style.color = '#c0392b'; }
    else if (val.length < 10) { msg.textContent = 'Medium strength'; msg.style.color = '#D4A853'; }
    else { msg.textContent = 'Strong password'; msg.style.color = '#27a844'; }
});
</script>
</body>
</html>
