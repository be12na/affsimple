<?php
function sa_inject_meta_pixel_html($html, $pixelId, $pixelToken, $pixelTest) {
	$markerStart = '<meta name="sa-meta-pixel" content="start">';
	$markerEnd = '<meta name="sa-meta-pixel" content="end">';

	$startPos = strpos($html, $markerStart);
	$endPos = strpos($html, $markerEnd);
	if ($startPos !== false && $endPos !== false && $endPos > $startPos) {
		$afterEnd = $endPos + strlen($markerEnd);
		$html = substr($html, 0, $startPos).substr($html, $afterEnd);
	}

	$pixelId = trim($pixelId);
	$pixelToken = trim($pixelToken);
	$pixelTest = trim($pixelTest);

	if ($pixelId === '') {
		return $html;
	}

	$encodedId = addslashes($pixelId);
	$encodedToken = addslashes($pixelToken);
	$encodedTest = addslashes($pixelTest);
	$testQuery = $pixelTest !== '' ? '&test_event_code='.rawurlencode($pixelTest) : '';

	$snippet = $markerStart."\n";
	$snippet .= "<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src='https://connect.facebook.net/en_US/fbevents.js';s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '".$encodedId."');
fbq('track', 'PageView');
</script>
";
	$snippet .= "<noscript><img height=\"1\" width=\"1\" style=\"display:none\" src=\"https://www.facebook.com/tr?id=".$encodedId."&ev=PageView&noscript=1".$testQuery."\"/></noscript>\n";
	$snippet .= "<script>
window.metaPixelToken = '".$encodedToken."';
window.metaPixelTestCode = '".$encodedTest."';
</script>\n";
	$snippet .= $markerEnd;

	$posHead = stripos($html, '</head>');
	if ($posHead !== false) {
		return substr($html, 0, $posHead)."\n".$snippet."\n".substr($html, $posHead);
	}
	$posBody = stripos($html, '</body>');
	if ($posBody !== false) {
		return substr($html, 0, $posBody)."\n".$snippet."\n".substr($html, $posBody);
	}
	return $html."\n".$snippet;
}

if (isset($_POST['metodelp']) && $_POST['metodelp'] != '') {
	# URL page bisa kosong jika metode = Komponen HTML
	$postUrlPage = $_POST['urlpage'] ?? '';
	$postHtmlCode = $_POST['page_html'] ?? '';
	$metaPixelId = $_POST['meta_pixel_id'] ?? '';
	$metaPixelToken = $_POST['meta_pixel_token'] ?? '';
	$metaPixelTest = $_POST['meta_pixel_test'] ?? '';

	if ($_POST['metodelp'] == '4') {
		$postHtmlCode = sa_inject_meta_pixel_html($postHtmlCode, $metaPixelId, $metaPixelToken, $metaPixelTest);
	}

	# Auto-create page_html column if not exists
	if (!db_var("SHOW COLUMNS FROM `sa_page` LIKE 'page_html'")) {
		db_query("ALTER TABLE `sa_page` ADD `page_html` LONGTEXT NULL");
	}

	# Auto-create Meta Pixel columns if not exists
	if (!db_var("SHOW COLUMNS FROM `sa_page` LIKE 'page_meta_pixel_id'")) {
		db_query("ALTER TABLE `sa_page` ADD `page_meta_pixel_id` VARCHAR(191) NULL");
	}
	if (!db_var("SHOW COLUMNS FROM `sa_page` LIKE 'page_meta_pixel_token'")) {
		db_query("ALTER TABLE `sa_page` ADD `page_meta_pixel_token` VARCHAR(191) NULL");
	}
	if (!db_var("SHOW COLUMNS FROM `sa_page` LIKE 'page_meta_pixel_test'")) {
		db_query("ALTER TABLE `sa_page` ADD `page_meta_pixel_test` VARCHAR(191) NULL");
	}

	# Cek apakah page_url sudah dipakai page lain atau belum
	if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
		# Edit Page
		$cek = db_query("UPDATE `sa_page` SET 
			`page_judul` = '".cek($_POST['judulpage'])."',
			`page_diskripsi` = '".cek($_POST['diskripsipage'])."',
			`page_url` = '".cekurlpage($_POST['alamatpage'],$_GET['edit'])."',
			`page_iframe` = '".cek($postUrlPage)."',
			`page_method`= ".cek($_POST['metodelp']).",
			`page_fr` = '".serialize($_POST['fr'])."',
			`page_html` = '".cek($postHtmlCode)."',
			`page_meta_pixel_id` = '".cek($metaPixelId)."',
			`page_meta_pixel_token` = '".cek($metaPixelToken)."',
			`page_meta_pixel_test` = '".cek($metaPixelTest)."'
			WHERE `page_id`=".$_GET['edit']);
	} else {
		# Simpan di database
		$cek = db_query("INSERT INTO `sa_page` (`page_judul`,`page_diskripsi`,`page_url`,`page_iframe`,`page_method`,`page_fr`,`page_html`,`page_meta_pixel_id`,`page_meta_pixel_token`,`page_meta_pixel_test`) VALUES 
			('".cek($_POST['judulpage'])."','".cek($_POST['diskripsipage'])."','".cekurlpage($_POST['alamatpage'])."','".cek($postUrlPage)."',".cek($_POST['metodelp']).",'" .serialize($_POST['fr'])."','".cek($postHtmlCode)."','".cek($metaPixelId)."','".cek($metaPixelToken)."','".cek($metaPixelTest)."')");
	}

	if ($cek === false) {
		echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
		  <strong>Error!</strong> '.db_error().'
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
	} else {
		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
		  <strong>Ok!</strong> Page telah disimpan.
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
	}
}

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
	$page = db_row("SELECT * FROM `sa_page` WHERE `page_id`=".$_GET['edit']);
}
?>

