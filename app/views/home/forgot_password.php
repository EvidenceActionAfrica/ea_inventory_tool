<?php require_once __DIR__ . '/../../config/config.php'; ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../../models/AuthUser.php';

$authUserModel = new \App\Models\AuthUser();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $user = $authUserModel->findByEmail($email);

    if ($user) {
        // Generate random 8-character password
        $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        $authUserModel->updatePassword($email, $newPassword);

        // Store new password in session for the login page
        $_SESSION['reset_message'] = "Your new password is: <strong>$newPassword</strong>";

        // Redirect to login page
        header('Location: login');
        exit();
    } else {
        $message = "Email not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link href="<?php echo URL; ?>css/login.css" rel="stylesheet">
  <!-- <script>
    function validatePasswords() {
        var newPassword1 = document.getElementById('newPassword1').value;
        var newPassword2 = document.getElementById('newPassword2').value;

        if (newPassword1 !== newPassword2) {
            alert("Passwords do not match. Please enter the same password.");
            return false;
        }
        return true;
    }
</script> -->

</head>
<body>
 <?php  if (!empty($invalid_credentials)){echo $invalid_credentials; }?>
 <?php  if (!empty($successful_login)){echo $successful_login; }?>
 <?php  if (!empty($null_result)){echo $null_result;}?>
 <div class="container">
   <div class="split left">
     <div class="svg-container">
       <img src="<?php echo URL; ?>img/Primary-Logo_Reversed.png" alt="logo" style="width: 50%; height: 20%;">
      </div>
    </div>
      <div class="split right">
      <?php if ($message): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>
  </div>
</body>
</html>