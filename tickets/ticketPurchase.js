function validateForm() {
	var fn = document.forms["guestReg"]["first_name"].value;
	var ln = document.forms["guestReg"]["last_name"].value;
	var st = document.forms["guestReg"]["street"].value;
	var ct = document.forms["guestReg"]["city"].value;
	var zip = document.forms["guestReg"]["zipCode"].value;
	var em = document.forms["guestReg"]["email"].value;
	var numTickets=document.forms["guestReg"]["numTickets"].value;
	var ticketType=document.forms["guestReg"]["ticketType"].value;
	// Following insure that the required fields have input
	if (fn == "") {
		alert("Please enter a first name");
		return false;
	}
	if (ln == "") {
		alert("Please enter a last name");
		return false;
	}
	// validate the address
	if (st == "" || st.length < 10) {
		alert("Please enter a full street address");
		return false;
	}
	if (ct == "") {
		alert("Please enter a city");
		return false;
	}
	if (zip.length < 5 || zip.length > 11 ) {
		alert("Please enter a valid postal code");
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
	numTickets = parseInt(numTickets);
	if (numTickets == 0) {
		alert('Ticket quantity cannot be 0');
		return false;
	} else {
		var price = 0;
		if(ticketType == "VIP") {
			price = 125;
		} else {
			price = 50;
		}
		total = numTickets * price;
		if(confirm("Your total is $" +total+ ".00. Press OK to continue.")) {
			return true;
		} else {
			return false;
		}
		return false;
	}
}	