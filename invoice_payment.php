<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
  	?>

<!DOCTYPE HTML>
<html>
<head>
	<title>Client Management Sysytem || Invoice Payment </title>
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
	<script src="css/jquery-ui.css"></script>
	<!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css' />
	<!-- Graph CSS -->
	<link href="css/font-awesome.css" rel="stylesheet"> 
	<!-- jQuery -->
	<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'>
	<!-- lined-icons -->
	<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
	<!-- /js -->
	<script src="js/jquery-3.5.0.min.js"></script>
	<script src="js/jquery-ui.js"></script>
	<!-- //js-->
</head> 
<body>
	<div class="page-container">
		<!--/content-inner-->
		<div class="left-content">
			<div class="inner-content">
				<!-- header-starts -->
				<?php include_once('includes/header.php');?>
				<!-- //header-ends -->
				<!--outter-wp-->
				<div class="outter-wp">
					<!--sub-heard-part-->
					<div class="sub-heard-part">
						<ol class="breadcrumb m-b-0">
							<li><a href="dashboard.php">Home</a></li>
							<li class="active">Invoice Payment</li>
						</ol>
					</div>
					<!--//sub-heard-part-->
		<div class="graph-visual tables-main" id="exampl">
						
					
						<h3 class="inner-tittle two">Invoice Pyament </h3>
<?php
$invid=intval($_GET['invoiceid']);


$sql="SELECT * from  invoices WHERE invoice_id= :invoiceid ";
$query = $dbh -> prepare($sql);
$query->bindParam(':invoiceid',$invid,PDO::PARAM_STR);
$query->execute();

$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{
	$client_id = $row->client_id;
	$service_id = $row->service_id;
	$invoice_id = $row->invoice_id;

	$sql_client="SELECT ContactName, CompanyName, Cellphnumber, Family from  tblClient WHERE ID= :client_id";
	$query_client = $dbh -> prepare($sql_client);
	$query_client->bindParam(':client_id',$client_id,PDO::PARAM_STR);
	$query_client->execute();
	$results_client=$query_client->fetchAll(PDO::FETCH_OBJ);
									
	foreach ($results_client as $rowclient) 
	{
		$client_name = $rowclient->ContactName;
		$company_name = $rowclient->CompanyName;
		$cellphnumber = $rowclient->Cellphnumber;
	}
 }
}


	?>
<div class="graph">
	<div class="tables">
		<h4>Invoice #<?php echo $invid;?> for <?php echo $client_name;?></h4>
									
<table class="table table-bordered" width="100%" border="1"> 
	<tr>
		<th colspan="8" style="text-align: center;">Invoice Repayment Schedules</th>	
	</tr>
	<tr>
		<th>#</th>	
		<th>Serial<br>No.</th>
		<th>Expected<br>Payment Date</th>
		<th>Installment<br>Amount</th>
		<th>Amount <br>Paid</th>
		<th>Date<br> Paid</th>
		<th>Update <br>Payments</th>
		<th>Action</th>
	</tr>

	<?php
	$ret="SELECT * FROM invoice_items where invoice_id=:invid";
	$query1 = $dbh -> prepare($ret);
	$query1->bindParam(':invid',$invid,PDO::PARAM_STR);
	$query1->execute();

	$rs=$query1->fetchAll(PDO::FETCH_OBJ);

	$cnt=1;
	if($query1->rowCount() > 0)
	{
		foreach($rs as $row1)
		{   
			$datepai =    $row1->date_paid;
			if ($datepai== "0000-00-00") 
			{
			    $datepaid = date("Y-m-d");
			         
			}
			else
			{
				$datepaid = $datepai;
			} 

			$edit = '<a href="edit_payment_details.php?id='.$row1->id.'" title="Click to Edit" style="text-decoration:none">Edit</a>';

			$delete = '<a href="delete_payment_details.php?invoice_id='.$invid.'&id='.$row1->id.'" title="Click to Edit" style="text-decoration:none">Delete</a>';

			$action = '<div class="dropdown">

                  			<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cogs"></i> Actions

                        	<span class="caret"></span></button>

                        	<ul class="dropdown-menu">

                        		<li>'.$edit.'</li>

                         	   	<li>'.$delete.'</li>                                                  

                       		</ul>

                   		</div>';
     
			?>

				<tr>
					<th id="count<?php echo $cnt;?>"><?php echo $cnt;?></th>
					<th id="id<?php echo $cnt;?>"><?php echo $row1->id;?></th>
					<td nowrap="nowrap"><?php echo date("d-m-Y",strtotime($row1->repayment_date));?></td>	
					<td style="text-align: right;" nowrap="nowrap"><?php echo number_format($row1->amount,2);?></td>
					<td style="text-align: right;"  nowrap="nowrap"><input type="text" id="amount_paid<?php echo $cnt;?>" name="amountpaid" value="<?php echo number_format($row1->amount_paid, 2);?>"></td>
					<td style="text-align: center;"  nowrap="nowrap">
						<input type="text" name="datepaid" id="datepaid<?php echo $cnt;?>" value="<?php echo date("m/d/Y",strtotime($datepaid));?>" readonly>
					</td>
					<td style="text-align: center;" nowrap="nowrap">
						<button class='btn btn-info button_update' id='<?php echo $cnt;?>' >Update</button>
					</td>
					<td style="text-align: center;" nowrap="nowrap"><?php echo $action;?>
					</td>
					

					
				</tr>

			<?php $cnt=$cnt+1;
			//$gtotal+=$subtotal;
		$total_amounts += $row1->amount;
		$total_payments += $row1->amount_paid;
		}
		?>
		  <button class='btn btn-info' id='button_add' >Add New</button>

		<?php
		
	} 
	?>

