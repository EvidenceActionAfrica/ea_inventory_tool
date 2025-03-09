<?php

// Include the Composer autoloader if using Composer
require_once __DIR__ . '/../vendor/autoload.php'; // Adjust the path as necessary

// Import the Database class from the App\Config namespace
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
        JOIN offices o ON l.id = o.location_id
        WHERE o.id = :office_id
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':office_id', $office_id);
    $stmt->execute();
    $location = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($location ? [$location] : []);
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
        <div class="row table-container">
            <h4>Authorized Users</h4>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
            <?php endif; ?>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Location</th>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </div>
</div>
<script>
    document.getElementById('officeSelect').addEventListener('change', function() {
    const officeId = this.value;
    const locationSelect = document.getElementById('locationSelect');

    if (officeId) {
        fetch(`auth_users.php?office_id=${officeId}`)
            .then(response => response.json())
            .then(data => {
                locationSelect.innerHTML = '<option value="">Select Location</option>';
                if (data.length) {
                    const option = document.createElement('option');
                    option.value = data[0].id;
                    option.textContent = data[0].name;
                    option.selected = true;
                    locationSelect.appendChild(option);
                }
            });
    } else {
        locationSelect.innerHTML = '<option value="">Select Location</option>';
    }
});


</script>
</body>
</html>
