<?php
	include 'const.php';
	include 'databaseSupport.php';

	$headers = array(
		 'Accept: application/json',
		 'Content-Type: application/json'
		 );

	//project_key is taken from the const.php
	$url = "https://sirnatope.atlassian.net/rest/api/2/search?jql=project=$project_key";
	
	$chandler = curl_init();
	
	curl_setopt_array(
	$chandler,
	array(
	CURLOPT_NOBODY=>true,
	CURLOPT_VERBOSE=>false,
	CURLOPT_HTTPHEADER=>$headers,
	CURLOPT_HTTPGET=>true,
	CURLOPT_URL=>$url,
	CURLOPT_USERPWD=>"$username:$password",
	CURLOPT_RETURNTRANSFER=>true
	)
	);

	try
	{
	$result = curl_exec($chandler);
	$json = json_decode((string)$result,true);
	$issues = $json["issues"];
	foreach ($issues as $issue)
	{
		$i_id = $issue["id"];
		$i_key = $issue["key"];
		$i_summary =(string) $issue["fields"]["summary"];
		$description =(string) $issue["fields"]["description"];
		$issuetype =(string) $issue["fields"]["issuetype"]["name"];
		$priority = $issue["fields"]["priority"]["name"];
		$status = $issue["fields"]["status"]["name"];
		
		syncFetchData($i_id,$i_key,$i_summary,$description,$issuetype,$priority,$status);

		
	}
	echo "<br>Number of issues sync are:".count($issues);
	curl_close($chandler);
	}
	catch(Exception $e)
	{
	echo 'An error occured : $error->getMessage()';
	}
	
?>