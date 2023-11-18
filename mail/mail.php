<?php
require("PHPMailerAutoload.php");


function mailsend($email,$body,$subject,$project,$filenames=NULL)
{
	$mail = new PHPMailer();	

	$mail->IsSMTP();                                      	// set mailer to use SMTP
	$mail->CharSet = 'UTF-8';
	$mail->Host = "smtp.hostinger.in";  					// specify main and backup server
	$mail->SMTPAuth = true;    	 							// turn on SMTP authentication
	$mail->Username = "admin@raindetails.in";  	// SMTP username
	$mail->Password = "Admin@Rain";						// SMTP password
	$mail->Port = 587;
	$mail->From = "admin@raindetails.in";
	$mail->FromName = $project . ' '. 'जिल्हा आपत्ती व्यवस्थापन प्राधिकरण';
	$mail->addReplyTo("admin@raindetails.in");	
	foreach($email as $e)
	{
		$mail->AddAddress($e);
	}
	foreach($filenames as $file)
	{
		$mail->addAttachment($file);
	}
	$mail->WordWrap = 50;                                 // set word wrap to 50 character
	$mail->IsHTML(true);                                  // set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = $body;
	$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

	if(!$mail->Send())
	{
		return "Fail";	
	}
	else
	{
		return "Success";
	}
}
