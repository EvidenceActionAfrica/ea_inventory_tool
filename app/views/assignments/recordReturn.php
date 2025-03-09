<?php
    var_dump($items);
    var_dump($receivers);
?>

<?php
session_start();
use App\Controllers\ItemReturnController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ItemReturnController();
    $response = $controller->recordReturn($_POST);

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
