<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

function getClientbalance($clientid, $dbh)

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

		$sql_inv_items="SELECT sum(amount_paid) as amount_pai from  invoice_items WHERE invoice_id=:inv_id group by invoice_id";
		$query_inv_items = $dbh -> prepare($sql_inv_items);
		$query_inv_items->bindParam(':inv_id',$inv_id,PDO::PARAM_STR);
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
	$total_bal_due = $total_repay_amt - $total_amnt_paid;	
	return $total_bal_due;

}
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
    if(isset($_POST['submit']))
  {

$query_invoice_no="SELECT invoice_no from ser01 ";
$query_invoice_no = $dbh -> prepare($query_invoice_no);
$query_invoice_no->execute();
$result_invoice_no = $query_invoice_no->fetchAll(PDO::FETCH_OBJ);

//$result_invoice = $row_service;
foreach($result_invoice_no  as $row_invoice)
{
	$invoice_no = $row_invoice->invoice_no;
}


 $client_id 			=	$_POST['client_id'];
 $service_id 			=	$_POST['service_id'];
 $amount 				=	$_POST['amount'];
 $repayment_amount		=	$_POST['repayment_amount'];
 $repayment_startdate	=	date("Y-m-d", strtotime($_POST['repayment_startdate']));
 $invoice_date 			=	date("Y-m-d", strtotime($_POST['invoice_date']));

 $date_created 			=	date("Y-m-d H:i:s");


$query_scharge="SELECT service_charge from services where service_id=:service_id ";
$query_scharge = $dbh -> prepare($query_scharge);
$query_scharge->bindParam(':service_id',$service_id,PDO::PARAM_STR);
$query_scharge->execute();
$result_scharge = $query_scharge->fetchAll(PDO::FETCH_OBJ);

//$result_invoice = $row_service;
foreach($result_scharge  as $row_charge)
{
	$service_charge_amt = $row_charge->service_charge;
}

 
 
$sql="INSERT into invoices(client_id,service_id, amount, repayment_amount, repayment_startdate, date_created, invoice_date, invoice_no, service_charge) values(:client_id,:service_id, :amount, :repayment_amount,:repayment_startdate,:date_created, :invoice_date,:invoice_no,:scharge)";
$query=$dbh->prepare($sql);
$query->bindParam(':client_id',$client_id,PDO::PARAM_STR);
$query->bindParam(':service_id',$service_id,PDO::PARAM_STR);
$query->bindParam(':amount',$amount,PDO::PARAM_STR);
$query->bindParam(':repayment_amount',$repayment_amount,PDO::PARAM_STR);
$query->bindParam(':repayment_startdate',$repayment_startdate,PDO::PARAM_STR);
$query->bindParam(':date_created',$date_created,PDO::PARAM_STR);
$query->bindParam(':invoice_date',$invoice_date,PDO::PARAM_STR);
$query->bindParam(':invoice_no',$invoice_no,PDO::PARAM_STR);
$query->bindParam(':scharge',$service_charge_amt,PDO::PARAM_STR);

$query->execute();

$LastInsertId=$dbh->lastInsertId();

$invoice_id = $LastInsertId;

$new_inv_no = $invoice_no+1;

$sql_update_inv_no="update ser01 set invoice_no=:invoice_no";
$query_updte_inv_no=$dbh->prepare($sql_update_inv_no);
$query_updte_inv_no->bindParam(':invoice_no',$new_inv_no,PDO::PARAM_STR);
$query_updte_inv_no->execute();

$query_service="SELECT instalment_rate, payment_frequency from services where service_id=:service_id";
$query_service = $dbh -> prepare($query_service);
$query_service->bindParam(':service_id',$service_id,PDO::PARAM_STR);
$query_service->execute();
$result_invoice=$query_service->fetchAll(PDO::FETCH_OBJ);

//$result_invoice = $row_service;
foreach($result_invoice as $row_service)
{ 

	$rte = $row_service->instalment_rate;

	$rate = $rte + 2;

	$frequency = $row_service->payment_frequency;

}

$amount_per_installment = $repayment_amount/$rate;

$count = 0;

for ($k=0; $k < round($rate); $k++) 
{ 
	//$count = $frequency+$count; 
	if ($k==0) 
	{
		$count = 0;
	 	
	} 
	else{
		$count = $frequency+$count; 
	}
    $is_sunday = date('l', strtotime("$count days",strtotime($repayment_startdate)));
    if($is_sunday == "Sunday")
    {
        $count=$count+1;
    }
    $date_input = date('Y-m-d', strtotime("$count days",strtotime($repayment_startdate)));


    $sql_invoice_items="INSERT into invoice_items(invoice_id,amount, repayment_date) values(:invoice_id,:amount_per_installment, :date_input)";
	$query_iems=$dbh->prepare($sql_invoice_items);
	$query_iems->bindParam(':invoice_id',$invoice_id,PDO::PARAM_STR);
	$query_iems->bindParam(':amount_per_installment',$amount_per_installment,PDO::PARAM_STR);
	$query_iems->bindParam(':date_input',$date_input,PDO::PARAM_STR);

	$query_iems->execute();

}



   if ($LastInsertId>0) {


    echo '<script>alert("Invoice has been added.")</script>';
	echo "<script>window.location.href ='new_invoice.php'</script>";
  }
  else
    {
         echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }

  
}

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Client Management Sysytem|| New Invoice</title>

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
	<!--//skycons-icons-->
