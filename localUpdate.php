<?php
	include "databaseSupport.php";
	$project_key = $_POST['project_key'];
	$summary = $_POST['summary'];
	$description = $_POST['description'];
	$issuetype = $_POST['issuetype'];

	stowIssues($project_key,$summary,$description,$issuetype);
?>