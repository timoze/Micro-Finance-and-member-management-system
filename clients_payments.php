<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
function getClientbalance($clientid, $date1, $date2, $dbh)

{	
	//CHECK CLIENT INVOICES

	$sql="SELECT invoice_id, repayment_amount from  invoices WHERE client_id= :clientid ";
	$query_details = $dbh -> prepare($sql);
	$query_details->bindParam(':clientid',$clientid,PDO::PARAM_STR);
	$query_details->execute();

	$results=$query_details->fetchAll(PDO::FETCH_OBJ);

	$total_amnt_paid = 0;
	$total_repay_amt = 0;
	$total_bal_due = 0;

	foreach($results as $row)
	{
		$inv_id = $row->invoice_id;

		$repay_amt = $row->repayment_amount;

		$sql_inv_items="SELECT sum(amount_paid) as amount_pai from  invoice_items WHERE invoice_id=:inv_id and date_paid between :date1 and :date2 group by invoice_id";
		$query_inv_items = $dbh -> prepare($sql_inv_items);
		$query_inv_items->bindParam(':inv_id',$inv_id,PDO::PARAM_STR);
		$query_inv_items->bindParam(':date1',$date1,PDO::PARAM_STR);
		$query_inv_items->bindParam(':date2',$date2,PDO::PARAM_STR);
		$query_inv_items->execute();
		$results_items=$query_inv_items->fetchAll(PDO::FETCH_OBJ);
		$amnt_paid = 0;
		foreach ($results_items as $rs) 
		{
			$amnt_pa = $rs->amount_pai;
			$amnt_paid = $amnt_paid+$amnt_pa;
		}
									
		//$amnt_paid = $results_items->amount_pai;

		$total_amnt_paid = $amnt_paid+$total_amnt_paid;

		$total_repay_amt =$repay_amt + $total_repay_amt; 

	}
	//$total_bal_due[] = $total_amnt_paid;	
	return $total_amnt_paid;

}
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{

  	if (isset($_GET['date2'])) {
  		$date1 = date('Y-m-d',strtotime($_GET['date1']));
  	}else{
  		$date1 = date('Y-m-d');
  	}

  	if (isset($_GET['date2'])) {
  		$date2 = date('Y-m-d',strtotime($_GET['date2']));
  	}else{
  		$date2 = date('Y-m-d');
  	}

  	if (strtotime($date1)==strtotime($date2)) {
  		$desc = "On ". date('d-m-Y', strtotime($date1)); 
  	}else{
  		$desc = "From: " .date("d-m-Y",strtotime($date1))." to ". date("d-m-Y", strtotime($date2));
  	}

  	   
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
	<!-- //lined-icons -->
	<script src="js/jquery-3.5.0.min.js"></script>
	<link href="css/jquery-ui.css" rel='stylesheet' type='text/css' />
	<script src="js/jquery-ui.js"></script>
	<!--clock init-->
	<script src="js/css3clock.js"></script>
	<!--Easy Pie Chart-->
	<!--skycons-icons-->
	<script src="js/skycons.js"></script>

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
							<li class="active">Amount Received </li>
						</ol>
					</div>
					<!--//sub-heard-part-->
					<div class="graph-visual tables-main">
						
					
						<h3 class="inner-tittle two">Amount Received <?php echo $desc; ?></h3>
						<div class="container">
							<form method="post" name="search" action="clients_payments.php">
  
							 <div class="form-group row">
							 	<div class="col-xs-6 col-sm-4">
							 		<input id="date_from" type="text" name="date_from" required="true"  value="<?php echo date('m/d/Y',strtotime($date1));?>"  class="form-control datepick">
							 	</div>
							 	<div class="col-xs-6 col-sm-4">
							 		<input id="date_to" type="text" name="date_to" value="<?php echo date('m/d/Y',strtotime($date2));?>" required="true" class="form-control datepick2">
							 	</div>
							 	<div class="col-xs-6 col-sm-4">
							 		<button type="submit" name="search" class="btn btn-primary btn-sm">Search</button> 
							 	</div>
							 </div>
							  </form> 
						</div>
						<?php
						if(isset($_POST['search']))
						{ 

							$date_from=$_POST['date_from'];
							$date_to=$_POST['date_to'];

							$url = "clients_payments.php?date1=$date_from&date2=$date_to";

							
							print "<script language='javascript'>window.location.href = '".$url."';</script>";

							

						}
						?>
						<div class="graph">
							<form name="form1" method="post" action="clients_payments.php">
							<div class="tables">
							
								<table class="table" border="1">
									<thead> 
										<tr> 
											<th>#</th> 
											<th>Client Name</th>
											<th>Client Home</th>
											<th>Client Contacts</th>
									 		<th>Amount<br>Received</th>
								
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
	
	$client_bal = getClientbalance($row->ID, $date1, $date2, $dbh);
	//$client_balance = $client_bal[0]-$client_bal[1];
	

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
                    if ($client_bal>0) 
                    {
                    	
                    ?>
									     <tr class="active">
									      	
									      	<th scope="row"><?php echo htmlentities($cnt);?></th>
									      
									        <td><?php  echo htmlentities($row->ContactName);?></td> 
									        <td><?php  echo htmlentities($row->Family);?></td> 
									        <td>Cell No. - <?php  echo htmlentities($row->Clientphnumber);?><br>
									        	 </td>

									       	<td style="text-align: right;"><?php print number_format($client_bal,2);?></td>
									        

									        <td style="text-align: center;" nowrap="nowrap"><?php  echo $action;?></td>
									        
									     </tr>

										<input type=hidden name=client_id'<?php echo $row->ID;?>' value="">

									   	<?php 
									   	$cnt=$cnt+1;

									   	$total_received += $client_bal; 
                    				
                    				}

									}
									?>

									<tr class="active">
									      	
									      	<th colspan="4" style="text-align: center;">Total Received</th>
									      
									        <th style="text-align: right;"><?php print number_format($total_received,2);?></th>
									        

									        <th>&nbsp;</th>
									        
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

		$( function() {
    		$(".datepick").datepicker();
    		$(".datepick2").datepicker();
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