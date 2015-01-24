<?php

/* try to connect */
$inbox = imap_open($email_hostname,$email_username,$email_password) or die('Cannot connect to Gmail: ' . imap_last_error());

/* grab emails */
$emails = imap_search($inbox,'FROM "desaivaibhav94@gmail.com"');

/* if emails are returned, cycle through each... */
if($emails) {
	
	/* begin output var */
	$output = '';
	rsort($emails);
 	 
	/* put the newest emails on top */
	
	foreach ($emails as $email_number)
	{
		$email_number = $emails[0];
		$header = imap_headerinfo($inbox,$email_number);
		$subject = $header->subject;
		$body = imap_fetchbody($inbox,$email_number,1);
		if(preg_match("/#[a-zA-Z]+/",$body,$match)&&preg_match("/@[a-zA-Z]+/",$body,$match))
		{
		$content = preg_replace("/#[a-zA-Z]+/","",$body);
		$content = preg_replace("/@[a-zA-Z]+/","",$content);
		preg_match("/#[a-zA-Z]+/",$body,$issueType);
		preg_match("/@[a-zA-Z]+/",$body,$project_key);
		echo "project key ". substr($project_key[0],1)." </br> summary is $subject </br> description is </br>$content</br>issue type is ". substr($issueType[0],1)."<br>";	
		}
	}
}
imap_close($inbox);
?>
