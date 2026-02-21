<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
$head['pagetitle']='Point';
showheader($head);

$point = db_select("SELECT * FROM `sa_point` WHERE `poin_idsponsor`=".$iduser);
$total = 0;
if (count($point) > 0) {
	echo '
	<div class="table-responsive">
		<table class="table table-hover table-bordered">
			<thead class="table-secondary">
			<tr>
				<th>Tanggal</th>
				<th>Transaksi</th>
				<th class="text-end">Point</th>
				<th class="text-end">Jumlah</th>
			</tr>
			</thead>
			<tbody>';
	foreach ($point as $point) {
		$total = $total + $point['poin_jumlah'];
		echo '
			<tr>
				<td>'.$point['poin_tanggal'].'</td>
				<td>'.$point['poin_keterangan'].'</td>
				<td class="text-end">'.number_format($point['poin_jumlah']).'</td>
				<td class="text-end">'.number_format($total).'</td>
			</tr>';
	}
	echo '
			</tbody>
		</table>
	</div>';

	if ($total > 0) {
		$isiwa = 	'Halo Admin Saya Mau Claim Poin ....'."\n".
							'Nama: '.$datamember['mem_nama']."\n".
							'Jumlah Poin: '.$total;
		$waadmin = db_var("SELECT `mem_whatsapp` FROM `sa_member` WHERE `mem_id`=1");
		echo '<a href="https://wa.me/'.$waadmin.'/?text='.rawurlencode($isiwa).'" class="btn btn-success">Klaim Point</a>';
	}
} else {
	echo 'Anda belum mendapat point';
}

showfooter();