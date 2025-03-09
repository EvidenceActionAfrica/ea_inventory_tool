<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../authusers/auth.php";
checkRoles(['QAQC', 'IT', 'super_admin']); 

use App\Controllers\ItemReturnController;

$controller = new ItemReturnController();
$controller->getLostItems();
?>
<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost Items</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-2">
        <h2>Lost Items</h2>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['success'])): ?>
            <p class="success-message"><?= htmlspecialchars($_GET['success']); ?></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error-message"><?= htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <!-- Search Bar -->
        <div class="top-bar d-flex justify-content-between align-items-center mb-3">
        <form method="get" action="<?= URL; ?>collections/lost">
            <div class="input-group mb-3">
                <input type="text" name="search" placeholder="Search by Tag or Serial No." 
                    class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit" class="btn btn-primary">Search</button>

                <?php if (!empty($_GET['search'])): ?>
                    <a href="<?= URL; ?>collections/lost" class="btn btn-secondary ms-2">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        </div>

        <!-- Inventory Table -->
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Serial Number</th>
                    <th>Tag Number</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lostItems)): ?>
                    <?php foreach ($lostItems as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['category']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td><?= htmlspecialchars($item['serial_number']) ?></td>
                            <td><?= htmlspecialchars($item['tag_number']) ?></td>
                            <td><?= htmlspecialchars($item['return_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No lost items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
