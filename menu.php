<?php
if ($datamember['mem_role'] >= 5) {
  foreach ($menu as $menuadmin) {
    if (isset($menuadmin['label'])) {
      echo '
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          '.$menuadmin['label'].'
        </a>
        <ul class="dropdown-menu">';
        foreach ($menuadmin['submenu'] as $key => $value) {          
          echo '<li><a class="dropdown-item" href="'.$weburl.'dashboard/'.$key.'">'.$value[0].'</a></li>';
        }
      echo '
        </ul>
      </li>
      ';
    }
  }
} else {
  $menumember = $menu['membermenu']['submenu'];

  if (isset($settings['klienoff']) && $settings['klienoff'] == 1) {
    unset($menumember['klien']);
  }
  if (isset($settings['networkoff']) && $settings['networkoff'] == 1) {
    unset($menumember['jaringan']);
  }
  
  foreach ($menumember as $key => $value) {
    echo '
    <li class="nav-item">
      <a class="nav-link" href="'.$weburl.'dashboard/'.$key.'">'.$value[0].'</a>
    </li>
    ';
  }
}