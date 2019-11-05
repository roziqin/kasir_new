<?php 
session_start();
include '../../config/database.php';
include "../../include/slug.php";
date_default_timezone_set('Asia/jakarta');
$tgl=date('Y-m-j');
$bln=date('Y-m');
$wkt=date('H:i:s');
$array_datas = array();

$user = $_SESSION['login_user'];
$order_type = $_SESSION['order_type'];
if($_GET['ket']=='tambahmenu'){

	$id = $_POST['barang_id'];	
	$jumlah = $_POST['jumlah'];
	$ket = $_POST['keterangan'];

	$sql="SELECT * from barang where barang_id='$id'";
	$query=mysqli_query($con,$sql);
	$data=mysqli_fetch_assoc($query);

	$sqla="SELECT sum(transaksi_detail_temp_jumlah) as transaksi_detail_temp_jumlah from transaksi_detail_temp where transaksi_detail_temp_barang_id='$id' and transaksi_detail_temp_user='$user'";
	$querya=mysqli_query($con,$sqla);
	$dataa=mysqli_fetch_assoc($querya);

	if($dataa!=null) {
		$jml=$dataa['transaksi_detail_temp_jumlah']+$jumlah;
	} else {
		$jml=$jumlah;
	}

	if ($data['barang_set_stok']==1 && $jml>$data['barang_stok']) {

		$array_datas['totalordertemp']=["Stok Kurang"];
		//echo ("<script>location.href='../home.php?menu=jumlah&id=$id&nama=$data[barang_nama]&ket=Stok Kurang&pelanggan='</script>");
	} else {
		if ($order_type=='online') {
			$harga = $data['barang_harga_jual_online'];
		} else {
			$harga = $data['barang_harga_jual'];
		}

		$hargabeli = $data['barang_harga_beli'];

		$tot = $harga*$jumlah;
		
		$sql = "INSERT INTO transaksi_detail_temp(transaksi_detail_temp_barang_id,transaksi_detail_temp_harga,transaksi_detail_temp_harga_beli,transaksi_detail_temp_jumlah,transaksi_detail_temp_total,transaksi_detail_temp_keterangan,transaksi_detail_temp_user)values('$id','$harga','$hargabeli','$jumlah','$tot','$ket','$user')";
		//$array_datas[] = ["Ok"];

		mysqli_query($con,$sql);

		$query="SELECT * from transaksi_detail_temp, barang, kategori where transaksi_detail_temp_barang_id=barang_id and kategori_id=barang_kategori and transaksi_detail_temp_user='$user' ORDER BY transaksi_detail_temp_id DESC LIMIT 1";
		$result = mysqli_query($con,$query);

		while($baris = mysqli_fetch_assoc($result))
		{
		  $array_datas['item']=$baris;
		}

		$total = 0;
		$query="SELECT * from transaksi_detail_temp, barang, kategori where transaksi_detail_temp_barang_id=barang_id and kategori_id=barang_kategori and transaksi_detail_temp_user='$user' ORDER BY transaksi_detail_temp_id";
		$result = mysqli_query($con,$query);
		while($data = mysqli_fetch_assoc($result)) {
			$total+=$data['transaksi_detail_temp_total'];
		}

		$array_datas['totalordertemp']=$total;
	}
	
	echo json_encode($array_datas);
	
} elseif($_GET['ket']=='batal'){

    $sql = "DELETE from transaksi_detail_temp where transaksi_detail_temp_user='$user'";
    mysqli_query($con,$sql);


		$_SESSION['order_type'] = "";
		$array_datas[] = ["ok"];
	echo json_encode($array_datas);

} elseif($_GET['ket']=='removeitem'){
	$id = $_POST['id'];	
    $sql = "DELETE from transaksi_detail_temp where transaksi_detail_temp_id='$id'";
    mysqli_query($con,$sql);

	$total = 0;
	$query="SELECT * from transaksi_detail_temp, barang, kategori where transaksi_detail_temp_barang_id=barang_id and kategori_id=barang_kategori and transaksi_detail_temp_user='$user' ORDER BY transaksi_detail_temp_id";
	$result = mysqli_query($con,$query);
	while($data = mysqli_fetch_assoc($result)) {
		$total+=$data['transaksi_detail_temp_total'];
	}

	$array_datas['totalordertemp']=$total;
	echo json_encode($array_datas);

} elseif($_GET['ket']=='plusminus'){
	$id = $_POST['id'];
	$idbarang = $_POST['idbarang'];
	$keterangan = $_POST['keterangan'];	

	if ($keterangan=='plus') {
		$jumlah = $_POST['jumlah']+1;
	} else {
		$jumlah = $_POST['jumlah']-1;
	}
	$jml=$jumlah;

	$sql="SELECT * from barang where barang_id='$idbarang'";
	$query=mysqli_query($con,$sql);
	$data=mysqli_fetch_assoc($query);

	$sql1="SELECT * from transaksi_detail_temp where transaksi_detail_temp_id='$id'";
	$query1=mysqli_query($con,$sql1);
	$data1=mysqli_fetch_assoc($query1);
	$harga = $data1['transaksi_detail_temp_harga'];
	$tot = $harga*$jumlah;

	$array_datas['jumlahordertemp']=1;

	if ($data['barang_set_stok']==1 && $jml>$data['barang_stok']) {

		$array_datas['totalordertemp']=["Stok Kurang"];

	} else {
		
		if ($keterangan=='minus' && $jumlah==0) {
			$sql="DELETE from transaksi_detail_temp where transaksi_detail_temp_id='$id'";
			$array_datas['jumlahordertemp']=0;
		} else {
			$sql="UPDATE transaksi_detail_temp set transaksi_detail_temp_jumlah='$jumlah',transaksi_detail_temp_total='$tot' where transaksi_detail_temp_id='$id'";
	
		}

		mysqli_query($con,$sql);

		$query="SELECT * from transaksi_detail_temp, barang, kategori where transaksi_detail_temp_barang_id=barang_id and kategori_id=barang_kategori and transaksi_detail_temp_id=$id ";
		$result = mysqli_query($con,$query);

		while($baris = mysqli_fetch_assoc($result)) {
		  $array_datas['item']=$baris;
		}

		$total = 0;
		$query="SELECT * from transaksi_detail_temp, barang, kategori where transaksi_detail_temp_barang_id=barang_id and kategori_id=barang_kategori and transaksi_detail_temp_user='$user' ORDER BY transaksi_detail_temp_id";
		$result = mysqli_query($con,$query);
		while($data = mysqli_fetch_assoc($result)) {
			$total+=$data['transaksi_detail_temp_total'];
		}

		$array_datas['totalordertemp']=$total;
	}
	
	echo json_encode($array_datas);

} elseif($_GET['ket']=='ordertype'){

	$id = $_POST['id'];	
	if ($id=='dinein') {
		$array_datas[] = ["dinein"];
		$_SESSION['order_type'] = "dinein";

	} elseif ($id=='takeaway') {
		$array_datas[] = ["takeaway"];
		$_SESSION['order_type'] = "takeaway";

	} elseif ($id=='online') {
		$array_datas[] = ["online"];
		$_SESSION['order_type'] = "online";

	}
	echo json_encode($array_datas);

} elseif($_GET['ket']=='prosestransaksi'){

	$qc= "SELECT MAX( transaksi_id ) AS total FROM transaksi where transaksi_tanggal='$tgl' and transaksi_user='$user' ";
    $rc=mysqli_query($con,$qc);
    $dc=mysqli_fetch_assoc($rc);
    if ($user<10) {
    	$nouser = "0".$user;
    } else {
    	$nouser = $user;
    }
    
    $dtot = ($dc['total']+1);
    if ($dtot<10) {
    	$dtot = "0".$dtot;
    }
	$no_not = date('ymd')."".$nouser."".$dtot;
	
	
	$nama = $_POST['ip-nama'];
	$meja = $_POST['ip-meja'];
	$lantai = $_POST['ip-lantai'];
	$total = $_POST['ip-total'];
	$paytype = $_POST['ip-paytype'];
	$ordertype = $_POST['ip-ordertype'];
	$jenisdiskon = $_POST['ip-jenisdiskon'];
	$jumlahdiskon = $_POST['ip-jumlahdiskon'];
	$tax = $_POST['ip-tax'];
	$servicetax = $_POST['ip-servicetax'];
	$bayar = $_POST['ip-bayar'];

	$kembalian = $bayar - $total;

	$sql = "INSERT INTO transaksi (transaksi_nota,transaksi_tanggal,transaksi_bulan,transaksi_waktu,transaksi_pelanggan,transaksi_no_meja,transaksi_lantai,transaksi_total,transaksi_diskon,transaksi_tax,transaksi_tax_service,transaksi_bayar,transaksi_type_bayar,transaksi_user,transaksi_ket,transaksi_ordertype) VALUES ('$no_not','$tgl','$bln','$wkt','$nama','$meja','$lantai','$total','$jumlahdiskon','$tax','0','$bayar','$paytype','$user','','$ordertype')" ;

	mysqli_query($con,$sql);

    $_SESSION['no-nota'] = $no_not;	

    $query="SELECT * from transaksi_detail_temp where transaksi_detail_temp_user='$user'";
	$result = mysqli_query($con,$query);
	while($baris = mysqli_fetch_assoc($result)) {

    	$barang = $baris['transaksi_detail_temp_barang_id'];
    	$harga = $baris['transaksi_detail_temp_harga'];
    	$hargabeli = $baris['transaksi_detail_temp_harga_beli'];
    	$jumlah = $baris['transaksi_detail_temp_jumlah'];
    	$total = $baris['transaksi_detail_temp_total'];
    	$ket = $baris['transaksi_detail_temp_keterangan'];
    	$status = $baris['transaksi_detail_temp_status'];
    	$user = $baris['transaksi_detail_temp_user'];


    	$a="INSERT into transaksi_detail(transaksi_detail_nota,transaksi_detail_barang_id,transaksi_detail_harga,transaksi_detail_harga_beli,transaksi_detail_jumlah,transaksi_detail_total,transaksi_detail_keterangan,transaksi_detail_status,transaksi_detail_user)values('$no_not','$barang','$harga','$hargabeli','$jumlah','$total','$ket','$status','$user')";
		mysqli_query($con,$a);

		//Select Stok Barang
		$sqlstok="SELECT * from barang where barang_id='$barang'";
        $resultstok=mysqli_query($con,$sqlstok);
	    $datastok=mysqli_fetch_assoc($resultstok);

        if($datastok['barang_set_stok']!=0) {
        	$jml_stok = $datastok['barang_stok'] - $jumlah;
        
	        $sqlupdatestok = "UPDATE barang SET barang_stok='$jml_stok' WHERE barang_id='$barang'";
	        mysqli_query($con,$sqlupdatestok);
        }
		
    }

	$sqlc1=mysqli_query($con, "SELECT COUNT(*) as snack from transaksi_detail_temp, barang, kategori where transaksi_detail_temp_barang_id=barang_id and barang_kategori=kategori_id and kategori_jenis='Snack' and transaksi_detail_temp_user='$user'");
    $datac1=mysqli_fetch_assoc($sqlc1);
    $snack=$datac1['snack'];

    $sqlc2=mysqli_query($con, "SELECT COUNT(*) as makanan from transaksi_detail_temp, barang, kategori where transaksi_detail_temp_barang_id=barang_id and barang_kategori=kategori_id and kategori_jenis='Makanan' and transaksi_detail_temp_user='$user'");
    $datac2=mysqli_fetch_assoc($sqlc2);
    $makanan=$datac2['makanan'];

    $sqlc3=mysqli_query($con, "SELECT COUNT(*) as minuman from transaksi_detail_temp, barang, kategori where transaksi_detail_temp_barang_id=barang_id and barang_kategori=kategori_id and kategori_jenis='Minuman' and transaksi_detail_temp_user='$user'");
    $datac3=mysqli_fetch_assoc($sqlc3);
    $minuman=$datac3['minuman'];



    $_SESSION['kembalian'] = $kembalian;
    $_SESSION['print'] = 'ya';
    $_SESSION['printmakanan'] = $makanan;
    $_SESSION['printminuman'] = $minuman;
    $_SESSION['printsnack'] = $snack;
    $_SESSION['order']='';
    $_SESSION['order_type'] = $ordertype;

    $sqldelete = "DELETE from transaksi_detail_temp where transaksi_detail_temp_user='$user'";
    mysqli_query($con,$sqldelete);

    $array_dataa = array('nota'=>$no_not);


	echo json_encode($array_dataa);
	

}  elseif($_GET['ket']=='tutupkasir'){

	$uangfisik = $_POST['uangfisik'];
	//$uangfisik = 200000;

	$sqlcek="SELECT count(*) as jml from validasi where validasi_user_id='$user' and validasi_tanggal='$tgl'";
	$querycek=mysqli_query($con,$sqlcek);
	$datacek=mysqli_fetch_assoc($querycek);

	if ($datacek['jml']!=0) {
		$array_datas['ket'] = "gagal";

	} else {

		$sql="SELECT * from users where id='$user'";
		$query=mysqli_query($con,$sql);
		$data=mysqli_fetch_assoc($query);
		$usernama=$data['name'];

		$sql1="SELECT count(transaksi_id) as jumlah, sum(transaksi_total) as total, sum(transaksi_diskon) as diskon from transaksi where transaksi_tanggal='$tgl' and transaksi_user = '$user' group by transaksi_tanggal";
		$query1=mysqli_query($con,$sql1);
		$data1=mysqli_fetch_assoc($query1);

		$sql2="SELECT count(transaksi_id) as jumlah, sum(transaksi_total) as debet, sum(transaksi_diskon) as diskon from transaksi where transaksi_tanggal='$tgl' and transaksi_user = '$user' and transaksi_type_bayar='debet' group by transaksi_tanggal";
		$query2=mysqli_query($con,$sql2);
		$data2=mysqli_fetch_assoc($query2);

		if ($data2['debet']=='') {
			$totdebet = 0;
		} else {
			$totdebet = $data2['debet'];
		}

		$sql3="SELECT count(transaksi_id) as jumlah, sum(transaksi_total) as cash, sum(transaksi_diskon) as diskon from transaksi where transaksi_tanggal='$tgl' and transaksi_user = '$user' and transaksi_type_bayar='cash' group by transaksi_tanggal";
		$query3=mysqli_query($con,$sql3);
		$data3=mysqli_fetch_assoc($query3);

		if ($data3['cash']=='') {
			$totcash = 0;
		} else {
			$totcash = $data3['cash'];
		}

		$sql4="SELECT count(transaksi_id) as jumlah, sum(transaksi_total) as goresto, sum(transaksi_diskon) as diskon from transaksi where transaksi_tanggal='$tgl' and transaksi_user = '$user' and transaksi_type_bayar='goresto' group by transaksi_tanggal";
		$query4=mysqli_query($con,$sql4);
		$data4=mysqli_fetch_assoc($query4);

		if ($data4['goresto']=='') {
			$totgoresto = 0;
		} else {
			$totgoresto = $data4['goresto'];
		}

		$a="INSERT into validasi(validasi_tanggal,validasi_waktu,validasi_user_id,validasi_user_nama,validasi_jumlah,validasi_cash,validasi_debet,validasi_online,validasi_omset)values('$tgl','$wkt','$user','$usernama','$uangfisik','$totcash','$totdebet','$totgoresto','$data1[total]')";
			mysqli_query($con,$a);


		$array_datas['omset'] = $data1['total'];
		$array_datas['debet'] = $totdebet;
		$array_datas['cash'] = $totcash;
		$array_datas['goresto'] = $totgoresto;
		$array_datas['uangfisik'] = $uangfisik;
		$array_datas['ket'] = "sukses";

	}
	echo json_encode($array_datas);
	
} elseif($_GET['ket']=='tes') {
	$sql="SELECT * from users where id='$user'";
		$query=mysqli_query($con,$sql);
		$data=mysqli_fetch_assoc($query);
		$usernama=$data['name'];

		$sql1="SELECT count(transaksi_id) as jumlah, sum(transaksi_total) as total, sum(transaksi_diskon) as diskon from transaksi where transaksi_tanggal='$tgl' and transaksi_user = '$user' group by transaksi_tanggal";
		$query1=mysqli_query($con,$sql1);
		$data1=mysqli_fetch_assoc($query1);

		$sql2="SELECT count(transaksi_id) as jumlah, sum(transaksi_total) as debet, sum(transaksi_diskon) as diskon from transaksi where transaksi_tanggal='$tgl' and transaksi_user = '$user' and transaksi_type_bayar='debet' group by transaksi_tanggal";
		$query2=mysqli_query($con,$sql2);
		$data2=mysqli_fetch_assoc($query2);

		$sql3="SELECT count(transaksi_id) as jumlah, sum(transaksi_total) as cash, sum(transaksi_diskon) as diskon from transaksi where transaksi_tanggal='$tgl' and transaksi_user = '$user' and transaksi_type_bayar='cash' group by transaksi_tanggal";
		$query3=mysqli_query($con,$sql3);
		$data3=mysqli_fetch_assoc($query3);

		$sql4="SELECT count(transaksi_id) as jumlah, sum(transaksi_total) as goresto, sum(transaksi_diskon) as diskon from transaksi where transaksi_tanggal='$tgl' and transaksi_user = '$user' and transaksi_type_bayar='goresto' group by transaksi_tanggal";
		$query4=mysqli_query($con,$sql4);
		$data4=mysqli_fetch_assoc($query4);

	echo $data1['total']." - " .$data2['debet']." - " .$data3['cash']." - " .$data4['goresto'];
}

?>  