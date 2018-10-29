<?php

// Define variables and initialize with empty values -->
$nameErr = $emailErr = $messageErr = "";
$FName = $LName = $Street = $City = $State = $Zip = $Phone = $Email = $Notes = "";


// Not sure is this connects, having trouble testing --> 
$servername = "https://db.whxytewedding.ord:8443";
$username = "hanadesigns";
$password = "M4dr!9aL$";
$dbname ="hanadesigns_whxytewedding";

$link = mysqli_connect($servername,$username,$password,$dbname);
if($link->connect_errno) {
	die("Connection failed : " . $mysqli->connect_error);
	}
//error checking to be done: check email format, check if email exists. -->

$Person_ID = '???????';
// Status is automatically marked as active upon registration -->	
$Status = 'Active';
// check for escape characters that might break our database, variable names may be incorrect, not sure if it wants name or id, example looks like name
// it's not liking any of this, example is from tutorial republic
// Further playing, it's not passing the vallues from the html form
$FName = mysqli_real_escape_string($link, $_REQUEST['first_name']);
$LName = mysqli_real_escape_string($link, $_REQUEST['last_name']);
$Street = mysqli_real_escape_string($link, $_REQUEST['street']);
$City = mysqli_real_escape_string($link, $_REQUEST['city']);
$State = mysqli_real_escape_string($link, $_REQUEST['state']);
$Zip = mysqli_real_escape_string($link, $_REQUEST['zip']);
$Phone = mysqli_real_escape_string($link, $_REQUEST['phone_num']);
$Email = mysqli_real_escape_string($link, $_REQUEST['email']);
// Availibilty- I really need the answer as to how this will be handled, am I concatenating the inputs or are they being kept separate?
//$availibilty
$Notes = 'tired of working on this today'; //mysqli_real_escape_string($link, $_REQUEST['notes']);



// other error checking not yet in place



// insert form data into the database-->
// Person has: Person_ID, First_Name, Last_Name, Street, City, State, Zip, Email, Phone_Num, Password -->
// What are we doing for Person_ID and password?
	$sql1 = "INSERT INTO Person (Person_ID, First_Name, Last_Name, Street, City, State, Zip, Email, Phone_Num) VALUES (FName, LName, Street, City, State, Zip, Email, Phone)"; 
	$sql2 = "INSERT INTO Volunteer (Person_ID, Availability, Status)";
	if(mysqli_query($link, $sql1)){
		if(mysqli_query($link, $sql2)){
			echo "Thank you for your interest in volunteering with us!<br> A Volunteer coordinator will be in touch with shortly.";
		};
	} else{
		echo "ERROR: Could not execute $sql.".mysqli_error($link);
	};

// This still needs an auto generated email to the volunteer coordinator informing of them of a new volunteer registration.
// get the volunteer coordinator's email from the database
// $email = GET from coordinator where role is volunteer coordinator;
// $subject = "New Volunteer Registration";
// $email = send the volunteer information to the coordinator. 
// send it off
// mail($email, $subject, $message)
	
//<!-- All done with insertion, close the link to the database -->	
	mysqli_close($link)
?>
</body>
