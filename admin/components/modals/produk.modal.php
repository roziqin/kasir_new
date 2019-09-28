<?php $con = mysqli_connect("localhost","root","","kasir_new"); ?>

<!-------------- Modal tambah produk -------------->

<div class="modal fade" id="modaltambahproduk" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Tambah Produk</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
        <form method="post" class="form-produk">
          <div class="md-form mb-0">
            <input type="text" id="defaultForm-nama" class="form-control validate mb-3" name="ip-nama">
            <label for="defaultForm-nama">Nama Produk</label>
          </div>
          <div class="md-form mb-0">
              <select class="mdb-select md-form" name="ip-kategori">
                  <option value="" disabled selected>Pilih Kategori</option>
              <?php
                  $sql="SELECT * from kategori";
                  $result=mysqli_query($con,$sql);
                  while ($data1=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                    /*
                      if ($data['kategori_id']==$data1['kategori_id']) {
                          $select="selected";
                      } else {
                          $select="";
                      }
                      */
                      echo "<option value='$data1[kategori_id]'>$data1[kategori_nama]</option>";
                  }
              ?>
              </select>
          </div>
          <div class="md-form mb-0 mt-0">
            <input type="text" id="defaultForm-beli" class="form-control validate mb-3" name="ip-beli">
            <label for="defaultForm-beli">Harga Beli</label>
          </div>
          <div class="md-form mb-0 mt-0">
            <input type="text" id="defaultForm-jual" class="form-control validate mb-3" name="ip-jual">
            <label for="defaultForm-jual">Harga Jual</label>
          </div>
          <div class="md-form mb-0 mt-0">
            <input type="text" id="defaultForm-jual-online" class="form-control validate mb-3" name="ip-jual-online">
            <label for="defaultForm-jual-online">Harga Jual Online</label>
          </div>
          <div class="md-form mb-0 mt-0">
              <select class="mdb-select md-form" name="ip-setstok">
                  <option value="" disabled selected>Set Stok</option>
                  <option value="0">Tidak</option>
                  <option value="1">Ya</option>
              </select>
          </div>
          <div class="md-form mb-0 mt-0">
            <input type="text" id="defaultForm-stok" class="form-control validate mb-3" name="ip-stok">
            <label for="defaultForm-stok">Stok Awal</label>
          </div>
          <div class="md-form mb-0 mt-0">
            <input type="text" id="defaultForm-batas-stok" class="form-control validate mb-3" name="ip-batas-stok">
            <label for="defaultForm-batas-stok">Batas Stok</label>
          </div>

          <div class="md-form mb-0 mt-0">
              <select class="mdb-select md-form" name="ip-disable">
                  <option value="" disabled selected>Set Disable</option>
                  <option value="0">Tidak</option>
                  <option value="1">Ya</option>
              </select>
          </div>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-primary" id="submit-produk" data-dismiss="modal" aria-label="Close">Proses</button>
      </div>
    </div>
  </div>
</div>

<!-------------- End modal tambah produk -------------->



<!-------------- Modal edit produk -------------->
<div class="modal fade" id="modaleditproduk" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Edit Produk</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
        <form method="post" class="form-produk-update">
          <input type="hidden" id="defaultForm-id" name="ip-id">
          <div class="md-form mb-0">
            <input type="text" id="defaultForm-nama" class="form-control validate mb-3" name="ip-nama">
            <label for="defaultForm-nama">Nama Produk</label>
          </div>
          <div class="md-form mb-0">
              <select class="mdb-select md-form" id="defaultForm-kategori" name="ip-kategori">
                  <option value="" disabled selected>Pilih Kategori</option>
              <?php
                  $sql="SELECT * from kategori";
                  $result=mysqli_query($con,$sql);
                  while ($data1=mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                    /*
                      if ($data['kategori_id']==$data1['kategori_id']) {
                          $select="selected";
                      } else {
                          $select="";
                      }
                      */
                      echo "<option value='$data1[kategori_id]'>$data1[kategori_nama]</option>";
                  }
              ?>
              </select>
          </div>
          <div class="md-form mb-0 mt-0">
            <input type="text" id="defaultForm-beli" class="form-control validate mb-3" name="ip-beli">
            <label for="defaultForm-beli">Harga Beli</label>
          </div>
          <div class="md-form mb-0 mt-0">
            <input type="text" id="defaultForm-jual" class="form-control validate mb-3" name="ip-jual">
            <label for="defaultForm-jual">Harga Jual</label>
          </div>
          <div class="md-form mb-0 mt-0">
            <input type="text" id="defaultForm-jual-online" class="form-control validate mb-3" name="ip-jual-online">
            <label for="defaultForm-jual-online">Harga Jual Online</label>
          </div>
          <div class="md-form mb-0 mt-0">
              <select class="mdb-select md-form" id="defaultForm-setstok" name="ip-setstok">
                  <option value="" disabled selected>Set Stok</option>
                  <option value="0">Tidak</option>
                  <option value="1">Ya</option>
              </select>
          </div>
          <div class="md-form mb-0 mt-0">
            <input type="text" id="defaultForm-stok" class="form-control validate mb-3" name="ip-stok">
            <label for="defaultForm-stok">Stok Awal</label>
          </div>
          <div class="md-form mb-0 mt-0">
            <input type="text" id="defaultForm-batas-stok" class="form-control validate mb-3" name="ip-batas-stok">
            <label for="defaultForm-batas-stok">Batas Stok</label>
          </div>

          <div class="md-form mb-0 mt-0">
              <select class="mdb-select md-form" id="defaultForm-disable" name="ip-disable">
                  <option value="" disabled selected>Set Disable</option>
                  <option value="0">Tidak</option>
                  <option value="1">Ya</option>
              </select>
          </div>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-primary" id="update-produk" data-dismiss="modal" aria-label="Close">Proses</button>
      </div>
    </div>
  </div>
</div>


<!-------------- End modal edit produk -------------->



  <script type="text/javascript">
    $(document).ready(function(){

      $('.mdb-select').materialSelect();

      $("#submit-produk").click(function(){
        var data = $('#modaltambahproduk .form-produk').serialize();
        $.ajax({
          type: 'POST',
          url: "controllers/produk.ctrl.php?ket=submit-produk",
          data: data,
          success: function() {
            console.log("sukses")
            $('#example').DataTable().ajax.reload();
          }
        });
      });   


      $("#update-produk").click(function(){
        var data = $('.form-produk-update').serialize();
        $.ajax({
          type: 'POST',
          url: "controllers/produk.ctrl.php?ket=update-produk",
          data: data,
          success: function() {
            console.log("sukses edit")
            $('#example').DataTable().ajax.reload();
          }
        });
      }); 
      
    });
  </script> 