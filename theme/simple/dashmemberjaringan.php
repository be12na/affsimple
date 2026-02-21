<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }

$head['pagetitle'] = 'Jaringan Anda';
$head['scripthead'] = '
<style>
/* ===== Network Tree — Modern Design ===== */
.sa-network-wrap {
  max-width: 800px;
}
.sa-network-stats {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  margin-bottom: 1.5rem;
}
.sa-stat-card {
  background: #fff;
  border: 1px solid var(--sa-border, #e5e7eb);
  border-radius: 12px;
  padding: 1rem 1.5rem;
  flex: 1;
  min-width: 140px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.sa-stat-card .sa-stat-num {
  font-size: 1.75rem;
  font-weight: 700;
  color: #6366f1;
  line-height: 1.2;
}
.sa-stat-card .sa-stat-lbl {
  font-size: 0.8rem;
  font-weight: 500;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

/* Search */
.sa-net-search {
  position: relative;
  margin-bottom: 1.25rem;
}
.sa-net-search input {
  padding-left: 2.5rem;
  border: 1.5px solid #e5e7eb;
  border-radius: 10px;
  font-size: 0.875rem;
  height: 42px;
}
.sa-net-search input:focus {
  border-color: #818cf8;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}
.sa-net-search i {
  position: absolute;
  left: 0.85rem;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  font-size: 0.85rem;
}

/* Tree */
.sa-tree {
  list-style: none;
  padding: 0;
  margin: 0;
}
.sa-tree .sa-tree {
  margin-left: 1.5rem;
  padding-left: 1rem;
  border-left: 2px solid #e5e7eb;
}
.sa-tree-node {
  position: relative;
  padding: 0;
  margin: 0;
}
.sa-tree-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 0.65rem;
  margin: 2px 0;
  border-radius: 8px;
  cursor: default;
  transition: background 0.15s ease;
}
.sa-tree-item:hover {
  background: rgba(99,102,241,0.04);
}

.sa-tree-toggle {
  width: 24px;
  height: 24px;
  border-radius: 6px;
  border: 1.5px solid #e5e7eb;
  background: #fff;
  color: #64748b;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 0.65rem;
  transition: all 0.2s ease;
  flex-shrink: 0;
}
.sa-tree-toggle:hover {
  border-color: #6366f1;
  color: #6366f1;
  background: rgba(99,102,241,0.06);
}
.sa-tree-toggle.open {
  background: #6366f1;
  border-color: #6366f1;
  color: #fff;
}

.sa-tree-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 600;
  flex-shrink: 0;
}

.sa-tree-name {
  font-weight: 500;
  font-size: 0.9rem;
  color: #1e293b;
  cursor: pointer;
  transition: color 0.15s ease;
}
.sa-tree-name:hover {
  color: #6366f1;
}

.sa-tree-badge {
  display: inline-flex;
  align-items: center;
  font-size: 0.7rem;
  font-weight: 500;
  padding: 0.15em 0.55em;
  border-radius: 5px;
  flex-shrink: 0;
}
.sa-badge-free {
  background: rgba(245,158,11,0.1);
  color: #d97706;
}
.sa-badge-premium {
  background: rgba(16,185,129,0.1);
  color: #059669;
}
.sa-badge-pending {
  background: rgba(148,163,184,0.15);
  color: #64748b;
}

.sa-tree-count {
  font-size: 0.75rem;
  color: #94a3b8;
  margin-left: auto;
  flex-shrink: 0;
}

.sa-tree-children {
  /* Inserted via AJAX */
}

/* Loading spinner */
.sa-tree-loading {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid #e5e7eb;
  border-top-color: #6366f1;
  border-radius: 50%;
  animation: sa-spin 0.6s linear infinite;
  margin-left: 0.5rem;
}
@keyframes sa-spin {
  to { transform: rotate(360deg); }
}

/* Detail Modal Override */
#detilprofil {
  backdrop-filter: blur(6px);
}
#themember {
  border-radius: 16px;
  box-shadow: 0 8px 40px rgba(0,0,0,0.15);
  padding: 0;
  overflow: hidden;
}
#themember .sa-detail-header {
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: #fff;
  padding: 1.25rem 1.5rem;
  position: relative;
}
#themember .sa-detail-header h6 {
  margin: 0;
  font-weight: 600;
  font-size: 1rem;
}
#themember .sa-detail-close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(255,255,255,0.2);
  border: none;
  color: #fff;
  width: 30px;
  height: 30px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 0.85rem;
  transition: background 0.15s;
}
#themember .sa-detail-close:hover {
  background: rgba(255,255,255,0.35);
}
#themember .sa-detail-body {
  padding: 1.25rem 1.5rem;
  max-height: 60vh;
  overflow-y: auto;
}
.sa-detail-row {
  display: flex;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.875rem;
}
.sa-detail-row:last-child {
  border-bottom: none;
}
.sa-detail-label {
  font-weight: 600;
  color: #64748b;
  width: 140px;
  flex-shrink: 0;
}
.sa-detail-value {
  color: #1e293b;
  word-break: break-word;
}

/* Empty state */
.sa-empty-state {
  text-align: center;
  padding: 3rem 1.5rem;
  color: #94a3b8;
}
.sa-empty-state i {
  font-size: 3rem;
  margin-bottom: 1rem;
  color: #cbd5e1;
}
.sa-empty-state p {
  font-size: 0.95rem;
  font-weight: 500;
  margin: 0;
}
</style>';

