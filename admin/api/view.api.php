<?php
include '../../config/database.php';
session_start();
$bln=date('Y-m');

$func = $_GET['func'];

if ($func=='dasboard-omset') {

	$query = "SELECT transaksi_tanggal, sum(transaksi_total) as total FROM transaksi where transaksi_bulan = '$bln' GROUP BY transaksi_tanggal";

} elseif ($func=='dasboard-pelanggan') {

	$query = "SELECT count(*) as jumlah FROM transaksi where transaksi_bulan = '$bln' GROUP BY transaksi_tanggal";

} elseif ($func=='dasboard-itemsold') {

	$query = "SELECT count(*) as jumlah FROM transaksi, transaksi_detail where transaksi_nota=transaksi_detail_nota and  transaksi_bulan = '$bln' GROUP BY transaksi_tanggal";

} elseif ($func=='listproduk') {

	$query = "SELECT barang_nama, kategori_nama, barang_stok, barang_harga_beli, barang_harga_jual, barang_harga_jual_online FROM barang, kategori where barang_kategori=kategori_id";

} elseif ($func=='editproduk') {
	$id = $_POST['produk_id'];
	$query = "SELECT * from barang, kategori where barang_kategori=kategori_id and barang_id='$id'";

} elseif ($func=='listkategori') {

	$query = "SELECT * FROM kategori";

} elseif ($func=='editkategori') {
	$id = $_POST['kategori_id'];
	$query = "SELECT * from kategori where kategori_id='$id'";

} elseif ($func=='edituser') {
	$id = $_POST['id'];
	$query = "SELECT * from users where id='$id'";

} elseif ($func=='editsetting') {
	$id = 1;
	$query = "SELECT * from pengaturan_perusahaan where pengaturan_id='$id'";

} elseif ($func=='editstok') {
	$id = $_POST['id'];
	$query = "SELECT * from barang where barang_id='$id'";

} elseif ($func=='list-transaksi-temp') {
    $user = $_SESSION['login_user'];
    $query="SELECT * from transaksi_detail_temp, barang, kategori where transaksi_detail_temp_barang_id=barang_id and kategori_id=barang_kategori and transaksi_detail_temp_user='$user' ORDER BY transaksi_detail_temp_id";
} elseif ($func=='laporan-omset') {
	
    
    if ($_POST['daterange']=="harian") {
        $ket = "transaksi_tanggal"; 
		$tgl11 = date("Y-m-j", strtotime($_POST['start']));
	    $tgl22 = date("Y-m-j", strtotime($_POST['end']));
    } elseif ($_POST['daterange']=="bulanan") {
        $ket = "transaksi_bulan";     
		$tgl11 = date("Y-m", strtotime($_POST['start']));
	    $tgl22 = date("Y-m", strtotime($_POST['end']));
    }

	$query ="SELECT transaksi_tanggal, transaksi_bulan, sum(transaksi_total) as total, sum(transaksi_diskon) as diskon from transaksi WHERE $ket BETWEEN '$tgl11' AND '$tgl22' GROUP BY $ket  ";

}  elseif ($func=='laporan-kasir') {
	
    $kasir = $_POST['kasir'];

    if ($kasir==0) {
        $text1 = '';
        $text2 = ', transaksi_user';
    } else {
        $text1 = 'transaksi_user='.$kasir.' and ';
        $text2 = '';

    }

    if ($_POST['daterange']=="harian") {
        $ket = "transaksi_tanggal"; 
		$tgl11 = date("Y-m-j", strtotime($_POST['start']));
	    $tgl22 = date("Y-m-j", strtotime($_POST['end']));
    } elseif ($_POST['daterange']=="bulanan") {
        $ket = "transaksi_bulan";     
		$tgl11 = date("Y-m", strtotime($_POST['start']));
	    $tgl22 = date("Y-m", strtotime($_POST['end']));
    }

	$query ="SELECT transaksi_tanggal, transaksi_bulan, sum(transaksi_total) as total, sum(transaksi_diskon) as diskon, transaksi_user, id, name from transaksi, users WHERE transaksi_user=id and $text1 $ket BETWEEN '$tgl11' AND '$tgl22' GROUP BY $ket $text2 ";

}  elseif ($func=='laporan-menu') {
	
    $menu = $_POST['menu'];

    if ($menu==0) {
        $text1 = '';
        $text2 = ', barang_id';
    } else {
        $text1 = 'barang_id='.$menu.' and ';
        $text2 = '';

    }

    if ($_POST['daterange']=="harian") {
        $ket = "transaksi_tanggal"; 
		$tgl11 = date("Y-m-j", strtotime($_POST['start']));
	    $tgl22 = date("Y-m-j", strtotime($_POST['end']));
    } elseif ($_POST['daterange']=="bulanan") {
        $ket = "transaksi_bulan";     
		$tgl11 = date("Y-m", strtotime($_POST['start']));
	    $tgl22 = date("Y-m", strtotime($_POST['end']));
    }

	$query ="SELECT transaksi_tanggal, transaksi_bulan, barang_nama, barang_id, sum(transaksi_detail_jumlah) as jumlah from transaksi, transaksi_detail, barang WHERE transaksi_nota=transaksi_detail_nota and transaksi_detail_barang_id=barang_id and $text1 $ket BETWEEN '$tgl11' AND '$tgl22' GROUP BY $ket $text2 ORDER BY $ket ASC";

} elseif ($func=='logs') {
    
    $ketlog = $_POST['ketlog']; 
    $tgl11 = date("Y-m-j", strtotime($_POST['start']));
    $tgl22 = date("Y-m-j", strtotime($_POST['end']));

    if ($ketlog=="validasi") {
        $query="SELECT * from  validasi WHERE validasi_tanggal BETWEEN '$tgl11' AND '$tgl22' ORDER BY validasi_id asc";

    } elseif ($ketlog=="stok") {
        $query="SELECT * from barang, log_stok, users where barang_id=barang and user=id and tanggal BETWEEN '$tgl11' AND '$tgl22' ORDER BY log_id asc";

    } elseif ($ketlog=="harga") {
        $query="SELECT * from barang, log_harga, users where barang.barang_id=log_harga.barang_id and user=id and tanggal BETWEEN '$tgl11' AND '$tgl22' ORDER BY log_id asc";

    } elseif ($ketlog=="login") {
        $query="SELECT * from log_user, users where user=id and login BETWEEN '$tgl11%' AND '$tgl22%' ORDER BY log_id asc";

    }

}


