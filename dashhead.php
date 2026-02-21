<!DOCTYPE html>
<html class="full" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="<?= $weburl;?>img/<?= $favicon;?>" />
  <title><?php echo $head['pagetitle'] ??= 'Dashboard';?></title>  
  <!-- Bootstrap Core CSS -->
  <link href="<?= $weburl;?>bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?php echo $weburl;?>style.css">
  <link href="<?=$weburl;?>fontawesome/css/fontawesome.min.css" rel="stylesheet" />
  <link href="<?=$weburl;?>fontawesome/css/regular.min.css" rel="stylesheet" />
  <link href="<?=$weburl;?>fontawesome/css/solid.min.css" rel="stylesheet" />
  <link href="<?=$weburl;?>editor/css/froala_style.min.css" rel="stylesheet" type="text/css" />
  <?php 
  echo $head['scripthead']??='';
  $container = $mycontainer ??= 'container';
  ?>
</head>

<body>
  <nav class="navbar sticky-top navbar-expand-md bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><img src="<?php echo $weburl;?>img/<?= $logoweb;?>" alt="Dashboard" height="45"></a>
      <button class="navbar-toggler"  type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="<?= $weburl;?>dashboard">Home</a>
          </li>
          <?php          
          include('menu.php');          
          ?>         
          <li class="nav-item">
            <a class="nav-link" href="<?= $weburl;?>dashboard/profil">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $weburl.($settings['url_artikel']??='artikel');?>"><?= ucwords($settings['url_artikel']??='artikel');?></a>
          </li>          
          <li class="nav-item">
            <a class="nav-link" href="<?= $weburl;?>logout">Logout</a>
          </li>          
        </ul>
        <div class="d-flex">          
          Hi, <?= $datamember['mem_nama'];?>
        </div>
      </div>
    </div>
  </nav>
<div class="<?=$container;?> p-md-5 p-2"> 
<!-- Content Start -->