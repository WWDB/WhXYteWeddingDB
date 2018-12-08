<?php
// php document that works with the form on purchase-tickets.html. Takes in the guest information and ticket info and places in the right tables
// Created by Amanda Suydam unless noted. Novemeber 2018


//  borrowed from Peter's since we know his connects. 
	$servername = "localhost";
	$username="dbuser";
	$password="M4dr!9aL$";
	$dbname="hanadesigns_whxytewedding";
	
	$link= new mysqli($servername,$username,$password,$dbname);
	
	if($link->connect_errno) {
		die("Connection failed : " . $mysqli->connect_error);
	}

	$Fname = mysqli_real_escape_string($link, $_REQUEST['first_name']);
	// if this empty redirect to html form
	if ($Fname==""){
		header("Location: https://hanahopedb.net//testview/tickets/add-guest.html");  // Redirect to login
		exit();
	}
	$Lname = mysqli_real_escape_string($link, $_REQUEST['last_name']);
	$Street = mysqli_real_escape_string($link, $_REQUEST['street']);
	$Apt = mysqli_real_escape_string($link, $_REQUEST['unit_type']); 
	$AptNum = mysqli_real_escape_string($link, $_REQUEST['apt']); 
	$City = mysqli_real_escape_string($link, $_REQUEST['city']);
	$State = mysqli_real_escape_string($link, $_REQUEST['state']);
	$Zip = mysqli_real_escape_string($link, $_REQUEST['zip']);
	$email = mysqli_real_escape_string($link, $_REQUEST['email']);
	$Status = "Active";
	if ($AptNum == "") {
		$Apt = "";
	};
	
	// check to see if guest already exists
	// first, check if the email already exists
	$stmt = $link->stmt_init();
		if ($stmt->prepare("SELECT Person.Person_ID FROM Person WHERE Person.Email=?")) {
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->bind_result($person_id);
			$stmt->fetch();
			$stmt->close();
		};
	if($person_id == "") {
		// Enter the guest data into the database since they're not there yet
		$sql = "INSERT INTO `Person` (`Person_ID`, `First_Name`, `Last_Name`, `Street`, `Apt_Type`, `Apt_Num`, `City`, `State`, `Zip`, `Email`, `Phone_Num`, `Status`, `Notes`) VALUES 
			(NULL, '$Fname', '$Lname', '$Street', '$Apt', '$AptNum', '$City', '$State', '$Zip', '$email', NULL, '$Status', NULL)";
		if($link->query($sql)){
			echo "If you would like to pre-register additional guests click <a href='add-guest.html'>HERE</a>";
		} else{
			echo "ERROR: Could not execute $sql.".mysqli_error($link);
		};
	}else {	
		// else just update information
		$sql1 = "UPDATE `Person` SET `Street`='$Street', `Apt_Type`='$Apt', `Apt_Num`='$AptNum', `City`='$City', `State`='$State', `Zip`='$Zip', `Status`='$Status' WHERE 'Person_ID' = '$person_id'";
		if($link->query($sql1)) {
			echo "Profile updated.<br>If you would like to pre-register additional guests click <a href='add-guest.html'>HERE</a>";
		} else {
			echo "ERROR: Could not execute $sql1.".mysqli_error($link);
		};
	}
	// close the link	
	mysqli_close($link);
?>