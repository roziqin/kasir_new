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

	$query = "SELECT count(*) as jumlah FROM transaksi, transaksi_detail where transaksi_id=transaksi_detail_nota and  transaksi_bulan = '$bln' GROUP BY transaksi_tanggal";

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

}  elseif ($func=='laporan-omset') {
	
    
    if ($_POST['daterange']=="harian") {
        $ket = "transaksi_tanggal"; 
		$tgl11 = date("Y-m-j", strtotime($_POST['start']));
	    $tgl22 = date("Y-m-j", strtotime($_POST['end']));
    } elseif ($_POST['daterange']=="bulanan") {
        $ket = "transaksi_bulan";     
		$tgl11 = date("Y-m", strtotime($_POST['start']));
	    $tgl22 = date("Y-m", strtotime($_POST['end']));
    }

    /*
    $tgl11 = date("Y-m-j", strtotime("2 September, 2019"));
    $tgl22 = date("Y-m-j", strtotime("10 September, 2019"));

    $ket = "transaksi_tanggal";
    */
	$sql = mysqli_query($con, "SELECT transaksi_id FROM transaksi WHERE $ket BETWEEN '$tgl11' AND '$tgl22' GROUP BY $ket"); // Query untuk menghitung seluruh data siswa
	$sql_count = mysqli_num_rows($sql); // Hitung data yg ada pada query $sql
	$query ="SELECT transaksi_tanggal, transaksi_bulan, sum(transaksi_total) as total, sum(transaksi_diskon) as diskon from transaksi WHERE $ket BETWEEN '$tgl11' AND '$tgl22' GROUP BY $ket  ";
}


$result = mysqli_query($con,$query);
$array_data = array();
if ($func=="laporan-omset") {
	
	if ($_POST['daterange']=="harian") {
        $ket = "transaksi_tanggal"; 
    } elseif ($_POST['daterange']=="bulanan") {
        $ket = "transaksi_bulan";     
    }
    
    //$ket = "transaksi_tanggal";
	while($data = mysqli_fetch_assoc($result))
	{

		$tglket = $data[$ket];
        $sqlcash="SELECT sum(transaksi_total) as total from transaksi WHERE $ket='$tglket' and transaksi_type_bayar='GoResto' GROUP BY $ket  ";
        $querycash=mysqli_query($con, $sqlcash);
        $datacash=mysqli_fetch_assoc($querycash);
        $totalcash = 0;
        if ($datacash['total']!='') {
            $totalcash = $datacash['total'];
        }
		
        $sqlonline="SELECT sum(transaksi_total) as total from transaksi WHERE $ket='$tglket' and transaksi_type_bayar='GoResto' GROUP BY $ket  ";
        $queryonline=mysqli_query($con, $sqlonline);
        $dataonline=mysqli_fetch_assoc($queryonline);
        $totalonline = 0;
        if ($dataonline['total']!='') {
            $totalonline = $dataonline['total'];
        }

        $sqldebet="SELECT sum(transaksi_total) as total from transaksi WHERE $ket='$tglket' and transaksi_type_bayar='Debet' GROUP BY $ket  ";
        $querydebet=mysqli_query($con, $sqldebet);
        $datadebet=mysqli_fetch_assoc($querydebet);
        $totaldebet = 0;
        if ($datadebet['total']!='') {
            $totaldebet = $datadebet['total'];
        }
        
	  //$array_data[]=($ket=>$data[$ket], 'cash'=>$totalcash, 'debet'=>$totaldebet, 'online'=>$totalonline);

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


