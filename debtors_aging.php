<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
function getClientbalance($clientid, $dbh)

{	

	//CHECK CLIENT INVOICES
	$sql="SELECT invoice_id, repayment_amount, invoice_date from  invoices WHERE client_id= :clientid ";
	$query_details = $dbh -> prepare($sql);
	$query_details->bindParam(':clientid',$clientid,PDO::PARAM_STR);
	$query_details->execute();

	$results=$query_details->fetchAll(PDO::FETCH_OBJ);

	$total_amnt_paid = 0;
	$total_repay_amt = 0;
	$total_bal_due = "";
	$date_array[] = "";
	$amnt_paid = 0;
	foreach($results as $row)
	{
		$inv_id = $row->invoice_id;

		$repay_amt = $row->repayment_amount;
		$invoice_date = strtotime($row->invoice_date);

		$sql_inv_items="SELECT amount_paid, repayment_date  from  invoice_items WHERE invoice_id=:inv_id";
		$query_inv_items = $dbh -> prepare($sql_inv_items);
		$query_inv_items->bindParam(':inv_id',$inv_id,PDO::PARAM_STR);
		$query_inv_items->execute();
		$results_items=$query_inv_items->fetchAll(PDO::FETCH_OBJ);
		
		foreach ($results_items as $rs) 
		{
			$amnt_pa = $rs->amount_paid;
			$amnt_paid = $amnt_paid+$amnt_pa;
			$date_array[] = strtotime($rs->repayment_date);
		}
									
		//$amnt_paid = $results_items->amount_pai;

		$total_amnt_paid = $amnt_paid+$total_amnt_paid;

		$total_repay_amt =$repay_amt + $total_repay_amt; 

	}
	$date_repay_max = max($date_array);
	if ($total_amnt_paid<=0) {
		$date_repay_max = $invoice_date;
	}else{
		$date_repay_max = max($date_array);
	}
	$total_bal_due = array($total_repay_amt,$total_amnt_paid, $date_repay_max);	
	return $total_bal_due;

}
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
  	?>

<!DOCTYPE HTML>
<html>
<head>
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
							<li class="active">Debtors Aging Report</li>
						</ol>
					</div>
					<!--//sub-heard-part-->
					<div class="graph-visual tables-main">
						
					
						<h3 class="inner-tittle two">Debtors Aging</h3>
						<div class="graph">
							<form name="form1" method="post" action="manage-client.php">
							<div class="tables">
							
								<table class="table" border="1">
									<thead> 
										<tr> 
											<th>#</th> 
											<th>Client Name</th>
											<th>Client Home</th>
											<th>Client Contacts</th>
											<th>Repayment<BR>Amount</th>
									 		<th>Amount<br>Paid</th>
											<th>Days in<br>Default</th>
									 		<th>Balance<br><=30 Days</th>
									 		<th>Balance<br><=60 Days</th>
									 		<th>Balance<br><=90 Days</th>
									 		<th>Balance<br>>90 Days</th>

									 		<th>Status</th>
								
									 		<th>Action</th>
									  	</tr>
									</thead>
							<tbody>
<?php


/*
$limit = 50;
$quer = "SELECT * FROM tblclient";

$s = $dbh->prepare($quer);
//$s->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
$s->execute();
$total_results = $s->rowCount();
$total_pages = ceil($total_results/$limit);
//print $total_results;

if (!isset($_GET['page'])) {
    $page = 1;
} else{
    $page = $_GET['page'];
}

*/
function convert_days($sum) {
    $years = floor($sum / 365);
    $months = floor(($sum - ($years * 365))/30.5);
    $days = ($sum - ($years * 365) - ($months * 30.5));
    //echo "Days received: " . $sum . " days <br />";
    //echo $years . " years, " . $months . "months, " . $days . "days";
	$days_desc = "(".$sum." Days) :<br> ".$years . "Y ".$months ."M " .$days."D";
	return $days_desc;
}

$starting_limit = ($page-1)*$limit;


$sql="SELECT * from tblclient";
$query = $dbh->prepare($sql);

