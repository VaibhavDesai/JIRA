<?php
	include 'const.php';

	function DBConnection()
	{
		$conn = mysql_connect($GLOBALS['dbhost'],$GLOBALS['dbuser'],$GLOBALS['dbpass']);
		if(! $conn)
			die("Couldnt connect".mysql_error());
		return $conn;
	}	

	function createDatabase()
	{
		$create_db = "CREATE DATABASE ".$GLOBALS['dbname'].";";
		$create_table = "CREATE TABLE jira_temp(".
			      "_id int(11) not null auto_increment,".
			      "project_key varchar(100) not null,".
			      "summary varchar(500) not null,".
			      "description varchar(500) not null,".
			      "issuetype varchar(50) not null,".
			      "PRIMARY KEY(_id));";

		try
		{
			$conn = DBConnection();
			if(! $conn)
				die("Couldnt connect".mysql_error());
			
			if(!mysql_query($create_db,$conn))
				echo mysql_error()."</br>";

			mysql_select_db($GLOBALS['dbname']);
			if(mysql_query($create_table,$conn))
				echo "New table created!!! </br>";
			else
				echo mysql_error()."</br>";
			
		}
		catch(Exception $e)
		{
		echo "exception caught",$e->getMessage(),"\n";
		}
	}

	function syncFetchData($id,$key,$summary,$description,$issuetype,$priority,$status)
	{
	
		$nd = str_replace("'","''",$description);
		echo $key;
		$conn = DBConnection();
		
		mysql_select_db($GLOBALS['dbname']);

		$sql = "INSERT INTO ".$GLOBALS['master_table'].
		     "(jira_id,IdeaTitle,SUMMARY,DESCRIPTION,issuetype,PRIORITY,issuestatus)".
		     " VALUES('$id','$key','$summary','$nd','$issuetype','$priority','$status')";
		

		$sqlupdate = "UPDATE ".$GLOBALS['master_table']." SET SUMMARY='$summary',DESCRIPTION='$nd',issuetype='$issuetype', issuetype='$priority',issuestatus='$status' WHERE jira_id='$id'";
		
		$record_check = "SELECT 1 FROM ".$GLOBALS['master_table']." WHERE jira_id='$id'";
		$result = mysql_query($record_check,$conn);
		if(mysql_num_rows($result) == 0)
			if(mysql_query($sql,$conn))
				echo " --->new record created</br>";
			else
		   		echo "Error:".mysql_error();
		else
			if(mysql_query($sqlupdate,$conn))
			  	echo " --->Record updated successfully</br>";
			else
				echo "Error:". mysql_error(); 

		
	}


	function stowIssues($project_key,$summary,$description,$issuetype)
	{
		$conn = DBConnection();		
		mysql_select_db($GLOBALS['dbname']);

		$sql = "INSERT INTO ".$GLOBALS['temp_jira_table']."(project_key,summary,description,issuetype) VALUES('$project_key','$summary','$description','$issuetype')";
						
		if(mysql_query($sql,$conn))
			echo " New record updated!!</br>";
		else
			echo "Error:".mysql_error();			
		
	}

	function fetchTempData()
	{	 
	 	$conn = DBConnection();
		mysql_select_db($GLOBALS['dbname']);

		$sql = 'SELECT _id,project_key,summary,description,issuetype FROM '.$GLOBALS['temp_jira_table'];
		$result = mysql_query($sql,$conn);
		if(! $result)
			{ die("Couldnt get data:".mysql_error());}
		echo "Fetch successfull</br>";
		mysql_close($conn);
		return $result;		 
	}

	function deleteRecord($i_id)
	{
	 	$conn = DBConnection();
		mysql_select_db($GLOBALS['dbname']);
		if(! mysql_query("DELETE FROM ".$GLOBALS['temp_jira_table']." WHERE _id = '$i_id'"))
			echo "Error ".mysql_error();
		 
		mysql_close($conn);
	}

?>
