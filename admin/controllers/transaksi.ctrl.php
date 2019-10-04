<?php 
session_start();
include '../../config/database.php';
include "../../include/slug.php";
date_default_timezone_set('Asia/jakarta');
$tgl=date('Y-m-j');
$array_datas = array();
if($_GET['ket']=='tambahmenu'){

	$id = $_POST['barang_id'];	
	$jumlah = 1;
	$ket = '';
	$user = $_SESSION['login_user'];

	$sql="SELECT * from barang where barang_id='$id'";
	$query=mysqli_query($con,$sql);
	$data=mysqli_fetch_assoc($query);

	$sqla="SELECT * from transaksi_detail_temp where transaksi_detail_temp_barang_id='$id'";
	$querya=mysqli_query($con,$sqla);
	$dataa=mysqli_fetch_assoc($querya);

	if($dataa) {
		$jml=$dataa['transaksi_detail_temp_jumlah']+$jumlah;
	} else {
		$jml=$jumlah;
	}

	if ($data['barang_set_stok']==1 && $jml>$data['barang_stok']) {
		$array_datas[] = ["Stok Kurang"];
		//echo ("<script>location.href='../home.php?menu=jumlah&id=$id&nama=$data[barang_nama]&ket=Stok Kurang&pelanggan='</script>");
	} else {

		$tot = $data['barang_harga_jual']*$jumlah;
		
		$sql = "INSERT INTO transaksi_detail_temp(transaksi_detail_temp_barang_id,transaksi_detail_temp_harga,transaksi_detail_temp_jumlah,transaksi_detail_temp_total,transaksi_detail_temp_keterangan,transaksi_detail_temp_user)values('$id','$data[barang_harga_jual]','$jumlah','$tot','$ket','$user')";
		//$array_datas[] = ["Ok"];

		mysqli_query($con,$sql);

		$query="SELECT * from transaksi_detail_temp, barang, kategori where transaksi_detail_temp_barang_id=barang_id and kategori_id=barang_kategori and transaksi_detail_temp_user='$user' ORDER BY transaksi_detail_temp_id DESC LIMIT 1";
		$result = mysqli_query($con,$query);

		while($baris = mysqli_fetch_assoc($result))
		{
		  $array_datas['item']=$baris;
		}
	}
	
	echo json_encode($array_datas);
	
} elseif($_GET['ket']=='update-produk'){


	$id = $_POST['ip-id'];
	$nama = $_POST['ip-nama'];
	$kategori = $_POST['ip-kategori'];
	$beli = $_POST['ip-beli'];
	$jual = $_POST['ip-jual'];
	$jualonline = $_POST['ip-jual-online'];
	if ($_POST['ip-setstok']=='') {
		$setstok = '0';
	} else {
		$setstok = $_POST['ip-setstok'];
	}
	$stok = $_POST['ip-stok'];
	$batas = $_POST['ip-batas-stok'];
	if ($_POST['ip-disable']=='') {
		$disable = '0';
	} else {
		$disable = $_POST['ip-disable'];
	}
	$user = $_SESSION['login_user'];

	$sql1="SELECT * from barang where barang_id='$id'";
	$query1=mysqli_query($con,$sql1);
	$data=mysqli_fetch_assoc($query1);

	mysqli_query($con,"INSERT INTO log_harga(barang_id,harga_beli_awal,harga_beli_baru,harga_jual_awal,harga_jual_baru,harga_jual_online_lama,harga_jual_online_baru,user,tanggal) VALUES ('$id','$data[barang_harga_beli]','$beli','$data[barang_harga_jual]','$jual','$data[barang_harga_jual_online]','$jualonline','$user','$tgl')");

	if (isset($_FILES['inputfile'])) {
		$logo = $_FILES['inputfile']['name'];

		$file_tmp = $_FILES['inputfile']['tmp_name'];
		move_uploaded_file($file_tmp, '../../assets/img/produk/'.$logo);

		$sql="UPDATE barang set barang_nama='$nama',barang_kategori='$kategori',barang_harga_beli='$beli',barang_harga_jual='$jual',barang_harga_jual_online='$jualonline',barang_set_stok='$setstok',barang_stok='$stok',barang_batas_stok='$batas',barang_disable='$disable', barang_image='$logo' where barang_id='$id'";

		echo $logo;
	} else {
		$sql="UPDATE barang set barang_nama='$nama',barang_kategori='$kategori',barang_harga_beli='$beli',barang_harga_jual='$jual',barang_harga_jual_online='$jualonline',barang_set_stok='$setstok',barang_stok='$stok',barang_batas_stok='$batas',barang_disable='$disable' where barang_id='$id'";

	}

	mysqli_query($con,$sql);
	
} elseif($_GET['ket']=='remove-produk'){
	$array_datas = array();
	
	$id = $_POST['produk_id'];
	$sql="DELETE from barang where barang_id='$id'";
	if (!mysqli_query($con,$sql)) {
		$array_datas[] = ["gagal"];
	}else{
		$array_datas[] = ["ok"];
	}
	echo json_encode($array_datas);
	
}

?>  