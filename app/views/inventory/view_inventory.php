<?php
namespace App\Models;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../authusers/auth.php";

checkRoles(['IT', 'super_admin']); 
use App\Controllers\InventoryController;
// Include the header for the page
include(__DIR__ . "/../../../public/includes/header.php"); 

// Ensure $items are set, default to an empty array if not
$items = $items ?? [];

// Handle search query
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory List</title>
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
    <script>
        function updateCategory() {
            var description = document.getElementById("description").value;
            var categoryField = document.getElementById("category");

            var categoryMapping = {
                "Laptop": "Electronics",
                "Printer": "Office Equipment",
                "Chair": "Furniture",
                "Monitor": "Electronics"
            };

            categoryField.value = categoryMapping[description] || "";
        }
    </script>
</head>
<body>

    <h2>Inventory List</h2>

    <!-- Flash Messages -->
    <?php if (isset($_GET['success'])): ?>
        <p class="success-message"><?= htmlspecialchars($_GET['success']); ?></p>
    <?php elseif (isset($_GET['error'])): ?>
        <p class="error-message"><?= htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <!-- Top Bar -->
    <div class="top-bar">
        <form method="GET" action="<?= URL ?>inventory/search" class="search-form">
        <input type="text" name="search" placeholder="Search by Tag, Serial Number, or Description" 
            value="<?= htmlspecialchars($search_query); ?>" required>
        <button type="submit" aria-label="Search for inventory items">Search</button>
        <?php if (!empty($search_query)): ?>
            <a href="<?= URL ?>inventory" class="reset-search">Reset</a>
        <?php endif; ?>
    </form>

        <form action="<?= URL ?>inventory/add" method="GET">
            <button type="submit" class="add-btn">Add New Item</button>
        </form>


    </div>

    <!-- Inventory Table -->
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Description</th>
                <th>Serial Number</th>
                <th>Tag Number</th>
                <th>Acquisition Date</th>
                <th>Acquisition Cost ($)</th>
                <th>Warranty Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['category'] ?? "N/A"); ?></td>
                        <td><?= htmlspecialchars($item['description']); ?></td>
                        <td><?= htmlspecialchars($item['serial_number']); ?></td>
                        <td><?= htmlspecialchars($item['tag_number']); ?></td>
                        <td><?= htmlspecialchars($item['acquisition_date']); ?></td>
                        <td><?= htmlspecialchars($item['acquisition_cost']); ?></td>
                        <td><?= htmlspecialchars($item['warranty_date']); ?></td>
                        <td>
                        <a href="<?= URL ?>inventory/edit?id=<?= htmlspecialchars($item['id']); ?>">Edit</a> |
                            <form action="<?= URL ?>inventory/delete" method="POST" 
                                onsubmit="return confirm('Are you sure you want to delete this item?');" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']); ?>">
                                <button type="submit" name="delete" class="delete-btn">Delete</button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">Oop!! yooh Not Found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
