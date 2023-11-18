<?php
   						
    define ('hostnameorservername',"localhost");	 // Server Name or host Name 
    define ('serverusername','u910794608_adnan'); // database Username 
    define ('serverpassword','Adnan@135'); // database Password 
    define ('databasename','u910794608_adnan'); // database Name 

    
    $project = "Adnan Enterprises";
    $slogan = "Star Traders";
    $officename = "Sangli";
    $officename1 = "Adnan";
    global $connection;
    $connection = @mysqli_connect(hostnameorservername,serverusername,serverpassword,databasename) or die('Connection could not be made to the SQL Server. Please contact report this system error at <font color="blue">7588171304</font>');
   

?>
