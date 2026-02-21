<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
showheader();
$settings = ubahshortcode($settings,$datamember['mem_id']);
?>
<?php if (isset($settings['informasi']) && !empty($settings['informasi']) && $datamember['mem_status'] == 1) :?>
<div class="row">
  <div class="col">
    <div class="card mb-3">
      <div class="card-header">
        Informasi
      </div>
      <div class="card-body fr-view"> 
        <?php echo $settings['informasi'];?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<?php if (isset($settings['infopremium']) && !empty($settings['infopremium']) && $datamember['mem_status'] == 2) :?>
<div class="row">
  <div class="col">
    <div class="card mb-3">
      <div class="card-header">
        Informasi
      </div>
      <div class="card-body fr-view"> 
        <?php echo $settings['infopremium'];?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="row">
  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-header">
        Affiliasi
      </div>
      <div class="card-body"> 
        <div class="row mt-1">
          <div class="col-4">URL Affiliasi</div>
          <div class="col-8"><a href="<?php echo $weburl.$datamember['mem_kodeaff'];?>"><?php echo $weburl.$datamember['mem_kodeaff'];?></a>
            &nbsp;&nbsp;<a onclick="copyToClipboard('<?php echo $weburl.$datamember['mem_kodeaff'];?>')" style="text-decoration:none;cursor: pointer;" 
              title="Copy to Clipboard"><i class="fa-regular fa-copy"></i></a>
          </div>
        </div>
        <?php
        $jmldl = db_select("SELECT `sa_member`.`mem_status` AS `status`,count(*) AS `jmldl` FROM `sa_sponsor` 
          LEFT JOIN `sa_member` ON `sa_sponsor`.`sp_mem_id` = `sa_member`.`mem_id`
          WHERE `sp_sponsor_id`=".$datamember['mem_id']."
          GROUP BY `mem_status`");
        $jumlah = array(0,0,0);
        if (count($jmldl) > 0) {
          foreach ($jmldl as $jmldl) {
            $jumlah[$jmldl['status']] = $jmldl['jmldl'];
          }
        }
        ?>
        <div class="row mt-1">
          <div class="col-4">Free Member</div>
          <div class="col-8"><a href="dashboard/klien?status=1"><?= number_format($jumlah[1]);?></a></div>
        </div>
        <div class="row mt-1">
          <div class="col-4">Premium Member</div>
          <div class="col-8"><a href="dashboard/klien?status=2"><?= number_format($jumlah[2]);?></a></div>
        </div>
        <div class="row mt-1">
          <div class="col-4">Total Member</div>
          <div class="col-8"><a href="dashboard/klien"><?= number_format($jumlah[1] + $jumlah[2]);?></a></div>
        </div>
        <div class="row mt-1">
          <div class="col-4">Sponsor Anda</div>
          <div class="col-8">
            <?php
            $datasponsor = db_row("SELECT * FROM `sa_member` WHERE `mem_id`=".$datamember['sp_sponsor_id']);
            if (isset($datasponsor['mem_nama'])) {
              echo $datasponsor['mem_nama'].'<br/>';
              echo '<a href="https://wa.me/'.formatwa($datasponsor['mem_whatsapp']).'" target="_blank">'.$datasponsor['mem_whatsapp'].'</a><br/>';
              echo $datasponsor['mem_email'];
            } else {
              echo 'Tidak ada data sponsor';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card mb-3">
      <div class="card-header">
        Klien Terbaru
      </div>
      <div class="card-body">
        <?php
        $data = db_select("SELECT * FROM `sa_member` 
        LEFT JOIN `sa_sponsor` ON `sa_sponsor`.`sp_mem_id` = `sa_member`.`mem_id`
        WHERE `sa_sponsor`.`sp_sponsor_id`=".$iduser." 
        ORDER BY `mem_tgldaftar` DESC LIMIT 0,5");

        if (count($data) > 0) {
          echo '<ol>';
          foreach ($data as $data) {
            echo '<li>'.$data['mem_nama'].' <small>('.date('Y-m-d',strtotime($data['mem_tgldaftar'])).')</small></li>';
          }
          echo '</ol>';
        } else {
          echo '<em>Belum ada Member</em>';
        }
        ?>
      </div>
    </div>
  </div>
</div>
    <?php
    $page = db_select("SELECT * FROM `sa_page`");
    if (count($page) > 0) {
      echo '
<div class="row">
  <div class="col">
    <div class="card mb-3">
      <div class="card-header">
        Landing Page
      </div>
      <div class="card-body">
        <ol>
          <li>
            <a href="'.$weburl.$datamember['mem_kodeaff'].'/register" target="_blank" title="Visit Link">Page Registrasi</a>
            &nbsp;&nbsp;<a onclick="copyToClipboard(\''.$weburl.$datamember['mem_kodeaff'].'/register\')" 
            style="text-decoration:none;cursor: pointer;" title="Copy to Clipboard"> 
            <i class="fa-regular fa-copy"></i></a>        
          </li>
      ';
      foreach ($page as $page) {
        echo '<li>
          <a href="'.$weburl.$datamember['mem_kodeaff'].'/'.$page['page_url'].'" target="_blank" title="Visit Link">'.$page['page_judul'].'</a>
          &nbsp;&nbsp;<a onclick="copyToClipboard(\''.$weburl.$datamember['mem_kodeaff'].'/'.$page['page_url'].'\')" 
          style="text-decoration:none;cursor: pointer;" title="Copy to Clipboard"> 
          <i class="fa-regular fa-copy"></i></a>        
        </li>';
      }
      echo '
        </ol>
      </div>
    </div>
  </div>
</div>      
      ';
    }
$stats = db_select("SELECT DATE(`mem_tgldaftar`) as `tanggal`, 
  SUM(CASE WHEN mem_status = 1 THEN 1 ELSE 0 END) as `pendaftar_status_1`, 
  SUM(CASE WHEN mem_status = 2 THEN 1 ELSE 0 END) as `pendaftar_status_2` 
  FROM `sa_member` LEFT JOIN `sa_sponsor` ON `sa_sponsor`.`sp_mem_id` = `sa_member`.`mem_id` 
  WHERE `sa_sponsor`.`sp_sponsor_id` = ".$iduser." GROUP BY `tanggal`");

$visitor = visitor_data($iduser);
if (count($visitor) > 0) {
  $chart = '';  
  foreach ($visitor as $key => $value) {    
    if (strtotime($key) > strtotime('-30 days')) {
      $chart .= "['".date('Y-m-d',strtotime($key))."',".$value."],";
    }
  }
  
} 
?>
<div class="row">
  <div class="col">
    <div class="card mb-3">
      <div class="card-header">
        Pengunjung 30 hari terakhir
      </div>
      <div class="card-body">
        <div id="chart_div" style="margin:30px 0 0 0; width: 100%; height: 300px"></div>
      </div>
    </div>
  </div>
</div>
<?php if($datamember['mem_role'] == 9) { echo '<small class="form-text text-muted">SimpleAff Plus ver '.$settings['ver'].'</small>'; } ?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart); 
  function drawChart() {
    var dataTable = new google.visualization.DataTable();
    dataTable.addColumn('string', 'Tanggal');
    dataTable.addColumn('number', 'Jml Pengunjung');
    // A column for custom tooltip content
    
    dataTable.addRows([
      <?php echo substr($chart, 0,-1);?>
      // Treat first row as data as well.
    ]);     
  var options = {
      series: {
        1: {type: 'line'}
      },      
      vAxis: { title: 'Jml Pengunjung' },
      hAxis: { title: 'Tanggal' },
      legend: {position: 'top'},
      chartArea:{left:70,top:10,width:'100%',height:'70%'}
  };
  var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    chart.draw(dataTable, options);
  }
</script>
<script>
  function copyToClipboard(text) {
    var dummy = document.createElement("textarea");
    document.body.appendChild(dummy);
    dummy.value = text;
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);
    alert("Data copied to clipboard!");
  }
</script>
<?php showfooter(); ?>