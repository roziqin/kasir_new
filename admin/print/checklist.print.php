<?php
session_start();
  include '../../config/database.php';
  function format_rupiah($angka){
    $rupiah=number_format($angka,0,',','.');
    return $rupiah;
  }
   date_default_timezone_set('Asia/jakarta');
$tgl=date('Y-m-j');
$wkt=date('G:i:s');

$aid = $_SESSION['login_user'];
$aa = "SELECT * from users where id='$aid'";
$bb=mysqli_query($con,$aa);
$cc=mysqli_fetch_assoc($bb);

$id=$cc['name'];
$iduser=$cc['id'];
$t = $_GET['id'];
  
    $sqlpengaturan="SELECT * from pengaturan_perusahaan where  pengaturan_id='1' ";
    $querypengaturan=mysqli_query($con, $sqlpengaturan);
    $datapengaturan=mysqli_fetch_assoc($querypengaturan);
    $pajakresto = $datapengaturan['pengaturan_pajak'];
    $pajakservice = $datapengaturan['pengaturan_service'];
    $pajakonline = $datapengaturan['pengaturan_pajak_online'];
    $pajakpembulatan = $datapengaturan['pengaturan_pajak_pembulatan'];


    $sql="SELECT * from transaksi where transaksi_nota='$t' ";
    $query = mysqli_query($con,$sql);
    while($data = mysqli_fetch_assoc($query)) {

      $pelanggan=$data['transaksi_pelanggan'];
      $meja=$data['transaksi_no_meja'];
      $lantai=$data['transaksi_lantai'];
      $type=$data['transaksi_type_bayar'];
      $ordertype=$data['transaksi_ordertype'];
      $tanggal = $data['transaksi_tanggal'];
      $tran_diskon = $data['transaksi_diskon'];
      $tran_total = $data['transaksi_total'];
      $tax = $data['transaksi_tax'];
      $servicetax = $data['transaksi_tax_service'];
      $bayar = $data['transaksi_bayar'];
      $kembalian = $bayar - $tran_total;
    }

    $ketorder = "";
    if ($ordertype!='dinein') {
      $ketorder = "(".$ordertype.")";
    }

    $set = '';
    if ($_GET["set"]!="check") {
      $set = "and kategori_jenis='".$_GET["set"]."'";
    }
        

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <script type="text/javascript">
    window.setTimeout(function() {
      window.close();
    },1000)
  </script>
  </head>

  <body onLoad="window.print()" style="
    font-family: 'Merchant Copy'; font-size: 13px;">
      <div class="wrapper">
        <table  width="100%" border="0">
          <tr>
            <td width="80px"></td>
            <td width="50px"></td>
            <td style="text-align:right;"><?php echo $id; ?></td>
          </tr>
            <tr>
            <td></td>
            <td></td>
            <td style="text-align:right;"></td>
          </tr>
          <tr>
            <td>Check List</td>
            <td colspan=2 style="text-align:right;"><?php echo $t; ?></td>
          </tr>
          <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td style="text-align:right;"><?php echo $tanggal; ?></td>
          </tr>
          <tr>
            <td>Customer</td>
            <td>:</td>
            <td style="text-align:right;"><?php echo $pelanggan." ".$ketorder; ?></td>
          </tr>
           <tr>
            <td>Lantai</td>
            <td>:</td>
            <td style="text-align:right;"><?php echo $lantai; ?></td>
          </tr>
          <tr>
            <td>No. Meja</td>
            <td>:</td>
            <td style="text-align:right;"><?php echo $meja; ?></td>
          </tr>
          <tr>
            <td colspan="3" ><hr color="black"></td>
          </tr>
        </table>
        <table width="100%" border="0">
           <?php
            $sql="SELECT * from transaksi_detail,barang, kategori where barang_kategori=kategori_id and transaksi_detail_barang_id=barang_id ".$set." and transaksi_detail_nota='$t'";
            $query=mysqli_query($con, $sql);
            while ($data=mysqli_fetch_assoc($query)) {
              
              $namamenu=$data['barang_nama'];
              if ($data['transaksi_detail_keterangan']!='') {
                $ket_lain="  (".$data['transaksi_detail_keterangan'].")";
              } else {
                $ket_lain = '';
              }
              
              $tran_jumlah=$data['transaksi_detail_jumlah'];
              echo "
              <tr>
                <td>".$namamenu."".$ket_lain."</td>
            
                <td style='text-align:right;'>(".$tran_jumlah.")</td>
              </tr>
              <tr>
                <td colspan=2><hr></td>
             </tr>";
            }      
          ?>
        </table>
      </div>
  </body>
</html>
