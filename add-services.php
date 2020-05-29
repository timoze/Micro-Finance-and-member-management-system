<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
    if(isset($_POST['submit']))
  {


 $sname 			=	$_POST['sname'];
 $scharge 			=	$_POST['scharge'];
 $rate_instalment 	=	$_POST['rate_instalment'];
 $pay_frequency		=	$_POST['pay_frequency'];

 $date_created 		=	date("Y-m-d H:i:s");
 
 
$sql="insert into services(service_description,service_charge, instalment_rate, payment_frequency, date_created) values(:sname,:scharge, :rate_instalment,:pay_frequency,:date_created)";
$query=$dbh->prepare($sql);
$query->bindParam(':sname',$sname,PDO::PARAM_STR);
$query->bindParam(':scharge',$scharge,PDO::PARAM_STR);
$query->bindParam(':rate_instalment',$rate_instalment,PDO::PARAM_STR);
$query->bindParam(':pay_frequency',$pay_frequency,PDO::PARAM_STR);
$query->bindParam(':date_created',$date_created,PDO::PARAM_STR);
 $query->execute();

   $LastInsertId=$dbh->lastInsertId();
   if ($LastInsertId>0) {
    echo '<script>alert("Service has been added.")</script>';
echo "<script>window.location.href ='add-services.php'</script>";
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
	<title>Client Management Sysytem|| Add Services</title>

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
	
<?php include_once('includes/header.php');?>
				<!--//outer-wp-->
<div class="outter-wp">
					<!--/sub-heard-part-->
<div class="sub-heard-part">
<ol class="breadcrumb m-b-0">
<li><a href="dashboard.php">Home</a></li>
<li class="active">Add Services</li>
</ol>
</div>	
					<!--/sub-heard-part-->	
					<!--/forms-->
<div class="forms-main">
<h2 class="inner-tittle">Add Services </h2>
<div class="graph-form">
<div class="form-body">
<form method="post"> 
	
	<div class="form-group"> <label for="exampleInputEmail1">Service Name / Description</label> <input type="text" name="sname" placeholder="Service Name" value="" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Service Charge</label> <input type="number" name="scharge" placeholder="Service charge(100)" value="" class="form-control" required='true'> </div>

	<div class="form-group"> <label for="exampleInputEmail1">Installment Rate</label> <input type="number" name="rate_instalment" placeholder="Installment Rate in percentage (10)" value="" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Payment Frequency</label> <input type="number" name="pay_frequency" placeholder="Frequency in Days e.g daily(1) after two days (2)" value="" class="form-control" required='true'> </div>
		
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
	</script>
	<!--js -->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php }  ?>