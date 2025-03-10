<?php require_once __DIR__ . '/../../config/config.php'; ?>
<?php include(__DIR__ . "/../../../public/includes/navheader.php"); ?>
<?php
require_once __DIR__ . "/../authusers/auth.php";
$role = $_SESSION['user']['role'] ?? '';
?>

<style>
  @font-face {
    font-family: ArchivoBlack;
    src: url("<?php echo URL; ?>fonts/ArchivoBlack-Regular.ttf");
  }

</style>

<link href="<?php echo URL; ?>css/home.css" rel="stylesheet">
<link href="<?php echo URL; ?>css/styles.css" rel="stylesheet">
<div class="grey-bc">
  <div class="container">

    <?php if($role === 'IT'|| $role === 'super_admin'): ?>
      <div class="card" style="background-color:#05545a;" 
           onClick="to_url('<?php echo URL . 'inventory'; ?>')">
        <b style="color: #fff; margin-left:3rem; font-family: ArchivoBlack; font-size:20px;">INVENTORY</b>
      </div>
    <?php endif; ?>

    <?php if($role === 'IT'|| $role === ' QAQC' || $role === 'super_admin'): ?>
      <div class="card" style="background-color:#20253a;" 
           onClick="to_url('<?php echo URL . 'item-assignments'; ?>')">
        <b style="color: #fff; margin-left:3rem; font-family: ArchivoBlack; font-size:20px;">ASSIGNMENTS</b>
      </div>
    <?php endif; ?>

    <?php if($role === 'IT'|| $role === 'MLE' || $role === 'QAQC'|| $role === 'super_admin'): ?>
      <div class="card" style="background-color:#e600a0;" 
           onClick="to_url('<?php echo URL . 'item-returns'; ?>')">
        <b style="color: #fff; margin-left:3rem; font-family: ArchivoBlack; font-size:20px;">COLLECTIONS</b>
      </div>
    <?php endif; ?>

    <?php if( $role === 'super_admin'): ?>
      <div class="card" style="background-color:#5c5161;" 
           onClick="to_url('<?php echo URL . 'auth_users'; ?>')">
        <b style="color: #fff; margin-left:3rem; font-family: ArchivoBlack; font-size:20px;">USER<br> MANAGEMENT</b>
      </div>
    <?php endif; ?>

  </div>
</div>

<script src="<?php echo URL; ?>js/utils.js"></script>
