<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
$head['pagetitle']='Laporan Perolehan Komisi';
showheader($head);

if (isset($_GET['detil'])) {
	
	$exp = explode('-',$_GET['detil']);
	if (is_numeric($exp[0]) && is_numeric($exp[1])) {
		$tgl = $exp[0].'-'.$exp[1];
		$select = "SELECT * FROM `sa_laporan`
		LEFT JOIN `sa_member` ON `sa_member`.`mem_id` = `sa_laporan`.`lap_idmember` 
		WHERE `lap_idsponsor`=".$iduser."
		AND `lap_code` =2
		AND MONTH(`lap_tanggal`) = ".$exp[1]." AND YEAR(`lap_tanggal`) = ".$exp[0]."
		ORDER BY `lap_tanggal`";
		$data = db_select($select);
		echo '
		<h4>Laporan '.date('F Y',strtotime($tgl.'-10 10:00:00')).'</h4>
		<div class="table-responsive">
		<table class="table table-hover table-bordered">
			<thead class="table-secondary">
			<tr>
				<th>Tanggal</th>
				<th>Transaksi</th>
				<th>Member</th>
				<th class="text-end">Pemasukan</th>
				<th class="text-end">Pengeluaran</th>
			</tr>
			</thead>
			<tbody>';
		foreach ($data as $data) {
			echo '
			<tr>
				<td>'.date('d-m H:i', strtotime($data['lap_tanggal'])).'</td>
				<td>'.$data['lap_keterangan'].'</td>
				<td><a href="'.$weburl.'dashboard/kliendetil?id='.$data['mem_id'].'" target="_blank">'.$data['mem_nama'].'</a></td>
				<td class="text-end">'.number_format($data['lap_masuk']).'</td>
				<td class="text-end">'.number_format($data['lap_keluar']).'</td>
			</tr>';			
		}
		echo '
			</tbody>
		</table>
		</div>
		';
	}
	
} else {
	$data = db_select("SELECT SUM(`lap_masuk`)-SUM(`lap_keluar`) AS `komisi`,
		DATE_FORMAT( `lap_tanggal`,  '%Y-%m' ) AS `bulan`, `lap_tanggal` FROM  `sa_laporan` 
		WHERE `lap_idsponsor`=".$iduser." AND `lap_code` = 2
		GROUP BY `bulan` ORDER BY `lap_tanggal` DESC");
	echo db_error();
	foreach ($data as $data) {		
		$duit[$data['bulan']]['komisi'] = $data['komisi'];
	}

	$now = date("Y-m-d");
	echo '
	<div class="table-responsive">
	<table class="table table-hover table-bordered" style="max-width: 500px">
		<thead class="table-secondary">
			<tr>
				<th>Bulan</th>
				<th class="text-end">Komisi</th>
			</tr>
		</thead>
		<tbody>';
			if (isset($duit) && is_array($duit)) {
				foreach ($duit as $key => $value) {					
					echo  '
					<tr>
					<td><a href="laporankomisi?detil='.$key.'">'.date('F Y',strtotime($key)).'</a></td>					
					<td class="text-end">'.number_format($value['komisi']).'</td>
					</tr>';
				}
			}
	echo '
		</tbody>
	</table>
	</div>';
}
showfooter();
