<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
	
	$client_id=  $_REQUEST['client_id'];

	$status = $_REQUEST['status'];

	$sql="UPDATE tblclient SET status=:status where ID=:eid";	
	$query=$dbh->prepare($sql);
	$query->bindParam(':eid',$client_id,PDO::PARAM_STR);
	$query->bindParam(':status',$status,PDO::PARAM_STR);
	$query->execute();


	echo "Client Status Changed successful";

	echo "<script type='text/javascript'> document.location ='manage-client.php'; </script>";
}


?>