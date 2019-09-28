<?php
$ket = $_GET['ket'];

if ($ket=='omset') {
?>
<div class="row">
	<div class="col-md-8">
		<div class="row">
			<div class="col-md-2">
			    <div class="md-form">
			        <select class="mdb-select md-form" id="daterange" name="ip-daterange">
			            <option value="harian">Harian</option>
			            <option value="bulanan">Bulanan</option>
			        </select>
			    </div>
			</div>
			<div class="col-md-8">
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
				<div class="row form-month hidden">
					<div class="col-md-6">
			            <div class="md-form">
							<input type="text" id="defaultForm-startmonth" class="form-control validate mb-3" name="ip-startmonth">
							<label for="defaultForm-startmonth">Start Month</label>
			            </div>
					</div>
					<div class="col-md-6">
			            <div class="md-form">
							<input type="text" id="defaultForm-endmonth" class="form-control validate mb-3" name="ip-endmonth">
							<label for="defaultForm-endmonth">End Month</label>
			            </div>
			        </div>
				</div>
			</div>
			<div class="col-md-2">
			    <div class="md-form">
			    	<button class="btn btn-primary btn-proses-laporan">Proses</button>
			    </div>
			</div>
		</div>	
		<div class="row">
			<div class="col-md-12">
				<table id="table-omset" class="table table-striped table-bordered" style="width:100%">
			        <thead>
			            <tr>
                            <th>tanggal</th>
                            <th style="text-align: right;">Cash</th>
                            <th style="text-align: right;">Debet</th>
                            <th style="text-align: right;">online</th>
                            <th>total omset</th>
			            </tr>
			        </thead>
			        <tfoot>
			            <tr>
                            <th>tanggal</th>
                            <th style="text-align: right;">Cash</th>
                            <th style="text-align: right;">Debet</th>
                            <th style="text-align: right;">online</th>
                            <th>total omset</th>
			            </tr>
			        </tfoot>
			    </table>
			</div>
		</div>
	</div>
</div>

<?php
} elseif ($ket=='kasir') {
	echo "Kasir";

} elseif ($ket=='menu') {
	echo "Menu";

}




?>
<script type="text/javascript">

  $(document).ready(function(){
      	$('.mdb-select').materialSelect();
		
		$("#daterange").change(function(){
			if ($(this).val()=="harian") {

	            $("#defaultForm-startdate").val('');
	            $("#defaultForm-startmonth").val('');
	           
	            $(".form-month").addClass('hidden');
	            $(".form-date").removeClass('hidden');
			
			} else if ($(this).val()=="bulanan") {
	        
	            $("#defaultForm-startdate").val('');
	            $("#defaultForm-startmonth").val('');

	            $(".form-month").removeClass('hidden');
	            $(".form-date").addClass('hidden');
			
			}
		});
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
		
		var dateformat = ["01","02","03","04","05","06","07","08","09","10",
		            "11","12","13","14","15","16","17","18","19","20",
		            "21","22","23","24","25","26","27","28","29","30","31"];

		
		$('.btn-proses-laporan').on('click',function(){
			var daterange = $('#daterange').val();

			if (daterange=='harian') {

	          	var start = $('#defaultForm-startdate').val();
	          	var end = $('#defaultForm-enddate').val();
	          	var kettext = 'transaksi_tanggal';
				
			} else if (daterange=='bulanan') {

	          	var start = $('#defaultForm-startmonth').val();
	          	var end = $('#defaultForm-endmonth').val();
	          	var kettext = 'transaksi_bulan';
				
			}

			
			$.ajax({
		        type:'POST',
		        url:'api/view.api.php?func=laporan-omset',
		        dataType: "json",
            	data:{
            		daterange:daterange,
            		start:start,
            		end:end
            	},
		        success:function(data){
		        	$('#table-omset').DataTable( {
					    paging: false,
					    searching: false,
					    data: data,
					    columns: [
					        { data: kettext },
					        { data: 'cash' },
					        { data: 'debet' },
					        { data: 'online' },
					        { data: 'total' }
					    ]
					} );
		        	console.log("success");
		        	console.log(data);
		        }
		    });
			/*
		    
			$('#table-omset').DataTable( {
			    ajax:  {
			        type:'POST',
			        url:'api/view.api.php?func=laporan-omset',
			        dataType: "json",
	            	data:{
	            		daterange:daterange,
	            		start:start,
	            		end:end
	            	}
			    },
			    columns: [
			        { data: 'transaksi_tanggal' },
			        { data: 'cash' },
			        { data: 'debet' },
			        { data: 'online' },
			        { data: 'total' }
			    ]
			} );

		    $.ajax({
		        type:'POST',
		        url:'api/view.api.php?func=laporan-omset',
		        dataType: "json",
            	data:{
            		daterange:daterange,
            		start:start,
            		end:end
            	},
		        success:function(data){
		            var date = [];
		            var total = [];
		            var omset = 0;

		            for (var i in data) {
		                date.push(moment(new Date(data[i].transaksi_tanggal)).format('ddd')+'-'+moment(new Date(data[i].transaksi_tanggal)).format('DD'));
		                total.push(data[i].total);
		                omset += parseInt(data[i].total);
		            }
		            $('#totomset').text(convertToRupiah(omset));
		            var ctxL = document.getElementById("lineChart").getContext('2d');
		            var myLineChart = new Chart(ctxL, {
		                type: 'line',
		                data: {
		                    labels: date,
		                    datasets: [{
		                            label: "",
		                            data: total,
		                            backgroundColor: [
		                                'rgba(0, 137, 132, .2)',
		                            ],
		                            borderColor: [
		                                'rgba(0, 10, 130, .7)',
		                            ],
		                            borderWidth: 2
		                        }
		                    ]
		                },
		                options: {
		                    responsive: true,
		                    aspectRatio: 2,
		                    tooltips: {
		                      callbacks: {
		                        label: function(t, d) {
		                           var xLabel = d.datasets[t.datasetIndex].label;
		                           var yLabel = t.yLabel >= 1000 ? '$' + t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : '$' + t.yLabel;
		                           return xLabel + ': ' + yLabel;
		                        }
		                      }
		                    },
		                    scales: {
		                      yAxes: [{
		                        ticks: {
		                           callback: function(value, index, total) {
		                              if (parseInt(value) >= 1000) {
		                                 return 'Rp. ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		                              } else {
		                                 return 'Rp. ' + value;
		                              }
		                           }
		                        }
		                      }]
		                    }
		                }
		            });
		        }
		    });
		    */
		});            
	});
