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
	<title>Client Management Sysytem || Search Invoice </title>
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
							<li class="active">Search Invoice</li>
						</ol>
					</div>
					<!--//sub-heard-part-->
					<div class="graph-visual tables-main">
						
					
						<h3 class="inner-tittle two">Search Invoice </h3>
						<div class="graph">

							<div class="tables">
								<form method="post" name="search" action="">
								<p style="font-size:16px; color:red" align="center"> <?php if($msg){
    echo $msg;
  }  ?> </p>

  
							 <div class="form-group"> <label for="exampleInputEmail1">Search by Client ID Number or Name</label> <input id="searchdata" type="text" name="searchdata" required="true" class="form-control">
						
							<br>
							  <button type="submit" name="search" class="btn btn-primary btn-sm">Search</button> </form> 
						</div>
						<?php
						if(isset($_POST['search']))
						{ 

							$sdata=$_POST['searchdata'];
  							?>
  								<h4 align="center">Results against "<?php echo $sdata;?>" keyword(s) </h4> 
								<table class="table" border="1"> <thead> <tr> <th>#</th> 
									<th>Invoice Id</th>
									 <th>Client Name</th> 
									 <th>Family</th>
									 <th>ID / Passport</th>
									 <th>Invoice Date</th>
									 <th>Invoice Amount</th>
									 <th>Repayment <br>Amount</th>
									 <th>Action</th>
									  </tr>
									   </thead>
									    <tbody>
									    	<?php
									$sql="SELECT distinct invoices.invoice_no, invoices.client_id, invoices.invoice_date, invoices.invoice_id, invoices.amount, invoices.repayment_amount, tblclient.NationalID, tblclient.Family, tblclient.ContactName from invoices join tblclient on tblclient.ID=invoices.client_id where (tblclient.NationalID like '%$sdata%' OR tblclient.ContactName like '%$sdata%' )";
									$query = $dbh -> prepare($sql);
									$query->execute();
									$results=$query->fetchAll(PDO::FETCH_OBJ);

									$cnt=1;
									if($query->rowCount() > 0)
									{
									foreach($results as $row)
									{    

										$client_name = $row->ContactName;
										$family = $row->Family;
										$idno = $row->NationalID;

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
									       <td style="text-align: center;" nowrap="nowrap"><?php  echo htmlentities($row->invoice_no);?></td>
									       <td><?php  echo htmlentities($client_name);?></td>
									       <td><?php  echo htmlentities($family);?></td>
									       <td><?php  echo htmlentities($idno);?></td>
									       <td style="text-align: center;"><?php  echo htmlentities(date("d-m-Y", strtotime($row->invoice_date)));?></td> 
									       <td style="text-align: center;"><?php  echo number_format(htmlentities($row->amount));?></td>
									       <td style="text-align: center;"><?php  echo number_format(htmlentities($row->repayment_amount));?></td>
									         
									        <td><?php print $action;?></td>
									     </tr>
									     <?php $cnt=$cnt+1;}}} ?>
									     </tbody> </table> 
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