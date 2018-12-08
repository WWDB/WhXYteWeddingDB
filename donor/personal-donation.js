function validateForm() {
	var fn = document.forms["PersonalDonation"]["first_name"].value;
	var ln = document.forms["PersonalDonation"]["last_name"].value;
	var em = document.forms["PersonalDonation"]["email"].value;
	var ph = document.forms["PersonalDonation"]["phoneNum"].value;
	// Following insure that the required fields have input
	if (fn == "") {
		alert("Please enter a first name");
		return false;
	}
	if (ln == "") {
		alert("Please enter a last name");
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
	if (ph.length < 13) {
		alert("Please enter a valid phone number");
		return false;
	}
}	