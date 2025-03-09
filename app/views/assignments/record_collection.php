<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user_id = $_SESSION['user']['id'] ?? null;
if (!$user_id) {
    die("User not logged in. Session issue.");
}

require_once __DIR__ . "/../authusers/auth.php";

use App\Controllers\ItemReturnController;

$itemReturnController = new ItemReturnController();
$items = $itemReturnController->showUserItems();
$receivers = $itemReturnController->getAllReceivers();
?>

<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Returned Items</title>
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
</head>
<body>

<div class="form-container">
    <h2>Record Returned Items</h2>

    <?php if (!empty($items)): ?>
        <form action="<?= URL; ?>item-returns/record" method="post">
            <div class="form-group">
                <label for="inventory_ids[]">Select Items to Return:</label>
                <select name="inventory_ids[]" multiple required>
                    <?php foreach ($items as $item): ?>
                        <option value="<?= $item['item_id']; ?>">
                            <?= htmlspecialchars($item['description']); ?> (Serial: <?= htmlspecialchars($item['serial_number']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="return_date">Return Date:</label>
                <input type="date" id="return_date" name="return_date" required>
            </div>

            <div class="form-group">
                <label for="receiver_id">Select Receiver:</label>
                <select name="receiver_id" required>
                    <option value="">Select Receiver</option>
                    <?php foreach ($receivers as $receiver): ?>
                        <option value="<?= $receiver['id']; ?>">
                            <?= htmlspecialchars($receiver['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="submit-btn">Record Return</button>
        </form>
    <?php else: ?>
        <p>No items to return.</p>
    <?php endif; ?>
</div>

</body>
</html>
