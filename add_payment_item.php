<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
	
	$invoice_id =  $_POST['invoice_id'];

	$service_id =  $_POST['service_id'];

	$query_inv_details="SELECT repayment_amount from invoices where invoice_id=:invoice_id ";
	$query_inv_details = $dbh -> prepare($query_inv_details);
	$query_inv_details->bindParam(':invoice_id',$invoice_id,PDO::PARAM_STR);
	$query_inv_details->execute();
	$result_invoice_details=$query_inv_details->fetchAll(PDO::FETCH_OBJ);

//$result_invoice = $row_service;
	foreach($result_invoice_details as $row_details)
	{ 

		$repayment_amount = $row_details->repayment_amount;

	}

	$query_inv_itms="SELECT repayment_date from invoice_items where invoice_id=:invoice_id order by repayment_date desc limit 1";
	$query_inv_itms = $dbh -> prepare($query_inv_itms);
	$query_inv_itms->bindParam(':invoice_id',$invoice_id,PDO::PARAM_STR);
	$query_inv_itms->execute();
	$result_invoice_itms=$query_inv_itms->fetchAll(PDO::FETCH_OBJ);

//$result_invoice = $row_service;
	foreach($result_invoice_itms as $row_itmsdetails)
	{ 

		$new_repayment_date = $row_itmsdetails->repayment_date;

	}

	$query_service="SELECT instalment_rate, payment_frequency from services where service_id=:service_id";
	$query_service = $dbh -> prepare($query_service);
	$query_service->bindParam(':service_id',$service_id,PDO::PARAM_STR);
	$query_service->execute();
	$result_invoice=$query_service->fetchAll(PDO::FETCH_OBJ);

//$result_invoice = $row_service;
	foreach($result_invoice as $row_service)
	{ 

		$rte = $row_service->instalment_rate;

		$rate = $rte + 2;

		$frequency = $row_service->payment_frequency;

	}

	$amount_per_installment = $repayment_amount/$rate;

		$count = 0;
		
		$count = $frequency+$count;  
    	$is_sunday = date('l', strtotime("$count days",strtotime($new_repayment_date)));
    	if($is_sunday == "Sunday")
    	{
    	    $count=$count+1;
    	}
    	$date_input = date('Y-m-d', strtotime("$count days",strtotime($new_repayment_date)));


    	$sql_invoice_items="INSERT into invoice_items(invoice_id,amount, repayment_date) values(:invoice_id,:amount_per_installment, :date_input)";
		$query_iems=$dbh->prepare($sql_invoice_items);
		$query_iems->bindParam(':invoice_id',$invoice_id,PDO::PARAM_STR);
		$query_iems->bindParam(':amount_per_installment',$amount_per_installment,PDO::PARAM_STR);
		$query_iems->bindParam(':date_input',$date_input,PDO::PARAM_STR);

		$query_iems->execute();

	echo "Invoice payment item added successfully";
}


?>