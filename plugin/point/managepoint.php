<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
$head['pagetitle']='Manage Point';
showheader($head);

if (isset($_GET['detil']) && is_numeric($_GET['detil'])) {
	$data = db_select("SELECT * FROM `sa_point` WHERE `poin_idsponsor`=".$_GET['detil']);	
	echo '
		<div class="table-responsive">
		<table class="table table-hover table-bordered">
		<thead class="table-secondary">
			<tr>
				<th>Tanggal</th>
				<th>Keterangan</th>				
				<th class="text-end">Jumlah</th>
				<th class="text-end">Total</th>
			</tr>
		</thead>
		<tbody>';
	if (count($data) > 0) {
		$saldo = 0;
		foreach ($data as $data) {
			$saldo = $saldo + $data['poin_jumlah'];
			echo '
		<tr>
			<td>'.$data['poin_tanggal'].'</td>
			<td>'.$data['poin_keterangan'].'</td>			
			<td class="text-end">'.number_format($data['poin_jumlah']).'</td>
			<td class="text-end">'.number_format($saldo).'</td>
		</tr>
			';
		}
	}
	echo '
		</tbody>
		</table>
		</div>
	';
	
} else {
	if (isset($_POST['klaim']) && is_numeric($_POST['klaim']) && isset($_POST['idmember']) && is_numeric($_POST['idmember'])) {
		# Klaim Point
		if (db_var("SELECT SUM(`poin_jumlah`) FROM `sa_point` WHERE `poin_idsponsor`=".$_POST['idmember']) >= $_POST['klaim']) {
			$pointket = 'Klaim Point';
			$cek = db_insert("INSERT INTO `sa_point` (`poin_tanggal`,`poin_idmember`,`poin_idsponsor`,`poin_idorder`,`poin_jumlah`,`poin_keterangan`) 
				VALUES ('".date('Y-m-d H:i:s')."',0,".$_POST['idmember'].",0,".($_POST['klaim']*-1).",'".cek($pointket)."')");
			if ($cek === false) {
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				  <strong>Error!</strong> '.db_error().'
				  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>';
			} else {
				echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
				  Klaim berhasil!
				  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>';
			}
		} else {
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				  <strong>Error!</strong> Klaim lebih besar dari jumlah point yang tersedia
				  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>';
		}
	}

	$where = '';
	if (isset($_GET['cari']) && !empty($_GET['cari'])) {
		$s = cek($_GET['cari']);
		$where = "WHERE (`mem_nama` LIKE '%".$s."%' 
							OR `mem_email` LIKE '%".$s."%' 
							OR `mem_whatsapp` LIKE '%".$s."%' 
							OR `mem_datalain` LIKE '%".$s."%' 
							OR `mem_kodeaff` LIKE '%".$s."%')";
	}

	$data = db_select("SELECT *,SUM(`poin_jumlah`) AS `jmlpoint` FROM `sa_point` 
		LEFT JOIN `sa_member` ON `sa_member`.`mem_id`=`sa_point`.`poin_idsponsor` 
		".$where." GROUP BY `poin_idsponsor`");
	echo db_error();
	
	echo '
	<form action="" method="get">
	<div class="card mb-3">
		<div class="card-body">
		  <div class="row">	    
		    <div class="col">
		    	<div class="input-group">
					  <input type="text" class="form-control" name="cari" value="'.($_GET['cari'] ??= '').'">
					  <input type="submit" value=" Cari " class="btn btn-secondary">
					</div>	      
		    </div>
		  </div>
		</div>
	</div>
	</form>
	<div class="table-responsive">
	<table class="table table-hover table-bordered">
	<thead class="table-secondary">
		<tr>
			<th width="40%">Nama</th>
			<th width="20%">Point</th>
			<th width="40%">Klaim</th>
		</tr>
	</thead>
	<tbody>';
	if (count($data) > 0) {
		foreach ($data as $data) {
			$datalain = extractdata($data);
			echo '
		<tr>
			<td>
				<a href="?detil='.$data['mem_id'].'">'.$data['mem_nama'].'</a>
			</td>
			<td>'.number_format($data['jmlpoint']).'</td>
			<td>
				<form action="" method="post">
					<div class="input-group">
						<input type="hidden" name="idmember" value="'.$data['mem_id'].'"/>
						<input type="number" name="klaim" value="'.$data['jmlpoint'].'" class="form-control"/>
						<input type="submit" value="GO" class="btn btn-secondary"/>
					</div>
				</form>
			</td>
		</tr>
			';
		}	
	}
	echo '
	</tbody>
	</table>
	</div>';
}
showfooter();
?>