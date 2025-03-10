<?php
use App\Config\Database;
require_once __DIR__ . "/../authusers/auth.php";
// Database connection
$db = new Database();
$conn = $db->connect();

// Fetch auth users with department, position, office, and location
// Fetch Departments
$deptQuery = "SELECT id, name FROM departments";
$stmt = $conn->prepare($deptQuery);
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

// Fetch Positions
$posQuery = "SELECT id, name FROM positions";
$stmt = $conn->prepare($posQuery);
$stmt->execute();
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

// Fetch Offices
$officeQuery = "SELECT id, name FROM offices";
$stmt = $conn->prepare($officeQuery);
$stmt->execute();
$offices = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

// Handle AJAX request to fetch locations by office_id
if (isset($_GET['office_id'])) {
    $office_id = intval($_GET['office_id']);
    $query = "
        SELECT l.id, l.name 
        FROM locations l
        JOIN offices o ON l.id = o.location_id
        WHERE o.id = :office_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':office_id', $office_id, PDO::PARAM_INT);
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll to handle multiple records
    echo json_encode($locations); // Send JSON response
    exit;
}


// Fetch authorized users with their related data
$authUsersQuery = "
    SELECT 
        au.id, au.name, au.email, au.role,
        d.name AS department_name,
        p.name AS position_name,
        o.name AS office_name,
        l.name AS location_name
    FROM auth_users au
    LEFT JOIN departments d ON au.department_id = d.id
    LEFT JOIN positions p ON au.position_id = p.id
    LEFT JOIN offices o ON au.office_id = o.id
    LEFT JOIN locations l ON au.location_id = l.id
";

$stmt = $conn->prepare($authUsersQuery);
$stmt->execute();
$authUsers = $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];

// Add new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $role = $_POST['role'] ?? null;
    $department_id = $_POST['department_id'] ?? null;
    $position_id = $_POST['position_id'] ?? null;
    $office_id = $_POST['office_id'] ?? null;

    if ($name && $email && $password && $role && $department_id && $position_id && $office_id) {
        // Auto-fetch location_id based on office_id
        $locationQuery = "SELECT location_id FROM offices WHERE id = :office_id";
        $stmt = $conn->prepare($locationQuery);
        $stmt->bindParam(':office_id', $office_id, PDO::PARAM_INT);
        $stmt->execute();
        $location = $stmt->fetch(PDO::FETCH_ASSOC);
        $location_id = $location['location_id'] ?? null;

        $insertQuery = "
            INSERT INTO auth_users (name, email, password, role, department_id, position_id, office_id, location_id) 
            VALUES (:name, :email, :password, :role, :department_id, :position_id, :office_id, :location_id)
        ";

        $stmt = $conn->prepare($insertQuery);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':department_id', $department_id);
        $stmt->bindParam(':position_id', $position_id);
        $stmt->bindParam(':office_id', $office_id);
        $stmt->bindParam(':location_id', $location_id);

        if ($stmt->execute()) {
            header('Location: auth_users?success=User added successfully');
            exit();
        } else {
            echo "Error adding user.";
        }
    } else {
        echo "Please fill out all required fields.";
    }
}

// Edit user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $department_id = $_POST['department_id'];
    $position_id = $_POST['position_id'];
    $office_id = $_POST['office_id'];
    $location_id = $_POST['location_id'];

    $updateQuery = "
        UPDATE auth_users 
        SET name = :name, email = :email, role = :role, department_id = :department_id, 
            position_id = :position_id, office_id = :office_id, location_id = :location_id
        WHERE id = :id
    ";

    $stmt = $conn->prepare($updateQuery);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':department_id', $department_id);
    $stmt->bindParam(':position_id', $position_id);
    $stmt->bindParam(':office_id', $office_id);
    $stmt->bindParam(':location_id', $location_id);

    if ($stmt->execute()) {
        header('Location: auth_users.php?success=User updated successfully');
        exit();
    } else {
        echo "Error updating user.";
    }
}
?>

