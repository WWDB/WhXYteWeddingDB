<?php 
	// Another part of the donation ability. This will display the user name, confirmation of the amount, and a paypal button
	// Created Dec 7, 2015 by Amanda Suydam

	// connect to the DB
	$servername = "localhost";
	$username="dbuser";
	$password="M4dr!9aL$";
	$dbname="hanadesigns_whxytewedding";
	
	$link= new mysqli($servername,$username,$password,$dbname);
	
	if($link->connect_errno) {
		die("Connection failed : " . $mysqli->connect_error);
	}
	
	// person_id should have been passed via the header
	if(!empty($_GET['num'])) {
		$pid = $_GET['num'];
		
		// use the person id to get the user first name and last name
		$stmt = $link->stmt_init();
		if ($stmt->prepare("SELECT Person.First_Name, Person.Last_Name FROM Person WHERE Person.Person_ID=?")) {
			$stmt->bind_param("s", $pid);
			$stmt->execute();
			$stmt->bind_result($fname, $lname);
			$stmt->fetch();
			$stmt->close();
		};
		echo "Thank you $fname $lname for wanting to contribute to our cause.<br>";
		// get the donation amount from the donation table
		$stmt = $link->stmt_init();
		if ($stmt->prepare("SELECT Tickets.Num_Of_Tickets, Tickets.Ticket_Cost FROM Tickets WHERE Tickets.Person_ID=?")) {
			$stmt->bind_param("s", $pid);
			$stmt->execute();
			$stmt->bind_result($num, $cost);
			$stmt->fetch();
			$stmt->close();
		};
		$cash = floatval($num) * floatval($cost);
		echo "To complete your donation of $num tickets at $cost each for $cash, please click the Paypal button below. <br>
		Username: atsuydam-buyer@gmail.com <br>
		Password: tester123 <br>";
	}
	else {
		// I have an error and this isn't working
		echo "It didn't work";
	}
?>	
	<!-- display paypal button -->
	<div id="paypal-button-container"></div>
	<script src="https://www.paypalobjects.com/api/checkout.js"></script>
	<script>
		paypal.Button.render({
			env: 'sandbox',   // just sandbox for now. 

			client: {
				 sandbox:    'AfF-1ZzCImlLGig7PouSDPYTpbXmkv9aYqT1ydi-YIhx5aO3MwRMuL9p4LTeGCNIPxOb5Nmy9jemDXWL',
				 production: ''
			},

			// Show the buyer a 'Pay Now' button in the checkout flow
			commit: true,

			// payment() is called when the button is clicked
			payment: function(data, actions) {

			   // Make a call to the REST api to create the payment
			   return actions.payment.create({
			   payment: {
					transactions: [
				   {
					amount: {
					total: '<?php echo $cash ?>', // put the donation amount here
					currency: 'USD' // USD?
					}
					} ]
					}
					});
					},

					// onAuthorize() is called when the buyer approves the payment
					 onAuthorize: function(data, actions) {
							// Make a call to the REST api to execute the payment
							return actions.payment.execute().then(function() {
							console.log('Payment Complete!');

							window.location = "https://hanahopedb.net//testview/tickets/ticket-process.php?paymentID="+data.paymentID+"&payerID="+data.payerID+"&token="+data.paymentToken+"&pid=<?php echo $pid  ?>";

							});
						}
		}, '#paypal-button-container');
	</script>

