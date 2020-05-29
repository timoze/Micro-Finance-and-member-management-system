<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
    if(isset($_POST['submit']))
  {
$eid=$_GET['id'];

 $repayment_date=date('Y-m-d', strtotime($_POST['repayment_date']));
 $installment_amount=$_POST['installment_amount'];
 
$sql="update invoice_items set repayment_date=:repayment_date,amount=:repayment_amount where id=:eid";
$query=$dbh->prepare($sql);
$query->bindParam(':repayment_date',$repayment_date,PDO::PARAM_STR);
$query->bindParam(':repayment_amount',$installment_amount,PDO::PARAM_STR);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);

$query->execute();
echo '<script>alert("Payment details has been updated")</script>';
echo "<script type='text/javascript'> document.location ='edit_payment_details.php?id=$eid'; </script>";
  }
  ?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Client Management Sysytem|| Update Payment Details</title>

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
	<script src="js/jquery-1.10.2.min.js"></script>
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
	
<?php //include_once('includes/header.php');?>
				<!--//outer-wp-->
<div class="outter-wp">
					<!--/sub-heard-part-->
<div class="sub-heard-part">
<ol class="breadcrumb m-b-0">
<li><a href="dashboard.php">Home</a></li>
<li class="active">Update Payment Details</li>
</ol>
</div>	
					<!--/sub-heard-part-->	
					<!--/forms-->
<div class="forms-main">
<h2 class="inner-tittle">Update Payment Details </h2>
<div class="graph-form">
<div class="form-body">
<form method="post"> 
	<?php
$eid=$_GET['id'];
$sql="SELECT * from invoice_items where id=:eid";
$query = $dbh -> prepare($sql);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{           
	//$repaymentdate = date("m/d/Y", $row->repayment_date ) ;   

	?>								
	
	<div class="form-group"> <label for="exampleInputEmail1">Repayment Date</label> <input type="text" name="repayment_date" value="<?php  echo date('m/d/Y', strtotime($row->repayment_date));?>" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Installment Amount</label> <input type="text" name="installment_amount" value="<?php  echo $row->amount;?>" class="form-control" required='true'> </div>

	<div class="form-group"> <label for="exampleInputEmail1">Amount Paid</label> <input type="text" name="amount_paid" value="<?php  echo $row->amount_paid;?>" class="form-control" readonly> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Date Paid</label> <input type="text" name="date_paid" value="<?php  echo date('d-m-Y',strtotime($row->date_paid));?>" class="form-control" readonly> </div>
	<?php $cnt=$cnt+1;}} ?>
	 <button type="submit" class="btn btn-default" name="submit" id="submit">Update</button><input type="button" class="btn btn-default" value="Back" onClick="history.back();return true;"> </form> 
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
	</script>
	<!--js -->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php }  ?>