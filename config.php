<?php
$weburl = "https://member.cepat.digital/";
$dbhost     = "localhost";
$dbname     = "adsnetid_adima";
$dbuser     = "adsnetid_adima";
$dbpassword = "Bs-]c6M@&wG;"; # Jangan gunakan karakter $
define('SECRET', "Bs-]c6M@&wG;Bs-]c6M@&wG;");
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'off') { 
	header("Location:".$weburl);
}
?>