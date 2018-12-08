<?php
// works in conjunction sponser-reg.html which has a form for a company to express interest in sponsoring an event.
//created by Amanda Suydam, November 2018

$servername = "localhost";
$username="dbuser";
$password="M4dr!9aL$";
$dbname="hanadesigns_whxytewedding";

$link= new mysqli($servername,$username,$password,$dbname);

if($link->connect_errno) {
	die("Connection failed : " . $mysqli->connect_error);
}

$Cname = mysqli_real_escape_string($link, $_REQUEST['companyName']);
// if this empty die, stop script, so empty data isn't entered into the DB
if ($Cname==""){
		header("Location: https://hanahopedb.net/testview/sponsor/sponsor-reg.html");  // Redirect to login
		exit();
	}
$Street = mysqli_real_escape_string($link, $_REQUEST['street']);
if(!$Street) {
	$Street = 'NULL';
};
$UType = mysqli_real_escape_string($link, $_REQUEST['unit_type']);  
$UNum = mysqli_real_escape_string($link, $_REQUEST['unit_number']);
if(!$UNum) {
	$UNum = 'NULL';
};  
$City = mysqli_real_escape_string($link, $_REQUEST['city']);
if(!$City) {
	$City = 'NULL';
};
$State = mysqli_real_escape_string($link, $_REQUEST['state']);
$Zip = mysqli_real_escape_string($link, $_REQUEST['zip']);
if(!$Zip) {
	$Zip = 'NULL';
};

// these are required. I need to redirect or something to force them enter them
$Contact = mysqli_real_escape_string($link, $_REQUEST['contact']);
$phone = mysqli_real_escape_string($link, $_REQUEST['phone_num']);
$email = mysqli_real_escape_string($link, $_REQUEST['email']);

// auto set values
$Status = 'Active';
$CType = 'Sponsor';

// Enter the guest data into the database
$sql = "INSERT INTO `Company`(`Company_ID`, `Company_Name`, `Street`, `Unit_Type`, `Unit_Num`, `City`, `State`, `Zip`, `Company_Type`, `Contact_Name`, `Contact_Email`, `Contact_Phone`, `Status`) VALUES
	(NULL, '$Cname', '$Street', '$UType', '$UNum', '$City', '$State', '$Zip', 'Sponsor', '$Contact', '$email', '$phone', '$Status')";

if($link->query($sql)){
echo "Thank you, $Contact and $Cname, for your interest in our event!<br><br> A coordinator will be in contact shortly to discuss your sponsorship";
	} else{
		echo "ERROR: Could not execute $sql.".mysqli_error($link);
	};

// auto generate an email for sponsor and coordinator.
// we need to get the email from the coordinator for this. DO I need to put volunteer as well for the middle man between the two? A little concerned about the spaces in the committee enums
// $sql1= "SELECT email FROM Person INNER JOIN Committee WHERE Committee_Member.Committee=Communication committee"
// the committee email will equal that result
$committeeEmail = 'atsuydam@happler.com';
$committeeSubject = 'New Sponsor!';
$committeeMessage = "{$Cname} would like to sponsor the event! Please contact {$contact} at {$email} or {$phone} soon.";
	mail($committeeEmail, $c0mmitteeSubject, $committeeMessage);
$subjectSponsor = "Thank you for supporting the WhXYte Wedding!";
$sponsorMessage = "In here will be a better message that will give instructions if they don't hear within a few days."; //or we call to a document that is stored where?
	mail($email, $subjectSponsor, $sponsorMessage);

	
// close the link	
mysqli_close($link);
?>