showheader($head);

$data = db_select("SELECT * FROM `sa_sponsor` LEFT JOIN `sa_member` ON `sa_member`.`mem_id`=`sa_sponsor`.`sp_mem_id` WHERE `sp_sponsor_id`=".$iduser);

// Count stats
$totalMember = count($data);
$freeMember = 0;
$premiumMember = 0;
foreach ($data as $row) {
	if ($row['mem_status'] == 1) $freeMember++;
	if ($row['mem_status'] == 2) $premiumMember++;
}
?>

<div class="sa-network-wrap sa-animate">

  <!-- Stats Cards -->
  <div class="sa-network-stats">
    <div class="sa-stat-card">
      <div class="sa-stat-num"><?= number_format($totalMember); ?></div>
      <div class="sa-stat-lbl">Total Downline</div>
    </div>
    <div class="sa-stat-card">
      <div class="sa-stat-num"><?= number_format($freeMember); ?></div>
      <div class="sa-stat-lbl">Free Member</div>
    </div>
    <div class="sa-stat-card">
      <div class="sa-stat-num"><?= number_format($premiumMember); ?></div>
      <div class="sa-stat-lbl">Premium</div>
    </div>
  </div>

  <!-- Search -->
  <?php if ($totalMember > 5) : ?>
  <div class="sa-net-search">
    <i class="fas fa-search"></i>
    <input type="text" id="searchNetwork" class="form-control" placeholder="Cari nama member...">
  </div>
  <?php endif; ?>

  <!-- Tree -->
  <?php if ($totalMember > 0) : ?>
  <div class="card">
    <div class="card-body p-2 p-md-3">
      <ul class="sa-tree" id="networkTree">
      <?php 
      $statusLabel = array('Blm Valid','Free Member','Premium');
      $statusClass = array('sa-badge-pending','sa-badge-free','sa-badge-premium');
      foreach ($data as $member) :
        $initials = strtoupper(mb_substr($member['mem_nama'], 0, 2));
        $jmldownline = isset($member['sp_jmldownline']) && $member['sp_jmldownline'] > 0 ? $member['sp_jmldownline'] : 0;
        $memStatus = $member['mem_status'] ?? 0;
      ?>
        <li class="sa-tree-node" id="member<?= $member['mem_id']; ?>">
          <div class="sa-tree-item">
            <span class="sa-tree-toggle" data-id="<?= $member['mem_id']; ?>" title="Lihat downline">
              <i class="fas fa-chevron-right"></i>
            </span>
            <span class="sa-tree-avatar"><?= $initials; ?></span>
            <span class="sa-tree-name" data-id="detil<?= $member['mem_id']; ?>"><?= htmlspecialchars($member['mem_nama']); ?></span>
            <span class="sa-tree-badge <?= $statusClass[$memStatus]; ?>"><?= $statusLabel[$memStatus]; ?></span>
            <?php if ($jmldownline > 0) : ?>
            <span class="sa-tree-count" title="Jumlah downline"><i class="fas fa-users fa-xs"></i> <?= $jmldownline; ?></span>
            <?php endif; ?>
          </div>
        </li>
      <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php else : ?>
  <div class="card">
    <div class="card-body">
      <div class="sa-empty-state">
        <i class="fas fa-network-wired"></i>
        <p>Belum ada jaringan.</p>
        <small class="text-muted">Mulai promosi dan rekrut member baru!</small>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div id="detilprofil"></div>
</div>

<?php
$footer['scriptfoot'] = '
<script type="text/javascript">
var $j = jQuery.noConflict();
$j(function(){
  // Search filter
  $j("#searchNetwork").on("keyup", function() {
    var val = $j(this).val().toLowerCase();
    $j("#networkTree > .sa-tree-node").each(function() {
      var name = $j(this).find(".sa-tree-name").first().text().toLowerCase();
      $j(this).toggle(name.indexOf(val) > -1);
    });
  });

  // Toggle children
  $j(document).on("click", ".sa-tree-toggle", function() {
    var btn = $j(this);
    var idmember = btn.data("id");
    var node = btn.closest(".sa-tree-node");

    if (node.find("> .sa-tree-children").length) {
      node.find("> .sa-tree-children").slideToggle(200);
      btn.toggleClass("open");
      btn.find("i").toggleClass("fa-chevron-right fa-chevron-down");
    } else {
      btn.addClass("open");
      btn.find("i").removeClass("fa-chevron-right").addClass("fa-chevron-down");
      btn.after(\'<span class="sa-tree-loading"></span>\');
      $j.get("'.$weburl.'jaringanmember", { id: idmember }, function(data){
        btn.siblings(".sa-tree-loading").remove();
        node.append(data);
      });
    }
  });

  // Detail panel
  $j("#detilprofil").hide();

  $j(document).on("click", ".sa-detail-close, #detilprofil", function(e) {
    if (e.target === this) {
      $j("#detilprofil").hide();
      $j("#themember").remove();
    }
  });

  $j(document).on("click", ".sa-tree-name", function() {
    var idmember = $j(this).data("id");
    $j("#themember").remove();
    $j("#detilprofil").show();
    $j("#detilprofil").html(\'<div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%)"><span class="sa-tree-loading" style="width:32px;height:32px;border-width:3px"></span></div>\');
    $j.get("'.$weburl.'jaringanmember", { member: idmember }, function(data){
      $j("#detilprofil").html(data);
    });
  });
});
</script>';
showfooter($footer); ?>