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
	<title>Client Management Sysytem || Client Details </title>
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
	<!-- //js-->

		<style type="text/css">
			@media print {
  				
  				#other {
  				 display: none;
  				}
			}
		</style>
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
							<li class="active">Client Details</li>
						</ol>
					</div>
					<!--//sub-heard-part-->
		<div class="graph-visual tables-main" id="DivIdToPrint">
						
					
						<h3 class="inner-tittle two">Client Details </h3>
<?php
$client_id=intval($_GET['client_id']);


	$sql_client="SELECT * from  tblclient WHERE ID= :client_id";
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
		$guarantor=$rowclient->Guarantor;
		$phnumber=$rowclient->Clientphnumber;
		$gphnumber = $rowclient->Guarantorphnumber;
	}




	?>
						<div class="graph">
							<div class="tables">
								<h4><?php echo $client_name;?></h4>
									<table class="table table-bordered" width="100%" border="1"> 
							<tr>
								<th colspan="6">Client Details</th>	
							</tr>
							 <tr> 
								
								<th>Client Business</th> 
								<td><?php  echo htmlentities($company_name);?></td> 
								<th>ID / Passport No.</th> 
								<td><?php  echo htmlentities($nationalid);?></td>
								<th>Phone Number</th> 
								<td><?php echo  htmlentities($phnumber);?></td> 
							</tr> 
							 <tr> 
								<th>Family</th> 
								<td><?php echo htmlentities($family);?></td> 
								
								<th>Guarantor</th> 
								<td><?php echo  htmlentities($guarantor);?></td> 
								<th>Guarantor Contact</th> 
								<td><?php  echo htmlentities($gphnumber);?></td>
							</tr> 

<?php

