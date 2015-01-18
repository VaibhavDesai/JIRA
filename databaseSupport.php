<?php
	include 'const.php';
	
	function syncFetchData($id,$key,$summary,$description,$issuetype,$priority,$status)
	{
	
		$nd = str_replace("'","''",$description);
		echo "<br>".$key;
		$conn = mysql_connect($GLOBALS['dbhost'],$GLOBALS['dbuser'],$GLOBALS['dbpass']);

		if(! $conn)
		{die("Couldnt connect".mysql_error());}

		mysql_select_db('jira');

		$sql = "INSERT INTO all_issues".
		     "(i_id,i_key,i_summary,i_description,i_type,i_priority,i_status)".
		     " VALUES('$id','$key','$summary','$nd','$issuetype','$priority','$status')";
		

		$sqlupdate = "UPDATE all_issues SET i_summary='$summary',i_description='$nd',i_type='$issuetype', i_priority='$priority',i_status='$status' WHERE i_id='$id'";

		if(mysql_query($sql,$conn))
		{ 
		  echo " --->New record created successfully";
		}
		else
		{
		if(mysql_error() == "Duplicate entry '$id' for key 'PRIMARY'")
		{ 
		  if(mysql_query($sqlupdate,$conn))
			{echo " --->synced";}
		   else
		   {echo "Error:".mysql_error();}
		}
		else
		{
		 echo "Error:". mysql_error();}
		}
	}


	function stowIssues($project_key,$summary,$description,$issuetype)
	{
		$conn = mysql_connect($GLOBALS['dbhost'],$GLOBALS['dbuser'],$GLOBALS['dbpass']);
		if(! $conn)
		{ die("Couldnt connect".mysql_error());}
		
		$sql = "INSERT INTO temp_issues(project_key,summary,description,issuetype) VALUES ('$project_key','$summary','$description','$issuetype')";
		
		mysql_select_db('jira');
		
		if(mysql_query($sql,$conn))
		{echo " New record updated!!</br>";}
		else
		{echo "Error:".mysql_error();}			
		
	}

	function fetchTempData()
	{	 
		 $conn = mysql_connect($GLOBALS['dbhost'],$GLOBALS['dbuser'],$GLOBALS['dbpass']);
		 if(! $conn)
		 {die("Couldnt connect,".mysql_error());}
	
		$sql = 'SELECT issue_id,project_key,summary,description,issuetype FROM temp_issues';
		mysql_select_db("jira");
	
		$result = mysql_query($sql,$conn);
		if(! $result)
		{ die("Couldnt get data:".mysql_error());}
		echo "Fetch successfull</br>";
		mysql_close($conn);
		return $result;		 
	}

	function deleteRecord($i_id)
	{
	 $conn = mysql_connect($GLOBALS['dbhost'],$GLOBALS['dbuser'],$GLOBALS['dbpass']);
	if(! $conn)
	     {die("Couldnt connect,".mysql_error());}
	
	mysql_select_db('jira');
	if(! mysql_query("DELETE FROM temp_issues WHERE issue_id='$i_id'"))
			echo "Error ".mysql_error();
		 

	mysql_close($conn);
	}

?>