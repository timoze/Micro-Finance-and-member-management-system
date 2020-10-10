<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['clientmsaid']==0)) {
  header('location:logout.php');
  } else{
//if(isset($_POST['submit']))
        

if (isset($_GET['menucat_id']) && isset($_GET['id'])) {
  $menu_ids = $_GET['menucat_id'];
  $sub_ids = $_GET['id'];
}
else{
  $menu_ids = '4';
  //$menu_ids = '42';
}

		if ($menu_ids==4) {
           $hrefs = "dashboard.php";
        }
        else
        {

        	$stat ='1';

            $sqlmenu_subs="SELECT * from  menu_sub where status=:aid and menu_id=:menuid and sub_id=:subs";
			$query_menu_subs = $dbh -> prepare($sqlmenu_subs);
			$query_menu_subs->bindParam(':subs',$sub_ids,PDO::PARAM_STR);
			$query_menu_subs->bindParam(':aid',$stat,PDO::PARAM_STR);
			$query_menu_subs->bindParam(':menuid',$menu_ids,PDO::PARAM_STR);
			$query_menu_subs->execute();
			$result_menu_subs=$query_menu_subs->fetchAll(PDO::FETCH_OBJ);
			if($query_menu_subs->rowCount() > 0)
			{
				foreach($result_menu_subs as $row_menu_subs)
				{ 
					//$sub_id 	= $row_menu_subs->sub_id;
					$sub_descs	= $row_menu_subs->description;	
					$hrefs		= $row_menu_subs->href; 
					
				}
			}
		}

  ?>
<html>
<head>
<title><?php echo $sub_descs;?></title>
<link rel="stylesheet" href="styless.css" />
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
  <script type="text/javascript">
        //function resizeIframe(obj) {
        //  obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        //}


function resizeIFrameToFitContent(iFrame) {

    iFrame.style.height = iFrame.contentWindow.document.body.scrollHeight + 'px';
}

window.addEventListener('DOMContentLoaded', function(e) {

    var iFrame = document.getElementById( 'iframeID' );
    resizeIFrameToFitContent( iFrame );
    
} );
    
   
  </script>
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
					<!--
<div class="sub-heard-part">
<ol class="breadcrumb m-b-0">
<li><a href="dashboard.php">Home</a></li>
<li class="active">Update Clients</li>
</ol>
</div>	-->
					<!--/sub-heard-part-->	
					<!--/forms-->


						
								<div class="main">

									<iframe width="100%" name="iframeID" gesture="media" src="<?php print $hrefs; ?>" id = "iframeID"  frameborder="0" scrolling="yes" onload="resizeIFrameToFitContent(this)"></iframe>
								</div>
							
						



</div> 
          

<?php include_once('includes/footer.php');?>
</div>
</div>		

<div class="sidebar-menu">
    <header class="logo">
        <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> <a href="dashboard.php"> <span id="logo"> <h1>CMS</h1></span> 
            <!--<img id="logo" src="" alt="Logo"/>--> 
        </a> 
    </header>
    <div style="border-top:1px solid rgba(69, 74, 84, 0.7)"></div>
    <!--/down-->
    <div class="down">  
<?php
$aid=$_SESSION['clientmsaid'];
$sql="SELECT AdminName from  tbladmin where ID=:aid";
$query = $dbh -> prepare($sql);
$query->bindParam(':aid',$aid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               
    ?>
        <a href="dashboard.php"><img src="images/images.jpg" height="70" width="70"></a>
        <a href="dashboard.php"><span class=" name-caret"><?php  echo $row->AdminName;?></span></a>
        
        <?php $cnt=$cnt+1;}} ?>
        <ul>
            <li><a class="tooltips" href="admin-profile.php"><span>Profile</span><i class="lnr lnr-user"></i></a></li>
            <li><a class="tooltips" href="change-password.php"><span>Settings</span><i class="lnr lnr-cog"></i></a></li>
            <li><a class="tooltips" href="logout.php"><span>Log out</span><i class="lnr lnr-power-switch"></i></a></li>
        </ul>
    </div>
    <!--//down-->
    <div class="menu">
        <ul id="menu" >


          <li><a href="dashboard.php"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>

<?php
//$aid=$_SESSION['clientmsaid'];
$status='1';
$sqlmenu="SELECT * from  menu where status=:aid order by ordering";
$query_menu = $dbh -> prepare($sqlmenu);
$query_menu->bindParam(':aid',$status,PDO::PARAM_STR);
$query_menu->execute();
$result_menu=$query_menu->fetchAll(PDO::FETCH_OBJ);
if($query_menu->rowCount() > 0)
{
	foreach($result_menu as $row_menu)
	{ 
		$menu_id	= $row_menu->menu_id;
		$main_desc	= $row_menu->description;	
		$href1		= $row_menu->href1; 
		$faicons	= $row_menu->faicons;               
    	
    	?>
    		<li id="menu-academico" ><a href="<?php echo $href1;?>"><i class="<?php echo $faicons;?>"></i> <span> <?php echo $main_desc; ?></span> <span class="fa fa-angle-right" style="float: right"></span></a>
                <ul id="menu-academico-sub" >

    	<?php

    	$sqlmenu_sub="SELECT * from  menu_sub where status=:aid and menu_id=:menuid ";
		$query_menu_sub = $dbh -> prepare($sqlmenu_sub);
		$query_menu_sub->bindParam(':aid',$status,PDO::PARAM_STR);
		$query_menu_sub->bindParam(':menuid',$menu_id,PDO::PARAM_STR);
		$query_menu_sub->execute();
		$result_menu_sub=$query_menu_sub->fetchAll(PDO::FETCH_OBJ);
		if($query_menu_sub->rowCount() > 0)
		{
			foreach($result_menu_sub as $row_menu_sub)
			{ 
				$sub_id 	= $row_menu_sub->sub_id;
				$sub_desc	= $row_menu_sub->description;	
				$href		= $row_menu_sub->href; 

				?>

    				<li id="menu-academico-boletim" ><a href="main.php?menucat_id=<?php echo $menu_id;?>&id=<?php echo $sub_id;?>"><?php echo $sub_desc; ?></a></li>

    			<?php
				
			}
		}
		?>
			 </ul>
            </li>

		<?php 
	}
} 
?>


           
            <li><a href="search-invoices.php"><i class="fa fa-search"></i> <span>Search Invoice</span></a></li>
            
      
        </ul>
    </div>
</div>



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
<?php } ?>
