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
        <img src="../../assets/img/<?php echo $datapengaturan['pengaturan_logo']; ?>" width="120" style="margin: 0 auto 10px;
          display: block;">
        <table  width="100%" border="0">
          <tr>
            <th colspan="4"><?php echo $datapengaturan['pengaturan_alamat']; ?></th>
          </tr>
          <tr>
            <th colspan="4"><?php echo $datapengaturan['pengaturan_telp']; ?></th>
          </tr>
          <tr>
            <th colspan="4"><?php echo $tgl." - ".$wkt; ?></th>
          </tr>
          <tr>
            <td width="60">Pelanggan</td>
            <td width="10">:</td>
            <td ><?php echo $pelanggan." ".$ketorder;?></td>
            <td  align="right">Nota - <?php echo $t; ?></td>
          </tr>
          <tr>
            <td>No Meja</td>
            <td>:</td>
            <td><?php echo $meja;?></td>
            <td  align="right">Lt-<?php echo $lantai; ?></td>
          </tr>
          <tr>
            <td colspan="4"><hr></td>
          </tr>
        </table>
        <table width="100%" border="0">
          <tr>
            <td>Menu</td>
            <td width="24" align="center">Jml.</td>
            <td width="60" align="center">Harga</td>
            <td width="60" align="center">Subtotal</td>
          </tr>
           <?php
            $no=1;
            $tran_tot = 0;
            $sql="SELECT * from transaksi,transaksi_detail,barang WHERE transaksi_nota=transaksi_detail_nota and transaksi_detail_barang_id=barang_id and transaksi_nota='$t'";
            $query=mysqli_query($con, $sql);
            while ($data=mysqli_fetch_assoc($query)) {
              
              $ket = '';
        		  if ($data["transaksi_detail_keterangan"]!='') {
                $ket = "(".$data["transaksi_detail_keterangan"].")";
              }
              $barang = $data['barang_nama'];
              $jumlah = $data['transaksi_detail_jumlah'];
              $harga = $data['transaksi_detail_harga'];
              $tot = $jumlah*$harga;
              $tran_tot += $tot;

              echo "

              <tr>
                <td>".$barang." ".$ket."</td>
                <td align='center'>".$jumlah."</td>
                <td align='right'>".format_rupiah($harga)."</td>
                <td align='right'>".format_rupiah($tot)."</td>
              </tr>
              ";
              
              $no=$no+1;
            }      
          ?>
          <tr>
            <td colspan="4"><hr color="black"></td>
          </tr>
          <tr>
            <th align="left" scope="row" colspan="2">Subtotal </th>
            <td align="right">: Rp.</td>
            <td align="right"><?php echo format_rupiah($tran_total+$tran_diskon-$tax-$servicetax); ?></td>
          </tr>
          <?php if ($tran_diskon!=0) { ?>
          <tr>
            <th align="left" scope="row" colspan="2">Diskon</th>
            <td align="right">: Rp.</td>
            <td align="right"><?php echo format_rupiah($tran_diskon); ?></td>
          </tr>
          <?php } ?>
          <tr>
            <th align="left" scope="row" colspan="2">Tax </th>
            <td align="right">: Rp.</td>
            <td align="right"><?php echo format_rupiah($tax); ?></td>
          </tr>
          <?php if ($servicetax!=0) { ?>
          <tr>
            <th align="left" scope="row" colspan="2">Tax Service</th>
            <td align="right">: Rp.</td>
            <td align="right"><?php echo format_rupiah($servicetax); ?></td>
          </tr>
          <?php } ?>
          <tr>
            <th align="left" scope="row" colspan="2">Total</th>
            <td align="right">: Rp.</td>
            <td align="right"><?php echo format_rupiah($tran_total) ; ?></td>
          </tr>
          <tr>
            <th align="left" scope="row" colspan="2">Bayar</th>
            <td align="right">: Rp.</td>
            <td align="right"><?php echo format_rupiah($bayar) ; ?></td>
          </tr>
          <tr>
            <th align="left" scope="row" colspan="2">Kembalian</th>
            <td align="right">: Rp.</td>
            <td align="right"><?php echo format_rupiah($kembalian) ; ?></td>
          </tr>
          <tr>
            <th align="left" scope="row" colspan="2">Pembayaran</th>
            <td align="left">&nbsp;</td>
            <td align="right"><?php echo $type ; ?></td>
          </tr>
          <tr>
            <th colspan="4">TERIMA KASIH<br>Let's order by Go-Food & get more value!</th>
          </tr>
          </tr>
          <tr>
            <th colspan="4"><br>&nbsp;<br></th>
          </tr>
        </table>
      </div>
  </body>
</html>
