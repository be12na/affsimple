<?php
if (isset($slug[2]) && !empty($slug[2])) :
	if ($iduser = is_login()) :
		if (is_numeric($slug[2])) {
			$single = db_row("SELECT * FROM `sa_artikel` WHERE `art_id` = '".$slug[2]."'");
			if (isset($single['art_id'])) {
				$showtxt = '<h2>'.$single['art_judul'].'</h2>'.$single['art_konten'];
				$produk = db_row("SELECT * FROM `sa_page` WHERE `page_id`='".$single['art_product']."'");
				$showtitle = $single['art_judul'];
			}
		} else {
			$produk = db_row("SELECT * FROM `sa_page` WHERE `page_url`='".$slug[2]."'");
			if (isset($produk['page_judul'])) {
				$showtitle = $produk['page_judul'];
			} else {
				$showtitle = 'Not Found';
			}
		}
		
	
		if (isset($produk['page_id'])) {
			$cekorder = db_row("SELECT * FROM `sa_order` WHERE `order_idproduk`=".$produk['page_id']." AND `order_idmember`=".$iduser." AND `order_status`=1");
			if (isset($cekorder['order_id'])) {
				$artikel = db_select("SELECT * FROM `sa_artikel` LEFT JOIN `sa_kategori` ON `sa_kategori`.`kat_id`=`sa_artikel`.`art_kat_id` 
				WHERE `art_product`=".$produk['page_id']." ORDER BY `art_judul`");
				if (count($artikel) > 0) {
					foreach ($artikel as $artikel) {
						if (!isset($list[$artikel['kat_id']])) { 
							$list[$artikel['kat_id']]['judul'] = $artikel['kat_nama'];
							$list[$artikel['kat_id']]['artikel'] = ''; 
						}
						if (isset($single['art_id']) && $single['art_id'] == $artikel['art_id']) {
							$list[$artikel['kat_id']]['artikel'] .= '<li><strong>'.$artikel['art_judul'].'</strong></li>';
						} else {
							$list[$artikel['kat_id']]['artikel'] .= '<li><a href="'.$weburl.($settings['url_materi']??='materi').'/'.$artikel['art_id'].'">'.$artikel['art_judul'].'</a></li>';
						}
					}
					#$showtxt .= '<h3>Materi '.$produk['page_judul'].'</h3>';
					if (isset($list) && count($list) > 0) {
						$menu = '';
						foreach ($list as $key => $value ) {
							$menu .= '
							<div class="card mb-3">
								<div class="card-header info" style="cursor: pointer;" data-target="konten_'.$key.'">
									<h5>'.$value['judul'].'</h5>
								</div>
								<div class="card-body konten_'.$key.' konten">
									<ol>
										'.$value['artikel'].'
									</ol>
								</div>
							</div>';
							if (!isset($first)) { $first = $key; }
						}

						if (isset($single['art_kat_id'])) { $first = $single['art_kat_id']; }
					}
				} else {
					$menu = '';
					$showtxt = 'Maaf, belum ada artikel khusus produk ini.';
				}
			} else {
				$menu = '
						<div class="card">
							<div class="card-body">';
				if (isset($produk['pro_img']) && !empty($produk['pro_img'])) {
					$menu .= '<img src="'.$weburl.'upload/'.$produk['pro_img'].'" alt="'.$produk['page_judul'].'" class="img-fluid mb-3"/>';
				}
				$menu .= '
						<h2>'.$produk['page_judul'].'</h2>
						<p>'.$produk['page_diskripsi'].'</p>
					</div>
				</div>'; 
				if (is_login()) {
					$action = '<a href="'.$weburl.'order/'.$produk['page_url'].'" class="btn btn-success">Order '.$produk['page_judul'].' dulu</a>';
				} else {
					$action = '<a href="'.$weburl.'login?redirect='.$slugartikel.'/'.$data['art_slug'].'" class="btn btn-success">Silahkan login dulu</a>';
				}

				$showtxt = '<p>Maaf, artikel ini hanya untuk pembeli produk '.$produk['page_judul'].'.</p>
								'.$action;
			}
			
		}	else {
			$menu = '';
			$showtxt = 'Maaf, halaman tidak ditemukan';
		}
?>
<!DOCTYPE html>
<html class="full" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $weburl;?>img/<?= $favicon;?>" />
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $showtitle??='Not Found';?></title>
    
    <link rel="StyleSheet" href="<?= $weburl;?>style.css" type="text/css" />	
		
		<!-- Bootstrap Core CSS -->
    <link href="<?= $weburl;?>bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=$weburl;?>fontawesome/css/fontawesome.min.css" rel="stylesheet" />
	  <link href="<?=$weburl;?>fontawesome/css/regular.min.css" rel="stylesheet" />
	  <link href="<?=$weburl;?>fontawesome/css/solid.min.css" rel="stylesheet" />
	  <link href="<?=$weburl;?>editor/css/froala_style.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <nav class="navbar sticky-top navbar-expand-md bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><img src="<?php echo $weburl;?>img/<?= $logoweb;?>" alt="Dashboard" height="45"></a>
      <button class="navbar-toggler"  type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?= $weburl;?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $weburl.($settings['url_artikel']??='artikel');?>"><?= ucwords($settings['url_artikel']??='artikel');?></a>
          </li>  
          <?php if (is_login()) :?>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?= $weburl;?>dashboard">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?= $weburl;?>logout">Logout</a>
          </li>
          <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?= $weburl;?>login?redirect=<?=$urlslug;?>">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $weburl;?>register">Register</a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
	<div class="container-fluid p-md-5">
		<div class="row mt-2">			
			<div class="col-md-8 col-lg-9 mb-3 order-md-2">
				<div class="card">
					<div class="card-body fr-view" style="overflow: hidden;">
					<?php if (isset($showtxt)) { echo $showtxt; } else { echo $menu; } ?>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-3 order-md-1">				
				<div class="sticky-top">
					<?php 
					if (isset($showtxt)) { 
						if (isset($produk['page_judul'])) {
							echo $menu; 
						}
					} else { 
						echo '
						<div class="card">
							<div class="card-body">';
						if (isset($produk['pro_img']) && !empty($produk['pro_img'])) {
							echo '<img src="'.$weburl.'upload/'.$produk['pro_img'].'" alt="'.$produk['page_judul'].'" class="img-fluid mb-3"/>';
						}
						echo '
								<h2>'.$produk['page_judul'].'</h2>
								<p>'.$produk['page_diskripsi'].'</p>
							</div>
						</div>'; 
					} ?>
				</div>
			</div>
		</div>
	</div>
	<script src="<?= $weburl;?>bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
		  $(".konten").hide(); // sembunyikan semua konten pada awalnya
		  <?php if (isset($first)) { echo '$(".konten_'.$first.'").show();'; } ?>
	    $('.info').click(function() {
	        // Temukan konten terkait dengan data-target yang sesuai
	        var target = $(this).data('target');
	        var konten = $('.' + target);

	        // Toggle (sembunyikan/tampilkan) konten
	        konten.slideToggle();
	    });
		});
	</script>
	<script>
    document.addEventListener("contextmenu", function(e) {
        e.preventDefault();
    });
	</script>
</body>
</html>
<?php 
	else :
		header("Location: ".$weburl."login?redirect=materi/".$slug[2]);
	endif;
else :
	
endif; ?>