<?php 
/*
Name : Point Produk
URI : https://cafebisnis.com/produk/simpleaffplus
Author : Lutvi Avandi
Version : 1.0
Description : Plugin untuk menambah fitur point pada produk SimpleAff Plus
*/
function point_install() {
	global $con;
	if (!db_var("show tables like 'sa_point'")) {
    $cek = db_query("CREATE TABLE `sa_point` (
      `poin_id` bigint(20) NOT NULL auto_increment,
      `poin_tanggal` datetime NOT NULL DEFAULT current_timestamp(),
      `poin_idmember` bigint(20) DEFAULT 0,
      `poin_idsponsor` bigint(20) DEFAULT 0,
      `poin_idorder` bigint(20) DEFAULT 0,
      `poin_jumlah` bigint(20) DEFAULT 0,
      `poin_keterangan` text DEFAULT '',
      PRIMARY KEY  (`poin_id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

  }
}

function point_menu($menu) {
  global $settings;
  $menu['manage']['submenu']['orderlist'] = array('Order List','plugin/point/aturorder.php',9);
  $menu['manage']['submenu']['produk'] = array('Produk','plugin/point/aturproduk.php',9);
  $menu['manage']['submenu']['managepoint'] = array('Manage Point','plugin/point/managepoint.php',9);
  $menu['membermenu']['submenu']['point'] = array('Point','plugin/point/memberpoint.php',1);
  return $menu; 
}

add_filter('menu','point_menu');