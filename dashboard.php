<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } 
     ?>
<!DOCTYPE HTML>
<html>
<head>
<title>Client Management System||Dashboard</title>

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
<script src="js/amcharts.js"></script>	
<script src="js/serial.js"></script>	
<script src="js/light.js"></script>	
<script src="js/radar.js"></script>	
<link href="css/barChart.css" rel='stylesheet' type='text/css' />
<link href="css/fabochart.css" rel='stylesheet' type='text/css' />
<!--clock init-->
<script src="js/css3clock.js"></script>
<!--Easy Pie Chart-->
<!--skycons-icons-->
<script src="js/skycons.js"></script>

<script src="js/jquery.easydropdown.js"></script>

<!--//skycons-icons-->
</head> 
<body>
<div class="page-container">
	<!--/content-inner-->
	<div class="left-content">
		<div class="inner-content">
		
			<?php include_once('includes/header.php');?>
			
			<div class="outter-wp">
				<!--custom-widgets-->
				<div class="custom-widgets">
					<div class="row-one">
						<div class="col-md-4 widget">
							<div class="stats-left ">
								<?php 
$sql ="SELECT ID from tblclient where status='1' ";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$tclients=$query->rowCount();
?>
								<h5>Total</h5>
								<h4> Clients</h4>
							</div>
							<div class="stats-right">
								<label><?php echo htmlentities($tclients);?></label>
							</div>
							<div class="clearfix"> </div>	
						</div>
						<div class="col-md-4 widget states-mdl">
							<div class="stats-left">
							<?php 
$sql1 ="SELECT service_id from services where status='1' ";
$query1 = $dbh -> prepare($sql1);
$query1->execute();
$results1=$query1->fetchAll(PDO::FETCH_OBJ);
$tser=$query1->rowCount();
?>	
								<h5>Total</h5>
								<h4>Services</h4>
							</div>
							<div class="stats-right">
								<label> <?php echo htmlentities($tser);?></label>
							</div>
							<div class="clearfix"> </div>	
						</div>
						
						<div class="col-md-4 widget">
							<div class="stats-left">
								<?php
$sql6="select  sum(amount_paid) as todaysale from invoice_items where date(date_paid)=CURDATE() and status='1' ";

  $query6 = $dbh -> prepare($sql6);
  $query6->execute();
  $results6=$query6->fetchAll(PDO::FETCH_OBJ);
  foreach($results6 as $row6)
{

$todays_sale=$row6->todaysale;
}


  ?>
								<h5>Today</h5>
								<h5>Received(KSh)</h5>
							</div>
							<div class="stats-right">
								<label><?php echo $todays_sale;?></label>
							</div>
							<div class="clearfix"> </div>	
						</div>
						<div class="clearfix"> </div>	
					</div>
				</div>
						</div>
						<div class="outter-wp">
				<!--custom-widgets-->
				<div class="custom-widgets">
					<div class="row-one">
					
						<div class="col-md-4 widget states-mdl">
							<div class="stats-left">
								<?php
$sql7="select  sum(amount) as totalcost from invoices where date(invoice_date)=CURDATE() and status='1'; ";

  $query7 = $dbh -> prepare($sql7);
  $query7->execute();
  $results7=$query7->fetchAll(PDO::FETCH_OBJ);
  foreach($results7 as $row7)
{

$loans_today=$row7->totalcost;
}


  ?>
								<h5>Today</h5>
								<h5>Loans Given(KSh)</h5>
							</div>
							<div class="stats-right">
								<label> <?php echo $loans_today;?></label>
							</div>
							<div class="clearfix"> </div>	
						</div>
						<div class="col-md-4 widget states-thrd">
							<div class="stats-left">
								<?php
$sql8="select  sum(service_charge) as totalcost from invoices where date(invoice_date)=CURDATE() and status='1';";

  $query8 = $dbh -> prepare($sql8);
  $query8->execute();
  $results8=$query8->fetchAll(PDO::FETCH_OBJ);
  foreach($results8 as $row8)
{

$today_service_charge=$row8->totalcost;
}


  ?>

								<h5>Todays</h5>
								<h5>Service Charge(KSh)</h5>
							</div>
							<div class="stats-right">
								<label><?php echo $today_service_charge;?></label>
							</div>
							<div class="clearfix"> </div>	
						</div>
						<div class="col-md-4 widget states-last">
							<div class="stats-left">
								<?php
$sql9="select  sum(amount_paid) as totalcost from invoice_items where status='1' ";

  $query9 = $dbh -> prepare($sql9);
  $query9->execute();
  $results9=$query9->fetchAll(PDO::FETCH_OBJ);
  foreach($results9 as $row9)
{

$total_sale=$row9->totalcost;
}


  ?>
								<h5>Total</h5>
								<h5>Received(KSh)</h5>
							</div>
							<div class="stats-right">
								<label><?php echo $total_sale;?></label>
							</div>
							<div class="clearfix"> </div>	
						</div>
						<div class="clearfix"> </div>	
					</div>
				</div>
						</div>
		
		<?php include_once('includes/footer.php');?>
		
	</div>
</div>
<!--//content-inner-->

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
<link rel="stylesheet" href="css/vroom.css">
<script type="text/javascript" src="js/vroom.js"></script>
<script type="text/javascript" src="js/TweenLite.min.js"></script>
<script type="text/javascript" src="js/CSSPlugin.min.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>