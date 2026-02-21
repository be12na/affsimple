<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
if (!isset($idsponsor)) {
	if (isset($_COOKIE['idsponsor']) && is_numeric($_COOKIE['idsponsor'])) {
		$idsponsor = $_COOKIE['idsponsor'];
	} else {
		$idsponsor = 1;
	}
}

$datasponsor = db_row("SELECT * FROM `sa_member` WHERE `mem_id`=".$idsponsor);
$datasponsor = extractdata($datasponsor);
?>
<!DOCTYPE html>
<html class="full" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="<?= $weburl;?>img/<?= $favicon;?>" />
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Registrasi</title>

  <!-- Bootstrap Core CSS -->
  <link href="<?= $weburl;?>bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=$weburl;?>fontawesome/css/fontawesome.min.css" rel="stylesheet" />
  <link href="<?=$weburl;?>fontawesome/css/regular.min.css" rel="stylesheet" />
  <link href="<?=$weburl;?>fontawesome/css/solid.min.css" rel="stylesheet" />
  <style type="text/css">
      .password-wrapper {
        position: relative;
      }
      
      .password-wrapper input[type="password"] {
        padding-right: 30px; /* Ruang untuk ikon */
      }
      
      .password-wrapper .toggle-password {
        position: absolute;
        top: 50%;
        right: 5px;
        transform: translateY(-50%);
        cursor: pointer;
      }
  </style>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>
    function togglePassword() {
      var passwordInput = document.getElementById("password");
      var toggleBtn = document.getElementById("togglePassword");

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleBtn.innerHTML = '<i class="fas fa-eye-slash text-secondary"></i>';
      } else {
        passwordInput.type = "password";
        toggleBtn.innerHTML = '<i class="fas fa-eye text-secondary"></i>';
      }
    }
    function onSubmit(token) {
     document.getElementById("registrasi").submit();
   }
  </script>
  <?php if (isset($datasponsor['fbpixel']) && !empty($datasponsor['fbpixel'])): ?>
  	<!-- Meta Pixel Code -->
		<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '<?= $datasponsor['fbpixel']??='';?>');
		fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=396425529796614&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Meta Pixel Code -->
	<?php endif; ?>
	<?php if (isset($datasponsor['gtm']) && !empty($datasponsor['gtm'])): ?>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-<?= $datasponsor['gtm']??='';?>');</script>
		<!-- End Google Tag Manager-->
	<?php endif;?>
</head>

