<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
    if(isset($_POST['submit']))
  {

  $eid=$_REQUEST['editid'];


 $client_id 			=	$_POST['client_id'];
 $amount 				=	$_POST['amount'];
 $repayment_amount		=	$_POST['repayment_amount'];
 $scharge 				=	$_POST['scharge'];

 //$repayment_startdate	=	date("Y-m-d", strtotime($_POST['repayment_startdate']));
 //$invoice_date 			=	date("Y-m-d", strtotime($_POST['invoice_date']));

 $repayment_startdate = date('Y-m-d', strtotime($_POST['repayment_startdate']));
 //$rep_m = $repayment_stat_arr[1];
 //$rep_d = $repayment_stat_arr[0];
 //$rep_y = $repayment_stat_arr[2];

 $invoice_date = date('Y-m-d', strtotime($_POST['invoice_date']));
 //$inv_m = $invoice_date_arr[1];
 //$inv_d = $invoice_date_arr[0];
 //$inv_y = $invoice_date_arr[2];
 //$invoice_date	=	date("$inv_y-$inv_m-$inv_d");
// $repayment_startdate	=	date("$rep_y-$rep_m-$rep_d");

 
 
$sql="UPDATE invoices SET client_id=:client_id,amount=:amount,repayment_amount=:repayment_amount,repayment_startdate=:repayment_startdate,invoice_date=:invoice_date,service_charge=:service_charge WHERE invoice_id=:eid";
$query=$dbh->prepare($sql);
$query->bindParam(':client_id',$client_id,PDO::PARAM_STR);
$query->bindParam(':amount',$amount,PDO::PARAM_STR);
$query->bindParam(':repayment_amount',$repayment_amount,PDO::PARAM_STR);
$query->bindParam(':repayment_startdate',$repayment_startdate,PDO::PARAM_STR);
$query->bindParam(':invoice_date',$invoice_date,PDO::PARAM_STR);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->bindParam(':service_charge',$scharge,PDO::PARAM_STR);

$query->execute();


    echo '<script>alert("Invoice has been updated added.")</script>';
	echo "<script>window.location.href ='edit_invoice_details.php?editid=$eid'</script>";


}


?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Client Management Sysytem|| Edit Invoice</title>

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

	<script src="js/skycons.js"></script>
	<link href="select2/dist/css/select2.min.css" rel="stylesheet" />
	<script src="select2/dist/js/select2.min.js"></script>
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
<li class="active">Edit Invoice Details</li>
</ol>
</div>	
					<!--/sub-heard-part-->	
					<!--/forms-->
<div class="forms-main">
<h2 class="inner-tittle">Edit Invoice Details </h2>
<div class="graph-form">
<div class="form-body">
<form method="post"> 

<?php
$eid=$_GET['editid'];
$sql="SELECT * from invoices where invoice_id=:eid";
$query = $dbh -> prepare($sql);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $rw)
{               
	?>	
	


	<div class="form-group"> <label for="exampleInputEmail1">Invoice Date</label> <input type="text" name="invoice_date" value="<?php echo date('m/d/Y', strtotime($rw->invoice_date));?>" class="form-control datepick" required='true'> </div>

	<div class="form-group"> 
		<label for="exampleInputEmail1">Client</label> 

		<select name="client_id" id="client_id" placeholder="Select Client ..." class="form-control client_id" required='true'>
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
					{  ?>
    			    	<option value="<?php  echo $row_clients->ID;?>"<?php if($rw->client_id==$row_clients->ID) echo "selected";?>><?php  echo $row_clients->ContactName;?></option>
    			    
    			    	<?php 
    			    	$cnt=$cnt+1;
    				}
    			} 

    		?>
			
		</select>  


	</div>
	<div class="form-group"> <label for="exampleInputEmail1">Service Charge</label> <input type="number" name="scharge" placeholder="Service Charge" value="<?php echo $rw->service_charge;?>" class="form-control" required='true'> </div>

	<div class="form-group"> <label for="exampleInputEmail1">Amount Given</label> <input type="number" name="amount" placeholder="Amount Given" value="<?php echo $rw->amount;?>" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Amount to Be Paid</label> <input type="number" name="repayment_amount" placeholder="Repayment Amount" value="<?php echo $rw->repayment_amount;?>" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Repayment Start Date</label> <input type="text" name="repayment_startdate" value="<?php echo date("m/d/Y", strtotime($rw->repayment_startdate));?>" class="form-control datepick2" required='true'> </div>

	<input type="hidden" name="editid" value="<?php echo $eid;?>">

<?php }} ?>
		
		
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
    		$(".datepick" ).datepicker();
    		$(".datepick2" ).datepicker();
    	});

    	// In your Javascript (external .js resource or <script> tag)
		$(document).ready(function() {
    		$('.client_id').select2();
		});



</script>
	</script>
	<!--js -->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php }  ?>