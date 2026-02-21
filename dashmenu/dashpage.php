<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
if ($datamember['mem_role'] < 9) { die(); exit(); }
$head['pagetitle']='Setting Page';
showheader($head);
$method = array('','Redirect','iFrame','Inject');

if (isset($_POST['urlpage']) && $_POST['urlpage'] != '') {
	# Cek apakah page_url sudah dipakai page lain atau belum
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		# Edit Page
		$cek = db_query("UPDATE `sa_page` SET 
			`page_judul` = '".cek($_POST['judulpage'])."',
			`page_diskripsi` = '".cek($_POST['diskripsipage'])."',
			`page_url` = '".cekurlpage($_POST['alamatpage'],$_GET['id'])."',
			`page_iframe` = '".cek($_POST['urlpage'])."'
			WHERE `page_id`=".$_GET['id']);
	} else {
		# Simpan di database
		$cek = db_query("INSERT INTO `sa_page` (`page_judul`,`page_diskripsi`,`page_url`,`page_iframe`,`page_method`) VALUES 
			('".cek($_POST['judulpage'])."','".cek($_POST['diskripsipage'])."','".cekurlpage($_POST['alamatpage'])."','".cek($_POST['urlpage'])."',1)");
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
} elseif (isset($_GET['del']) && is_numeric($_GET['del'])) {
	$cek = db_query("DELETE FROM `sa_page` WHERE `page_id`=".$_GET['del']);
	if ($cek === false) {
		echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
		  <strong>Error!</strong> '.db_error().'
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
	} else {
		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
		  <strong>Ok!</strong> Page telah dihapus.
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
	}
}
?>

<div class="table-responsive">
<table class="table table-hover table-bordered">
	<thead class="table-secondary">
		<tr>
			<th>Judul Page</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody class="table-group-divider">
		<?php 
		$data = db_select("SELECT * FROM `sa_page` WHERE `pro_harga` IS NULL ORDER BY `page_judul`");
		if (count($data) > 0) {			
			foreach ($data as $data) {
				echo '
			<tr>
			<td>
			<a href="#" class="info" data-target="konten'.$data['page_id'].'">'.$data['page_judul'].'</a>
			<div class="konten'.$data['page_id'].' konten mt-2">
				Alamat Page: 
					<a href="'.$weburl.$datamember['mem_kodeaff'].'/'.$data['page_url'].'" target="_blank">
					'.$weburl.$datamember['mem_kodeaff'].'/'.$data['page_url'].'</a><br/>
				URL Sales Page: <a href="'.$data['page_iframe'].'" target="_blank">'.$data['page_iframe'].'</a>
			</div>
			</td>
			<td class="text-end">
				<a href="page?id='.$data['page_id'].'#form"><i class="fa-solid fa-pen-to-square text-success"></i></a>
				&nbsp; 
					<a href="#" data-bs-toggle="modal" data-bs-target="#konfirmasi" data-bs-nama="'.$data['page_judul'].'" 
					data-bs-id="'.$data['page_id'].'"><i class="fa-solid fa-trash-can text-danger"></i></a>
			</td>
			</tr>';
			}  				
		}
		?>
	</tbody>
</table>
</div>
<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	$page = db_row("SELECT * FROM `sa_page` WHERE `page_id`=".$_GET['id']);
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
	    <label class="col-sm-2 col-form-label">URL Sales Page</label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" name="urlpage" value="<?= $page['page_iframe'] ??= 'https://';?>">
	    </div>
	  </div>
	  <!--
	  <div class="mb-3 row">
	    <label class="col-sm-2 col-form-label">Metode</label>
	    <div class="col-sm-10">
	      <select class="form-select" name="metodepage">
	      	<?php
	      	foreach ($method as $key => $value) {
	      		if (!empty($value)) {
	      			echo '<option value="'.$key.'"';
	      			if (isset($page['page_method']) && $page['page_method'] == $key) { echo ' selected'; }
	      			echo '>'.$value.'</option>';
	      		}
	      	}
	      	?>
	      </select>
	    </div>
	  </div>
	  -->	  
	  <input type="submit" class="btn btn-success" name="" value=" SIMPAN ">
	</div>
</div>
</form>

<!-- Modal -->
<div class="modal fade" id="konfirmasi" tabindex="-1" aria-labelledby="konfirmasilabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="konfirmasilabel">JUDUL</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">ISI
      </div>
      <div class="modal-footer">        
        <a href="#" class="btn btn-secondary delbutton">Hapus</a>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>
<?php 
$footer['konfirm'] = "⚠️ Anda akan menghapus page <strong>'+nama+'</strong>";
showfooter($footer);
?>