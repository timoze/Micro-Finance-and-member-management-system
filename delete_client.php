<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
	
	//$id=  $_REQUEST['id'];

	$client_id=  $_REQUEST['client_id'];

	$sql="DELETE from tblclient where ID=:eid";	
	$query=$dbh->prepare($sql);
	$query->bindParam(':eid',$client_id,PDO::PARAM_STR);
	$query->execute();

	

	echo "Client Deleted successful";

	echo "<script type='text/javascript'> document.location ='manage-client.php'; </script>";
}


?>