
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Evidence Action</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link href="<?php echo URL; ?>css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>
<body>
  <div class="top-nav dfaicjcsbg2" >
    <a href="<?php echo URL; ?>home/index">
      <img src="<?php echo URL; ?>img/ea_logo.png" alt="logo" style="padding: 10px; width: 150px; height: auto;">
    </a>
    <div class="dfaicjcsbg2">
      <!-- <p style="font-size:14px; font-weight:bold;">Welcome.</p> -->
       
      <a href="<?php echo URL; ?>home/logout" class="bt-logout">LOGOUT</a>
    </div>
   
  </div>

   