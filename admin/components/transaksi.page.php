	
    <input type="hidden" id="defaultForm-role" name="ip-role" value="<?php echo $_SESSION['role']; ?>">
	<main class="transaksi p-0 mr-0">
		<div class="main-wrapper">
		    <div class="container-fluid">
				<div class="row">
					<div class="col-md-8 pl-0 pr-0 container__load">

					</div>

					<div class="col-md-4 position-relative box-right">
						<div class="row">
							<div class="col-md-12 position-fixed info-color text-white col-right"></div>
							<div class="col-md-12">
								<h3 class="text-white pt-3">Order List</h3>
								<!-- Search form -->
								<div class="form-inline md-form form-sm mt-2 form-search info-color-dark">
									<input class="form-control form-control-sm text-white " type="text" placeholder="Cari Menu"
									    aria-label="Search" id="carimenu">
									<i class="fas fa-search text-white" aria-hidden="true"></i>
								</div>
							</div>
							<div class="col-md-12 text-center">
								<button type="button" class="btn btn-white waves-effect mr-2 text-info" id="dinein"><i class="fas fa-utensils"></i>Dine In</button>
								<button type="button" class="btn btn-white waves-effect mr-2 text-info" id="takeaway"><i class="fab fa-gulp"></i>Take Away</button>
								<button type="button" class="btn btn-white waves-effect text-info" id="online"><i class="fas fa-motorcycle"></i>Online</button>
							</div>
							<div class="col-md-12 text-white mt-4 info-color-dark fadeIn animated" id="listitem">
								<table class="pt-2 pb-2"></table>
							</div>
							<div class="col-md-12 box-bottom">
								<div class="row">
									<div class="col-md-6"><p class="h6">Subtotal</p></div>
									<div class="col-md-6 text-right"><p class="h5" id="subtotal"></p></div>
								</div>
								<div class="row">
									<div class="col-md-6"><p class="h6">Tax</p></div>
									<div class="col-md-6 text-right"><p class="h5">Rp. 0</p></div>
								</div>
								<div class="row border-top pt-2">
									<div class="col-md-6"><p class="h1">Total</p></div>
									<div class="col-md-6 text-right"><p class="h1">Rp. 15000</p></div>
								</div>
								<div class="row pt-2 pb-2">
									<div class="col-md-12">
										<button type="button" class="btn btn-white waves-effect text-danger" id="batal"><i class="fas fa-trash d-inline-block mr-2"></i>Batal</button>
										<button type="button" class="btn btn-white waves-effect text-warning" id="print"><i class="fas fa-print d-inline-block mr-2"></i>Print</button>
										<button type="button" class="btn btn-white waves-effect text-info" id="bayar"><i class="fas fa-money-bill d-inline-block mr-2"></i>Bayar</button>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
		    </div>
		</div>
	</main>


	<?php include 'partials/footer.php'; ?>

<script type="text/javascript">
	$(document).ready(function(){
		$('.container__load').load('components/content/transaksi.content.php?kond=home');

		$('#carimenu').bind("enterKey",function(e){
			var search = $(this).val();
			$('.container__load').load('components/content/transaksi.content.php?kond=search&q='+search);
			//alert(search);
			/*
			$.ajax({
                type: 'POST',
                url: "components/content/transaksi.content.php?kond=search",
                dataType: "json",
                data:{search:search},
                success: function(data) {
                	console.log(data)
                  //$('.container__load').load(data);
                }
            });
            */
		});
		

		$('#carimenu').keyup(function(e){
			if(e.keyCode == 13) {
				$(this).trigger("enterKey");
			}
		});


	});
</script>