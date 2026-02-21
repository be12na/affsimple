<?php
$skema = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$rekues = parse_url($_SERVER["REQUEST_URI"]);
$visiturl = $skema . "://" . $_SERVER['HTTP_HOST'] . $rekues['path'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="<?= $weburl.$favicon;?>" />
  <title><?= $head['pagetitle'] ??= 'Dashboard';?></title>
  
  <link href="<?= $weburl;?>bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="<?= $weburl;?>theme/simple/style.css">
  <link href="<?=$weburl;?>fontawesome/css/fontawesome.min.css" rel="stylesheet" />
  <link href="<?=$weburl;?>fontawesome/css/regular.min.css" rel="stylesheet" />
  <link href="<?=$weburl;?>fontawesome/css/solid.min.css" rel="stylesheet" />
  <link href="<?=$weburl;?>editor/css/froala_style.min.css" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <meta name="description" content="<?= $head['description'] ?? ''; ?>" />
  <meta property="og:title" content="<?= $head['pagetitle'] ?? 'Dashboard';?>"/>
  <meta property="og:description" content="<?= $head['description'] ?? ''; ?>"/>
  <meta property="og:url" content="<?= $visiturl; ?>"/>
  <meta property="og:image" content="<?= $head['thumbnail'] ?? $weburl.$logoweb;?>"/>
  <meta property="og:type" content="website" />

  <?php 
  echo $head['scripthead']??='';
  $container = $head['container'] ??= 'container';
  if (isset($slug[1]) && $slug[1] == 'dashboard') {
    $homeurl = $weburl.'dashboard';
  } else {
    $homeurl = $weburl;
  }
  ?>
</head>

<body>
<!-- Modern Navbar -->
<nav class="navbar sticky-top navbar-expand-md sa-navbar">
  <div class="container-fluid px-3 px-md-4">
    <a class="navbar-brand sa-brand" href="<?= $homeurl;?>">
      <img src="<?= $weburl.$logoweb;?>" alt="Dashboard" height="36">
    </a>
    <button class="navbar-toggler sa-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link<?= (!isset($bcslug)) ? ' active' : '';?>" href="<?=$weburl;?>dashboard">
            <i class="fas fa-home sa-nav-icon"></i> Home
          </a>
        </li>
        <?php 
        if (file_exists('theme/'.$settings['theme'].'/menu.php')) {
          include('theme/'.$settings['theme'].'/menu.php');
        } else {
          include('theme/simple/menu.php');
        } 
        ?>        
      </ul>
      <div class="dropdown d-flex align-items-center">
        <a href="#" class="sa-user-btn d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="<?= isset($datamember['fotoprofil']) ? $weburl.'upload/'.$datamember['fotoprofil'] : $weburl.'img/pp.png';?>" alt="avatar" width="32" height="32" class="rounded-circle sa-avatar">
          <span class="sa-user-name d-none d-md-inline ms-2"><?= $datamember['mem_nama'] ?? '';?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-md-end dropdown-menu-sm-start sa-dropdown">
          <?php if (isset($datamember['mem_id'])) :?>
          <li class="sa-dropdown-header">
            <small class="text-muted">Logged in as</small><br>
            <strong><?= $datamember['mem_nama'] ?? '';?></strong>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item sa-dropdown-item" href="<?= $weburl;?>dashboard/profil"><i class="fas fa-user-pen sa-dd-icon"></i> Profile</a></li>
          <li><a class="dropdown-item sa-dropdown-item" href="<?= $weburl;?>dashboard/orderanda"><i class="fas fa-receipt sa-dd-icon"></i> Order Anda</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item sa-dropdown-item sa-logout" href="<?= $weburl;?>logout"><i class="fas fa-right-from-bracket sa-dd-icon"></i> Logout</a></li>
          <?php else : ?>
            <li><a class="dropdown-item sa-dropdown-item" href="<?= $weburl;?>login"><i class="fas fa-sign-in-alt sa-dd-icon"></i> Login</a></li>
            <li><a class="dropdown-item sa-dropdown-item" href="<?= $weburl;?>register"><i class="fas fa-user-plus sa-dd-icon"></i> Register</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</nav>

<div class="sa-page-wrapper">
<div class="<?=$container;?> sa-content"> 
  <?php 
  if (isset($bcslug)) :  ?>
  <nav aria-label="breadcrumb" class="sa-breadcrumb-nav">
    <ol class="breadcrumb sa-breadcrumb">
      <li class="breadcrumb-item"><a href="<?= $weburl.'dashboard';?>"><i class="fas fa-home"></i> Dashboard</a></li>
      <?php 
      if (isset($_GET['edit'])) {
        if (is_numeric($_GET['edit'])) {
          $act = 'Edit ';
        } else {
          $act = 'Tambah ';
        }
        echo '
        <li class="breadcrumb-item"><a href="'.$weburl.'dashboard/'.$bcslug.'">'.$bcjudul.'</a></li>
        <li class="breadcrumb-item active" aria-current="page">'.$act.$bcjudul.'</li>';
      } elseif (isset($_GET['detil'])) {
        echo '
        <li class="breadcrumb-item"><a href="'.$weburl.'dashboard/'.$bcslug.'">'.$bcjudul.'</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detil '.$bcjudul.'</li>';
      } else {
        echo '<li class="breadcrumb-item active" aria-current="page">'.$bcjudul.'</li>';
      }
      ?>
    </ol>
  </nav>
<?php endif; ?>
<!-- Content Start -->