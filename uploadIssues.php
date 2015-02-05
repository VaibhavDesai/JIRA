<?php

include "databaseSupport.php";
include "const.php";

$handle=curl_init();
$headers = array(
    'Accept: application/json',
    'Content-Type: application/json',
);



curl_setopt_array(
	$handle,
	array(
	CURLOPT_URL=>$base_url.'/rest/api/2/issue/',
	CURLOPT_POST=>true,
	CURLOPT_VERBOSE=>1,
	CURLOPT_SSL_VERIFYHOST=> 0,
	CURLOPT_SSL_VERIFYPEER=> 0,
	CURLOPT_RETURNTRANSFER=>true,
	CURLOPT_HEADER=>false,
	CURLOPT_HTTPHEADER=> $headers,
	CURLOPT_USERPWD=>"$username:$password",
	)
);


$result = fetchTempData();
if (! $result){
   throw new My_Db_Exception('Database error: ' . mysql_error());
}

$count = 0;

while($row = mysql_fetch_array($result, MYSQL_NUM))
{
	echo "<br>$row[1]";

	$data = <<<JSON
	{
    	"fields": {
       	"project":{"key": "{$row[1]}"},
       	"summary": "{$row[2]}",
       	"description": "{$row[3]}",
       	"issuetype": {"name": "{$row[4]}"}
   	}
	}
JSON;
	
	curl_setopt($handle,CURLOPT_POSTFIELDS,$data);
	$retval=curl_exec($handle);
	$ch_error = curl_error($handle);

	if ($ch_error) {echo "cURL Error: $ch_error";} 
	else
	{
		$val = json_decode($retval,true);
		$error = false;
		foreach ($val as $key=>$value)
		if($key == "errors")
		{$error = true;}
		
		if($error == true)
		{ 
		echo "Error in the uploaded data<tb>";
		echo $retval;
		}
		else
		{
		$count++;
		echo "Issue". "{$row[0]}"." is uploaded to main server<br>";
		echo $retval;
		deleteRecord("{$row[0]}");
		}
	}
}
curl_close($handle);
echo "<br>Number of issues uploaded are:".$count;
?>
<a href="http://ec2-54-84-127-175.compute-1.amazonaws.com/JIRA/">Back Home</a>