<body>
	<?php if (isset($datasponsor['gtm']) && !empty($datasponsor['gtm'])): ?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-<?= $datasponsor['gtm']??='';?>"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript)-->
	<?php endif;?>
	<div class="container-fluid p-md-5 p-3 text-center">
	  <!-- Content here -->
	  <div class="row mt-3 align-items-center">
	    <div class="col d-none d-md-block align-self-start">	      
	    </div>
	    <div class="col-md-6 col-lg-4 col-sm-12 align-self-center bg-body-tertiary rounded p-3 border">
	      <?php
			  if (isset($_POST['nama']) && !empty($_POST['nama']) && isset($_POST['email']) && validemail($_POST['email'])) {
			  	
				  if (isset($settings['recap_secret']) && !empty($settings['recap_secret'])) {
						$secretKey = $settings['recap_secret'];

						// Data yang dikirimkan oleh formulir
						$recaptchaResponse = $_POST['g-recaptcha-response'];

						// Mendekripsi dan memeriksa respons reCAPTCHA menggunakan cURL
						$ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, [
						    'secret' => $secretKey,
						    'response' => $recaptchaResponse,
						]);

						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$response = curl_exec($ch);
						curl_close($ch);

						// Menguraikan respons JSON
						$result = json_decode($response, true);

						// Memeriksa apakah verifikasi reCAPTCHA berhasil
						if ($result && isset($result['success']) && $result['success']) {
						    // Proses formulir atau lakukan tindakan yang diinginkan di sini
						    $formok = 1;
						} else {
							echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
							  <strong>Error!</strong> Verifikasi reCAPTCHA gagal
							  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>';
						}
					} else {
						$formok = 1;
					}

					if (isset($formok) && $formok == 1) {

					  if (db_exist("SELECT `mem_email` FROM `sa_member` WHERE `mem_email`='".cek($_POST['email'])."'")) {
							$error = 'Email sudah ada yang menggunakan';
						}

						# Cek form yg required

						$req = db_select("SELECT * FROM `sa_form` WHERE `ff_registrasi`=1 AND `ff_required`=1");
						if (count($req) > 0) {
							foreach ($req as $req) {
								if (!isset($_POST[$req['ff_field']]) || empty($_POST[$req['ff_field']])) {
									if ($req['ff_field'] == 'whatsapp') {
										if (formatwa($_POST['whatsapp']) == '') {
											$error = $req['ff_label'].' wajib diisi';
										}
									} else {
										$error = $req['ff_label'].' wajib diisi';
									}
								}
							}
						}

						if (!isset($error)) {
							if (isset($_POST['sponsor']) && !empty($_POST['sponsor'])) {
								$sponsor = db_var("SELECT `mem_id` FROM `sa_member` WHERE `mem_kodeaff`='".txtonly(strtolower($_POST['sponsor']))."'");
								
								if (is_numeric($sponsor)) {
									$idsponsor = $sponsor;
								} 
							}

							$defaultkey = array('nama','email','password','whatsapp','kodeaff');
							$datalain = '';
							

							foreach ($_POST as $key => $value) {
								if (in_array($key, $defaultkey)) {
									${$key} = cek($value);
								} else {
									$datalain .= '['.txtonly(strtolower($key)).'|'.cek($value).']';
								}
							}
							

							if (isset($_FILES) && count($_FILES) > 0) {
								$max_size = 1024000;
								$whitelist_ext = array('jpeg','jpg','png','gif');
								$whitelist_type = array('image/jpeg', 'image/jpg', 'image/png','image/gif');
								$pic_dir = str_replace('saregister.php','upload',__FILE__);
								$memberid = 'XXX'.rand(1000,9999).'XXX';
								
								if( ! file_exists( $pic_dir ) ) { mkdir( $pic_dir ); }

								foreach($_FILES as $field => $files) {
									$filename = $memberid.'_'.$field;
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
						        $datalain .= '['.txtonly(strtolower($field)).'|'.$filename.'.'.$imageFileType.']'; 
						    	}
								}
							}
							
							if (!isset($password) || empty($password)) { $password = randomword(); } else { $password = $_POST['password']; }

							if (!isset($kodeaff)) { $kodeaff = $nama; }
							$kodeaff = cekkodeaff(txtonly(strtolower($kodeaff)));
							if (isset($whatsapp)) { $whatsapp = formatwa($whatsapp); } else { $whatsapp = ''; }
													
							$newuserid = db_insert("INSERT INTO `sa_member` (
								`mem_nama`,`mem_email`,`mem_password`,`mem_whatsapp`,`mem_kodeaff`,
								`mem_datalain`,`mem_tgldaftar`,`mem_status`,`mem_role`) 
							VALUES ('".$nama."','".$email."','".create_hash($password)."',
								'".$whatsapp."','".$kodeaff."','".$datalain."','".date('Y-m-d H:i:s')."',
								1,1)");

							
							if (is_numeric($newuserid)) {
								$network = '['.numonly($idsponsor).']'.db_var("SELECT `sp_network` FROM `sa_sponsor` WHERE `sp_mem_id`=".$idsponsor);
								$cek = db_insert("INSERT INTO `sa_sponsor` (`sp_mem_id`,`sp_sponsor_id`,`sp_network`) VALUES ($newuserid,$idsponsor,'".$network."')");
								echo db_error();
								if (isset($memberid)) {
									$datalain = str_replace($memberid,$newuserid,$datalain);
									db_query("UPDATE `sa_member` SET `mem_datalain`='".$datalain."' WHERE `mem_id`=".$newuserid);
									$files = glob($pic_dir . '/'.$memberid.'*');					
									// Loop semua file yang ditemukan dan ganti nama file
									foreach ($files as $file) {
									    // Buat nama file baru dengan mengganti teks XXX123XXX dengan ID member baru
									    $newName = str_replace($memberid, $newuserid, $file);
									    // Ganti nama file
									    rename($file, $newName);
									}
								}
								# Kirim Notif yuk							
								$customfield['newpass'] = $password;
								sa_notif('daftar',$newuserid,$customfield);

							} else {
								echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
									  <strong>Error!</strong> '.db_error().'
									  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>';
							}

							if (isset($cek)) {
								if ($cek === false) {
									echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
									  <strong>Error!</strong> '.db_error().'
									  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>';
								} else {
									if (isset($settings['reg_sukses']) && !empty($settings['reg_sukses'])) {
										echo '
										<script type="text/javascript">
										<!--
										window.location = "'.$settings['reg_sukses'].'"
										//-->
										</script>';
									} else {
										echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
										  <strong>Ok!</strong> Pendaftaran berhasil. Silahkan <a href="login">login ke dashboard</a>
										  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
										</div>';
									}
								}
							}							
						} else {
							echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
									  <strong>Error!</strong> '.$error.'
									  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>';
						}
					} #form ok
				}
				?>
	      <form action="" method="post" id="registrasi" onsubmit="document.getElementById('formsubmit').disabled=true;
					document.getElementById('formsubmit').value='Tunggu sebentar...';" enctype="multipart/form-data">
		      <div class="mb-3 row">
		      	<div class="text-center"><img src="<?php echo $weburl;?>img/<?= $logoweb;?>" style="max-width: 150px;"/></div>
		      	<h2>Register</h2>
		      </div>
		      <?php 
		      echo form_builder('register'); 

		      if (isset($settings['recap_site']) && !empty($settings['recap_site'])) {
		      	echo '<button class="g-recaptcha btn btn-success" data-sitekey="'.$settings['recap_site'].'" id="formsubmit" data-callback="onSubmit" data-action="submit"> REGISTRASI </button>';
		      } else {
		      	echo '<input type="submit" class="btn btn-success" id="formsubmit" value=" REGISTRASI ">';
		      }
		      ?>
				  <div class="mt-3 pt-3">
				  	<?php 
			  		if (isset($datasponsor['nama'])) {
			  			if (isset($settings['boxsponsor']) && !empty($settings['boxsponsor'])) {
								$isibox = $settings['boxsponsor'];
								foreach ($datasponsor as $key => $value) {
									$isibox = str_replace('['.$key.']', ($value??=''), $isibox);
								}

							echo $isibox;
			  			} else {
			  				echo 'Sponsor: '.$datasponsor['nama'];
			  			}
			  		}
				  	?>
				  </div>
				</form>
				<div class="mt-3 pt-3 border-top row">					
					<div class="col text-end">
						<a href="login">Login</a>
					</div>
				</div>
	    </div>
	    <div class="col d-none d-md-block align-self-end">	      
	    </div>
	  </div>
	</div>
	<script src="<?= $weburl;?>bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>