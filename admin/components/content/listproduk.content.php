<?php include '../modals/produk.modal.php'; ?>

    <button class="btn btn-primary" data-toggle="modal" data-target="#modaltambahproduk">Tambah Produk <i class="fas fa-box-open ml-1"></i></button>
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>nama</th>
                <th>kategori</th>
                <th>stok</th>
                <th>harga beli</th>
                <th>harga jual</th>
                <th>harga online</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>nama</th>
                <th>kategori</th>
                <th>stok</th>
                <th>harga beli</th>
                <th>harga jual</th>
                <th>harga online</th>
                <th></th>
            </tr>
        </tfoot>
    </table>



    <script type="text/javascript">
      
    $(document).ready(function() {
        $('#example').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": 
            {
                "url": "api/datatable.api.php?ket=produk", // URL file untuk proses select datanya
                "type": "POST"
            },
            "deferRender": true,
            "columns": [
                { "data": "barang_nama" },
                { "data": "kategori_nama" },
                { "data": "barang_stok" },
                { "data": "barang_harga_beli" },
                { "data": "barang_harga_jual" },
                { "data": "barang_harga_jual_online" },

                { "width": "150px", "render": function(data, type, full){
                   return '<a class="btn-floating btn-sm btn-default mr-2 btn-edit" data-toggle="modal" data-target="#modaleditproduk" data-id="' + full['barang_id'] + '" title="Edit"><i class="fas fa-pen"></i></a> <a class="btn-floating btn-sm btn-danger btn-remove" data-id="' + full['barang_id'] + '" title="Delete"><i class="fas fa-trash"></i></a>';
                }
                },
            ],
            "initComplete": function( settings, json ) {
              $('.btn-edit').on('click',function(){
                  var produk_id = $(this).data('id');
                  console.log(produk_id)
                  $.ajax({
                      type:'POST',
                      url:'api/view.api.php?func=editproduk',
                      dataType: "json",
                      data:{produk_id:produk_id},
                      success:function(data){
                          $("#modaleditproduk label").addClass("active");
                          $("#modaleditproduk #defaultForm-id").val(produk_id);
                          $("#modaleditproduk #defaultForm-nama").val(data[0].barang_nama);
                          $("#modaleditproduk #defaultForm-kategori").val(data[0].barang_kategori);
                          $("#modaleditproduk #defaultForm-beli").val(data[0].barang_harga_beli);
                          $("#modaleditproduk #defaultForm-jual").val(data[0].barang_harga_jual);
                          $("#modaleditproduk #defaultForm-jual-online").val(data[0].barang_harga_jual_online);
                          $("#modaleditproduk #defaultForm-setstok").val(data[0].barang_set_stok);
                          $("#modaleditproduk #defaultForm-stok").val(data[0].barang_stok);
                          $("#modaleditproduk #defaultForm-batas-stok").val(data[0].barang_batas_stok);
                          $("#modaleditproduk #defaultForm-disable").val(data[0].barang_disable);

                      }
                  });
                  
              });
            },
            "drawCallback": function( settings ) {
              $('.btn-edit').on('click',function(){
                  var produk_id = $(this).data('id');
                  console.log(produk_id)
                  $.ajax({
                      type:'POST',
                      url:'api/view.api.php?func=editproduk',
                      dataType: "json",
                      data:{produk_id:produk_id},
                      success:function(data){
                          $("#modaleditproduk label").addClass("active");
                          $("#modaleditproduk #defaultForm-id").val(produk_id);
                          $("#modaleditproduk #defaultForm-nama").val(data[0].barang_nama);
                          $("#modaleditproduk #defaultForm-kategori").val(data[0].barang_kategori);
                          $("#modaleditproduk #defaultForm-beli").val(data[0].barang_harga_beli);
                          $("#modaleditproduk #defaultForm-jual").val(data[0].barang_harga_jual);
                          $("#modaleditproduk #defaultForm-jual-online").val(data[0].barang_harga_jual_online);
                          $("#modaleditproduk #defaultForm-setstok").val(data[0].barang_set_stok);
                          $("#modaleditproduk #defaultForm-stok").val(data[0].barang_stok);
                          $("#modaleditproduk #defaultForm-batas-stok").val(data[0].barang_batas_stok);
                          $("#modaleditproduk #defaultForm-disable").val(data[0].barang_disable);

                      }
                  });
              });

              $('.btn-remove').on('click', function(){
                  var produk_id = $(this).data('id');
                  $.confirm({
                      title: 'Konfirmasi Hapus Produk',
                      content: 'Apakah yakin menghapus produk ini?',
                      buttons: {
                          confirm: {
                              text: 'Ya',
                              btnClass: 'col-md-6 btn blue-gradient',
                              action: function(){
                                  console.log(produk_id);
                                  
                                  $.ajax({
                                    type: 'POST',
                                    url: "controllers/produk.ctrl.php?ket=remove-produk",
                                    dataType: "json",
                                    data:{produk_id:produk_id},
                                    success: function(data) {
                                      if (data[0]=="ok") {
                                        $('#example').DataTable().ajax.reload();
                                      } else {
                                        alert('Produk gagal dihapus')
                                      }
                                    }
                                  });
                                  
                              }
                          },
                          cancel: {
                              text: 'Tidak',
                              btnClass: 'col-md-6 btn ripe-malinka-gradient text-white',
                              action: function(){
                                  console.log("tidak")
                                 
                              }
                              
                          }
                      }
                  });
              });
              
            }
        } );

      
    } );
    </script>