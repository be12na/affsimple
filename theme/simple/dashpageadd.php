<?php
if (isset($_POST['metodelp']) && $_POST['metodelp'] != '') {
	# URL page bisa kosong jika metode = Komponen HTML
	$postUrlPage = $_POST['urlpage'] ?? '';
	$postHtmlCode = $_POST['page_html'] ?? '';

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
			`page_html` = '".cek($postHtmlCode)."'
			WHERE `page_id`=".$_GET['edit']);
	} else {
		# Simpan di database
		$cek = db_query("INSERT INTO `sa_page` (`page_judul`,`page_diskripsi`,`page_url`,`page_iframe`,`page_method`,`page_fr`,`page_html`) VALUES 
			('".cek($_POST['judulpage'])."','".cek($_POST['diskripsipage'])."','".cekurlpage($_POST['alamatpage'])."','".cek($postUrlPage)."',".cek($_POST['metodelp']).",'" .serialize($_POST['fr'])."','".cek($postHtmlCode)."')");
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
	    		2 => 'Inject URL',
	    		3 => 'Redirect URL',
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

	  <!-- Fields for URL-based methods (iFrame, Inject, Redirect) -->
	  <div id="urlFields">
	  <div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">URL Sales Page</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="urlpage" value="<?= $page['page_iframe'] ??= 'https://';?>">
	    </div>
	  </div>
	  <div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">Find and Replace</label>
	    <div class="col-sm-10">
	    	<?php 
	    	if (isset($page['page_fr']) && !empty($page['page_fr'])) {
	    		$fr = unserialize($page['page_fr']);
	    	}
	    	for ($i=1; $i <= 5; $i++) :?>
	    	<div class="input-group">
	      	<input type="text" class="form-control" placeholder="find" name="fr[<?= $i;?>][find]" value="<?= $fr[$i]['find'] ??= '';?>">
	      	<input type="text" class="form-control" placeholder="replace" name="fr[<?= $i;?>][replace]" value="<?= $fr[$i]['replace'] ??= '';?>">
	      </div>
	    	<?php endfor; ?>
	      <small class="form-text text-muted">Ubah text landing page (hanya berlaku untuk metode Inject URL)</small>
	    </div>
	  </div>
	  </div>

	  <!-- Fields for HTML method -->
	  <div id="htmlFields" style="display:none;">
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