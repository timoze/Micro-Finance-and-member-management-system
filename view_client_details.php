<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
  	$com_nam = $company_name;
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
  				
  				#other{
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
		<div class="graph-visual tables-main" id="exampl">
						
					
						<h3 class="inner-tittle two">Client Details </h3>
<?php
$client_id=intval($_GET['client_id']);


	$sql_client="SELECT * from  tblClient WHERE ID= :client_id";
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
		$GuarantorID = $rowclient->GuarantorID;
		//$guarantor = $rowclient->guarantor;
		$path = $rowclient->path;
		$collateral = $rowclient->Notes;
	}




	?>
						<div class="graph">
							<div class="tables">
								<h4><?php print $com_nam;?></h4>
									<table class="table table-bordered" width="100%" border="1"> 
							<tr>
								<th colspan="3" style="text-align: center;">Client Details</th>	
							</tr>

							<tr> 
								
								<th>Client Name</th> 
								<td><?php  echo htmlentities($client_name);?></td>
								<td rowspan="5"><img src="<?php echo $path;?>" width="250px" height="230px"></td>
							</tr>
							 <tr> 
								
								<th>Client Business</th> 
								<td><?php  echo htmlentities($company_name);?></td>
							</tr>
							<tr>
								<th>ID / Passport No.</th> 
								<td><?php  echo htmlentities($nationalid);?></td>
							</tr> 
							<tr>
							
								<th>Phone Number</th> 
								<td><?php echo  htmlentities($phnumber);?></td> 
							</tr> 
							 <tr> 
								<th>Family</th> 
								<td><?php echo htmlentities($family);?></td>
							</tr> 
							<tr>
								<th>Collateral</th> 
								<td colspan="2"><?php echo  htmlentities($collateral);?></td> 
								
							</tr> 
							<tr>
								<th colspan="3" style="text-align: center;">Guarantor Details</th>	
							</tr>
							<tr>
								<th>Guarantor</th> 
								<td colspan="2"><?php echo  htmlentities($guarantor);?></td> 
								
							</tr>
							<tr>								
								<th>Guarantor ID/Passport</th> 
								<td colspan="2"><?php  echo htmlentities($GuarantorID);?></td>
							</tr>
							<tr>								
								<th>Guarantor Contact</th> 
								<td colspan="2"><?php  echo htmlentities($gphnumber);?></td>
							</tr> 

							<tr>
								<th colspan="3" style="text-align: center;">Signatures</th>	
							</tr>
							<tr>
								<th>Cient Signature</th> 
								<td colspan="2"></td> 
								
							</tr>
							<tr>								
								<th>Guarantor Signature</th> 
								<td colspan="2"></td>
							</tr>
							<tr>								
								<th>Company Signature</th> 
								<td colspan="2"></td>
							</tr> 


</table>

<p style="margin-top:1%"  align="center" id="other">
  <i class="fa fa-print fa-2x" style="cursor: pointer;"  OnClick="window.print();" ></i>
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

</script>
</body>
</html>
<?php }  ?>