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
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="<?= $weburl;?>img/<?= $favicon;?>" />
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Registrasi</title>

  <link href="<?= $weburl;?>bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=$weburl;?>fontawesome/css/fontawesome.min.css" rel="stylesheet" />
  <link href="<?=$weburl;?>fontawesome/css/regular.min.css" rel="stylesheet" />
  <link href="<?=$weburl;?>fontawesome/css/solid.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    *, *::before, *::after { box-sizing: border-box; }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      padding: 2rem 1rem;
    }

    .register-container {
      width: 100%;
      max-width: 480px;
    }

    .register-card {
      background: #fff;
      border-radius: 16px;
      padding: 2.5rem 2rem 2rem;
      box-shadow: 0 4px 24px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.04);
      animation: fadeIn 0.4s ease-out;
    }

    .register-logo {
      display: block;
      margin: 0 auto 0.5rem;
      max-height: 64px;
      width: auto;
      object-fit: contain;
    }

    .register-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #1a1a2e;
      margin-bottom: 0.25rem;
      text-align: center;
    }

    .register-subtitle {
      font-size: 0.875rem;
      color: #6b7280;
      text-align: center;
      margin-bottom: 1.75rem;
    }

    /* Override form_builder horizontal layout to stacked */
    .register-form .mb-3.row {
      flex-direction: column;
      margin-bottom: 1rem !important;
    }

    .register-form .col-sm-4,
    .register-form .col-sm-8,
    .register-form .col-sm-9,
    .register-form .col-sm-3 {
      width: 100%;
      max-width: 100%;
      flex: 0 0 100%;
    }

    .register-form .col-form-label {
      font-size: 0.8125rem;
      font-weight: 600;
      color: #374151;
      padding-bottom: 0.25rem;
      padding-top: 0;
      text-align: left !important;
    }

    .register-form .form-control,
    .register-form .form-select {
      border: 1.5px solid #e5e7eb;
      border-radius: 10px;
      padding: 0.625rem 0.875rem;
      font-size: 0.9375rem;
      transition: border-color 0.2s, box-shadow 0.2s;
      background: #f9fafb;
    }

    .register-form .form-control:focus,
    .register-form .form-select:focus {
      border-color: #6366f1;
      box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
      background: #fff;
      outline: none;
    }

    .register-form .form-control::placeholder {
      color: #9ca3af;
    }

    .register-form .input-group-text {
      border: 1.5px solid #e5e7eb;
      border-radius: 10px 0 0 10px;
      background: #f3f4f6;
      font-size: 0.8rem;
      color: #6b7280;
    }

    .register-form .input-group .form-control {
      border-radius: 0 10px 10px 0;
    }

    .register-form .form-text {
      font-size: 0.75rem;
      color: #9ca3af;
    }

    /* Password toggle */
    .password-wrapper {
      position: relative;
    }
    .password-wrapper input[type="password"],
    .password-wrapper input[type="text"] {
      padding-right: 2.5rem;
    }
    .password-wrapper .toggle-password {
      position: absolute;
      top: 50%;
      right: 0.75rem;
      transform: translateY(-50%);
      cursor: pointer;
      color: #9ca3af;
      transition: color 0.2s;
      z-index: 4;
    }
    .password-wrapper .toggle-password:hover {
      color: #6366f1;
    }

    /* File input */
    .register-form input[type="file"].form-control {
      font-size: 0.8125rem;
      padding: 0.5rem 0.75rem;
    }

    .btn-register {
      width: 100%;
      padding: 0.7rem;
      font-size: 0.9375rem;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      color: #fff;
      cursor: pointer;
      transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
      letter-spacing: 0.01em;
      margin-top: 0.5rem;
    }

    .btn-register:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 16px rgba(99,102,241,0.35);
      opacity: 0.95;
    }

    .btn-register:active {
      transform: translateY(0);
    }

    .btn-register:disabled {
      opacity: 0.65;
      cursor: not-allowed;
      transform: none;
    }

    /* Override recaptcha button */
    .g-recaptcha.btn-register {
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .sponsor-box {
      background: #f9fafb;
      border: 1.5px solid #e5e7eb;
      border-radius: 10px;
      padding: 0.875rem 1rem;
      margin-top: 1rem;
      font-size: 0.8125rem;
      color: #374151;
      text-align: center;
    }

    .register-footer {
      display: flex;
      justify-content: center;
      margin-top: 1.25rem;
      padding-top: 1.25rem;
      border-top: 1px solid #f3f4f6;
    }

    .register-footer a {
      font-size: 0.8125rem;
      font-weight: 500;
      color: #6366f1;
      text-decoration: none;
      transition: color 0.2s;
    }

    .register-footer a:hover {
      color: #4f46e5;
      text-decoration: underline;
    }

    .alert-modern {
      border-radius: 10px;
      padding: 0.75rem 1rem;
      font-size: 0.8125rem;
      margin-bottom: 1.25rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .alert-modern.alert-danger {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #991b1b;
    }

    .alert-modern.alert-success {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      color: #166534;
    }

    .alert-modern i {
      font-size: 1rem;
      flex-shrink: 0;
    }

    .alert-modern.alert-danger i { color: #ef4444; }
    .alert-modern.alert-success i { color: #22c55e; }

    .spinner {
      display: inline-block;
      width: 1rem;
      height: 1rem;
      border: 2px solid rgba(255,255,255,0.3);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
      margin-right: 0.5rem;
      vertical-align: middle;
    }

    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(12px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
      .register-card {
        padding: 2rem 1.5rem 1.5rem;
        border-radius: 12px;
      }
    }
  </style>

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>
    function togglePassword() {
      var passwordInput = document.getElementById("password");
      var toggleBtn = document.getElementById("togglePassword");
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
      } else {
        passwordInput.type = "password";
        toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
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

  <div class="register-container">
    <div class="register-card">
      <img src="<?php echo $weburl;?>img/<?= $logoweb;?>" alt="Logo" class="register-logo" />
      <h1 class="register-title">Buat Akun Baru</h1>
      <p class="register-subtitle">Daftarkan diri Anda untuk memulai</p>

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
							echo '<div class="alert-modern alert-danger">
							  <i class="fas fa-circle-exclamation"></i>
							  <span>Verifikasi reCAPTCHA gagal</span>
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
								echo '<div class="alert-modern alert-danger">
									  <i class="fas fa-circle-exclamation"></i>
									  <span>'.db_error().'</span>
									</div>';
							}

							if (isset($cek)) {
								if ($cek === false) {
									echo '<div class="alert-modern alert-danger">
									  <i class="fas fa-circle-exclamation"></i>
									  <span>'.db_error().'</span>
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
										echo '<div class="alert-modern alert-success">
										  <i class="fas fa-circle-check"></i>
										  <span>Pendaftaran berhasil. Silahkan <a href="login" style="color:#166534;font-weight:600">login ke dashboard</a></span>
										</div>';
									}
								}
							}							
						} else {
							echo '<div class="alert-modern alert-danger">
									  <i class="fas fa-circle-exclamation"></i>
									  <span>'.$error.'</span>
									</div>';
						}
					} #form ok
				}
				?>

      <form action="" method="post" id="registrasi" enctype="multipart/form-data" class="register-form">
        <?php 
        echo form_builder('register'); 

        if (isset($settings['recap_site']) && !empty($settings['recap_site'])) {
          echo '<button class="g-recaptcha btn-register" data-sitekey="'.$settings['recap_site'].'" id="formsubmit" data-callback="onSubmit" data-action="submit"><i class="fas fa-user-plus" style="margin-right:0.5rem"></i> REGISTRASI</button>';
        } else {
          echo '<button type="submit" class="btn-register" id="formsubmit"><i class="fas fa-user-plus" style="margin-right:0.5rem"></i> REGISTRASI</button>';
        }
        ?>

        <div class="sponsor-box">
          <?php 
          if (isset($datasponsor['nama'])) {
            if (isset($settings['boxsponsor']) && !empty($settings['boxsponsor'])) {
              $isibox = $settings['boxsponsor'];
              foreach ($datasponsor as $key => $value) {
                $isibox = str_replace('['.$key.']', ($value??=''), $isibox);
              }
              echo $isibox;
            } else {
              echo '<i class="fas fa-user-shield" style="margin-right:0.375rem;color:#6366f1"></i> Sponsor: <strong>'.$datasponsor['nama'].'</strong>';
            }
          }
          ?>
        </div>
      </form>

      <div class="register-footer">
        <a href="login"><i class="fas fa-sign-in-alt"></i> Sudah punya akun? Login</a>
      </div>
    </div>
  </div>

  <script src="<?= $weburl;?>bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('registrasi').addEventListener('submit', function() {
      var btn = document.getElementById('formsubmit');
      if (btn.tagName === 'BUTTON' && !btn.classList.contains('g-recaptcha')) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Mohon tunggu...';
      }
    });
  </script>
</body>
</html>