<?php
// a php doc to work in conjunction with the form at company-donation.html
// created by Amanda suydam November 2018

$servername = "localhost";
$username="dbuser";
$password="M4dr!9aL$";
$dbname="hanadesigns_whxytewedding";

$link= new mysqli($servername,$username,$password,$dbname);

if($link->connect_errno) {
	die("Connection failed : " . $mysqli->connect_error);
}

$Cname = mysqli_real_escape_string($link, $_REQUEST['companyName']);
if ($Cname==""){
		header("Location: https://hanahopedb.net//testview/donor/cdonation.html");  // Redirect
		exit();
	}
$Street = mysqli_real_escape_string($link, $_REQUEST['street']);
$UType = mysqli_real_escape_string($link, $_REQUEST['unit_type']);  
$UNum = mysqli_real_escape_string($link, $_REQUEST['unit_number']);
$City = mysqli_real_escape_string($link, $_REQUEST['city']);
$State = mysqli_real_escape_string($link, $_REQUEST['state']);
$Zip = mysqli_real_escape_string($link, $_REQUEST['zip']);
// check if street was left empty. If so all values are useless.
	if($street == ""){
		$street = NULL;
		$apt = NULL;
		$sptNum = NULL;
		$city = NULL;
		$state = NULL;
		$zip = NULL;
	};
	
$Contact = mysqli_real_escape_string($link, $_REQUEST['contact']);
$phone = mysqli_real_escape_string($link, $_REQUEST['phone_num']);
$email = mysqli_real_escape_string($link, $_REQUEST['email']);
$Status = 'Active';
$type = 'Monetary';
$value = mysqli_real_escape_string($link, $_REQUEST['cash']);
$des = 'Cash Donation, not completed';
	
$stmt = $link->stmt_init();
	if ($stmt->prepare("SELECT Company.Company_ID FROM Company WHERE Company.Contact_Email=?")) {
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->bind_result($company_id);
		$stmt->fetch();
		$stmt->close();
	};
	
	if ($company_id == NULL)	{ //this might be an empty string
		// if no match, put them in the people table
		$sql = "INSERT INTO `Company`(`Company_ID`, `Company_Name`, `Street`, `Unit_Type`, `Unit_Num`, `City`, `State`, `Zip`, `Company_Type`, `Contact_Name`, `Contact_Email`, `Contact_Phone`, `Status`) VALUES
			(NULL, '$Cname', '$Street', '$UType', '$UNum', '$City', '$State', '$Zip', 'Donor', '$Contact', '$email', '$phone', '$Status')";
		if($link->query($sql)){
			echo "Thank you!<br><br>";
		} else{
			echo "ERROR: Could not execute $sql.".mysqli_error($link);
		};
		// grab the newly created company id
		$stmt = $link->stmt_init();
		if ($stmt->prepare("SELECT Person.Person_ID FROM Person WHERE Person.Email=?")) {
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->bind_result($company_id);
			$stmt->fetch();
			$stmt->close();
		};
	};

// insert into donation database
$sql2 = "INSERT INTO `Donation_Item`(`Item_ID`, `Item_Type`, `Status`, `Value`, `Person_ID`, `Company_ID`, `Description`) VALUES (NULL, '$type', NULL, '$value', NULL, '$company_id', '$des')";
	if($link->query($sql2)){
		header("Location:https://hanahopedb.net//testview/donor/c-donor-checkout.php?num=".$company_id);	
		} else {
			echo "ERROR: Could not execute $sql.".mysqli_error($link);
		};
	
	
	// close the link	
	mysqli_close($link);
?>