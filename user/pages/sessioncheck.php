<?php

session_start();
ob_start();

include("../../db.php");
// include("../../mail/mail.php");

if (!isset($_SESSION['VALID_YASIN'])) 
{
  header("location:logout.php");
}

?>