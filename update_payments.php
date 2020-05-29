<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
	
	$id=  $_POST['idno'];

	$datepaid=  $_POST['datepaid'];
	
	$amount_paid=  $_POST['amount_paid'];

	$date_paid = date("Y-m-d", strtotime($datepaid));

	$sql="update invoice_items set amount_paid=:amounpaid,date_paid=:datepaid where id=:eid";

	$query=$dbh->prepare($sql);
	$query->bindParam(':amounpaid',$amount_paid,PDO::PARAM_STR);
	$query->bindParam(':datepaid',$date_paid,PDO::PARAM_STR);
	$query->bindParam(':eid',$id,PDO::PARAM_STR);
	$query->execute();
	echo "Ivoice payment updated successful";
}


?>