$sql="SELECT * from  invoices WHERE client_id= :client_id ";
$query = $dbh -> prepare($sql);
$query->bindParam(':client_id',$client_id,PDO::PARAM_STR);
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
$cnt=$cnt+1;
}
} 
?>
</table>
<table class="table table-bordered" width="100%" border="1"> 
	<thead>
	<tr>
		<th colspan="10" style="text-align: center;">Client Invoices</th>	
	</tr> 
										<tr> 
											<th style="text-align: center;">#</th> 
											<th style="text-align: center;">Invoice No.</th>
											<th style="text-align: center;">Client Name</th>
									
											<th style="text-align: center;">Invoice Date</th>
											<th style="text-align: center;">Service Charge</th>
											<th style="text-align: center;">Amount</th> 
											<th style="text-align: center;">Repayment<br>Amount</th>
											<th style="text-align: center;">Amount Paid</th> 
											<th style="text-align: center;">Balance</th> 
											<th style="text-align: center;">Action</th>
									  	</tr>
									</thead>
								<tbody>

								<?php
								$staus = "1";
								$sql="SELECT * from  invoices WHERE status= :staus and client_id=:client_id order by invoice_date desc";
								$query = $dbh -> prepare($sql);
								$query->bindParam(':staus',$staus,PDO::PARAM_STR);
								$query->bindParam(':client_id',$client_id,PDO::PARAM_STR);
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
									$invoice_no = $row->invoice_no;

									$sql_client="SELECT ContactName from  tblclient WHERE ID= :client_id";
									$query_client = $dbh -> prepare($sql_client);
									$query_client->bindParam(':client_id',$client_id,PDO::PARAM_STR);
									$query_client->execute();
									$results_client=$query_client->fetchAll(PDO::FETCH_OBJ);
									
									foreach ($results_client as $rowclient) 
									{
										$client_name = $rowclient->ContactName;
									}
									

									$sql_items="SELECT amount_paid from  invoice_items WHERE invoice_id= :invoice_i";
									$query_items = $dbh -> prepare($sql_items);
									$query_items->bindParam(':invoice_i',$invoice_id,PDO::PARAM_STR);
									$query_items->execute();
									$results_items=$query_items->fetchAll(PDO::FETCH_OBJ);
									$total_paid = 0;
									foreach ($results_items as $rs) 
									{
										$amountpaid = $rs->amount_paid;

										$total_paid +=$amountpaid; 
									}

									$amount_borrowed = $row->amount;
									$amount_tobepaid = $row->repayment_amount;

									$balance_amt = $amount_tobepaid - $total_paid;

									$view = '<a href="view-invoice.php?invoiceid='.$row->invoice_id.'" title="Click to View" style="text-decoration:none">View</a>';

									$edit = '<a href="edit_invoice_details.php?editid='.$row->invoice_id.'" title="Click to Edit" style="text-decoration:none">Edit</a>';

									$makepayments = '<a href="invoice_payment.php?invoiceid='.$row->invoice_id.'" title="Click to Edit" style="text-decoration:none">Make Payments</a>';

									$delete = '<a href="delete_invoice.php?invoice_id='.$row->invoice_id.'" title="Click to Delete" style="text-decoration:none">Delete</a>';

									$action = '<div class="dropdown">

                  										<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cogs"></i> Actions

                        								<span class="caret"></span></button>

                        								<ul class="dropdown-menu">

                        									<li>'.$edit.'</li>

                         								   	<li>'.$view.'</li>

                         								   	<li>'.$makepayments.'</li> 

                         								   	<li>'.$delete.'</li>                                                 

                       								 </ul>

                   								 </div>';


									?>
									    <tr class="active">
									      	<th scope="row"><?php echo htmlentities($cnt);?></th>
									       	<td><?php  echo htmlentities($invoice_no);?></td>
									       	<td style="width: 40%"><?php  echo htmlentities($client_name);?></td>
									       	<td nowrap="nowrap"><?php  echo htmlentities(date('d-m-Y',strtotime($row->invoice_date)));?></td>
									       	<td style="text-align: right;" nowrap="nowrap"><?php  echo htmlentities(number_format($row->service_charge,2));?></td>
									        <td style="text-align: right;" nowrap="nowrap"><?php  echo htmlentities(number_format($row->amount,2));?></td>
									       	<td style="text-align: right;" nowrap="nowrap"><?php  echo htmlentities(number_format($row->repayment_amount,2));?></td> 
									       	<td style="text-align: right;" nowrap="nowrap"><?php  echo htmlentities(number_format($total_paid,2));?></td> 
									       	<td style="text-align: right;" nowrap="nowrap"><?php  echo htmlentities(number_format($balance_amt,2));?></td> 
									         
									        <td><?php echo $action;?></td>
									     </tr>
									     <?php $cnt=$cnt+1;
									     $total_service_charge += $row->service_charge;
									     $total_amount += $row->amount;
									     $total_repayment_amount += $row->repayment_amount;
									     $total_total_paid += $total_paid;
									     $totla_balance_amt += $balance_amt;

									 	}
									     ?>

									     <tr class="active">
									      	<th colspan="4" style="text-align: center;">Totals</th>
									       	
									       	<th style="text-align: right;" nowrap="nowrap"><?php  echo htmlentities(number_format($total_service_charge,2));?></th>
									        <th style="text-align: right;" nowrap="nowrap"><?php  echo htmlentities(number_format($total_amount,2));?></th>
									       	<th style="text-align: right;" nowrap="nowrap"><?php  echo htmlentities(number_format($total_repayment_amount,2));?></th> 
									       	<th style="text-align: right;" nowrap="nowrap"><?php  echo htmlentities(number_format($total_total_paid,2));?></th> 
									       	<th style="text-align: right;" nowrap="nowrap"><?php  echo htmlentities(number_format($totla_balance_amt,2));?></th> 
									         
									        <th id="other"></th>
									     </tr>



									     <?php


									 } ?>
									     </tbody> </table> 
	
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
	<script>
function CallPrint() {
var prtContent = document.getElementById("exampl");
var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
WinPrint.document.write(prtContent.innerHTML);
WinPrint.document.close();
WinPrint.focus();
WinPrint.print();
WinPrint.close();
}

function printDiv() 
{

  var divToPrint=document.getElementById('DivIdToPrint');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},10);

}


</script>
</body>
</html>
<?php }  ?>