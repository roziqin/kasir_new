<?php 
session_start();
include '../../config/database.php';
date_default_timezone_set('Asia/jakarta');
$tgl=date('Y-m-j');

if($_GET['ket']=='update-setting'){

	$id = 1;
	$nama = $_POST['ip-nama'];
	$alamat = $_POST['ip-alamat'];
	$telp = $_POST['ip-telp'];
	$service = $_POST['ip-service'];
	$pajak = $_POST['ip-pajak'];
	$pajakonline = $_POST['ip-pajakonline'];

	if (isset($_FILES['inputfile'])) {
		$logo = $_FILES['inputfile']['name'];

		$file_tmp = $_FILES['inputfile']['tmp_name'];
		move_uploaded_file($file_tmp, '../../assets/img/'.$logo);


		$sql="UPDATE pengaturan_perusahaan set pengaturan_nama='$nama',pengaturan_alamat='$alamat',pengaturan_telp='$telp',pengaturan_service='$service',pengaturan_pajak='$pajak',pengaturan_pajak_online='$pajakonline',pengaturan_logo='$logo' where pengaturan_id='$id'";

		echo $logo;
	} else {
		$sql="UPDATE pengaturan_perusahaan set pengaturan_nama='$nama',pengaturan_alamat='$alamat',pengaturan_telp='$telp',pengaturan_service='$service',pengaturan_pajak='$pajak',pengaturan_pajak_online='$pajakonline' where pengaturan_id='$id'";
		echo "noupload";

	}


	
	mysqli_query($con,$sql);

		

} 

?>  