<?php
// WEEK 4 - Logout: Destroy session and redirect
if (session_status() === PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();
header('Location: /pitstop/index.php');
exit;
