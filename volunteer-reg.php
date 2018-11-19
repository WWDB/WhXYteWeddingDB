<?php
// Volunteer Registration page for the WhXYte Wedding Database by the UCDenver Senior Design team.
// This takes the form data from volunteer_reg.html and inserts it into the database
// Written by Amanda Suydam unless noted otherwise, OCT/NOV 2018.

//  set headers to NOT cache a page
	header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
	header("Pragma: no-cache"); //HTTP 1.0
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

//  borrowed from Peter's since we know his connects. 
	$servername = "localhost";
	$username="dbuser";
	$password="M4dr!9aL$";
	$dbname="hanadesigns_whxytewedding";
	
	$link= new mysqli($servername,$username,$password,$dbname);
	
	if($link->connect_errno) {
		die("Connection failed : " . $mysqli->connect_error);
	}
// check all entered values for escape characters that may mess up database
	$Fname = mysqli_real_escape_string($link, $_REQUEST['first_name']);
	$Lname = mysqli_real_escape_string($link, $_REQUEST['last_name']);
	$phone = mysqli_real_escape_string($link, $_REQUEST['phone_num']);
		// phone number, check for 13 characters. How much error checking? Omit 555? 000? change storage to just numerical digits?
	$email = mysqli_real_escape_string($link, $_REQUEST['email']);
		// check for @ sign. 
	// optional address
	$street = mysqli_real_escape_string($link, $_REQUEST['street']);
	$apt = mysqli_real_escape_string($link, $_REQUEST['unit_type']);
	$aptNum = mysqli_real_escape_string($link, $_REQUEST['apt']);
	$city = mysqli_real_escape_string($link, $_REQUEST['city']);
	$state = mysqli_real_escape_string($link, $_REQUEST['state']);
	$zip = mysqli_real_escape_string($link, $_REQUEST['zip']);
		// check for five digits min
	// end optional data
	$password = mysqli_real_escape_string($link, $_REQUEST['password']);
		// Hash the password
	$password = password_hash($password, PASSWORD_DEFAULT);
	$role = mysqli_real_escape_string($link, $_REQUEST['Role_ID']); 	
	
// check if street was left empty. If so all values are useless.
	if($street == ""){
		$street = NULL;
		$apt = NULL;
		$sptNum = NULL;
		$city = NULL;
		$state = NULL;
		$zip = NULL;
	};



// Enter the volunteer Data into the database
	// first, check if the email already exists
	$stmt = $link->stmt_init();
	if ($stmt->prepare("SELECT Person.Person_ID FROM Person WHERE Person.Email=?")) {
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->bind_result($person_id);
		$stmt->fetch();
		$stmt->close();
	};
	// if the person id isn't empty check for a password
	if($person_id != "") {
		$stmt = $link->stmt_init();
		if ($stmt->prepare("SELECT Volunteer.Password FROM Volunteer WHERE Person_ID=?")) {
			$stmt->bind_param("s", $person_id);
			$stmt->execute();
			$stmt->bind_result($passCheck);
			$stmt->fetch();
			$stmt->close();
		};
		// if the password isn't empty, redirect to the login page
		if ($passCheck != "") {
// it's redirecting but not saying why. All attempts to notify user are failing
			header("Location: https://hanahopedb.net/testview/login/login.php");  // Redirect to login
			exit();
		}
		else {
			// no password, put them in volunteer
			// use that Person_ID to properly connect the volunteer table that will store their password
			$sql2 = "INSERT INTO `Volunteer`(`Person_ID`, `Password`, `Volunteer_Status`) VALUES ('$person_id', '$password', 'Active')";
			if($link->query($sql2)){
				echo "Volunteer role added to volunteer table.<br><br>";
				} else {
				echo "ERROR: Could not execute $sql.".mysqli_error($link);
			};
		}
	}
	else {
		// next, put them in the people table
		$sql = "INSERT INTO `Person`(`Person_ID`, `First_Name`, `Last_Name`, `Street`, `Apt_Type`, `Apt_Num`, `City`, `State`, `Zip`, `Email`, `Phone_Num`, `Status`, `Notes`) 
			VALUES (NULL, '$Fname', '$Lname', '$street', '$apt', '$aptNum', '$city', '$state', 'zip', '$email', '$phone', '$Status', NULL)";

		if($link->query($sql)){
			echo "Thank you, $Fname, for your interest in volunteering with us!<br><br>";
		} else{
			echo "ERROR: Could not execute $sql.".mysqli_error($link);
		};

		// next put them in the volunteer table
		// get the Person_ID that was assigned to them
		$stmt = $link->stmt_init();
		if ($stmt->prepare("SELECT Person.Person_ID FROM Person WHERE Person.Email=?")) {
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->bind_result($person_id);
			$stmt->fetch();
			$stmt->close();
		};
		
		// use that Person_ID to properly connect the volunteer table that will store their password
		$sql2 = "INSERT INTO `Volunteer`(`Person_ID`, `Password`, `Volunteer_Status`) VALUES ('$person_id', '$password', 'Active')";
		if($link->query($sql2)){
			} else {
				echo "ERROR: Could not execute $sql.".mysqli_error($link);
			};
	}	
	//last, put their person id in the Role table with the role they selected.
		// get the name of the role again 
		// if other is selected the volunteer just goes into the volunteer table
	if ($role != "other") {
		$stmt = $link->stmt_init();
		if ($stmt->prepare("SELECT Roles.Role_Name FROM Roles WHERE Roles.Role_ID=?")) {
			$stmt->bind_param("s", $role);
			$stmt->execute();
			$stmt->bind_result($roleName);
			$stmt->fetch();
			$stmt->close();
		};
		$sql3 = "UPDATE `Roles` SET `Person_ID`='$person_id' WHERE Roles.Role_ID = '$role'"; 	//UPDATE `Roles` SET `Person_ID`='12391' WHERE Roles.Role_ID = '2' 
		if($link->query($sql3)){
			echo "<br><br>You've been assigned the role $roleName. You can update, add more roles, or change this via you account page
			<a href='https://hanahopedb.net/testview/login/login.php'> here.</a><br><br>";
			} else {
				echo "ERROR: Could not execute $sql.".mysqli_error($link);
			};
	}
	else {
		echo "<br><br> A volunteer coordinator will be in touch to discuss how you may help.";
	}
	// email the coordinator that a role has been filled?
	$committeeEmail = 'atsuydam@happler.com';
	$committeeSubject = 'New Volunteer!';
	$committeeMessage = "{$Fname} {$Lname} has registered to volunteer as {$roleName}";
		mail($committeeEmail, $committeeSubject, $committeeMessage);
	// email the volunteer what role they signed up for and where they can find more information or make account changes.
	$subjectVolunteer = "Thank you for supporting the WhXYte Wedding!";
	$volunteerMessage = "Hello, {$Fname}, from the UC Denver Senior Design team! This message was auto-generated to inform you that you have been added to the WhXYte Wedding Database. In the future this email will address your new volunteer with a thank you, what role(s) they signed up for, and whatever the charity ask us for in the template. Have a happy Thanksgiving! -Amanda"; //or we call to a document that is stored where?
		mail($email, $subjectVolunteer, $volunteerMessage);
	// close the link	
	mysqli_close($link);
?>
</body>