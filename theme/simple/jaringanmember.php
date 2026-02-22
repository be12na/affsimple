<?php
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }

$statusLabel = array('Blm Valid','Free Member','Premium');
$statusClass = array('sa-badge-pending','sa-badge-free','sa-badge-premium');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	// AJAX: Load child nodes for tree
	$id = $_GET['id'];	
	$data = db_select("SELECT * FROM `sa_sponsor` LEFT JOIN `sa_member` ON `sa_member`.`mem_id`=`sa_sponsor`.`sp_mem_id` WHERE `sp_sponsor_id`=".$id." AND `sa_member`.`mem_status` > 0");
	if (count($data) > 0) {
		echo '<ul class="sa-tree sa-tree-children" style="display:block;">';
		foreach ($data as $member) {
			$initials = strtoupper(mb_substr($member['mem_nama'], 0, 2));
			$memStatus = $member['mem_status'] ?? 0;
			$jmldownline = isset($member['sp_jmldownline']) && $member['sp_jmldownline'] > 0 ? $member['sp_jmldownline'] : 0;
			
			echo '<li class="sa-tree-node" id="member'.$member['mem_id'].'">';
			echo '<div class="sa-tree-item">';
			
			if ($memStatus > 0) {
				echo '<span class="sa-tree-toggle" data-id="'.$member['mem_id'].'" title="Lihat downline"><i class="fas fa-chevron-right"></i></span>';
			} else {
				echo '<span style="width:24px;flex-shrink:0;"></span>';
			}
			
			echo '<span class="sa-tree-avatar" style="width:28px;height:28px;font-size:0.65rem;">'.$initials.'</span>';
			echo '<span class="sa-tree-name" data-id="detil'.$member['mem_id'].'">'.htmlspecialchars($member['mem_nama']).'</span>';
			echo '<span class="sa-tree-badge '.$statusClass[$memStatus].'">'.$statusLabel[$memStatus].'</span>';
			
			if ($jmldownline > 0) {
				echo '<span class="sa-tree-count" title="Jumlah downline"><i class="fas fa-users fa-xs"></i> '.$jmldownline.'</span>';
			}
			
			echo '</div></li>';
		}
		echo '</ul>';
	}
} else {
	// AJAX: Load member detail panel  
	$idmember = str_replace('detil','',$_GET['member']);
	$dataklien = db_row("SELECT * FROM `sa_sponsor` LEFT JOIN `sa_member` ON `sa_member`.`mem_id`=`sa_sponsor`.`sp_mem_id` 
		WHERE `sp_mem_id`=".$idmember);
	
	$memStatus = $dataklien['mem_status'] ?? 0;
	$initials = strtoupper(mb_substr($dataklien['mem_nama'] ?? '?', 0, 2));
	
	echo '
	<div id="themember">
		<div class="sa-detail-header">
			<div class="d-flex align-items-center gap-3">
				<span class="sa-tree-avatar" style="width:44px;height:44px;font-size:1rem;background:rgba(255,255,255,0.2);">'.$initials.'</span>
				<div>
					<h6>'.htmlspecialchars($dataklien['mem_nama'] ?? '').'</h6>
					<span class="sa-tree-badge '.($statusClass[$memStatus] ?? 'sa-badge-pending').'" style="background:rgba(255,255,255,0.2);color:#fff;">'.($statusLabel[$memStatus] ?? 'Unknown').'</span>
				</div>
			</div>
			<button class="sa-detail-close" title="Tutup"><i class="fas fa-xmark"></i></button>
		</div>
		<div class="sa-detail-body">';
		
	$dataform = extractdata($dataklien);
	$formfield = db_select("SELECT * FROM `sa_form` WHERE `ff_network`=1 ORDER BY `ff_sort`");
	if (count($formfield) > 0) {
		foreach ($formfield as $field) {
			echo '<div class="sa-detail-row">';
			echo '<div class="sa-detail-label">'.$field['ff_label'].'</div>';
			echo '<div class="sa-detail-value">';
			if (isset($dataform[$field['ff_field']])) {
				if ($field['ff_field'] == 'kodeaff') {
					echo '<a href="'.$weburl.$dataform[$field['ff_field']].'">'.$weburl.$dataform[$field['ff_field']].'</a>';
				} elseif ($field['ff_field'] == 'whatsapp') {
					echo '<a href="https://wa.me/'.$dataform[$field['ff_field']].'" target="_blank"><i class="fab fa-whatsapp"></i> '.$dataform[$field['ff_field']].'</a>';
				} elseif ($field['ff_field'] == 'email') {
					echo '<a href="mailto:'.$dataform[$field['ff_field']].'">'.$dataform[$field['ff_field']].'</a>';
				} else {
					echo htmlspecialchars($dataform[$field['ff_field']]);
				}
			} else {
				echo '<span class="text-muted">—</span>';
			}
			echo '</div></div>';
		}
	}
	
	echo '
			<div class="sa-detail-row">
				<div class="sa-detail-label">Tanggal Daftar</div>
				<div class="sa-detail-value">'.date('d M Y', strtotime($dataklien['mem_tgldaftar'])).'</div>
			</div>
			<div class="sa-detail-row">
				<div class="sa-detail-label">Status</div>
				<div class="sa-detail-value"><span class="sa-tree-badge '.$statusClass[$memStatus].'">'.$statusLabel[$memStatus].'</span></div>
			</div>
		</div>
	</div>';
}
?>
