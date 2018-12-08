<?php
// created by AManda Suydam for the WhXYte Wedding Database. This php updates the ticket table with the receipt number and payment items.



	if(!empty($_GET['paymentID']) && !empty($_GET['payerID']) && !empty($_GET['token']) && !empty($_GET['pid']) ){
		$paymentID = $_GET['paymentID'];
		$payerID = $_GET['payerID'];
		$token = $_GET['token'];
		$pid = $_GET['pid'];
		
		$servername = "localhost";
		$username="dbuser";
		$password="M4dr!9aL$";
		$dbname="hanadesigns_whxytewedding";
		
		$link= new mysqli($servername,$username,$password,$dbname);
		
		if($link->connect_errno) {
			die("Connection failed : " . $mysqli->connect_error);
		}
		
		$sql = "UPDATE `Tickets` SET `Receipt`= '$paymentID', `Payment_Status`= 'PAID', `Thank_You_Status`= 'Sent' WHERE Tickets.Person_ID = $pid";
		if($link->query($sql)){
			// I need to get the information for the email so
			$stmt = $link->stmt_init();
			if ($stmt->prepare("SELECT Person.First_Name, Person.Last_Name, Person.Email FROM Person WHERE Person.Person_ID=?")) {
				$stmt->bind_param("s", $pid);
				$stmt->execute();
				$stmt->bind_result($fname, $lname, $email);
				$stmt->fetch();
				$stmt->close();
			};
			$committeeEmail = 'atsuydam@happler.com';
			$committeeSubject = 'New Donation!';
			$committeeMessage = "{$fname} {$lname} has made a cash donation.<br><br>";
				mail($committeeEmail, $committeeSubject, $committeeMessage);
		// email the volunteer what role they signed up for and where they can find more information or make account changes.
			$subjectDonor = "Thank you for supporting the WhXYte Wedding!";
			$donorMessage = "Hello, {$fname}, from the UCDenver Senior Design team! <br> You're receiving this email because you are on the spread sheet of contacts for the Hana Hope/ WhyXyte Wedding event charity. 
		This email was auto-generated as we used your information to test the data base. Don't worry, you'll only be entered once, but this will allow the event quick and easy access to contact you regarding 
		potential contributions; time, money or service, to the charity. Receipt number {$paymentID}, Thank you for your support of the charities! <br> Amanda Suydam, UCD Computer Science major.<br><br><br> 
		If you would like to unsubscribe to all future mails click <a href = 'https://hanahopedb.net//testview/unsubscribe.html'>here </a>"; 
			mail($email, $subjectDonor, $donorMessage);
	// close the link	
			header('Location:https://hanahopedb.net//testview/tickets/add-guest.html'); // Success redirect to orders
			} else {
				echo "ERROR: Could not execute $sql.".mysqli_error($link);
			//header('Location:https://hanahopedb.net//testview/tickets/purchase-tickets.html'); // Fail and go back to the donation page because the donation didn't go through
		};
    }
	else {header('Location:https://hanahopedb.net//testview/tickets/purchase-tickets.html'); // Fail and go back to the donation page because the donation didn't go throughecho "all url values are missing";
	}
?>