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
		$create_table = "CREATE TABLE issues(".
			      "_id int(11) not null auto_increment,".
			      "i_id int(11) null,".
			      "i_key varchar(100) not null,".
			      "i_summary varchar(500) not null,".
			      "i_description varchar(500) not null,".
			      "i_type varchar(50) not null,".
			      "i_priority varchar(50) null,".
			      "i_status varchar(50) null,".
			      "sync boolean DEFAULT false,".
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

		$sql = "INSERT INTO issues".
		     "(i_id,i_key,i_summary,i_description,i_type,i_priority,i_status,sync)".
		     " VALUES('$id','$key','$summary','$nd','$issuetype','$priority','$status','1')";
		

		$sqlupdate = "UPDATE issues SET i_summary='$summary',i_description='$nd',i_type='$issuetype', i_priority='$priority',i_status='$status',sync='1' WHERE i_id='$id'";
		
		$record_check = "SELECT 1 FROM issues WHERE i_id='$id'";
		if(mysql_num_rows(mysql_query($record_check,$conn))== 0)
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

		$sql = "INSERT INTO issues(i_key,i_summary,i_description,i_type) VALUES ('$project_key','$summary','$description','$issuetype')";
				
		if(mysql_query($sql,$conn))
			echo " New record updated!!</br>";
		else
			echo "Error:".mysql_error();			
		
	}

	function fetchTempData()
	{	 
		$conn = DBConnection();
		mysql_select_db($GLOBALS['dbname']);

		$sql = "SELECT _id,i_key,i_summary,i_description,i_type FROM issues WHERE i_id IS NULL";

		$result = mysql_query($sql,$conn);
		if(! $result)
			die("Couldnt get data:".mysql_error());
		echo "Fetch successfull</br>";
		mysql_close($conn);
		return $result;		 
	}

	function deleteRecord($_id)
	{
	 	$conn = DBConnection();
		mysql_select_db($GLOBALS['dbname']);

		if(! mysql_query("DELETE FROM issues WHERE _id='$_id'"))
			echo "Error ".mysql_error();
		 

		mysql_close($conn);
	}

?>
