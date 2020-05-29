<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
	
	$id=  $_REQUEST['id'];

	$invoice_id=  $_REQUEST['invoice_id'];

	$sql="DELETE from invoice_items where id=:eid";	
	$query=$dbh->prepare($sql);
	$query->bindParam(':eid',$id,PDO::PARAM_STR);
	$query->execute();
	echo "Ivoice payment Deleted successful";

	echo "<script type='text/javascript'> document.location ='invoice_payment.php?invoiceid=$invoice_id'; </script>";
}


?>