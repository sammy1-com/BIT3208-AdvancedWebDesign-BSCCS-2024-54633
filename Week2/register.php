<?php
// WEEK 2 - Register Page: HTML/CSS Design Only (no PHP processing yet)
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

        <!-- WEEK 2: Form designed but not yet connected to PHP/DB -->
        <form method="POST">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
            <button type="submit" class="btn-submit">Create Account</button>
        </form>

        <div class="auth-switch">Already have an account? <a href="login.php">Login</a></div>
    </div>
</div>
</body>
</html>
