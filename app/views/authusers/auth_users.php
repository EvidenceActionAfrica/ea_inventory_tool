<?php
use App\Config\Database;

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
        WHERE l.office_id = :office_id

    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':office_id', $office_id);
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($locations);
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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
    $role = $_POST['role'];
    $department_id = $_POST['department_id'];
    $position_id = $_POST['position_id'];
    $office_id = $_POST['office_id'];
    $location_id = $_POST['location_id'];

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
        header('Location: auth_users.php?success=User added successfully');
        exit();
    } else {
        echo "Error adding user.";
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
        <div class="col-md-8 table-container">
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
                                <a href="auth_users.php?action=edit&id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="auth_users.php?action=delete&id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Add/Edit Form -->
        <div class="col-md-4 form-container">
            <div class="card">
                <h5 class="text-center"><?= isset($_GET['edit']) ? 'Edit User' : 'Add User' ?></h5>

                <form method="POST" action="index.php?url=authUsers/save">
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

                    <!-- Office -->
                        <div class="mb-3">
                            <label>Office:</label>
                            <select name="office_id" id="office">
                                <option value="">Select Office</option>
                                <?php foreach ($offices as $office): ?>
                                    <option value="<?= $office['id'] ?>"><?= htmlspecialchars($office['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Location -->
                        <div class="mb-3">
                            <label>Location:</label>
                             <input type="text" id="location" name="location_id" readonly>
                        </div>

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
        fetch(`auth_users.php?fetch_locations=true&office_id=${this.value}`)
            .then(response => response.json())
            .then(data => {
                const location = document.getElementById('location');
                location.innerHTML = '<option value="">Select Location</option>';
                data.forEach(loc => {
                    location.innerHTML += `<option value="${loc.id}">${loc.name}</option>`;
                });
            });
    });
</script>


</body>
</html>
