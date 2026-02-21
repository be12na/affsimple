<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
showheader();
do_action('home_top');
$showdefault = 'YES';

if (isset($settings['homecanvas']) && !empty($settings['homecanvas'])) {
  $homecanvas = json_decode($settings['homecanvas'],TRUE);
  if (is_array($homecanvas) && count($homecanvas) > 0) {
    echo '<div class="row g-3 sa-animate">';
    foreach ($homecanvas as $modul) {      
      if (function_exists('modul_'.$modul['moduleId'])) {
        if (isset($settings[$modul['canvasId']])) {
          $modsettings = json_decode($settings[$modul['canvasId']],TRUE);
          $modsettings = $modsettings['data'];
          if (isset($modsettings['lebar'])) {
            echo '<div class="col-md-'.($modsettings['lebar']*4).'">';
          } else {
            echo '<div class="col-md-4">';
          }
        } else {
          echo '<div class="col-md-4">';
        }
        
        echo call_user_func('modul_'.$modul['moduleId'],$modul['canvasId']);
      } else {
        echo '<div class="col-md-4">
        <div class="card"><div class="card-body"><span class="text-muted">Modul '.$modul['moduleId'].' tidak ada</span></div></div>';
      }
      echo '</div>';
    } 
    echo '</div>';  
    $showdefault = 'NO';
  }
}

if ($showdefault == 'YES') :
  # Tampilkan Home Default
?>

<div class="sa-animate">
  <div class="row g-3 mb-3">
    <div class="col-12">
      <?= modul_informasi(); ?>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-6">
      <?= modul_affiliasi(); ?>
    </div>
    <div class="col-md-6">
      <?= modul_landingpage(); ?>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-6">    
      <?= modul_klienbaru('Klien Baru',5,'[nama] - <a href="https://wa.me/[whatsapp]" target="_blank">[whatsapp]</a> <small>([tgldaftar])</small>'); ?>
    </div>
    <div class="col-md-6">
      <?= modul_akses(); ?>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12">
      <?= modul_grafikvisitor(); ?>
    </div>
  </div>
</div>

<?php 
endif;
do_action('home_bottom');
showfooter(); ?>