<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../authusers/auth.php";

checkRoles(['QAQC', 'IT', 'super_admin']); 
?>
<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>
<?php
use App\Config\Database;


$database = new Database();
$conn = $database->connect();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query to get unassigned, approved functional, and repairable items
$query = "
    SELECT i.id, c.category_name AS category, i.description, 
           i.serial_number, i.tag_number, i.acquisition_date, i.warranty_date
    FROM inventory i
    LEFT JOIN categories c ON i.category_id = c.id
    LEFT JOIN item_assignments ia ON i.id = ia.inventory_id
    LEFT JOIN item_returns ir ON i.id = ir.item_id
    WHERE ia.inventory_id IS NULL
          OR (ir.item_id IS NOT NULL AND ir.item_state = 'functional' AND ir.status = 'approved')
          OR (ir.item_id IS NOT NULL AND ir.item_state = 'damaged' AND ir.repair_status = 'Repairable' AND ir.status = 'approved')
";

if (!empty($search)) {
    $query .= " AND (i.serial_number LIKE :search OR i.tag_number LIKE :search)";
}

$query .= " GROUP BY i.id ORDER BY i.acquisition_date DESC";
$stmt = $conn->prepare($query);

if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
}

$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In Stock Items</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-2">
        <h2>Items In Stock</h2>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['success'])): ?>
            <p class="success-message"><?= htmlspecialchars($_GET['success']); ?></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error-message"><?= htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <!-- Top Bar (Search + Add Button) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form method="GET" class="d-flex">
            <input type="text" name="search" placeholder="Search by Tag or Serial No." 
                class="form-control me-2" value="<?= htmlspecialchars($search ?? ''); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if (!empty($search)): ?>
                <a href="<?php echo URL; ?>assets/instock" class="btn btn-secondary ms-2">Reset</a>
            <?php endif; ?>
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
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['category']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td><?= htmlspecialchars($item['serial_number']) ?></td>
                            <td><?= htmlspecialchars($item['tag_number']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No items in stock found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