<form action="" method="post">
<a name="form"></a>
<div class="card">
  <div class="card-header">
     Tambah Page
  </div>
  <div class="card-body">
	  <div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">Judul Page</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="judulpage" value="<?= $page['page_judul'] ??= '';?>">
	    </div>
	  </div>
	  <div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">Diskripsi Page</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="diskripsipage" value="<?= $page['page_diskripsi'] ??= '';?>">
	    </div>
	  </div>
	  <div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">Alamat Page</label>
	    <div class="col-sm-10">
	      <div class="input-group">
			    <span class="input-group-text" id="basic-addon3"><?= $weburl.$datamember['mem_kodeaff'];?>/</span>
			    <input type="text" class="form-control" name="alamatpage" value="<?= $page['page_url'] ??= '';?>">
			  </div>
	    </div>
	  </div>
	  
		<div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">Metode</label>
	    <div class="col-sm-5">
	    	<?php
	    	$metode = array(
	    		1 => 'Gunakan iFrame',
	    		4 => 'Komponen HTML'
	    	);

	    	$metode = apply_filter('page_metode_lp',$metode);

	    	echo '<select name="metodelp" id="metodelp" class="form-select" onchange="toggleMetode()">';
	    	foreach ($metode as $key => $value) {
	    		echo '<option value="'.$key.'"';
	    		if (isset($page['page_method']) && $page['page_method'] == $key) {
	    			echo ' selected';
	    		}
	    		echo '>'.$value.'</option>';
	    	}
	    	echo '</select>';
	    	?>					    	
	    </div>
	  </div>

	  <!-- Fields for iFrame method -->
	  <div id="urlFields">
	  <div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">URL Sales Page</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="urlpage" value="<?= $page['page_iframe'] ??= 'https://';?>">
	    </div>
	  </div>
	  </div>

	  <!-- Fields for HTML method -->
	  <div id="htmlFields" style="display:none;">
	  <div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">Meta Pixel ID</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="meta_pixel_id" value="<?= $page['page_meta_pixel_id'] ??= '';?>">
	    </div>
	  </div>
	  <div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">Meta Pixel Token</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="meta_pixel_token" value="<?= $page['page_meta_pixel_token'] ??= '';?>">
	    </div>
	  </div>
	  <div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">Meta Pixel Test Event Code</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="meta_pixel_test" value="<?= $page['page_meta_pixel_test'] ??= '';?>">
	    </div>
	  </div>
	  <div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">Kode HTML</label>
	    <div class="col-sm-10">
	      <textarea name="page_html" id="page_html" class="form-control" rows="16" 
	        style="font-family: 'Courier New', monospace; font-size: 0.85rem; line-height: 1.5; background: #1e293b; color: #e2e8f0; border-radius: 8px; padding: 1rem;"
	        placeholder="Masukkan kode HTML lengkap di sini..."><?= htmlspecialchars($page['page_html'] ?? ''); ?></textarea>
	      <small class="form-text text-muted"><i class="fas fa-info-circle"></i> Masukkan kode HTML lengkap (termasuk &lt;html&gt;, &lt;head&gt;, &lt;body&gt;). Shortcode yang tersedia: <code>[nama]</code>, <code>[email]</code>, <code>[whatsapp]</code>, <code>[kodeaff]</code></small>
	    </div>
	  </div>
	  </div>

	  <input type="submit" class="btn btn-success" name="" value=" SIMPAN ">

	  <script>
	  function toggleMetode() {
	    var val = document.getElementById('metodelp').value;
	    var urlFields = document.getElementById('urlFields');
	    var htmlFields = document.getElementById('htmlFields');
	    if (val == '4') {
	      urlFields.style.display = 'none';
	      htmlFields.style.display = 'block';
	    } else {
	      urlFields.style.display = 'block';
	      htmlFields.style.display = 'none';
	    }
	  }
	  // Run on page load
	  document.addEventListener('DOMContentLoaded', toggleMetode);
	  </script>
	</div>
</div>
</form>