<?php include(__DIR__ . "/../../../public/includes/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorized Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URL; ?>css/tables.css" rel="stylesheet">
    <style>
        .container { margin-top: 80px; }
        .table-container { width: 70%; }
        .form-container { width: 30%; }
        .card { padding: 15px; }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <!-- User List -->
        <div class="col-md- table-container">
            <h4>Authorized Users</h4>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($authUsers as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td><?= htmlspecialchars($user['department_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['position_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['office_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['location_name'] ?? 'N/A') ?></td>
                            <td>
                                <a href="auth_users?edit=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= URL ?>auth_users/destroy?delete=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Add/Edit Form -->
        <div class="col-md-5 form-container">
            <div class="card">
                <h5 class="text-center"><?= isset($_GET['edit']) ? 'Edit User' : 'Add User' ?></h5>

                <form method="POST" action="<?php echo URL; ?>auth_users">
                    <input type="hidden" name="id" value="<?= $editUser['id'] ?? '' ?>">

                    <!-- Name -->
                    <div class="mb-3">
                        <label>Name:</label>
                        <input type="text" name="name" class="form-control" value="<?= $editUser['name'] ?? '' ?>" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label>Email:</label>
                        <input type="email" name="email" class="form-control" value="<?= $editUser['email'] ?? '' ?>" required>
                    </div>

                    <!-- Password (only for new users) -->
                    <?php if (!isset($_GET['edit'])): ?>
                        <div class="mb-3">
                            <label>Password:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    <?php endif; ?>

                    <!-- Role -->
                    <div class="mb-3">
                        <label>Role:</label>
                        <select name="role" class="form-control" required>
                            <option value="super_admin" <?= isset($editUser['role']) && $editUser['role'] === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                            <option value="IT" <?= isset($editUser['role']) && $editUser['role'] === 'IT' ? 'selected' : '' ?>>IT</option>
                            <option value="QAQC" <?= isset($editUser['role']) && $editUser['role'] === 'QAQC' ? 'selected' : '' ?>>QA/QC</option>
                            <option value="MLE" <?= isset($editUser['role']) && $editUser['role'] === 'MLE' ? 'selected' : '' ?>>MLE</option>
                        </select>
                    </div>

                    <!-- Department -->
                    <div class="mb-3">
                        <label>Department:</label>
                        <select name="department_id" class="form-control" required>
                            <option value="">Select Department</option>
                            <?php if (!empty($departments)): ?>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>" <?= isset($editUser['department_id']) && $editUser['department_id'] == $dept['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dept['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option disabled>No departments available</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Position -->
                    <div class="mb-3">
                        <label>Position:</label>
                        <select name="position_id" class="form-control" required>
                            <option value="">Select Position</option>
                            <?php if (!empty($positions)): ?>
                                <?php foreach ($positions as $pos): ?>
                                    <option value="<?= $pos['id'] ?>" <?= isset($editUser['position_id']) && $editUser['position_id'] == $pos['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pos['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option disabled>No positions available</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Office dropdown -->
                        <label for="office">Office:</label>
                        <select name="office_id" id="office" class="form-control" required>
                            <option value="">Select Office</option>
                            <?php
                            $officeModel = new App\Models\Office();
                            $offices = $officeModel->getAll();
                            foreach ($offices as $office) {
                                echo "<option value='{$office['id']}' data-location='{$office['location_name']}'>{$office['name']}</option>";
                            }
                            ?>
                        </select>

                        <!-- Location input (read-only) -->
                        <label for="location">Location:</label>
                        <input type="text" name="location" id="location" class="form-control" readonly placeholder="Location will auto-populate">


                    <!-- Submit Button -->
                    <button type="submit" name="<?= isset($editUser) ? 'update' : 'add' ?>" class="btn btn-primary w-100">
                        <?= isset($editUser) ? 'Update User' : 'Add User' ?>
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('office').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const location = selectedOption.getAttribute('data-location');
    document.getElementById('location').value = location || '';
});

</script>

</body>
</html>
