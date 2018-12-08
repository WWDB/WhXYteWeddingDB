<?php
// works in conjunction unsubscribe.html which has a form for a person to remove themselves from future emails from the cahrity.
//created by Amanda Suydam, November 2018

	// else connect to the database
	$servername = "localhost";
	$username="dbuser";
	$password="M4dr!9aL$";
	$dbname="hanadesigns_whxytewedding";

	$link= new mysqli($servername,$username,$password,$dbname);

	if($link->connect_errno) {
		die("Connection failed : " . $mysqli->connect_error);
	}
	// get email from form and make sure it's not empty; meaning someone came straight to the php and it going to get an error here
	$em = mysqli_real_escape_string($link, $_REQUEST['email']);
	if ($em==""){
		header("Location: https://hanahopedb.net/testview/unsubscribe.html");  // Redirect to login
		exit();
	}
	
	// make an sql update to change to inactive
	$sql = "UPDATE Person SET Status='Inactive' WHERE Email = '$em'";
	// send it through
	if($link->query($sql)){
		echo "$em has been successfully unsubscribed from out email list";
		} else{
			echo "ERROR: Could not execute $sql.".mysqli_error($link);
		};
	mysqli_close($link);
?>