<?php 
if (!defined('IS_IN_SCRIPT')) { die(); exit(); }
if ($datamember['mem_role'] < 5) { die(); exit(); }
$head['pagetitle'] = 'Order List';
showheader($head);

// Fungsi untuk men-download data dalam format CSV
if (isset($_GET['download']) && $_GET['download'] == 'excel') {
    // Query data order
    $order = db_select("SELECT * FROM `sa_order` 
        LEFT JOIN `sa_member` ON `sa_member`.`mem_id` = `sa_order`.`order_idmember`
        LEFT JOIN `sa_page` ON `sa_page`.`page_id` = `sa_order`.`order_idproduk`");

    // Cek jika data order ada
    if (count($order) > 0) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="order_list.csv"');
        $output = fopen("php://output", "w");
        fputcsv($output, array('ID', 'Tanggal Order', 'Nama', 'Produk', 'Harga', 'Status'));

        // Loop untuk memasukkan data ke dalam CSV
        foreach ($order as $row) {
            $status = ($row['order_status'] == 0) ? 'Belum Lunas' : 'Lunas';
            fputcsv($output, array($row['order_id'], $row['order_tglorder'], $row['mem_nama'], $row['page_judul'], number_format($row['order_hargaunik']), $status));
        }
        fclose($output);
    } else {
        // Jika tidak ada data, tampilkan pesan error
        echo '<div class="alert alert-warning">Tidak ada data order yang ditemukan.</div>';
    }
    exit();
}


