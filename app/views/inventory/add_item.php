<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../../app/config/Database.php";
require_once __DIR__ . "/../../../app/models/Category.php";
require_once __DIR__ . "/../authusers/auth.php";

use App\Models\Category;
use App\Models\Inventory;

checkRoles(['IT', 'super_admin']); 

$categoryModel = new Category();
$categories = $categoryModel->getCategories();
?>

<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_GET['error']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_GET['success']); ?>
    </div>
<?php endif; ?>
<?php
// Check if you're editing an item (assuming you have the item id passed in the URL)
$editMode = isset($_GET['id']); // Or check another variable if required

if ($editMode) {
    // Fetch item details from the database
    $itemId = $_GET['id'];
    $inventoryModel = new Inventory();
    $item = $inventoryModel->getItemById($itemId); // You need to implement this method
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Inventory Item</title>
    <link href="<?= htmlspecialchars(URL) ?>css/tables.css" rel="stylesheet">
</head>
<body>

    <div class="form-container">
        <h2>Add New Inventory Item</h2>

        <form action="<?= htmlspecialchars(URL) ?>inventory/<?= $editMode ? 'edit/' . $item['id'] . '/submit' : 'store' ?>" method="POST">
            <div class="form-group">
                <label for="description">Description:</label>
                <select name="category_id" id="description" required onchange="populateCategory()">
                    <option value="">Select Description</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']) ?>" 
                                data-description="<?= htmlspecialchars($category['description']) ?>" 
                                data-category="<?= htmlspecialchars($category['category_name']) ?>">
                            <?= htmlspecialchars($category['description']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" id="description_text" name="description">

            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" name="category_name" id="category" readonly>
            </div>

            <div class="form-group">
                <label for="serial_number">Serial Number:</label>
                <input type="text" id="serial_number" name="serial_number" required>
            </div>

            <div class="form-group">
                <label for="tag_number">Tag Number:</label>
                <input type="text" id="tag_number" name="tag_number" required>
            </div>

            <div class="form-group">
                <label for="acquisition_date">Acquisition Date:</label>
                <input type="date" id="acquisition_date" name="acquisition_date" required onchange="validateDate()">
            </div>

            <div class="form-group">
                <label for="acquisition_cost">Acquisition Cost ($):</label>
                <input type="number" id="acquisition_cost" name="acquisition_cost" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="warranty_date">Warranty Expiration Date:</label>
                <input type="date" id="warranty_date" name="warranty_date" required>
            </div>

            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>

    <script>
        function populateCategory() {
            let descriptionSelect = document.getElementById("description");
            let selectedOption = descriptionSelect.options[descriptionSelect.selectedIndex];
            document.getElementById("description_text").value = selectedOption.getAttribute("data-description");
            document.getElementById("category").value = selectedOption.getAttribute("data-category");
        }

        function validateDate() {
            let acquisitionDate = document.getElementById("acquisition_date").value;
            let today = new Date().toISOString().split("T")[0];

            if (acquisitionDate > today) {
                alert("Acquisition date cannot be in the future!");
                document.getElementById("acquisition_date").value = "";
            }
        }
    </script>

</body>
</html>
