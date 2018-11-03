<?php
//  borrowed from Peter's since we know his connects. Will the try make all the difference?


	$servername = "localhost";
	$username="dbuser";
	$password="M4dr!9aL$";
	$dbname="hanadesigns_whxytewedding";
	
	$link= new mysqli($servername,$username,$password,$dbname);
	
	if($link->connect_errno) {
		die("Connection failed : " . $mysqli->connect_error);
	}

// Status is automatically marked as active upon registration -->	

// Person_ID(int,6) First_Name(20) Last_Name(30) Street(60) City(25) State(2) Zip(10) Email(60) Phone_Num(25)  Status(Active Inactive) Password(16) Notes(500)

//INSERT INTO `Person` (`Person_ID`, `First_Name`, `Last_Name`, `Street`, `City`, `State`, `Zip`, `Email`, `Phone_Num`, `Status`, `Password`, `Notes`) 
	//VALUES (NULL, 'Sally', 'Walker', '123 Sesame St', 'New York', 'NY', '00013', 'sallywalker@email.com', '303-555-1234', 'Active', NULL, NULL);
//	$Fname = 'Sally';
//	$Lname = 'Walker';
//	$Street = '123 Same St';
//	$City = 'Thornton';
//	$State = 'CO';
//	$Zip = '80229';
//	$email = 'sally@email.com';
//	$phone = '303-555-1234';
//	$Status = 'Active';
//	$notes = 'Please please please work';

$Fname = mysqli_real_escape_string($link, $_REQUEST['first_name']);
$Lname = mysqli_real_escape_string($link, $_REQUEST['last_name']);
$Street = mysqli_real_escape_string($link, $_REQUEST['street']);
$City = mysqli_real_escape_string($link, $_REQUEST['city']);
$State = mysqli_real_escape_string($link, $_REQUEST['state']);
$Zip = mysqli_real_escape_string($link, $_REQUEST['zip']);
$phone = mysqli_real_escape_string($link, $_REQUEST['phone_num']);
$email = mysqli_real_escape_string($link, $_REQUEST['email']);
// Availibilty- I really need the answer as to how this will be handled, am I concatenating the inputs or are they being kept separate?
//$availibilty
$Status = 'Active';
$notes = mysqli_real_escape_string($link, $_REQUEST['notes']);

// This was working with data entered and commented out
$sql = "INSERT INTO `Person` (`Person_ID`, `First_Name`, `Last_Name`, `Street`, `City`, `State`, `Zip`, `Email`, `Phone_Num`, `Status`, `Notes`) VALUES (NULL, '$Fname', '$Lname', '$Street', '$City', '$State', '$Zip', '$email', '$phone', '$Status', '$notes')";

if($link->query($sql)){
echo "Thank you, $Fname, for your interest in volunteering with us!<br><br> A Volunteer coordinator will be in touch shortly.";
	} else{
		echo "ERROR: Could not execute $sql.".mysqli_error($link);
	};


mysqli_close($link);

?>
</body>