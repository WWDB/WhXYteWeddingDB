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
if ($Fname==""){
		header("Location: https://hanahopedb.net//testview/tickets/purchase-tickets.html");  // Redirect to login
		exit();
	}
$Lname = mysqli_real_escape_string($link, $_REQUEST['last_name']);
$Street = mysqli_real_escape_string($link, $_REQUEST['street']);
$Apt = mysqli_real_escape_string($link, $_REQUEST['unit_type']); 
$AptNum = mysqli_real_escape_string($link, $_REQUEST['unit_num']); 
$City = mysqli_real_escape_string($link, $_REQUEST['city']);
$State = mysqli_real_escape_string($link, $_REQUEST['state']);
$Zip = mysqli_real_escape_string($link, $_REQUEST['zip']);
$email = mysqli_real_escape_string($link, $_REQUEST['email']);

if ($apt == "NULL") {
	$apt = NULL;
}
$TicketType = mysqli_real_escape_string($link, $_REQUEST['ticketType']);
$TicketQty = mysqli_real_escape_string($link, $_REQUEST['numTickets']);
$Status = 'Active';

if ($TicketType == "VIP") {
	$price = 125;
} else {
	$price = 50;
}

// check to see if guest already exists
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
		// get the newly assigned person id
		echo "Profile made for $Fname.";
	} else{
		echo "ERROR: Could not execute $sql.".mysqli_error($link);
	};
}else {	
	$sql1 = "UPDATE `Person` SET `Street`='$Street', `Apt_Type`='$Apt', `Apt_Num`='$AptNum', `City`='$City', `State`='$State', `Zip`='$Zip', `Status`='$Status' WHERE 'Person_ID' = '$person_id'";
	if($link->query($sql1)) {
		echo "Profile updated.<br>";
	} else {
		echo "ERROR: Could not execute $sql1.".mysqli_error($link);
	}
}
$stmt = $link->stmt_init();
	if ($stmt->prepare("SELECT Person.Person_ID FROM Person WHERE Person.Email=?")) {
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->bind_result($person_id);
		$stmt->fetch();
		$stmt->close();
	};
$sql2 = "INSERT INTO `Tickets`(`Receipt`, `Person_ID`, `Num_Of_Tickets`, `Ticket_Type`, `Thank_You_Status`, `Ticket_Cost`, `Payment_Status`, `Confirmation_Status`) 
				VALUES (NULL, '$person_id', '$TicketQty', '$TicketType', 'NOT SENT', '$price', 'NOT PAID', 'NOT CONFIRMED')";
if($link->query($sql2)) {
	header("Location:https://hanahopedb.net//testview/tickets/ticket-checkout.php?num=".$person_id);
} else {
	echo "ERROR: Could not execute $sql1.".mysqli_error($link);
}
echo "<br><br>Pre-registration for additional guests will allow them to skip the line and straight to the auction. <br>
	If you would like to pre-register additional guests click <a href='add-guest.html'>HERE</a>";


	
// close the link	
mysqli_close($link);

?>
