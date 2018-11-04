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

$Fname = mysqli_real_escape_string($link, $_REQUEST['first_name']);
$Lname = mysqli_real_escape_string($link, $_REQUEST['last_name']);
$phone = mysqli_real_escape_string($link, $_REQUEST['phone_num']);
$email = mysqli_real_escape_string($link, $_REQUEST['email']);
$notes = mysqli_real_escape_string($link, $_REQUEST['volunteer_role']);
$Status = 'Active';

// I need to add some sort email checking error.

// Enter the volunteer Data into the database
$sql = "INSERT INTO `Person` (`Person_ID`, `First_Name`, `Last_Name`, `Street`, `City`, `State`, `Zip`, `Email`, `Phone_Num`, `Status`, `Notes`) VALUES (NULL, '$Fname', '$Lname', 'NULL', 'NULL', 'NULL', 'NULL', '$email', '$phone', '$Status', '$notes')";

if($link->query($sql)){
echo "Thank you, $Fname, for your interest in volunteering with us as a $notes!<br><br> A Volunteer coordinator will be in touch shortly.";
	} else{
		echo "ERROR: Could not execute $sql.".mysqli_error($link);
	};

// NOT WORKING! It's not fetching the Person_ID at all *****************************************************************************************************
// get the newly assigned person ID	
$sql1 = "SELECT 'Person_ID' FROM Person WHERE Email='$email'";
$result = $link->query($sql1);
if(!$result){
		echo "ERROR: Could not execute $sql.".mysqli_error($link);
	}
//**********************************************************************************************************************************************************	

// reset status to what volunteer is looking for
$Status = 'Active';	
$ID = "9000";  //No! Get the ID from the table since it's assigned
// pass that ID into the volunteer role table
$sql2 = "INSERT INTO 'Volunteer' ('Person_ID', 'Volunteer_Role', 'Availability', 'Status') VALUES ('$ID', '$notes', NULL, '$Status')";
if($link->query($sql2)){
	echo "Volunteer role added to volunteer table.";
	} else {
		echo "ERROR: Could not execute $sql.".mysqli_error($link);
	};	
mysqli_close($link);

?>
</body>