$result = mysqli_query($con,$query);
$array_data = array();
if ($func=="laporan-omset" || $func=="laporan-kasir") {
	
	if ($_POST['daterange']=="harian") {
        $ket = "transaksi_tanggal"; 
    } elseif ($_POST['daterange']=="bulanan") {
        $ket = "transaksi_bulan";     
    }
    
    //$ket = "transaksi_tanggal";
	while($data = mysqli_fetch_assoc($result))
	{
		if ($func=="laporan-kasir") {
            if ($data['id']==0) {
                $text = '';
                $text1 = ', transaksi_user';
            } else {
    	        $text = 'transaksi_user='.$data['id'].' and ';
                $text1 = '';
            }
	    } else {
            $text = '';
            $text1 = '';
        }

		$tglket = $data[$ket];
        $sqlcash="SELECT sum(transaksi_total) as total from transaksi WHERE $text $ket='$tglket' and transaksi_type_bayar='Cash' GROUP BY $ket $text1 ";
        $querycash=mysqli_query($con, $sqlcash);
        $datacash=mysqli_fetch_assoc($querycash);
        $totalcash = 0;
        if ($datacash['total']!='') {
            $totalcash = $datacash['total'];
        }
		
        $sqlonline="SELECT sum(transaksi_total) as total from transaksi WHERE $text $ket='$tglket' and transaksi_type_bayar='GoResto' GROUP BY $ket $text1 ";
        $queryonline=mysqli_query($con, $sqlonline);
        $dataonline=mysqli_fetch_assoc($queryonline);
        $totalonline = 0;
        if ($dataonline['total']!='') {
            $totalonline = $dataonline['total'];
        }

        $sqldebet="SELECT sum(transaksi_total) as total from transaksi WHERE $text $ket='$tglket' and transaksi_type_bayar='Debet' GROUP BY $ket $text1 ";
        $querydebet=mysqli_query($con, $sqldebet);
        $datadebet=mysqli_fetch_assoc($querydebet);
        $totaldebet = 0;
        if ($datadebet['total']!='') {
            $totaldebet = $datadebet['total'];
        }
        
	  //$array_data[]=($ket=>$data[$ket], 'cash'=>$totalcash, 'debet'=>$totaldebet, 'online'=>$totalonline);
        if ($func=="laporan-kasir") {
			$row_array['kasir'] = $data['name'];
        }
		$row_array[$ket] = $data[$ket];
	    $row_array['cash'] = $totalcash;
	    $row_array['debet'] = $totaldebet;
	    $row_array['online'] = $totalonline;
		$row_array['total'] = $data['total'];
        array_push($array_data,$row_array);
	}
} else {
	while($baris = mysqli_fetch_assoc($result))
	{
	  $array_data[]=$baris;
	}
}

if ($func=='listproduk') {
	$array_datas = array();
	$array_datas['data'] = $array_data;
	echo json_encode($array_datas);
} else {

	echo json_encode($array_data);
}

?>


