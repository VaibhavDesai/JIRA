<?php
include "const.php";
include "databaseSupport.php";
/* try to connect */
$inbox = imap_open($email_hostname,$email_username,$email_password) or die('Cannot connect to Gmail: ' . imap_last_error());

/* grab emails from a specific email(this is for testing)*/
$emails = imap_search($inbox,'FROM "desaivaibhav94@gmail.com"');

//$emails = imap_search($inbox,'ALL');

/* if emails are returned, cycle through each... */
if($emails) {
	
	/* begin output var */
	$output = '';
	rsort($emails);
 	 
	/* put the newest emails on top */
	
	foreach ($emails as $email_number)
	{
		$email_number = $emails[0];
		//using headerinfo we can gather the information about the sender.
		$header = imap_headerinfo($inbox,$email_number);
		$summary = $header->subject;
		$body = imap_fetchbody($inbox,$email_number,1);
		if(preg_match("/#[a-zA-Z]+/",$body,$match)&&preg_match("/@[a-zA-Z]+/",$body,$match))
		{
		$content = preg_replace("/#[a-zA-Z]+/","",$body);
		$description = preg_replace("/@[a-zA-Z]+/","",$content);
		preg_match("/#[a-zA-Z]+/",$body,$issueType);
		preg_match("/@[a-zA-Z]+/",$body,$project_key);
		//echo "project key ". substr($project_key[0],1)." </br> summary is $summary </br> description is </br>$description</br>issue type is ". substr($issueType[0],1)."<br>";
		stowIssues(substr($project_key[0],1),$summary,$description,substr($issueType[0],1));
		}
	}
}
imap_close($inbox);
?>
