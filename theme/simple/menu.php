<?php
# Hapus dulu menu materi
if (isset($settings['url_materi'])) {
  unset($menu[$settings['url_materi']]);
}

// Determine current page slug for active state
$currentSlug = $slug[2] ?? '';

if (isset($datamember['mem_role']) && $datamember['mem_role'] >= 5) {
  foreach ($menu as $keymenu => $menuadmin) {
    if (isset($menuadmin['label'])) {      
      if (isset($menuadmin['submenu'])) { 
        $submenu = '';
        $isGroupActive = false;
        foreach ($menuadmin['submenu'] as $key => $value) {          
          $isActive = ($currentSlug === $key);
          if ($isActive) $isGroupActive = true;
          $activeClass = $isActive ? ' active' : '';
          if (isset($value[2])) {
            if ($datamember['mem_role'] >= $value[2]) {
              $ico = isset($value[3]) ? '<i class="'.$value[3].' sa-dd-icon"></i> ' : '';
              $submenu .= '<li><a class="dropdown-item sa-dropdown-item'.$activeClass.'" href="'.$weburl.'dashboard/'.$key.'">'.$ico.$value[0].'</a></li>';
            }
          } else {
            $ico = isset($value[3]) ? '<i class="'.$value[3].' sa-dd-icon"></i> ' : '';
            $submenu .= '<li><a class="dropdown-item sa-dropdown-item'.$activeClass.'" href="'.$weburl.'dashboard/'.$key.'">'.$ico.$value[0].'</a></li>';
          }
        }
        if ($submenu != '') {
          $groupActive = $isGroupActive ? ' active' : '';
          echo '
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle'.$groupActive.'" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              '.$menuadmin['label'].'
            </a>
            <ul class="dropdown-menu sa-dropdown">'.$submenu.'</ul>
          </li>
          ';
        }
      } else {
        $isActive = (strpos($keymenu, $currentSlug) !== false && $currentSlug !== '') ? ' active' : '';
        echo '
        <li class="nav-item">
          <a class="nav-link'.$isActive.'" href="'.$weburl.$keymenu.'">'.$menuadmin['label'].'</a>
        </li>';
      }
    } 
  }
} else {
  foreach ($menu as $keymenu => $menuadmin) {
    if (isset($menuadmin['label'])) {
      if ($keymenu == 'membermenu') {
        $menumember = $menu['membermenu']['submenu'];

        if (isset($settings['klienoff']) && $settings['klienoff'] == 1) {
          unset($menumember['klien']);
        }
        if (isset($settings['networkoff']) && $settings['networkoff'] == 1) {
          unset($menumember['jaringan']);
        }        
        
        foreach ($menumember as $key => $value) {
          $isActive = ($currentSlug === $key) ? ' active' : '';
          $ico = isset($value[3]) ? '<i class="'.$value[3].' sa-nav-icon"></i> ' : '';
          if (isset($value[2])) {
            if (isset($datamember['mem_role']) && $datamember['mem_role'] >= $value[2]) {
              echo '
              <li class="nav-item">
                <a class="nav-link'.$isActive.'" href="'.$weburl.'dashboard/'.$key.'">'.$ico.$value[0].'</a>
              </li>
              ';
            }
          } else {
            echo '
              <li class="nav-item">
                <a class="nav-link'.$isActive.'" href="'.$weburl.'dashboard/'.$key.'">'.$ico.$value[0].'</a>
              </li>
              ';
          }
        }
      } else {
        if (!isset($menuadmin['submenu'])) {
          $isActive = (strpos($keymenu, $currentSlug) !== false && $currentSlug !== '') ? ' active' : '';
          echo '
            <li class="nav-item">
              <a class="nav-link'.$isActive.'" href="'.$weburl.$keymenu.'">'.$menuadmin['label'].'</a>
            </li>
            ';
        }
      }
    }
  }
}

do_action('nav_menu');