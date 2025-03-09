<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../authusers/auth.php";
checkRoles(['QAQC', 'IT', 'super_admin']); 
use App\Models\ItemReturn;

$itemReturn = new ItemReturn();
$disposedItems = $itemReturn->getDisposedItems(); //  pulls both lost and unrepairable items
?>
<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disposed Items</title>
    <link href="<?= URL; ?>css/tables.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Disposed Items</h2>

    <?php if (!empty($disposedItems)): ?>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Serial Number</th>
                    <th>Category</th>
                    <th>Returned By</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($disposedItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['description']); ?></td>
                        <td><?= htmlspecialchars($item['serial_number']); ?></td>
                        <td><?= htmlspecialchars($item['category_name']); ?></td>
                        <td><?= htmlspecialchars($item['returned_by']); ?></td>
                        <td>
                            <?= $item['item_state'] === 'lost' ? 'Lost' : ($item['repair_status'] === 'Unrepairable' ? 'Unrepairable' : ''); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No disposed items at the moment.</p>
    <?php endif; ?>
</div>

    </div>
</body>
</html>
