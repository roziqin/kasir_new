<?php $ket = $_GET['ket']; ?>
  <input type="hidden" name="ketlog" id="ketlog" value="<?php echo $ket; ?>">
  <div class="row justify-content-md-center">
    <div class="col-md-10">
      <div class="row">
        <div class="col-md-10">
          <div class="row form-date">
            <div class="col-md-6">
              <div class="md-form">
                <input placeholder="Start date" type="text" id="defaultForm-startdate" class="form-control datepicker">
              </div>
            </div>
            <div class="col-md-6">
              <div class="md-form">
                <input placeholder="End date" type="text" id="defaultForm-enddate" class="form-control datepicker">
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
            <div class="md-form">
              <button class="btn btn-primary btn-proses">Proses</button>
            </div>
        </div>
      </div>  
      <div class="row fadeInLeft slow animated">
        <div class="col-md-12">
          <?php
          if ($ket=='validasi') {
          ?>
            <table id="table-validasi" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>  
                        <th>tanggal</th>
                        <th>waktu</th>
                        <th>nama</th>
                        <th>uang fisik</th>
                        <th>cash</th>
                        <th>debet</th>
                        <th>online</th>
                        <th>total omset</th>
                    </tr>
                </thead>
            </table>

          <?php
          } elseif ($ket=='stok') {
          ?>
            <table id="table-stok" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>  
                        <th>tanggal</th>
                        <th>nama</th>
                        <th>stok awal</th>
                        <th>stok akhir</th>
                        <th>keterangan</th>
                        <th>user</th>
                    </tr>
                </thead>
            </table>

          <?php
          } elseif ($ket=='harga') {
          ?>
            <table id="table-harga" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr> 
                        <th>tanggal</th>
                        <th>nama</th>
                        <th>harga beli awal</th>
                        <th>harga beli baru</th>
                        <th>harga jual awal</th>
                        <th>harga jual baru</th>
                        <th>harga jual online awal</th>
                        <th>harga jual online baru</th>
                        <th>user</th>
                    </tr>
                </thead>
            </table>

          <?php
          } else {
          ?>
            <table id="table-login" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                      <tr>
                          <th>nama</th>
                          <th>tanggal login</th>
                          <th>tanggal logout</th>
                      </tr>
                  </thead>
              </table>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>


<script type="text/javascript">

  $(document).ready(function(){
    
    $('.datepicker').pickadate({
      weekdaysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
      showMonthsShort: true
    })
    
    function convertToRupiah(angka)
    {
      var rupiah = '';    
      var angkarev = angka.toString().split('').reverse().join('');
      for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
      return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
    }
    
    $('.btn-proses').on('click',function(){
      var ketlog = $('#ketlog').val();
      var start = $('#defaultForm-startdate').val();
      var end = $('#defaultForm-enddate').val();

      
      $.ajax({
            type:'POST',
            url:'api/view.api.php?func=logs',
            dataType: "json",
              data:{
                ketlog:ketlog,
                start:start,
                end:end
              },
            success:function(data){
              console.log("ok");
              if (ketlog=='validasi') {
                console.log("validasi");
                $('#table-validasi').DataTable().clear().destroy();
                $('#table-validasi').DataTable( {
                      paging: true,
                      searching: true,
                      ordering: false,
                      data: data,
                      deferRender: true,
                      columns: [
                          { data: 'validasi_tanggal' },
                          { data: 'validasi_waktu' },
                          { data: 'validasi_user_nama' },
                          { "render": function(data, type, full){
                             return formatRupiah(full['validasi_jumlah'].toString(), 'Rp. ');
                            }
                          },
                          { "render": function(data, type, full){
                             return formatRupiah(full['validasi_cash'].toString(), 'Rp. ');
                            }
                          },
                          { "render": function(data, type, full){
                             return formatRupiah(full['validasi_debet'].toString(), 'Rp. ');
                            }
                          },
                          { "render": function(data, type, full){
                             return formatRupiah(full['validasi_online'].toString(), 'Rp. ');
                            }
                          },
                          { "render": function(data, type, full){
                             return formatRupiah(full['validasi_omset'].toString(), 'Rp. ');
                            }
                          }
                      ]
                  });

              } else if (ketlog=='stok') {
                console.log("stok");
                $('#table-stok').DataTable().clear().destroy();
                $('#table-stok').DataTable( {
                    paging: false,
                    searching: false,
                    ordering: false,
                    data: data,
                    columns: [
                        { data: 'tanggal' },
                        { data: 'barang_nama' },
                        { data: 'stok_awal' },
                        { data: 'stok_jumlah' },
                        { data: 'alasan' },
                        { data: 'name' }
                    ]
                });
                
              } else if (ketlog=='harga') {
                console.log("harga");
                $('#table-harga').DataTable().clear().destroy();
                $('#table-harga').DataTable( {
                      paging: true,
                      searching: true,
                      ordering: false,
                      data: data,
                      deferRender: true,
                      columns: [
                          { data: 'tanggal' },
                          { data: 'barang_nama' },
                          { "render": function(data, type, full){
                             return formatRupiah(full['harga_beli_awal'].toString(), 'Rp. ');
                            }
                          },
                          { "render": function(data, type, full){
                             return formatRupiah(full['harga_beli_baru'].toString(), 'Rp. ');
                            }
                          },
                          { "render": function(data, type, full){
                             return formatRupiah(full['harga_jual_awal'].toString(), 'Rp. ');
                            }
                          },
                          { "render": function(data, type, full){
                             return formatRupiah(full['harga_jual_baru'].toString(), 'Rp. ');
                            }
                          },
                          { "render": function(data, type, full){
                             return formatRupiah(full['harga_jual_online_baru'].toString(), 'Rp. ');
                            }
                          },
                          { "render": function(data, type, full){
                             return formatRupiah(full['harga_jual_online_lama'].toString(), 'Rp. ');
                            }
                          },
                          { data: 'name' },
                      ]
                  });
              } else if (ketlog=='login') {
                console.log("login");
                $('#table-login').DataTable().clear().destroy();
                $('#table-login').DataTable( {
                    paging: false,
                    searching: false,
                    ordering: false,
                    data: data,
                    columns: [
                        { data: 'name' },
                        { data: 'login' },
                        { data: 'logout' }
                    ]
                });
              }

              console.log(data);
            }
        });
    });         
  });

</script>