$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{

	$stat = htmlentities($row->Status); 
	if ($stat =="1") {
		$status = '<font color=green><b>Active</b></font>';   
	}
	else
	{
		$status = '<font color=orange><b>In-Active</b></font>';   
	}

	$i=$cnt-1;

	$edit = '<a href="edit-client-details.php?editid='.$row->ID.'" title="Click to Edit" style="text-decoration:none">Edit</a>';

	if ($stat=='1') {
		$changestatus = '<a href="client_status_change.php?client_id='.$row->ID.'&status=0" title="Click to Change Status" style="text-decoration:none">De-Activate</a>';
	}
	else{
		$changestatus = '<a href="client_status_change.php?client_id='.$row->ID.'&status=1" title="Click to Change Status" style="text-decoration:none">Activate</a>';
	}

	$view_details = '<a href="view_client_invoice.php?client_id='.$row->ID.'" title="Click to View Client Payment Detail" style="text-decoration:none">View Payments</a>';

	$print = '<a href="view_client_details.php?client_id='.$row->ID.'" title="Click to View Client Details" style="text-decoration:none">Print</a>';

	$client_passport = '<a href="client_passport.php?client_id='.$row->ID.'" title="Click to Upload Client Passport" style="text-decoration:none">Upload Passport</a>';
	
	$client_bal = getClientbalance($row->ID, $dbh);
	$client_balance = $client_bal[0]-$client_bal[1];
	

	$action = '<div class="dropdown">

                  		<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-cogs"></i> Actions

                        <span class="caret"></span></button>

                        <ul class="dropdown-menu">

                            <li>'.$edit.'</li>

                            <li>'.$changestatus.'</li>  

                            <li>'.$view_details.'</li>   
                            <li>'.$print.'</li>  
                            <li>'.$client_passport.'</li>                                               

                        </ul>

                    </div>'; 
                    if ($client_balance>0) {

						$strtotime_date = strtotime(date('d-m-Y'));

                    	$days_with_no_pay = round(($strtotime_date-$client_bal[2]) / (60 * 60 * 24));

                       	$less_than30 =	30;
                       	$less_than60 =	60;
                       	$less_than90 =	90;
                       //	$more_than90 =	strtotime(date('d-m-Y -30 days'));
                   
                    ?>
									     <tr class="active">
									      	
									      	<th scope="row"><?php echo htmlentities($cnt);?></th>
									      
									        <td><?php  echo htmlentities($row->ContactName);?></td> 
									        <td><?php  echo htmlentities($row->Family);?></td> 
									        <td>Cell No. - <?php  echo htmlentities($row->Clientphnumber);?><br>
									        	 </td>

									        <td style="text-align: right;"><?php print number_format($client_bal[0],2);?></td>

									       	<td style="text-align: right;"><?php print number_format($client_bal[1],2);?></td>
											<td style="text-align: right;" nowrap="nowrap"><?php print date("d-m-Y",$client_bal[2]) ."<br>".convert_days($days_with_no_pay);?></td>

									       	<?php
									       		if ($days_with_no_pay<=$less_than30) 
									       		{
									       			?>
									       				<td style="text-align: center;" nowrap="nowrap"><?php  echo number_format($client_balance,2);?></td>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       			<?php
									       			$total_less_30 += $client_balance; 
									       		}
									       		elseif ($days_with_no_pay>$less_than30 && $days_with_no_pay<=$less_than60) 
									       		{
									       			?>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       				<td style="text-align: center;" nowrap="nowrap"><?php  echo number_format($client_balance,2);?></td>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       			<?php
									       			$total_less_60 += $client_balance; 
									       		}
									       		elseif ($days_with_no_pay>$less_than60 && $days_with_no_pay<=$less_than90) 
									       		{
									       			?>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       				<td style="text-align: center;" nowrap="nowrap"><?php  echo number_format($client_balance,2);?></td>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       			<?php
									       			$total_less_90 += $client_balance; 
									       		}
									       		elseif ($days_with_no_pay>$less_than90) 
									       		{
									       			?>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       				<td style="text-align: center;" nowrap="nowrap"></td>
									       				<td style="text-align: center;" nowrap="nowrap"><?php  echo number_format($client_balance,2);?></td>
									       			<?php
									       			$total_more_90 += $client_balance; 
									       		}
									       		
									       	?>
									        
									        <td style="text-align: center;" nowrap="nowrap"><?php  echo $status;?></td>
									        
									        <td style="text-align: center;" nowrap="nowrap"><?php  echo $action;?></td>
									        
									     </tr>

										<input type=hidden name=client_id'<?php echo $row->ID;?>' value="">

									   	<?php $cnt=$cnt+1;
									   	$total_gvn += $client_bal[0];
									   	$total_rcvd += $client_bal[1];
                    				}

							}
							$total_due = $total_less_30+$total_less_60+$total_less_90+$total_more_90;

							?>
							 	<tr class="active">
									      	
									      	<th colspan="5" style="text-align: center;">Totals</th>
											  
									      
									       

									        <th style="text-align: right;"><?php print number_format($total_gvn,2);?></th>

									       	<th style="text-align: right;"><?php print number_format($total_rcvd,2);?></th>
								       	
									       	<th style="text-align: right;" nowrap="nowrap"><?php  echo number_format($total_less_30,2);?></th>
									       	<th style="text-align: right;" nowrap="nowrap"><?php  echo number_format($total_less_60,2);?></th>
									       	<th style="text-align: right;" nowrap="nowrap"><?php  echo number_format($total_less_90,2);?></th>
									       	<th style="text-align: right;" nowrap="nowrap"><?php  echo number_format($total_more_90,2);?></th>
									        <th style="text-align: center;" nowrap="nowrap"><?php  echo number_format($total_due,2);?></th>
									        
									        <th style="text-align: center;" nowrap="nowrap">&nbsp;</th>
									        
									     </tr>


							<?php

			}?>
									     </tbody> </table> 
							</div>
						</form>

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

		var checkflag = "false";
		function check(field)
		{
			if (checkflag == "false") 
			{
				for (i = 0; i < field.length; i++) 
				{
					field[i].checked = true;
				}
				checkflag = "true";
				<? $checkflag  = '1' ?>
				return "Uncheck All"; 
			}
			else 
			{
				for (i = 0; i < field.length; i++) 
				{
					field[i].checked = false; 
				}
				checkflag = "false";
				<? $checkflag  = '0' ?>
				return "Check All"; 
			}
		}
	</script>
	<!--js -->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php }  ?>