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
	<title>Client Management Sysytem || View Invoice </title>
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
	<!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css' />
	<!-- Graph CSS -->
	<link href="css/font-awesome.css" rel="stylesheet"> 
	<!-- jQuery -->
	<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'>
	<!-- lined-icons -->
	<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
	<!-- /js -->
	<script src="js/jquery-1.10.2.min.js"></script>
	<style type="text/css">
			@media print {
  				
  				#other {
  				 display: none;
  				}
			}
		</style>
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
					<div class="sub-heard-part" id="other">
						<ol class="breadcrumb m-b-0">
							<li><a href="dashboard.php">Home</a></li>
							<li class="active">View Invoice</li>
						</ol>
					</div>
					<!--//sub-heard-part-->
		<div class="graph-visual tables-main" id="exampl">
						
					
						<h3 class="inner-tittle two">Invoice Details </h3>
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

	$sql_client="SELECT ContactName, CompanyName, NationalID, Family from  tblclient WHERE ID= :client_id";
	$query_client = $dbh -> prepare($sql_client);
	$query_client->bindParam(':client_id',$client_id,PDO::PARAM_STR);
	$query_client->execute();
	$results_client=$query_client->fetchAll(PDO::FETCH_OBJ);
									
	foreach ($results_client as $rowclient) 
	{
		$client_name = $rowclient->ContactName;
		$company_name = $rowclient->CompanyName;
		$nationalid = $rowclient->NationalID;
		$family=$rowclient->Family;
	}




	?>
						<div class="graph">
							<div class="tables">
								<h4>Invoice #<?php echo $invid;?></h4>
									<table class="table table-bordered" width="100%" border="1"> 
							<tr>
								<th colspan="6">Client Details</th>	
							</tr>
							 <tr> 
								<th>Client Name</th> 
								<td><?php  echo htmlentities($client_name);?></td>
								<th>Client Business</th> 
								<td><?php  echo htmlentities($company_name);?></td> 
								<th>ID / Passport No.</th> 
								<td><?php  echo htmlentities($nationalid);?></td>
							</tr> 
							 <tr> 
								<th>Family</th> 
								<td><?php echo htmlentities($family);?></td> 
								<th>Invoice Date</th> 
								<td colspan="3"><?php echo  htmlentities(date("d-m-Y",strtotime($row->invoice_date)));?></td> 
							</tr> 
<?php $cnt=$cnt+1;}} ?>
</table>
<table class="table table-bordered" width="100%" border="1"> 
	<tr>
		<th colspan="6" style="text-align: center;">Invoice Repayment Schedules</th>	
	</tr>
	<tr>
		<th>#</th>	
		<th>Expected<br>Payment Date</th>
		<th>Installment<br>Amount</th>
		<th>Amount <br>Paid</th>
		<th>Balance<br>Amount</th>
		<th>Date<br> Paid</th>
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
			?>

				<tr>
					<th><?php echo $cnt;?></th>
					<td nowrap="nowrap"><?php echo date("d-m-Y",strtotime($row1->repayment_date));?></td>	
					<td style="text-align: right;" nowrap="nowrap"><?php echo number_format($row1->amount,2);?></td>
					<td style="text-align: right;" nowrap="nowrap"><?php echo number_format($row1->amount_paid,2);?></td>
					<td style="text-align: right;" nowrap="nowrap"><?php echo number_format(($row1->amount-$row1->amount_paid),2);?></td>
					<td style="text-align: center;" nowrap="nowrap"><?php echo date("d-m-Y",strtotime($row1->date_paid));?></td>
				</tr>

			<?php $cnt=$cnt+1;
			//$gtotal+=$subtotal;
		$total_amounts += $row1->amount;
		$total_payments += $row1->amount_paid;
		}
		
	} 
	?>

<tr>
<th colspan="2" style="text-align:center">Totals</th>
<th style="text-align: right;"><?php echo number_format($total_amounts,2);?></th>
<th style="text-align: right;"><?php echo number_format($total_payments,2);?></th>
<th style="text-align: right;"><?php echo number_format(($total_amounts-$total_payments),2);?></th>		
<th>&nbsp;</th>

</tr>
</table>
<p style="margin-top:1%"  align="center" id="other">
  <i class="fa fa-print fa-2x" style="cursor: pointer;"  OnClick="window.print()" ></i>
</p>

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
</body>
</html>
<?php }  ?>