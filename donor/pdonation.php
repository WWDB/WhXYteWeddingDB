<?php
// Personal Donation page for the WhXYte Wedding Database by the UCDenver Senior Design team.
// This takes the form data from personal_donation.html and inserts it into the database
// Written by Amanda Suydam unless noted otherwise, OCT/NOV 2018.

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
	// if first name is empty someone came directly tot he PHP and empty data would be entered so close the connection and end the script
	if ($Fname==""){
		header("Location: https://hanahopedb.net//testview/donor/personal-donation.html");  // Redirect to login
		exit();
	}
	$Lname = mysqli_real_escape_string($link, $_REQUEST['last_name']);
	$phone = mysqli_real_escape_string($link, $_REQUEST['phoneNum']);
	$email = mysqli_real_escape_string($link, $_REQUEST['email']);
	// optional address
	$street = mysqli_real_escape_string($link, $_REQUEST['street']);
	$apt = mysqli_real_escape_string($link, $_REQUEST['unit_type']);
	$aptNum = mysqli_real_escape_string($link, $_REQUEST['apt']);
	$city = mysqli_real_escape_string($link, $_REQUEST['city']);
	$state = mysqli_real_escape_string($link, $_REQUEST['state']);
	$zip = mysqli_real_escape_string($link, $_REQUEST['zip']);
	// end optional data
	$type = 'Monetary';
	$value = mysqli_real_escape_string($link, $_REQUEST['cash']);
	$des = 'Cash Donation, not completed';
	$Status = 'Active';
// check if street was left empty. If so all values are useless.
	if($street == ""){
		$street = NULL;
		$apt = NULL;
		$sptNum = NULL;
		$city = NULL;
		$state = NULL;
		$zip = NULL;
	};

// Enter the donor's Data into the database
	//someone can donate and volunteer and might already exist. 
	// if so, skip this step and just get the person_Id and insert in the donation table
	$stmt = $link->stmt_init();
	if ($stmt->prepare("SELECT Person.Person_ID FROM Person WHERE Person.Email=?")) {
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->bind_result($person_id);
		$stmt->fetch();
		$stmt->close();
	};
	
	if ($person_id == "")	{ //this might be an empty string
		// if no match, put them in the people table
		$sql = "INSERT INTO `Person`(`Person_ID`, `First_Name`, `Last_Name`, `Street`, `Apt_Type`, `Apt_Num`, `City`, `State`, `Zip`, `Email`, `Phone_Num`, `Status`, `Notes`) 
			VALUES (NULL, '$Fname', '$Lname', '$street', '$apt', '$aptNum', '$city', '$state', '$zip', '$email', '$phone', '$Status', NULL)";

		if($link->query($sql)){
			echo "Thank you!<br><br>";
		} else{
			echo "ERROR: Could not execute $sql.".mysqli_error($link);
		};
		// grab the newly created person_id
		$stmt = $link->stmt_init();
		if ($stmt->prepare("SELECT Person.Person_ID FROM Person WHERE Person.Email=?")) {
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->bind_result($person_id);
			$stmt->fetch();
			$stmt->close();
		};
	} else {
		//update the person table for address information
		$sql1 = "UPDATE `Person` SET `Street`='$Street', `Apt_Type`='$Apt', `Apt_Num`='$AptNum', `City`='$City', `State`='$State', `Zip`='$Zip', `Phone_Num`='$phone', `Status`='$Status'  WHERE 'Person_ID' = '$person_id'";
		if($link->query($sql1)) {
			echo "Profile updated.<br>";
		} else {
			echo "ERROR: Could not execute $sql1.".mysqli_error($link);
		}
	}
	
	// next put the item in the donation table
	$sql2 = "INSERT INTO `Donation_Item`(`Item_ID`, `Item_Type`, `Status`, `Value`, `Person_ID`, `Company_ID`, `Description`) VALUES (NULL, '$type', NULL, '$value', '$person_id', NULL, '$des')";
	if($link->query($sql2)){
		header("Location:https://hanahopedb.net//testview/donor/p-donor-checkout.php?num=".$person_id);		
		} else {
			echo "ERROR: Could not execute $sql.".mysqli_error($link);
		};
	mysqli_close($link);
?>
