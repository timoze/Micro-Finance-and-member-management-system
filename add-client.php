<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
    if(isset($_POST['submit']))
  {

//$clientmsaid=$_SESSION['clientmsaid'];
 //$acctid=mt_rand(100000000, 999999999);
 //$accttype=$_POST['accounttype'];
// $password=md5($_POST['password']);
 $cname=$_POST['cname'];
 $comname=$_POST['comname'];
 $address=$_POST['address'];
 $city=$_POST['city'];
 $state=$_POST['state'];
//$zcode=$_POST['zcode'];
 $wphnumber=$_POST['wphnumber'];
 $guarantor_cell=$_POST['guarantor_cell'];
 //$ophnumber=$_POST['ophnumber'];
 $email=$_POST['email'];
// $websiteadd=$_POST['websiteadd'];
 $notes=$_POST['notes'];

 $guarantor=$_POST['guarantor'];
 $guarantor_id=$_POST['guarantor_id'];
 $national_id = $_POST['national_id'];


$query_client_n_id="SELECT NationalID from tblclient ";
$query_client_n_id = $dbh -> prepare($query_client_n_id);
$query_client_n_id->execute();
$result_client_n_id = $query_client_n_id->fetchAll(PDO::FETCH_OBJ);

//$result_invoice = $row_service;
$national_id_array = array();
foreach($result_client_n_id  as $row_client_n_id)
{
	$national_id_array[] = $row_client_n_id->NationalID;
}

if (in_array($national_id, $national_id_array)) {

		echo '<script>alert("Existing Client! Please check from your client list.")</script>';
		echo "<script>window.location.href ='add-client.php'</script>";


}else{
$sql="insert into tblclient(ContactName,CompanyName,Address,City,Family,Clientphnumber,Guarantorphnumber,Email,Notes,NationalID, Guarantor, GuarantorID)values(:cname,:comname,:address,:city,:state,:wphnumber,:guarantor_cell,:email,:notes,:national_id,:guarantor,:gurantorid)";
$query=$dbh->prepare($sql);

$query->bindParam(':cname',$cname,PDO::PARAM_STR);
$query->bindParam(':comname',$comname,PDO::PARAM_STR);
$query->bindParam(':address',$address,PDO::PARAM_STR);
$query->bindParam(':city',$city,PDO::PARAM_STR);
$query->bindParam(':state',$state,PDO::PARAM_STR);
$query->bindParam(':wphnumber',$wphnumber,PDO::PARAM_STR);
$query->bindParam(':guarantor_cell',$guarantor_cell,PDO::PARAM_STR);
//$query->bindParam(':ophnumber',$ophnumber,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->bindParam(':notes',$notes,PDO::PARAM_STR);

$query->bindParam(':national_id',$national_id,PDO::PARAM_STR);
$query->bindParam(':guarantor',$guarantor,PDO::PARAM_STR);
$query->bindParam(':gurantorid',$guarantor_id,PDO::PARAM_STR);
$query->execute();

   $LastInsertId=$dbh->lastInsertId();
   if ($LastInsertId>0) {
    	echo '<script>alert("Client has been added.")</script>';
		echo "<script>window.location.href ='add-client.php'</script>";
  }
  else
    {
         echo '<script>alert("Something Went Wrong. Please try again")</script>';
    }

}

  
}

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Client Management Sysytem|| Add Clients</title>

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
<li class="active">Add Clients</li>
</ol>
</div>	
					<!--/sub-heard-part-->	
					<!--/forms-->
<div class="forms-main">
<h2 class="inner-tittle">Add Clients </h2>
<div class="graph-form">
<div class="form-body">
<form method="post"> 
								
	<div class="form-group"> <label for="exampleInputEmail1">Contact Name</label> <input type="text" name="cname" placeholder="Contact Name" value="" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Home </label> <input type="text" name="state" placeholder="Describe Family" value="" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Type of Business (Industry)</label> <input type="text" name="comname" placeholder="Enter Business Type" value="" class="form-control" required='true'> </div>
	<div class="form-group"> <label for="exampleInputEmail1">Address</label> <textarea type="text" name="address" placeholder="Address" value="" class="form-control" required='true' rows="4" cols="3"></textarea> </div>
	<div class="form-group"> <label for="exampleInputEmail1">City / County</label> <input type="text" name="city" placeholder="City" value="" class="form-control" required='true'> </div>

	<div class="form-group"> <label for="exampleInputEmail1">Client ID / Passport</label><input type="text" name="national_id" value="" placeholder="Cell Phone Number"  class="form-control" maxlength='10' required="true"> </div>
	
	<div class="form-group"> <label for="exampleInputEmail1">Client Phone Number</label><input type="text" name="wphnumber" value="" placeholder="Work Phone Number"  class="form-control" maxlength='10' required='true' pattern="[0-9]+"> </div>

	<div class="form-group"> <label for="exampleInputEmail1">Email Address</label> <input type="email" name="email" value="" placeholder="Email address" class="form-control" required='true'> </div> 

	<div class="form-group"> <label for="exampleInputEmail1">Guarantor</label> <input type="text" name="guarantor" placeholder="Guarantor Details" value="" class="form-control" required='true'> </div>

	<div class="form-group"> <label for="exampleInputEmail1">Guarantor ID / Passport No.</label><input type="text" name="guarantor_id" value="" placeholder="Guarantor ID / Passport Number"  class="form-control" > </div>

	<div class="form-group"> <label for="exampleInputEmail1">Guarantor Phone Number</label><input type="text" name="guarantor_cell" value="" placeholder="Cell Phone Number"  class="form-control" maxlength='10' pattern="[0-9]+"> </div>

	
	<div class="form-group"> <label for="exampleInputEmail1">Collateral / Security</label> <textarea type="text" name="notes" placeholder="Notes" value="" class="form-control" required='true' rows="4" cols="3"></textarea> </div>

	
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