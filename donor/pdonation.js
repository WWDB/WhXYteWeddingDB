// javascript for the pdonation.html page
// created by Amanda Suydam, 2018

function validateForm() {
	var fn = document.forms["PDonation"]["first_name"].value;
	var ln = document.forms["PDonation"]["last_name"].value;
	var st = document.forms["PDonation"]["street"].value;
	var city = document.forms["PDonation"]["city"].value;
	var zip = document.forms["PDonation"]["zip"].value;
	var em = document.forms["PDonation"]["email"].value;
	var amt = document.forms["PDonation"]["cash"].value;
	// Following insure that the required fields have input
	if (fn == "") {
		alert("Please enter a first name");
		return false;
	}
	if (ln == "") {
		alert("Please enter a last name");
		return false;
	}
	//validate address
	if (st == "") {
		alert("Please enter a street address");
		return false;
	}if (city == "") {
		alert("Please enter a city");
		return false;
	}if (zip == "") {
		alert("Please enter a zip code");
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
	if (amt == "") {
		alert("Please enter an amount to donate");
		return false;
	} else {
		alert("Donation amount $" + amt + ".00");
	}
}	