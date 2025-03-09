<?php require_once __DIR__ . '/../../config/config.php'; ?>
<?php
require_once __DIR__ . '/../../models/AuthUser.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// Check if role is set in session (after login) or elsewhere
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// OR simply to avoid errors without session handling yet:
$role = $role ?? '';

$authUserModel = new \App\Models\AuthUser();
$message = '';

// Display password reset message if set
if (!empty($_SESSION['reset_message'])) {
    $message = $_SESSION['reset_message'];
    unset($_SESSION['reset_message']); // Clear the message after showing it
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user = $authUserModel->findByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        // Set session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        // Redirect based on role
        switch ($user['role']) {
            case 'super_admin':
                header('Location: user_profiles.php');
                break;
            case 'IT':
                header('Location: view_inventory.php');
                break;
            case 'QAQC':
                header('Location: view_assignments.php');
                break;
            case 'MLE':
                header('Location: returned_items.php');
                break;
            default:
                header('Location: login.php');
                break;
        }
        exit();
    } else {
        $message = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="<?php echo URL; ?>css/login.css" rel="stylesheet">
<style>
  .bt-logout{
    background-color: #20253a;
    color: #fff;
    border-style: none;
    padding: .5rem 1rem ;
    font-size: 16px;
    border-radius: 5px;
    margin-right: 2rem;
    text-decoration:none;
  }

  .dfaicjcsbg2{
    display:flex; 
    align-items:center; 
    justify-content:space-between; 
    gap:2rem;
  }


</style>
</head>
<body>
<?php  if (!empty($invalid_credentials)){echo $invalid_credentials; }?>
  <div class="container">
    <div class="split left">
      <div class="svg-container">
        <img src="<?php echo URL; ?>img/Primary-Logo_Reversed.png" alt="logo" style="width: 50%; height: 20%;">
      </div>
    </div>
    <div class="split right">
        
       <form class="login-form" method="POST" action="<?php echo URL; ?>home/login">
        <div style="display: flex; align-items: center;">
          <h2>Login</h2>
          <a style="margin-left: auto;" href="<?php echo URL; ?>home/forgot_password"><u>Reset Password?</u></a>
        </div>

        <?php if ($message): ?>
            <div style="color: green; font-weight: bold;">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <div class="form-group">
          <label for="email">Email Address:</label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit">Login</button>
      </form>
    </div>
  </div>
</body>

<script src="<?php echo URL;?>/js/login.js"></script>
</html>