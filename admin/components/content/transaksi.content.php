<?php 
$con = mysqli_connect("localhost","root","","kasir_new");
include '../../../include/format_rupiah.php';

$kond = $_GET['kond'];

if ($kond=='home' || $kond=='') { ?>
	<div class="classic-tabs">
		<ul class="nav tabs-white border-bottom" id="myClassicTab" role="tablist">
			<?php
                $n=0;
                $sql="SELECT * from kategori ORDER BY kategori_id";
                $query=mysqli_query($con, $sql);
                while ($data1=mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                    if ($n==0) {
                        $ket = 'active show';
                        $ket1 = 'true';
                        $ket2 = 'ml-0';
                    } else {
                        $ket = '';
                        $ket1 = 'false';
                        $ket2 = '';

                    }
                ?>
					<li class="nav-item <?php echo $ket2; ?>">
						<a class="nav-link  waves-light <?php echo $ket; ?>" id="profile-tab-classic" data-toggle="tab" href="#<?php echo $data1['kategori_slug']; ?>"
						role="tab" aria-controls="<?php echo $data1['kategori_slug']; ?>" aria-selected="<?php echo $ket1; ?>"><?php echo $data1['kategori_nama']; ?></a>
					</li>
                <?php
                $n++;

                }

            ?>
		</ul>
		<div class="tab-content" id="myClassicTabContent">
			<?php
                $n=0;
                $sql="SELECT * from kategori ORDER BY kategori_id";
                $query=mysqli_query($con, $sql);
                while ($data1=mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                    if ($n==0) {
                        $ket='show active';
                    } else {
                        $ket='';

                    }
                ?>
                	<div class="tab-pane fade <?php echo $ket; ?>" id="<?php echo $data1['kategori_slug']; ?>" role="tabpanel" aria-labelledby="<?php echo $data1['kategori_slug']; ?>-tab">
                        <div class="row">
                            <?php
                                $sqlbarang="SELECT * from barang where barang_kategori='$data1[kategori_id]'";
                                $querybarang=mysqli_query($con, $sqlbarang);
                                while ($databarang=mysqli_fetch_array($querybarang, MYSQLI_ASSOC)) {
                                	if ($databarang['barang_image']=='') {
                                		$image = 'default.jpg';
                                	} else {
                                		$image = $databarang['barang_image'];
                                	}
                                    if ($databarang['barang_set_stok']==0) {
                                        ?>
                                            <div class="col-3 mb-3">
                                                    <div class="card custom">
		                                            	<div class="box-button fadeIn faster animated">
		                                            		<button class="btn btn-primary tambahmenu" data-id="<?php echo $databarang['barang_id']; ?>"><i class="fas fa-magic mr-1"></i> Tambah</button>
															<button class="btn btn-default" id="pilihmenu">Pilih <i class="fas fa-magic ml-1"></i></button>
		                                            	</div>
                                                        <div class="card-body">
                                                        	<div class="image-menu" style="background-image: url(../assets/img/produk/<?php echo $image; ?>)"></div>
                                                        </div>
                                                        <div class="card-footer">
                                                            <strong class="card-title"><?php echo $databarang['barang_nama']; ?></strong>
                                                            Rp. <?php echo format_rupiah($databarang['barang_harga_jual']); ?>
                                                        </div>
                                                    </div>
                                            </div>


                                        <?php
                                        
                                    } else {
                                        if ($databarang['barang_stok']!=0) {
                                            if ($databarang['barang_stok']<$databarang['barang_batas_stok']) {
                                                $stok_status="warning";
                                            } else {
                                                $stok_status="";
                                            }
                                            
                                            ?>
                                                <div class="col-3 mb-3">
                                                    <div class="card custom <?php echo $stok_status; ?>">
		                                            	<div class="box-button fadeIn faster animated">
		                                            		<button class="btn btn-primary tambahmenu" data-id="<?php echo $databarang['barang_id']; ?>"><i class="fas fa-magic mr-1"></i> Tambah</button>
															<button class="btn btn-default" id="pilihmenu">Pilih <i class="fas fa-magic ml-1"></i></button>
		                                            	</div>
                                                        <div class="card-body">
                                                        	<div class="image-menu" style="background-image: url(../assets/img/produk/<?php echo $image; ?>)"></div>
                                                        </div>
                                                        <div class="card-footer">
                                                            <strong class="card-title"><?php echo $databarang['barang_nama']; ?></strong>
                                                        	Rp. <?php echo format_rupiah($databarang['barang_harga_jual']); ?><br>
                                                            <span style="color: red;">Stok: <?php echo $databarang['barang_stok']; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        } else {
                                            ?>
                                                <div class="col-3">
                                                    <div class="card custom grey-text">
                                                        <div class="card-body">
                                                        	<div class="image-menu" style="background-image: url(../assets/img/produk/<?php echo $image; ?>)"></div>
                                                        </div>
                                                        <div class="card-footer">
                                                            <strong class="card-title"><?php echo $databarang['barang_nama']; ?></strong>
                                                            Rp. <?php echo format_rupiah($databarang['barang_harga_jual']); ?><br>
                                                            <span style="color: red;">Stok: Habis</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                        
                                    }    
                                }
                            ?>
                        </div>
                    </div>
                    
                <?php
                $n++;
                }

            ?>
		</div>

	</div>

<?php if ($kond=='home') { ?>
    <script type="text/javascript">
		$.ajax({
	        type:'POST',
	        url:'api/view.api.php?func=list-transaksi-temp',
	        dataType: "json",
	        success:function(data){
	        	$('#listitem table').empty();
	        	$('#subtotal').empty();
	            var content = "";
	            var subtotal = 0;
				for (var i in data) {
				    content += '<tr><td>'+data[i].barang_nama+'</td><td>'+data[i].transaksi_detail_temp_jumlah+'</td><td>'+formatRupiah(data[i].transaksi_detail_temp_total, 'Rp. ')+'</td></tr>';
				    subtotal += parseInt(data[i].transaksi_detail_temp_total);
				}

				$('#subtotal').append(formatRupiah(subtotal.toString(), 'Rp. '));
				$('#listitem table').append(content);
	        }
	    });

	</script>

<?php } ?>

    <script type="text/javascript">

    	$('.tambahmenu').on('click',function(){
        	var barang_id = $(this).data('id');

            console.log(barang_id)
            
            $.ajax({
                type:'POST',
		        url: "controllers/transaksi.ctrl.php?ket=tambahmenu",
                dataType: "json",
                data:{barang_id:barang_id},
                success:function(data){
                	console.log("sukses");
					$('#carimenu').val('');
                	
		            var content = '<tr class="fadeInLeft animated"><td>'+data.item.barang_nama+'</td><td>'+data.item.transaksi_detail_temp_jumlah+'</td><td>'+formatRupiah(data.item.transaksi_detail_temp_total, 'Rp. ')+'</td></tr>';

					$('#listitem table').append(content);
					$('.container__load').load('components/content/transaksi.content.php?kond=');

                	console.log(data);
                	console.log(data.item);
                }
            });
            
              
		});
    </script>
<?php } elseif ($kond=='search') { ?>
	
    <div class="row p-3">
    	<div class="col-md-12 mb-2"><h1 class="secondary-heading mb-3 float-left">Hasil pencarian "<?php echo $_GET['q']; ?>"</h1> <button class="btn btn-danger btn-clear-search float-right" >Reset Pencarian <i class="fas fa-times ml-1"></i></button></div>
    	<div class="search-result">
        <?php
            $sqlbarang="SELECT * from barang where barang_nama LIKE '%$_GET[q]%'";
            $querybarang=mysqli_query($con, $sqlbarang);
            while ($databarang=mysqli_fetch_array($querybarang, MYSQLI_ASSOC)) {
            	if ($databarang['barang_image']=='') {
            		$image = 'default.jpg';
            	} else {
            		$image = $databarang['barang_image'];
            	}
                if ($databarang['barang_set_stok']==0) {
                    ?>
                        <div class="col-3 mb-3">
                            <div class="card custom">
                            	<div class="box-button fadeIn faster animated">
                            		<button class="btn btn-primary tambahmenu" data-id="<?php echo $databarang['barang_id']; ?>"><i class="fas fa-magic mr-1"></i> Tambah</button>
									<button class="btn btn-default" id="pilihmenu">Pilih <i class="fas fa-magic ml-1"></i></button>
                            	</div>
                                <div class="card-body">
                                	<div class="image-menu" style="background-image: url(../assets/img/produk/<?php echo $image; ?>)"></div>
                                </div>
                                <div class="card-footer">
                                    <strong class="card-title"><?php echo $databarang['barang_nama']; ?></strong>
                                    Rp. <?php echo format_rupiah($databarang['barang_harga_jual']); ?>
                                </div>
                            </div>
                        </div>


                    <?php
                    
                } else {
                    if ($databarang['barang_stok']!=0) {
                        if ($databarang['barang_stok']<$databarang['barang_batas_stok']) {
                            $stok_status="warning";
                        } else {
                            $stok_status="";
                        }
                        
                        ?>
                            <div class="col-3 mb-3">
                                <div class="card custom <?php echo $stok_status; ?>">
                                	<div class="box-button fadeIn faster animated">
                                		<button class="btn btn-primary tambahmenu" data-id="<?php echo $databarang['barang_id']; ?>"><i class="fas fa-magic mr-1"></i> Tambah</button>
										<button class="btn btn-default" id="pilihmenu">Pilih <i class="fas fa-magic ml-1"></i></button>
                                	</div>
                                    <div class="card-body">
                                    	<div class="image-menu" style="background-image: url(../assets/img/produk/<?php echo $image; ?>)"></div>
                                    </div>
                                    <div class="card-footer">
                                        <strong class="card-title"><?php echo $databarang['barang_nama']; ?></strong>
                                    	Rp. <?php echo format_rupiah($databarang['barang_harga_jual']); ?><br>
                                        <span style="color: red;">Stok: <?php echo $databarang['barang_stok']; ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php
                    } else {
                        ?>
                            <div class="col-3 mb-3">
	                            <div class="card custom grey-text">
	                                <div class="card-body">
	                                	<div class="image-menu" style="background-image: url(../assets/img/produk/<?php echo $image; ?>)"></div>
	                                </div>
	                                <div class="card-footer">
	                                    <strong class="card-title"><?php echo $databarang['barang_nama']; ?></strong>
	                                    Rp. <?php echo format_rupiah($databarang['barang_harga_jual']); ?><br>
	                                    <span style="color: red;">Stok: Habis</span>
	                                </div>
	                            </div>
	                        </div>
                        <?php
                    }
                    
                }    
            }
        ?>
	    </div>
    </div>
    <script type="text/javascript">
    	$('.btn-clear-search').on('click',function(){
			$('#carimenu').val('');
			$('.container__load').load('components/content/transaksi.content.php?kond=home');

		});

    	$('.tambahmenu').on('click',function(){
        	var barang_id = $(this).data('id');

            console.log(barang_id)
            
            $.ajax({
                type:'POST',
		        url: "controllers/transaksi.ctrl.php?ket=tambahmenu",
                dataType: "json",
                data:{barang_id:barang_id},
                success:function(data){
					$('#carimenu').val('');
                	
		            var content = '<tr class="fadeInLeft animated"><td>'+data.item.barang_nama+'</td><td>'+data.item.transaksi_detail_temp_jumlah+'</td><td>'+formatRupiah(data.item.transaksi_detail_temp_total, 'Rp. ')+'</td></tr>';

					$('#listitem table').append(content);
					$('.container__load').load('components/content/transaksi.content.php?kond=');

                }
            });
            
              
		});
    </script>
<?php } ?>