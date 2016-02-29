<?php
if($_POST)
{
	$to_Email   	= "hello@saltaudios.com"; //Replace with recipient email address
	$subject        = 'Salt Audios'; //Subject line for emails
	
	//check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	
		//exit script outputting json data
		$output = json_encode(
		array(
			'type'=>'error', 
			'text' => 'Request must come from Ajax'
		));
		
		die($output);
    } 
	
	//check $_POST vars are set, exit if any missing
	if(!isset($_POST["userName"]) || !isset($_POST["userEmail"]) || !isset($_POST["userPhone"]) || !isset($_POST["userCompany"]) || !isset($_POST["userMessage"]))
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
		die($output);
	}

	//Sanitize input data using PHP filter_var().
	$user_Name        = filter_var($_POST["userName"], FILTER_SANITIZE_STRING);
	$user_Email       = filter_var($_POST["userEmail"], FILTER_SANITIZE_EMAIL);
	$user_Phone       = filter_var($_POST["userPhone"], FILTER_SANITIZE_STRING);
	$user_Company     = filter_var($_POST["userCompany"], FILTER_SANITIZE_STRING);
	$user_Message     = filter_var($_POST["userMessage"], FILTER_SANITIZE_STRING);
	
	//additional php validation
	if(strlen($user_Name)<4) // If length is less than 4 it will throw an HTTP error.
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
		die($output);
	}
	if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //email validation
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
		die($output);
	}
	if(!is_numeric($user_Phone)) //check entered data is numbers
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Only numbers allowed in phone field'));
		die($output);
	}
	if(strlen($user_Company)<1) // If length is less than 4 it will throw an HTTP error.
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Company Name is too short or empty!'));
		die($output);
	}
	if(strlen($user_Message)<2) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something.'));
		die($output);
	}
	
	//proceed with PHP email.
	$headers = 'From: '.$user_Email.'' . "\r\n" .
	'Reply-To: '.$user_Email.'' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	
	$sentMail = @mail($to_Email, $subject, "Name: ".$user_Name."\n"."\n"."Email: ".$user_Email."\n"."\n"."Phone Number: ".$user_Phone."\n"."\n"."Company: ".$user_Company."\n"."\n"."Requirement: ".$user_Message, $headers);
	
	//proceed with PHP email.
	$headers2 = 'From: '.$to_Email.'' . "\r\n" .
	'Reply-To: '.$to_Email.'' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	
	$sentMail = @mail($user_Email, $subject, 'Hi '.$user_Name .','."\n"."\n".' Thank you for contacting Salt Audios. Your message is very important to us, we will return your request as soon as we can ...', $headers2);
	
	if(!$sentMail)
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
		die($output);
	}else{
		$output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_Name .','.' Thank you for contacting Salt Audios. Your message is very important to us, we will return your request as soon as we can ...'));
		die($output);
	}
}
?>