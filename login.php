<?php
// WEEK 3 - Login: PHP form validation added (no real DB check yet)
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // WEEK 3: PHP validation - check fields are not empty
    if (!$email || !$password) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // WEEK 3: Hardcoded credential check (real DB auth comes in Week 4)
        if ($email === 'admin@pitstopparts.co.ke' && $password === 'password') {
            // Redirect on "success" for demo purposes
            header('Location: index.php?welcome=1');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — PitStop Parts</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/pitstop/assets/css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo">Pit<span>Stop</span></div>
        <div class="auth-subtitle">Sign in to your account</div>

        <?php if ($error): ?>
        <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- WEEK 3: JavaScript validation runs BEFORE PHP -->
        <form method="POST" id="login-form" onsubmit="return validateLogin()">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" id="login-email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <label class="form-label">Password</label>
            <input type="password" name="password" id="login-password" class="form-control" required>
            <button type="submit" class="btn-submit">Sign In</button>
        </form>

        <div class="auth-switch">
            Don't have an account? <a href="register.php">Register</a>
        </div>
        <div class="auth-switch" style="margin-top:12px;">
            <a href="index.php" style="color:var(--taupe);">Back to Store</a>
        </div>
    </div>
</div>

<script>
// WEEK 3: JavaScript form validation
function validateLogin() {
    var email = document.getElementById('login-email').value.trim();
    var password = document.getElementById('login-password').value;

    if (!email || !password) {
        alert('Please fill in all fields.');
        return false;
    }

    if (password.length < 6) {
        alert('Password must be at least 6 characters.');
        return false;
    }

    return true; // Allow form to submit to PHP
}
</script>
</body>
</html>
