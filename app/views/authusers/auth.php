<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function checkRoles(array $allowedRoles) {
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role'])) {
        header('HTTP/1.1 403 Forbidden');
        exit('❌ Access Denied: No user session or role.');
    }

    $userRole = trim($_SESSION['user']['role']);
    $allowedRoles = array_map('trim', $allowedRoles);

    

    if (in_array($userRole, $allowedRoles, true)) {

        return; // Stop further execution if access is granted
    }

    header('HTTP/1.1 403 Forbidden');
    exit('❌ Access Denied: Unauthorized role.');
}
?>
