<?php
include('fungsi.php');
$merchantCode = $_POST['merchantCode'] ?? '';
$amount = $_POST['amount'] ?? '';
$merchantOrderId = $_POST['merchantOrderId'] ?? '';
$resultCode = $_POST['resultCode'] ?? '';
$signature = $_POST['signature'] ?? '';
$reference = $_POST['reference'] ?? '';
$merchantKey = getsettings('duitku_api_key');
$calcSignature = md5($merchantCode.$amount.$merchantOrderId.$merchantKey);
if (strtolower($signature) !== strtolower($calcSignature)) {
	echo 'Invalid signature';
	exit;
}
if ($resultCode === '00') {
	$paycode = array();
	$paycode = apply_filter('paycode',$paycode);
	if (count($paycode) > 0) {
		foreach ($paycode as $key => $fileproses) {
			$lencode = strlen($key);
			if (substr($merchantOrderId, 0,$lencode) == $key) {
				$idinvoice = intval(substr($merchantOrderId, $lencode));
				$staff = 0;
				include($fileproses);
				$proses = 1;
				break;
			}
		}
	}
	if (!isset($proses)) {
		$idinvoice = intval($merchantOrderId);
		$staff = 0;
		include('prosesorder.php');
	}
	echo 'OK';
} else {
	echo 'FAILED';
}

