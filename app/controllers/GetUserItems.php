<?php
require_once __DIR__ . '/../models/ItemReturn.php';

// Ensure the response is JSON
header('Content-Type: application/json');

if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $itemReturn = new ItemReturn();
    $items = $itemReturn->getUserItems($user_id);

    // Send back JSON data
    echo json_encode($items);
    exit();
} else {
    echo json_encode([]);
    exit();
}
?>
