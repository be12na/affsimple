<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
if ($datamember['mem_role'] < 5) { die(); exit(); }
$head['pagetitle']='Plugins';
showheader($head);

$pluginFolder = str_replace('dashmenu','',__DIR__) . '/plugin';
$items = scandir($pluginFolder);

if (is_array($items) && count($items) >= 3) {
  $folders = array_filter($items, function ($item) use ($pluginFolder) {
    $indexFile = $pluginFolder . '/' . $item . '/index.php';
    return is_dir($pluginFolder . '/' . $item) && !in_array($item, ['.', '..']) && file_exists($indexFile) && isPluginIndexFile($indexFile);
  });

  if (isset($_GET['aktif']) && !empty($_GET['aktif']) && in_array($_GET['aktif'], $folders)) {
    if (isset($settings['plugin_aktif'])) {
      $plugin_aktif = explode(',', $settings['plugin_aktif']);
      if (!in_array($_GET['aktif'],$plugin_aktif)) {
        array_push($plugin_aktif, $_GET['aktif']);
      } 
    } else {
      $plugin_aktif = array($_GET['aktif']);
    }
    
    $newsetting['plugin_aktif'] = implode(',', $plugin_aktif);
    $settings = updatesettings($newsetting);
    include($pluginFolder.'/'.$_GET['aktif'].'/index.php');

    # Jalankan fungsi aktifasi plugin jika ada
    $functionName = $_GET['aktif'] . '_install';
    if (function_exists($functionName)) {
      $functionName();
    }

    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Ok!</strong> Plugin '.$_GET['aktif'].' telah diaktifkan</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';

  } elseif (isset($_GET['nonaktif']) && !empty($_GET['nonaktif']) && in_array($_GET['nonaktif'], $folders)) {
    if (isset($settings['plugin_aktif'])) {
      $plugin_aktif = explode(',', $settings['plugin_aktif']);
      $plugin_aktif = array_diff($plugin_aktif, array($_GET['nonaktif']));
      $newsetting['plugin_aktif'] = implode(',', $plugin_aktif);
      $settings = updatesettings($newsetting);
      
      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Ok!</strong> Plugin '.$_GET['nonaktif'].' telah dinonaktifkan</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
  }

  // Output tabel daftar plugin
  if (isset($settings['plugin_aktif']) && !empty($settings['plugin_aktif'])) {
    $plugin_aktif = explode(',', $settings['plugin_aktif']);
  } else {
    $plugin_aktif = array();
  }
  echo '
<div class="table-responsive">
  <table class="table table-hover table-bordered">
    <thead class="table-secondary">
      <tr><th>Plugin</th><th>Description</th></tr>
    </thead>
    <tbody>';
  foreach ($folders as $folder) {
      $indexFile = $pluginFolder . '/' . $folder . '/index.php';
      if (file_exists($indexFile)) {
          $fileContent = file_get_contents($indexFile);
          preg_match('/Name\s*:\s*([^\n]+)/', $fileContent, $matches['Name']);
          preg_match('/URI\s*:\s*([^\n]+)/', $fileContent, $matches['URI']);
          preg_match('/Author\s*:\s*([^\n]+)/', $fileContent, $matches['Author']);
          preg_match('/Version\s*:\s*([^\n]+)/', $fileContent, $matches['Version']);
          preg_match('/Description\s*:\s*([^\n]+)/', $fileContent, $matches['Description']);

          echo '<tr>';
          echo '<td><strong>' . trim($matches['Name'][1]) . '</strong><br/>';
          echo '<small>';
          if (in_array($folder,$plugin_aktif)) {
            echo '<a href="plugin?nonaktif='.$folder.'">Non Aktif</a>';
          } else {
            echo '<a href="plugin?aktif='.$folder.'">Aktifkan</a>';
          }
          
          echo ' | 
          <a href="#" data-bs-toggle="modal" data-bs-target="#konfirmasi" data-bs-nama="'.$matches['Name'][1].'" 
          data-bs-id="'.$folder.'">Hapus</a></small>';
          echo '</td>';
          echo '<td>' . trim($matches['Description'][1]??='-'). '<br/><small>';
          if (isset($matches['Version'][1]) && !empty($matches['Version'][1])) {            
            echo 'Version: '.trim($matches['Version'][1]??='');
          }
          if (isset($matches['Author'][1]) && !empty($matches['Author'][1])) {
            echo ' By ' . trim($matches['Author'][1]??='');
          }
          if (isset($matches['URI'][1]) && !empty($matches['URI'][1])) {
            echo ' <a href="'.trim($matches['URI'][1]??='').'">Plugin URI</a>';
          }
          echo '</small></td>';
          echo '</tr>';
      }
    }
    echo '
    </tbody>
  </table>
</div>';
} else {
  echo 'Tidak ada plugin terinstall';
}
showfooter();
?>