<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
	
	//$id=  $_REQUEST['id'];

	$invoice_id=  $_REQUEST['invoice_id'];

	$sql="DELETE from invoices where invoice_id=:eid";	
	$query=$dbh->prepare($sql);
	$query->bindParam(':eid',$invoice_id,PDO::PARAM_STR);
	$query->execute();

	$sql2="DELETE from invoice_items where invoice_id=:eid";	
	$query2=$dbh->prepare($sql2);
	$query2->bindParam(':eid',$invoice_id,PDO::PARAM_STR);
	$query2->execute();

	echo "Invoice Deleted successful";

	echo "<script type='text/javascript'> document.location ='invoice_list.php'; </script>";
}


?>