</head> 
<body>
<div class="page-container">
<!--/content-inner-->
<div class="left-content">
<div class="inner-content">
	
<?php include_once('includes/header.php');?>
				<!--//outer-wp-->
<div class="outter-wp">
					<!--/sub-heard-part-->
<div class="sub-heard-part">
<ol class="breadcrumb m-b-0">
<li><a href="dashboard.php">Home</a></li>
<li class="active">New Invoice</li>
</ol>
</div>	
					<!--/sub-heard-part-->	
					<!--/forms-->
<div class="forms-main">
<h2 class="inner-tittle">New Invoice </h2>
<div class="graph-form">
<div class="form-body">
<form method="post"> 

	


	<div class="form-group"> <label for="exampleInputEmail1">Invoice Date</label> <input type="text" name="invoice_date" value="<?php echo date("m/d/Y");?>" class="form-control datepick" required='true'> </div>

	<div class="form-group"> 
		<label for="exampleInputEmail1">Client</label> 

		<select name="client_id" placeholder="Select Client ..." class="form-control" required='true'>
			<option value=""></option>

			<?php

				$staus = "1";

				$sqclients="SELECT ContactName, ID from tblclient where status=:staus";
				$query_clients = $dbh -> prepare($sqclients);
				$query_clients->bindParam(':staus',$staus,PDO::PARAM_STR);
				$query_clients->execute();
				$result_clients=$query_clients->fetchAll(PDO::FETCH_OBJ);
				$cnt=1;
				if($query_clients->rowCount() > 0)
				{
					foreach($result_clients as $row_clients)
					{  

						$clientid = $row_clients->ID;

						$client_bal = getClientbalance($clientid, $dbh);

						if ($client_bal<=0) 
						{

							?>
    			    		<option value="<?php  echo $row_clients->ID;?>"><?php  echo $row_clients->ContactName;?> (<?php  echo $client_bal;?>)</option>
    			    
    			    		<?php 
    			    	}


    			    	$cnt=$cnt+1;
    				}
    			} 

    		?>
			
		</select>  


	</div>
	<div class="form-group"> <label for="exampleInputEmail1">Service</label> 
		
		<select name="service_id" placeholder="Select Service Category ..." class="form-control" required='true'>
			<option value=""></option>

			<?php

				$staus = "1";

				$sql_services="SELECT service_description, service_id, instalment_rate from services where status=:staus";
				$query_services = $dbh -> prepare($sql_services);
				$query_services->bindParam(':staus',$staus,PDO::PARAM_STR);
				$query_services->execute();
				$result_services=$query_services->fetchAll(PDO::FETCH_OBJ);
				$cnt=1;
				if($query_services->rowCount() > 0)
				{
					foreach($result_services as $row_services)
					{  ?>
    			    	<option value="<?php  echo $row_services->service_id;?>"><?php  echo $row_services->service_description;?> ===> Rate (<?php  echo $row_services->instalment_rate;?>% per installment)</option>
    			    
    			    	<?php 
    			    	$cnt=$cnt+1;
    				}
    			} 

    		?>
			
		</select>   
	</div>

	<div class="form-group"> <label for="exampleInputEmail1">Amount Given</label> <input type="number" name="amount" placeholder="Amount Given" value="" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Amount to Be Paid</label> <input type="number" name="repayment_amount" placeholder="Amount To be Repaid" value="" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Repayment Start Date</label> <input type="text" name="repayment_startdate" value="" class="form-control datepick2" required='true'> </div>
		
	 <button type="submit" class="btn btn-default" name="submit" id="submit">Save</button> </form> 
</div>
</div>
</div> 
</div>
<?php include_once('includes/footer.php');?>
</div>
</div>		
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