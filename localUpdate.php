<?php
	include "databaseSupport.php";
	$project_key = $_POST['project_key'];
	$summary = $_POST['summary'];
	$description = $_POST['description'];
	$issuetype = $_POST['issuetype'];

	stowIssues($project_key,$summary,$description,$issuetype);
?>
<a href="http://ec2-54-84-127-175.compute-1.amazonaws.com/JIRA/">Back Home</a>