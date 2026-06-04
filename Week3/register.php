<?php
// WEEK 3 - Register: PHP validation + JavaScript password strength checker
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // WEEK 3: Server-side PHP validation
    if (!$name || !$email || !$password || !$confirm) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // WEEK 3: No DB yet - just show success message
        $success = 'Account validated successfully. (DB registration comes in Week 4)';
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
        <?php endif; ?>

        <form method="POST">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
            <label class="form-label">Password</label>
            <!-- WEEK 3: JS password strength checker on this input -->
            <input type="password" name="password" id="password" class="form-control" required>
            <small id="strength-msg" style="display:block;margin-bottom:8px;font-size:12px;"></small>
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
            <button type="submit" class="btn-submit">Create Account</button>
        </form>

        <div class="auth-switch">Already have an account? <a href="login.php">Login</a></div>
    </div>
</div>

<script>
// WEEK 3: Password strength checker - DOM manipulation practice
document.getElementById('password').addEventListener('input', function () {
    var val = this.value;
    var msg = document.getElementById('strength-msg');

    if (val.length === 0) {
        msg.textContent = '';
    } else if (val.length < 6) {
        msg.textContent = 'Too short — must be at least 6 characters';
        msg.style.color = '#c0392b';
    } else if (val.length < 10) {
        msg.textContent = 'Medium strength';
        msg.style.color = '#D4A853';
    } else {
        msg.textContent = 'Strong password';
        msg.style.color = '#27a844';
    }
});
</script>
</body>
</html>
