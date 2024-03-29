<!-------------- Modal Transaksi -------------->

<div class="modal fade" id="modaltransaksi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h2 class="modal-title w-100 font-weight-bold" id="totaltransaksi"></h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body mx-3">
        <div class="col-md-12 text-center paytype mb-3">
          <button type="button" class="btn btn-white waves-effect mr-2 text-info paytype select" data-id="cash" id="cash" disabled="true"><i class="fas fa-money-bill"></i>Cash</button>
          <button type="button" class="btn btn-white waves-effect mr-2 text-info paytype" data-id="debet" id="debet"><i class="far fa-credit-card"></i>Debet</button>
          <button type="button" class="btn btn-white waves-effect text-info paytype" data-id="goresto" id="goresto"><i class="fab fa-google-wallet"></i>GoResto</button>
        </div>
        <input type="hidden" id="defaultForm-paytype" name="ip-paytype" value="cash">
        <input type="hidden" id="defaultForm-totalmodal" name="ip-total">
        <div class="md-form mb-0">
          <input type="text" id="defaultForm-nama" class="form-control validate mb-3" name="ip-nama">
          <label for="defaultForm-nama">Nama Pelanggan</label>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="md-form mb-0">
              <input type="text" id="defaultForm-meja" class="form-control validate mb-3" name="ip-meja">
              <label for="defaultForm-meja">No Meja</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="md-form mb-0">
              <input type="text" id="defaultForm-lantai" class="form-control validate mb-3" name="ip-lantai">
              <label for="defaultForm-lantai">Lantai</label>
            </div>
          </div>
        </div>
        <div class="md-form mb-0">
          <input type="text" id="price" class="form-control validate mb-1" name="ip-bayar">
          <label for="price">Bayar</label>
        </div>
        
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button class="btn btn-primary" id="submit-transaksi" data-dismiss="modal" aria-label="Close">Proses</button>
      </div>
    </div>
  </div>
</div>

<!-------------- End modal transaksi -------------->





  <script type="text/javascript">
      
      $('.mdb-select').materialSelect();
      $('#price').priceFormat({ prefix: '', centsSeparator: ',', thousandsSeparator: '.', centsLimit: 0 });

      $("#submit-transaksi").click(function(e){
        e.preventDefault();
        var data = new FormData();
        data.append('ip-nama', $("#defaultForm-nama").val());
        data.append('ip-meja', $("#defaultForm-meja").val());
        data.append('ip-lantai', $("#defaultForm-lantai").val());
        data.append('ip-total', $("#defaultForm-total").val());
        data.append('ip-paytype', $("#defaultForm-paytype").val());
        data.append('ip-ordertype', $("#defaultForm-ordertype").val());
        data.append('ip-jenisdiskon', $("#defaultForm-jenisdiskon").val());
        data.append('ip-jumlahdiskon', $("#defaultForm-jumlahdiskon").val());
        data.append('ip-tax', $("#defaultForm-tax").val());
        data.append('ip-servicetax', $("#defaultForm-servicetax").val());

        var total = parseInt($("#defaultForm-total").val());
        var bayar = '';
        var text_line = $("#price").val().split(".");
        var length = text_line.length;

        if (length==1) {
          bayar=text_line[0];

        } else if (length==2) {
          bayar=text_line[0]+""+text_line[1];

        } else if (length==3) {
          bayar=text_line[0]+""+text_line[1]+""+text_line[2];

        } else if (length==4) {
          bayar=text_line[0]+""+text_line[1]+""+text_line[2]+""+text_line[3];

        } else if (length==5) {
          bayar=text_line[0]+""+text_line[1]+""+text_line[2]+""+text_line[3]+""+text_line[4];

        }

        data.append('ip-bayar', bayar);
        console.log(data);

        bayar = parseInt(bayar);

        if (bayar < total) {
            alert("Angka yang dibayarkan Kurang");
        } else {

          $.ajax({
            type: 'POST',
            url: "controllers/transaksi.ctrl.php?ket=prosestransaksi",
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
              console.log(data)
              $('.container__load').load('components/content/transaksi.content.php?kond=kembalian');
              $('#listitem table').empty();
              $('#subtotal').empty();
              $('#subtotal').append('Rp. 0');
              $('#pajak').empty();
              $('#pajak').append('Rp. 0');
              $('#total').empty();
              $('#total').append('Rp. 0');
              $('.text-jenisdiskon').empty();
              $('.text-jumlahdiskon').empty();
              $('#bayar').attr("disabled","true");
              $('.ordertype').removeAttr("disabled");
              $('.paytype').removeAttr("disabled");
              $('#defaultForm-ordertype').val('');
              $('#defaultForm-nama').val('');
              $('#defaultForm-meja').val('');
              $('#defaultForm-lantai').val('');
              $('#price').val('');
            }
          });
          
        }
      }); 
    
  </script> 