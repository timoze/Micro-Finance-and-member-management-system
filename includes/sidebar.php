 <div class="sidebar-menu" id="other">
    <header class="logo">
        <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a> <a href="dashboard.php"> <span id="logo"> <h1><?php print $company_code;?></h1></span> 
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

            <li id="menu-academico" ><a href="#"><i class="fa fa-user"></i> <span> Clients</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                <ul id="menu-academico-sub" >
                    <li id="menu-academico-avaliacoes" ><a href="add-client.php">Add Clients</a></li>
                    <li id="menu-academico-boletim" ><a href="manage-client.php"> Clients List</a></li>
                   
                </ul>
            </li>

            <li id="menu-academico" ><a href="#"><i class="fa fa-table"></i> <span> Services</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                <ul id="menu-academico-sub" >
                    <li id="menu-academico-avaliacoes" ><a href="add-services.php"> Add Services</a></li>
                    <li id="menu-academico-boletim" ><a href="manage-services.php">Manage Services</a></li>
                   
                </ul>
            </li>

            <li id="menu-academico" ><a href="#"><i class="fa fa-file-text-o"></i> <span> Invoices</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                <ul id="menu-academico-sub" >
                    <li id="menu-academico-avaliacoes" ><a href="new_invoice.php">New Invoices</a></li>
                    <li id="menu-academico-boletim" ><a href="invoice_list.php">Invoice List</a></li>
                   
                </ul>
            </li>

             <li id="menu-academico" ><a href="#"><i class="fa fa-table"></i> <span> Reports</span> <span class="fa fa-angle-right" style="float: right"></span></a>
                <ul id="menu-academico-sub" >
                    <li id="menu-academico-avaliacoes" ><a href="clients_with_outstanding_balances.php"> Clients With Balances</a></li>
                    <li id="menu-academico-boletim" ><a href="paid_clients.php">Clients with no Balances</a></li>
                    <li id="menu-academico-boletim" ><a href="debtors_aging.php">Debtors Aging Report</a></li>
                   
                </ul>
            </li>




            <li><a href="search-invoices.php"><i class="fa fa-search"></i> <span>Search Invoice</span></a></li>
            
      
        </ul>
    </div>
</div>