<tr>
<th colspan="3" style="text-align:center">Totals</th>
<th style="text-align: right;"><?php echo number_format($total_amounts,2);?></th>
<th style="text-align: right;"><?php echo number_format($total_payments,2);?></th>	
<th>&nbsp;</th>
<th>&nbsp;</th>

</tr>
</table>

							</div>

						</div>
				
					</div>
					<!--//graph-visual-->
				</div>
				<!--//outer-wp-->
				<?php include_once('includes/footer.php');?>
			</div>
		</div>
		<!--//content-inner-->
		<!--/sidebar-menu-->
		<?php include_once('includes/sidebar.php');?>
		<div class="clearfix"></div>		
	</div>
	<script>
		var toggle = true;

		$(".sidebar-icon").click(function() {                
			if (toggle)
			{
				$(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
				$("#menu span").css({"position":"absolute"});
			}
			else
			{
				$(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
				setTimeout(function() {
					$("#menu span").css({"position":"relative"});
				}, 400);
			}

			toggle = !toggle;
		});
	</script>
	<!--js -->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>
	<script>
function CallPrint(strid) {
var prtContent = document.getElementById("exampl");
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
WinPrint.close();
}
</script>
<script>
	$(document).ready(function()
	{
	  $('.button_update').click(function()
	  {

 	 	var rowId=$(this).attr('id');
 	 	var idno=$("#id" + rowId).text();
 	 	var amount_paid = $("#amount_paid"+ rowId).val();
 	 	var datepaid = $("#datepaid"+ rowId).val();
 		var rowData = {
 			idno : idno,	
 			amount_paid: amount_paid,
 			datepaid: datepaid
 		};

  		// AJAX Request
		  $.ajax
		  ({
   			url: 'update_payments.php',
   			type: 'post',
   			data: rowData,
		   	success: function(response)
		   	{
   					//alert (response);
   					location.reload(true);

    			}
  			});

		 });

	});
	$(document).ready(function(){
  	$('#button_add').click(function(){

 	 	var invoice_id="<?php echo $invid; ?>";
 	 	var service_id="<?php echo $service_id; ?>";
 	 	
 		var rowData = {
 			invoice_id : invoice_id,	
 			service_id: service_id
 		};

  // AJAX Request
  $.ajax({
   url: 'add_payment_item.php',
   type: 'post',
   data: rowData,
   success: function(response){
   	alert (response);
   	location.reload(true);

    }
  });

 });

});

	$( function() {
    	$( ".datepick" ).datepicker();
  	});
</script>
</body>
</html>
<?php }  ?>