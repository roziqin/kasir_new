	
    <input type="hidden" id="defaultForm-role" name="ip-role" value="<?php echo $_SESSION['role']; ?>">
	<div class="container-fluid p-0">
		<div class="row header-content pt-3 pb-3 info-color text-white">
			<div class="col-md-12">
				<h2>User</h2>
			</div>
		</div>
	</div>
	<main class="pt-4 user pl-3 pr-3 mr-0">
		<div class="main-wrapper">
		    <div class="container-fluid">
				<div class="row mt-2">
					<div class="col-md-12 container__load">

					</div>
				</div>
		    </div>
		</div>
	</main>


	<?php include 'partials/footer.php'; ?>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$('.container__load').load('components/content/user.content.php');
	});
</script>