<!DOCTYPE html>
<!-- Volunteer Registration HTML for the WhXYte Wedding Database which allows for online individual assignment of roles for the charity event.
Works in conjunction with the volunteer-reg.php which takes the form data and inserts it into the database
Created by Amanda Suydam except where noted, November 2018-->

<html lang = "en">
	<head>
		<meta charset = "UTF-8">
		<?php
		  //set headers to NOT cache a page
		  header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
		  header("Pragma: no-cache"); //HTTP 1.0
		  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

		?>
	<title> WhXYte Wedding Volunteer Registration </title>
	<!-- Form validation script. Peter is going to want this elsewhere -->
	<script>
	function validateForm() {
		var fn = document.forms["VolunteerReg"]["first_name"].value;
		var ln = document.forms["VolunteerReg"]["last_name"].value;
		var em = document.forms["VolunteerReg"]["email"].value;
		var pw = document.forms["VolunteerReg"]["password"].value;
		// Following insure that the required fields have input
		if (fn == "") {
			alert("Please enter a first name");
			return false;
		}
		if (ln == "") {
			alert("Please enter a last name");
			return false;
		}
		if (em ==""){
			alert("Please enter a email address");
			return false;
		}
		// validate email
		if(em.length < 10) {
			alert("Please enter a valid email");
			return false;
		}
		if (em.search('@') == -1) {
			alert("Please enter a valid email");
			return false;
		}
		//validate password		
		var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}/;
		if(!re.test(pw)) {
			alert("Please enter a valid password");
			return false;
		}
	}
	</script>
	</head>

	<body>

		<h2> Registration information </h2>
		<!-- instructions here -->
		Items marked with <span style="color: #ff0000">*</span> are required.
		<br><br>
		<form name="VolunteerReg" action="volunteer-reg.php" onsubmit="return validateForm()" method="post" id="VolunteerReg">
			<p>
<!-- NEXT: Form Validation -->			
			<!-- first name entry REQUIRED -->
				<label for="first_name"><span style="color: #ff0000">*</span>First Name: </label>
				<input type="text" name="first_name" id="first_name">
			<!-- Last name entry REQUIRED-->	
				<label for="last_name"><span style="color: #ff0000">*</span>Last Name: </label>
				<input type="text" name="last_name" id="last_name">
				<br><br>
			<!-- phone number -->
				<label for="phoneNum">Phone Number: </label>
				<input type="text" name="phone_num" id="phoneNum" maxlength="13">   (303)555-1234  <!-- What format do we want to sanitize? And example before, after, or in text box? -->
				<br><br>
			<!-- email address  REQUIRED-->
				<label for="email"><span style="color: #ff0000">*</span>Email: </label>
				<input type="text" name="email" id="email" style="width: 30em;"> <br><br>
			<!-- password REQUIRED-->
				Please create a password for you account.<br>It must include between 8 and 16 characters, at least one capital and one number. No symbols.<br>
				<label for='password'><span style='color: #ff0000'>*</span>Password: </label>
				<input type='password' name='password' id='password' >
			<!-- verify password option?-->
			<br><br> 
			Providing an address will allow us to notify you via mail of future WhXYte Wedding or Hana's Hope events. 
			<br> This is optional <br><br>
			<!-- Street address entry.  Address is NOT required for volunteers but is optional -->
				<label for="street">Street Address:</label>
				<input type="text" name="street" id="street" style="width: 28em;">
			<!-- unit type-->	
				<label for="unit_type"> Apt/Suite/Unit: </label>
				<select name="unit_type">
					<option value="NULL">None</option>
					<option value="APT">APT</option>
					<option value="Suite">Suite</option>
					<option value="Unit">Unit</option>
				</select>
			<!-- apartment or suite -->
				<label for="apt">Number:</label>
				<input type="text" name="apt" id="apt" style="width: 3em;">
				<br><br>
			<!-- City -->
				<label for="city">City:</label>
				<input type="text" name="city" id="city">
			<!-- State, needs to be a drop down menu option -->
				<label for="state">State:</label>
				<select name="state">
					<option value="AL"> AL </option>
					<option value="AK"> AK </option>
					<option value="AZ"> AZ </option>
					<option value="AR"> AR </option>
					<option value="CA"> CA </option>
					<option value="CO"> CO </option>
					<option value="CT"> CT </option>
					<option value="DC"> DC </option>
					<option value="DE"> DE </option>
					<option value="FL"> FL </option>
					<option value="GA"> GA </option>
					<option value="HI"> HI </option>
					<option value="ID"> ID </option>
					<option value="IL"> IL </option>
					<option value="IN"> IN </option>
					<option value="IA"> IA </option>
					<option value="KS"> KS </option>
					<option value="KY"> KY </option>
					<option value="LA"> LA </option>
					<option value="MD"> MD </option>
					<option value="ME"> ME </option>
					<option value="MA"> MA </option>
					<option value="MI"> MI </option>
					<option value="MN"> MN </option>
					<option value="MS"> MS </option>
					<option value="MO"> MO </option>
					<option value="MT"> MT </option>
					<option value="NE"> NE </option>
					<option value="NJ"> NJ </option>
					<option value="NM"> NM </option>
					<option value="NY"> NY </option>
					<option value="NC"> NC </option>
					<option value="ND"> ND </option>
					<option value="OH"> OH </option>
					<option value="OK"> OK </option>
					<option value="OR"> OR </option>
					<option value="PA"> PA </option>
					<option value="PR"> PR </option>
					<option value="RI"> RI </option>
					<option value="SC"> SC </option>
					<option value="SD"> SD </option>
					<option value="TN"> TN </option>
					<option value="TX"> TX </option>
					<option value="UT"> UT </option>
					<option value="VT"> VT </option>
					<option value="VA"> VA </option>
					<option value="WA"> WA </option>
					<option value="WV"> WV </option>
					<option value="WI"> WI </option>
					<option value="WY"> WY </option>
				</select>
			<!-- zip code -->
				<label for="zipCode">Zip Code:</label>
				<input type="text" name="zip" id="zipCode" style="width: 6em;" maxlength="5">  <!-- again we have a format fun. 5 digit zip or 5-4 format -->
				<br><br>
			Please select an available role from the drop down menu.
			<!-- I need to select Role_ID, Role_Name, Role_TIme_Start, and Role_Time_End from the Table Roles where Person_ID doesn't equal NULL-->
			<?php
				$servername = "localhost";
				$username="dbuser";
				$password="***********";
				$dbname="hanadesigns_whxytewedding";
				
				echo "<h2>Available roles</h2>";
				$link= new mysqli($servername,$username,$password,$dbname);
				if($link->connect_errno) {
					die("Connection failed : " . $mysqli->connect_error);
				}
						
				$sql = "SELECT `Role_ID`, `Role_Name`, `Role_Time_Start`, `Role_Time_End`, `Person_ID` FROM `Roles` WHERE `Person_ID` IS NULL";
				$result = $link->query($sql);
				// now for the creation of the drop down
					echo "<select name='Role_ID'>";
					while ($row = $result->fetch_assoc()) {
						unset($Role_ID, $Role_Name);
						$Role_ID = $row['Role_ID'];
						$Role_Name = $row['Role_Name'];
						echo '<option value="'.$Role_ID.'">'.$Role_Name.'</option>';
					}
						echo '<option value="other">Other</option>';
					echo "</select>";
				// close the link to DB -->
				mysqli_close($link);		
			?>
			<!-- submit all the info -->
			<input type="submit" value="Submit">
		</form>
	</body>
</html>	
		
