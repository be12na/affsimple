<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
if (isset($slug[3]) && !empty($slug[3])) {
	$produk = db_row("SELECT * FROM `sa_page` WHERE `page_url`='".cek($slug[3])."'");
	if (substr($produk['pro_file'], 0,4) == 'http') {
		header("Location:".$produk['pro_file']);
	} else {
				
		#echo $_SERVER['REMOTE_ADDR'];
		header("Location:".$weburl.'download.php?f='.$produk['pro_file']);
	}
}