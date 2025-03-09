<?php
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/views/authusers/auth.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['user']['role'] ?? 'default';

// Load the correct navbar based on the user's role
switch (trim($role)) {
    case 'IT':
        include(__DIR__ . "/itnavbar.php");

        break;
    case 'MLE':
        include(__DIR__ . "/mlenavbar.php");

        break;
    case 'QAQC':
        include(__DIR__ . "/qanavbar.php");

        break;
    case 'super_admin':
        include(__DIR__ . "/nav_navbar.php");

        break;
    default:
    include(__DIR__ . "/navnavbar.php");
        break;
}
?>