/*
if ($("main").hasClass("dashboard") == true) {

    $.ajax({
        type:'POST',
        url:'api/view.api.php?func=dasboard-omset',
        dataType: "json",
        success:function(data){
            var date = [];
            var total = [];
            var omset = 0;

            for (var i in data) {
                date.push(moment(new Date(data[i].transaksi_tanggal)).format('ddd')+'-'+moment(new Date(data[i].transaksi_tanggal)).format('DD'));
                total.push(data[i].total);
                omset += parseInt(data[i].total);
            }
            $('#totomset').text(convertToRupiah(omset));
            var ctxL = document.getElementById("lineChart").getContext('2d');
            var myLineChart = new Chart(ctxL, {
                type: 'line',
                data: {
                    labels: date,
                    datasets: [{
                            label: "",
                            data: total,
                            backgroundColor: [
                                'rgba(0, 137, 132, .2)',
                            ],
                            borderColor: [
                                'rgba(0, 10, 130, .7)',
                            ],
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    aspectRatio: 2,
                    tooltips: {
                      callbacks: {
                        label: function(t, d) {
                           var xLabel = d.datasets[t.datasetIndex].label;
                           var yLabel = t.yLabel >= 1000 ? '$' + t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : '$' + t.yLabel;
                           return xLabel + ': ' + yLabel;
                        }
                      }
                    },
                    scales: {
                      yAxes: [{
                        ticks: {
                           callback: function(value, index, total) {
                              if (parseInt(value) >= 1000) {
                                 return 'Rp. ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                              } else {
                                 return 'Rp. ' + value;
                              }
                           }
                        }
                      }]
                    }
                }
            });
        }
    });


    $.ajax({
        type:'POST',
        url:'api/view.api.php?func=dasboard-pelanggan',
        dataType: "json",
        success:function(data){
            var date = [];
            var jumlah = [];

            for (var i in data) {
                date.push(dateformat[i]);
                jumlah.push(data[i].jumlah);
            }
            var ctxL = document.getElementById("chartpelanggan").getContext('2d');
            var myLineChart = new Chart(ctxL, {
                type: 'line',
                data: {
                    labels: date,
                    datasets: [{
                            label: "",
                            data: jumlah,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.5)',
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, .9)',
                            ],
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    aspectRatio: 3,
                }
            });
        }
    });

    $.ajax({
        type:'POST',
        url:'api/view.api.php?func=dasboard-itemsold',
        dataType: "json",
        success:function(data){
            var date = [];
            var jumlah = [];

            for (var i in data) {
                date.push(dateformat[i]);
                jumlah.push(data[i].jumlah);
            }
            var ctxL = document.getElementById("chartitem").getContext('2d');
            var myLineChart = new Chart(ctxL, {
                type: 'line',
                data: {
                    labels: date,
                    datasets: [{
                            label: "",
                            data: jumlah,
                            backgroundColor: [
                                'rgba(255, 159, 64, 0.5)',
                            ],
                            borderColor: [
                                'rgba(255, 159, 64, .9)',
                            ],
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    aspectRatio: 3,
                }
            });
        }
    });
}
*/
</script>