<?php 
error_reporting(0);
ini_set('max_execution_time', 180);
date_default_timezone_set("Africa/Nairobi");
 
$servername = "localhost";
$username 	= "root";
$password 	= "";
	
$conn = new mysqli($servername, $username, $password);

$branch_url = $_SERVER['HTTP_HOST'];
$exp=explode(":", $branch_url);

if (isset($exp[1])) {
	$branch_code = $exp[1];
}else{
	$branch_code = "80";
}

$fb = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, company_name, db_name, company_code FROM companybranches.branchdata WHERE company_port = '$branch_code' AND agent_status='1'"));
$agent_id 		= $fb['id'];
$company_name 	= $fb['company_name'];
$database 		= $fb['db_name'];
$company_code 	= $fb['company_code'];

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME',$database);
// Establish database connection.
try
{
	$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
	exit("Error: " . $e->getMessage());
}
?>