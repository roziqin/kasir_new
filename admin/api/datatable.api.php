<?php
include '../../config/database.php';
$search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
$limit = $_POST['length']; // Ambil data limit per page
$start = $_POST['start']; // Ambil data start

if ($_GET['ket']=='produk') {

	$sql = mysqli_query($con, "SELECT barang_id FROM barang, kategori where barang_kategori=kategori_id"); // Query untuk menghitung seluruh data siswa
	$sql_count = mysqli_num_rows($sql); // Hitung data yg ada pada query $sql
	$query = "SELECT * FROM barang, kategori where barang_kategori=kategori_id and (barang_nama LIKE '%".$search."%' OR kategori_nama LIKE '%".$search."%')";

} elseif ($_GET['ket']=='kategori') {

	$sql = mysqli_query($con, "SELECT kategori_id FROM kategori"); // Query untuk menghitung seluruh data siswa
	$sql_count = mysqli_num_rows($sql); // Hitung data yg ada pada query $sql
	$query = "SELECT * FROM kategori where (kategori_nama LIKE '%".$search."%')";
	
} elseif ($_GET['ket']=='user') {

	$sql = mysqli_query($con, "SELECT id FROM users, roles where role=roles_id"); // Query untuk menghitung seluruh data siswa
	$sql_count = mysqli_num_rows($sql); // Hitung data yg ada pada query $sql
	$query = "SELECT * FROM users, roles where role=roles_id and (name LIKE '%".$search."%')";
	
} elseif ($_GET['ket']=='stok') {

	$sql = mysqli_query($con, "SELECT barang_id FROM barang"); // Query untuk menghitung seluruh data siswa
	$sql_count = mysqli_num_rows($sql); // Hitung data yg ada pada query $sql
	$query = "SELECT * FROM barang where (barang_nama LIKE '%".$search."%')";
	
}

$order_field = $_POST['order'][0]['column']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
$order_ascdesc = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
$order = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;
$sql_data = mysqli_query($con, $query.$order." LIMIT ".$limit." OFFSET ".$start); // Query untuk data yang akan di tampilkan
$sql_filter = mysqli_query($con, $query); // Query untuk count jumlah data sesuai dengan filter pada textbox pencarian
$sql_filter_count = mysqli_num_rows($sql_filter); // Hitung data yg ada pada query $sql_filter
$data = mysqli_fetch_all($sql_data, MYSQLI_ASSOC); // Untuk mengambil data hasil query menjadi array
$callback = array(
    'draw'=>$_POST['draw'], // Ini dari datatablenya
    'recordsTotal'=>$sql_count,
    'recordsFiltered'=>$sql_filter_count,
    'data'=>$data
);
header('Content-Type: application/json');
echo json_encode($callback); // Convert array $callback ke j