<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
if ($datamember['mem_role'] < 9) { die(); exit(); }
$head['pagetitle']='Setting Umum';
$head['scripthead'] = '
<link href="'.$weburl.'editor/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<link href="'.$weburl.'editor/css/froala_style.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
a[id="fr-logo"] {
  height:1px !important;
}
p[data-f-id="pbf"] {
  height:1px !important;
}
a[href*="www.froala.com"] {
  height:1px !important;
  background: #fff !important
}
</style>';
showheader($head);

if (isset($_POST['judulweb']) && !empty($_POST['judulweb'])) {
	$newsettings = $_POST;
	$newsettings = str_replace('<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>','',$newsettings);
	if (isset($_FILES) && count($_FILES) > 0) {
		$max_size = 1024000;
		$whitelist_ext = array('jpeg','jpg','png','gif');
		$whitelist_type = array('image/jpeg', 'image/jpg', 'image/png','image/gif');
		$pic_dir = str_replace('dashmenu/dashsetting.php','img',__FILE__);
		
		if( ! file_exists( $pic_dir ) ) { mkdir( $pic_dir ); }

		foreach($_FILES as $field => $files) {
			if (isset($files['name']) && !empty($files['name'])) {
				$filename = $field;
				$target_file = $pic_dir.'/'.$filename;
		    $uploadOk = 1;
		    $imageFileType = strtolower(pathinfo($files["name"],PATHINFO_EXTENSION));
		    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
		      $txterror = "Maaf, hanya support JPG, JPEG, PNG & GIF saja.";
		      $uploadOk = 0;
		    }
		    //Check that the file is of the right type
				if (!in_array($files["type"], $whitelist_type)) {
				  $txterror = "Maaf, hanya support JPG, JPEG, PNG & GIF saja.";
				  $uploadOk = 0;
				}
				// Check file size
		    if ($files["size"] > $max_size) {
		      $txterror = 'Maaf, gambar terlalu besar. Max. 1Mb';
		      $uploadOk = 0;
		    }
		    if ($uploadOk == 1) {
	        $file = $files["tmp_name"];
	        $target_file = $target_file.'.'.$imageFileType;
	        $img = new Imagick();
	        $img->readImage($file);
	        $width = $img->getImageWidth();
	        if ($width > 800) {
	            $width = 800;
	        }
	        $img->setimagebackgroundcolor('white');
	        //$img = $img->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
	        $img->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
	        $img->setImageCompression(Imagick::COMPRESSION_JPEG);
	        $img->setImageCompressionQuality(80);
	        $img->resizeImage($width,800,Imagick::FILTER_CATROM,1,TRUE);
	        $img->stripImage();
	        $img->writeImage($target_file);
	        #$gambar = $target_file.'.'.$imageFileType;
	        $newsettings[$field] = $filename.'.'.$imageFileType;	        
	    	}
    	}
    }
	}
	if (isset($_POST['khususpremium']) && $_POST['khususpremium'] == 1) {
		$newsettings['khususpremium'] = 1;
	} else {
		$newsettings['khususpremium'] = 0;
	}

	if (isset($_POST['wajibaff']) && $_POST['wajibaff'] == 1) {
		$newsettings['wajibaff'] = 1;
	} else {
		$newsettings['wajibaff'] = 0;
	}

	if (isset($_POST['klienoff']) && $_POST['klienoff'] == 1) {
		$newsettings['klienoff'] = 1;
	} else {
		$newsettings['klienoff'] = 0;
	}

	if (isset($_POST['networkoff']) && $_POST['networkoff'] == 1) {
		$newsettings['networkoff'] = 1;
	} else {
		$newsettings['networkoff'] = 0;
	}
	
	$settings = updatesettings($newsettings);
	if ($settings === false) {
		echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
		  <strong>Error!</strong> '.db_error().'
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
	} else {
		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
		  <strong>Ok!</strong> Setting telah disimpan.
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
	}
}
?>
<form action="" method="post" enctype="multipart/form-data">
<div class="card">
  <div class="card-header">
      Setting Umum
  </div>
  <div class="card-body">	    
	  <div class="table-responsive">
		<table class="table table-hover table-bordered">
			<tbody>
				<tr><td>
				  <a class="info" data-target="setting1">System Affiliasi</a>
					<div class="setting1 konten mt-2">
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">ID Sponsor Default</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" name="iddefault" placeholder="ID Sponsor Default. contoh: 1,2,3" value="<?= $settings['iddefault'] ??= '';?>">
					      <small class="form-text text-muted">ID Sponsor yang akan diacak jika berkunjung tanpa link affiliasi. Pisahkan dengan tanda koma. Contoh: 1,2,3</small>
					      <div class="form-check mt-2">
						      <input type="checkbox" class="form-check-input" name="khususpremium" value="1" 
						      <?php if (isset($settings['khususpremium']) && $settings['khususpremium'] == 1) { echo 'checked'; } ?>>
						      <label class="form-check-label" for="flexCheckChecked">
								    Affiliasi Khusus Premium
								  </label>
						    </div>
						    <div class="form-check">
						      <input type="checkbox" class="form-check-input" name="wajibaff" value="1" 
						      <?php if (isset($settings['wajibaff']) && $settings['wajibaff'] == 1) { echo 'checked'; } ?>>
						      <label class="form-check-label" for="flexCheckChecked">
								    Munculkan form input link affiliasi Sponsor jika berkunjung tanpa link affiliasi
								  </label>
						    </div>
						    <div class="form-check">
						      <input type="checkbox" class="form-check-input" name="klienoff" value="1" 
						      <?php if (isset($settings['klienoff']) && $settings['klienoff'] == 1) { echo 'checked'; } ?>>
						      <label class="form-check-label" for="flexCheckChecked">
								    Nonaktifkan menu Klien
								  </label>
						    </div>
						    <div class="form-check">
						      <input type="checkbox" class="form-check-input" name="networkoff" value="1" 
						      <?php if (isset($settings['networkoff']) && $settings['networkoff'] == 1) { echo 'checked'; } ?>>
						      <label class="form-check-label" for="flexCheckChecked">
								    Nonaktifkan menu Jaringan
								  </label>
						    </div>		    	    
					    </div>
					  </div>
					</div>
				</td></tr>
				<tr><td>
				  <a class="info" data-target="setting2">Settings Web</a>
					<div class="setting2 konten mt-2">
						<div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">Judul Web</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="judul" name="judulweb" value="<?= $settings['judulweb'] ??= '';?>">
					    </div>
					  </div>
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">Diskripsi Web</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" name="diskripsiweb" value="<?= $settings['diskripsiweb'] ??= '';?>">
					    </div>
					  </div>
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">URL Landing Page</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" name="homepage" value="<?= $settings['homepage'] ??= '';?>">
					      <small class="form-text text-muted">Alamat landing page yang akan muncul di depan. Contoh: https://cafebisnis.com</small>
					    </div>
					  </div>
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">URL Registrasi Sukses</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" name="reg_sukses" value="<?= $settings['reg_sukses'] ??= '';?>">
					      <small class="form-text text-muted">Alamat landing page yang akan muncul setelah pengunjung registrasi. Contoh: https://cafebisnis/sukses/</small>
					    </div>
					  </div>
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">URL Artikel</label>
					    <div class="col-sm-10">
					      <div class="input-group">
		              <span class="input-group-text" id="basic-addon3"><?= $weburl;?></span>
		              <input type="text" class="form-control" name="url_artikel" value="<?= $settings['url_artikel'] ??= 'artikel';?>">		              
		            </div>
					      <small class="form-text text-muted">Alamat URL untuk mengakses artikel</small>
					    </div>
					  </div>
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">URL Materi</label>
					    <div class="col-sm-10">
					      <div class="input-group">
		              <span class="input-group-text" id="basic-addon3"><?= $weburl;?></span>
		              <input type="text" class="form-control" name="url_materi" value="<?= $settings['url_materi'] ??= 'materi';?>">		              
		            </div>
					      <small class="form-text text-muted">Alamat URL untuk mengakses materi</small>
					    </div>
					  </div>
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">Logo Web</label>
					    <div class="col-sm-10">
					      <input type="file" class="form-control" name="logoweb" >
					      <small class="form-text text-muted">Logo yang akan digunakan di header dan halaman login dan registrasi</small>
					      <div class="mt-2" id="previewlogoweb">
				          <?php 
				          if (isset($settings['logoweb']) && $settings['logoweb'] != '') {
				            echo '<img src="'.$weburl.'img/'.$settings['logoweb'].'?id='.rand(100,999).'" class="img-fluid img-thumbnail" style="max-width: 100px">';
				          }
				          ?>
				        </div>
					    </div>
					  </div>
					  
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">Favicon</label>
					    <div class="col-sm-10">
					      <input type="file" class="form-control" name="favicon">
					      <small class="form-text text-muted">Logo yang akan dipasang di address bar</small>
					      <div class="mt-2" id="previewfavicon">
				          <?php 
				          if (isset($settings['favicon']) && $settings['favicon'] != '') {
				            echo '<img src="'.$weburl.'img/'.$settings['favicon'].'?id='.rand(100,999).'" class="img-fluid img-thumbnail" style="max-width: 100px">';
				          }
				          ?>
				        </div>
					    </div>
					  </div>
					</div>

				</td></tr>
				<tr><td>
				  <a class="info" data-target="setting3">re-Captcha</a>
					<div class="setting3 konten mt-2">
						<div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">reCAPTCHA Site Key </label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" name="recap_site" value="<?= $settings['recap_site'] ??= '';?>">
					      <small class="form-text text-muted">Dapatkan di <a href="https://www.google.com/recaptcha/" target="_blank">di sini</a>. Gunakan reCAPTCHA v3</small>
					    </div>
					  </div>
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">reCAPTCHA Secret Key</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" name="recap_secret" value="<?= $settings['recap_secret'] ??= '';?>">
					      <small class="form-text text-muted">Dapatkan di <a href="https://www.google.com/recaptcha/" target="_blank">di sini</a>. Gunakan reCAPTCHA v3</small>
					    </div>
					  </div>
					</div>
				</td></tr>
				<tr><td>
				  <a class="info" data-target="setting4">Home Premium Member</a>
					<div class="setting4 konten mt-2">
						<textarea class="form-control editor" id="editor" rows="5" data-judul="infopremium"  name="infopremium"><?= htmlspecialchars($settings['infopremium'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
	      		<small class="form-text text-muted">Informasi yang ditampilkan di halaman <a href="<?= $weburl;?>dashboard">home dashboard premium member</a></small>
					</div>
				</td></tr>
				<tr><td>
				  <a class="info" data-target="setting5">Home Free Member</a>
					<div class="setting5 konten mt-2">
			      <textarea class="form-control editor" rows="5" id="editor" data-judul="informasi" name="informasi"><?= htmlspecialchars($settings['informasi'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
			      <small class="form-text text-muted">Informasi yang ditampilkan di halaman <a href="<?= $weburl;?>dashboard">home dashboard free member</a></small>
					</div>
				</td></tr>
				<tr><td>
				  <a class="info" data-target="setting6">Box Data Sponsor</a>
					<div class="setting6 konten mt-2">
						<div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">Isi Box Sponsor</label>
					    <div class="col-sm-10">
					      <textarea class="form-control" id="editor" data-judul="boxsponsor" name="boxsponsor"><?= htmlspecialchars($settings['boxsponsor'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
					      <small class="form-text text-muted">Box yang akan muncul di halaman depan.<br/>
					      	Gunakan shortcode <code>[field]</code> untuk menampilkan data sponsor. <br/>
					      	Contoh: <code>Sponsor anda: [nama]</code><br/>
					      	Kosongi jika tidak ingin menampilkan 
					      </small>
					    </div>
					  </div>					    
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">Box Sponsor</label>
					    <div class="col">
					      <div class="input-group">
				          <span class="input-group-text" id="basic-addon3">Back</span>
				          <input type="color" class="form-color form-control-color" value="<?= $settings['bgsponsor'] ??= '#000000';?>" name="bgsponsor" >
				        </div> 
					    </div>
					    <div class="col">
					      <div class="input-group">
				          <span class="input-group-text" id="basic-addon3">Text</span>
				          <input type="color" class="form-color form-control-color" value="<?= $settings['txtsponsor'] ??= '#ffffff';?>" name="txtsponsor" >
				        </div> 
					    </div>	    
					  </div>
					</div>
				</td></tr>
				<tr><td>
				  <a class="info" data-target="setting7">Social Proof</a>
					<div class="setting7 konten mt-2">
						<div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">Isi Box Social Proof</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" name="boxsocialproof" value="<?= $settings['boxsocialproof'] ??= '';?>">
					      <small class="form-text text-muted">Box yang akan memunculkan animasi member yang baru masuk di halaman depan.<br/>
					      	Gunakan shortcode <code>[field]</code> untuk menampilkan data member. <br/>
					      	Contoh: <code>[nama] telah bergabung</code><br/>
					      	Kosongi jika tidak ingin menampilkan social proof
					      </small>
					    </div>
					  </div>
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">Tampilkan</label>
					    <div class="col-sm-3">
					    	<select name="socialstatus" class="form-select">
					    		<option value="1">Semua Member</option>
					    		<option value="2">Hanya Premium Member</option>
					    	</select>
					    </div>
					  </div>
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">Jarak dari Bawah</label>
					    <div class="col-sm-3">
					    	<input type="number" name="jarakbwh" class="form-control" value="<?= $settings['jarakbwh'] ??= '80';?>" />
					    </div>
					  </div>
					  <div class="mb-3 row">
					    <label class="col-sm-2 col-form-label">Warna Box Social Proof</label>    
				      <div class="col">
					      <div class="input-group">
				          <span class="input-group-text" id="basic-addon3">Back</span>
				          <input type="color" class="form-color form-control-color" value="<?= $settings['bgsocialproof'] ??= '#000000';?>" name="bgsocialproof" >
				        </div> 
					    </div>
					    <div class="col">
					      <div class="input-group">
				          <span class="input-group-text" id="basic-addon3">Text</span>
				          <input type="color" class="form-color form-control-color" value="<?= $settings['txtsocialproof'] ??= '#ffffff';?>" name="txtsocialproof" >
				        </div> 
						  </div>	    
					  </div>	
					</div>
				</td></tr>
			</tbody>
		</table>
		</div>
	  
	  <input type="submit" class="btn btn-success" name="" value=" SIMPAN ">
  </div>
</div>
</form>


<div class="card mt-3">
  <div class="card-header">
      Daftar Shortcode
  </div>
  <div class="card-body">
  	<?php
    $scmember = $membershort = $sponsorshort = '';
    $form = db_select("SELECT * FROM `sa_form` ORDER BY `ff_sort`");
    if (count($form) > 0) {
    	$default = array('nama','email','whatsapp','kodeaff');
    	foreach ($form as $form) {
    		if (!in_array($form['ff_field'], $default)) {
    			$scmember .= '<code>['.$form['ff_field'].']</code> : '.$form['ff_label'].'<br/>';
    			$membershort .= '<code>[member_'.$form['ff_field'].']</code> : '.$form['ff_label'].'<br/>';
    			$sponsorshort .= '<code>[sponsor_'.$form['ff_field'].']</code> : '.$form['ff_label'].'<br/>';
    		}
    	}
    }
    ?>
  	<div class="row">  		
  		<div class="col"> 
  			<strong>Shortcode Box Sponsor</strong><br/>		
  			<code>[nama]</code> : Nama member<br/>
  			<code>[email]</code> : Email member<br/>
  			<code>[whatsapp]</code> : WhatsApp member<br/>
  			<code>[kodeaff]</code> : URL Affiliasi member<br/>
  			<?php echo $scmember;?>
  		</div>
  		<div class="col">
  			<strong>Shortcode Data Member Box Informasi</strong><br/>
  			<code>[member_nama]</code> : Nama member<br/>
  			<code>[member_email]</code> : Email member<br/>
  			<code>[member_whatsapp]</code> : WhatsApp member<br/>
  			<code>[member_kodeaff]</code> : URL Affiliasi member<br/>
  			<?php echo $membershort;?>
  		</div>
  		<div class="col">  			
  			<strong>Shortcode Data Sponsor Box Informasi</strong><br/>
  			<code>[sponsor_nama]</code> : Nama sponsor<br/>
  			<code>[sponsor_email]</code> : Email sponsor<br/>
  			<code>[sponsor_whatsapp]</code> : WhatsApp sponsor<br/>
  			<code>[sponsor_kodeaff]</code> : URL Affiliasi sponsor<br/>
  			<?php echo $sponsorshort;?>
  		</div>
  	</div>
  </div>
</div>
<?php 
#$scriptfoot = '<script src="https://cdn.ckeditor.com/ckeditor5/38.0.0/classic/ckeditor.js"></script>';
$footer['scriptfoot'] = '
<script type="text/javascript" src="'.$weburl.'editor/js/froala_editor.pkgd.min.js"></script>
<script>
  document.addEventListener(\'DOMContentLoaded\', function () {
    new FroalaEditor(\'#editor\', {
      imageUploadURL: \''.$weburl.'upload_image.php\',
      imageUploadParams: {
        id: \'my_editor\'
      },
      codeViewKeepOriginal: true,
      htmlUntouched: true,
      htmlAllowedTags: [\'.*\'], // Allow all HTML tags
      htmlAllowedAttrs: [\'.*\'], // Allow all attributes
      htmlRemoveTags: [],
      events: {
        \'image.beforeUpload\': function (files) {
          var editor = this;

          // Create a FormData object.
          var formData = new FormData();

          // Append the uploaded image to the form data.
          formData.append(\'file\', files[0]);

          // Get the article title and append it to the form data.
          var namafile = document.querySelector(\'#editor\').getAttribute(\'data-judul\');
          formData.append(\'judul\', namafile);

          // Make the AJAX request.
          fetch(\''.$weburl.'upload_image.php\', {
            method: \'POST\',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.link) {
              // Insert the image into the editor.
              editor.image.insert(data.link, null, null, editor.image.get());
            } else {
              console.error(\'Upload failed:\', data.error);
            }
          })
          .catch(error => {
            console.error(\'Error:\', error);
          });

          // Prevent the default behavior.
          return false;
        }
      }
    });
  });
</script>';
showfooter($footer); ?>