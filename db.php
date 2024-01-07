<?php
   						
    define ('hostnameorservername',"localhost");	 // Server Name or host Name 
    define ('serverusername','root'); // database Username 
    define ('serverpassword',''); // database Password 
    define ('databasename','adnan'); // database Name 

    
    $project = "Adnan Enterprises";
    $slogan = "Star Traders";
    $officename = "Sangli";
    $officename1 = "Adnan";
    global $connection;
    $connection = @mysqli_connect(hostnameorservername,serverusername,serverpassword,databasename) or die('Connection could not be made to the SQL Server. Please contact report this system error at <font color="blue">7588171304</font>');
   

?>
