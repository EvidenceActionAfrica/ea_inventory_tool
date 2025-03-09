<?php
require_once __DIR__ . "/../authusers/auth.php";

checkRoles(['QAQC', 'IT', 'super_admin']); 
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<?php require_once __DIR__ . '/../app/models/ItemReturn.php'; ?>

<?php
$itemReturn = new ItemReturn();
$search = $_GET['search'] ?? null;
$returns = $itemReturn->getUserReturns($search);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returned Items</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<?php if (isset($_GET['success'])): ?>
    <p class="success-message"><?= htmlspecialchars($_GET['success']); ?></p>
<?php elseif (isset($_GET['error'])): ?>
    <p class="error-message"><?= htmlspecialchars($_GET['error']); ?></p>
<?php endif; ?>

<h2>Returned Items</h2>

<div class="top-bar">
    <form method="GET" action="view_collections.php" class="search-form">
        <input type="text" name="search" placeholder="Search by Name or Serial Number" 
               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
        <?php if (!empty($_GET['search'])): ?>
            <a href="view_collections.php" class="reset-search">Reset</a>
        <?php endif; ?>
    </form>

</div>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Item</th>
            <th>Serial Number</th>
            <th>Return Date</th>
            <th>Received By</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($returns as $return): ?>
            <tr>
                <td><?= htmlspecialchars($return['name']); ?></td>
                <td><?= htmlspecialchars($return['email']); ?></td>
                <td><?= htmlspecialchars($return['category_name'] ?? 'N/A'); ?>: <?= htmlspecialchars($return['description'] ?? 'N/A'); ?></td>
                <td><?= htmlspecialchars($return['serial_number']); ?></td>
                <td><?= htmlspecialchars($return['return_date']); ?></td>
                <td><?= htmlspecialchars($return['received_by']); ?></td>
                <td>
                    <?= $return['status'] === 'approved' 
                        ? ucfirst($return['item_state']) . " (Approved)" 
                        : "Pending"; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


</body>
</html>