// Proses order
if (isset($_GET['proses']) && is_numeric($_GET['proses'])) {
    $idinvoice = $_GET['proses'];
    $staff = $datamember['mem_id'];
    include('prosesorder.php');
} elseif (isset($_GET['batal']) && is_numeric($_GET['batal']) && $_GET['batal'] > 0) {
    $proses = db_row("SELECT * FROM `sa_order`
        LEFT JOIN `sa_member` ON `sa_member`.`mem_id` = `sa_order`.`order_idmember`
        LEFT JOIN `sa_sponsor` ON `sa_sponsor`.`sp_mem_id`= `sa_order`.`order_idmember`
        LEFT JOIN `sa_page` ON `sa_page`.`page_id` = `sa_order`.`order_idproduk`
        WHERE `sa_order`.`order_status` = 1 AND `sa_order`.`order_id`=".$_GET['batal']);
    if (isset($proses['order_id'])) {
        db_query("UPDATE `sa_order` SET `order_status`=0,`order_idstaff`=".$datamember['mem_id'].",`order_tglbayar`=NULL WHERE `order_id`=".$proses['order_id']);
        db_query("DELETE FROM `sa_laporan` WHERE `lap_idorder`=".$_GET['batal']);
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Ok!</strong> Order '.$proses['order_id'].' telah dibatalkan 🙏
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Error!</strong> Order tidak ditemukan. Mungkin sudah dihapus atau sudah dibatalkan sebelumnya.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
} elseif (isset($_GET['del']) && is_numeric($_GET['del'])) {
    db_query("DELETE FROM `sa_order` WHERE `order_id`=".$_GET['del']);
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Ok!</strong> Order '.$_GET['del'].' telah dihapus 🙏
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}
?>
<form action="" method="get">
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">    
                <div class="col">
                    <div class="input-group">
                        <input type="text" class="form-control" name="cari" value="<?= $_GET['cari'] ??= '';?>">
                        <?php 
                        $select = array('','','');
                        if (isset($_GET['status']) && is_numeric($_GET['status'])) {
                            $select[$_GET['status']] = ' selected';
                        }
                        ?>
                        <select name="status" class="form-select">
                            <option value="">All Order</option>
                            <option value="0"<?=$select[0];?>>Belum Lunas</option>
                            <option value="1"<?=$select[1];?>>Lunas</option>
                        </select>
                        <input type="submit" value=" Cari " class="btn btn-secondary">
                    </div>        
                </div>
                <div class="col-auto">
                    <!-- Tombol untuk mengunduh data Excel -->
                    <a href="?download=excel" class="btn btn-success">Download Excel</a>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>ID</th>
                <th class="d-none d-sm-table-cell">Tgl Order</th>
                <th>Nama</th>
                <th class="d-none d-sm-table-cell">Produk</th>
                <th class="d-none d-sm-table-cell text-end">Harga</th>
                <th class="d-none d-sm-table-cell">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $jmlperpage = 25;
            if (isset($_GET['start']) && is_numeric($_GET['start'])) {
                $start = ($_GET['start'] - 1) * $jmlperpage;
                $page = $_GET['start'];
            } else {
                $start = 0;
                $page = 1;
            }

            $where = '';
            if (isset($_GET['cari']) && !empty($_GET['cari'])) {
                $s = cek($_GET['cari']);
                if (is_numeric($_GET['cari'])) {
                    $where = "WHERE `order_id`=".$_GET['cari'];
                } else {
                    $where = "WHERE (`sa_member`.`mem_nama` LIKE '%".$s."%' 
                                    OR `sa_member`.`mem_email` LIKE '%".$s."%' 
                                    OR `sa_member`.`mem_whatsapp` LIKE '%".$s."%' 
                                    OR `sa_member`.`mem_datalain` LIKE '%".$s."%' 
                                    OR `sa_member`.`mem_kodeaff` LIKE '%".$s."%'
                                    OR `sa_page`.`page_judul` LIKE '%".$s."%' 
                                    OR `sa_page`.`page_diskripsi` LIKE '%".$s."%'
                                    OR `sa_page`.`page_url` LIKE '%".$s."%')";
                }
            }

            if (isset($_GET['status']) && is_numeric($_GET['status'])) {
                if ($where == '') {
                    $where .= "WHERE `sa_order`.`order_status`=".$_GET['status'];            
                } else {
                    $where .= " AND `sa_order`.`order_status`=".$_GET['status'];            
                }
            }

            $order = db_select("SELECT * FROM `sa_order` 
                LEFT JOIN `sa_member` ON `sa_member`.`mem_id` = `sa_order`.`order_idmember`
                LEFT JOIN `sa_page` ON `sa_page`.`page_id` = `sa_order`.`order_idproduk`
                ".$where."
                ORDER BY `order_tglorder` DESC
                LIMIT ".$start.",".$jmlperpage);

            if (count($order) > 0) {
                foreach ($order as $order) {
                    echo '
                    <tr>
                        <td><a href="'.$weburl.'invoice/'.$order['order_id'].'" target="_blank">'.$order['order_id'].'</td>
                        <td class="d-none d-sm-table-cell">'.$order['order_tglorder'].'</td>
                        <td>
                        <span class="d-none d-sm-block">'.$order['mem_nama'].'</span>
                        <span class="d-sm-none">
                        <strong>'.$order['mem_nama'].'</strong>
                        <small>('.$order['order_tglorder'].')</small>
                        <br/>Produk: '.$order['page_judul'].'<br/>
                        Harga: '.number_format($order['order_hargaunik']).'<br/>';
                    if ($order['order_status'] == 0) {
                        echo '<a href="'.$weburl.'dashboard/orderlist?proses='.$order['order_id'].'" class="btn btn-sm btn-success">Proses</a>';
                    } else {
                        echo '<a href="'.$weburl.'dashboard/orderlist?batal='.$order['order_id'].'" class="btn btn-sm btn-danger">Batal</a>';
                    }
                    echo '
                        </span>
                        </td>
                        <td class="d-none d-sm-table-cell">'.$order['page_judul'].'</td>
                        <td class="d-none d-sm-table-cell text-end">'.number_format($order['order_hargaunik']).'</td>
                        <td class="d-none d-sm-table-cell">
                            '.($order['order_status'] == 0 ? '<a href="'.$weburl.'dashboard/orderlist?proses='.$order['order_id'].'" class="btn btn-sm btn-success">Proses</a>' : '<a href="'.$weburl.'dashboard/orderlist?batal='.$order['order_id'].'" class="btn btn-sm btn-danger">Batal</a>').'
                            <a href="'.$weburl.'dashboard/orderlist?del='.$order['order_id'].'" class="btn btn-sm btn-secondary">Delete</a>
                        </td>
                    </tr>';
                }
            } else {
                echo '
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data order yang ditemukan.</td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>
