// js script for the cdonation html page
// created by Amanda Suydam, November 2018
function validateForm() {
	var cn = document.forms["cashDonor"]["companyName"].value;
	var st = document.forms["cashDonor"]["street"].value;
	var ct = document.forms["cashDonor"]["city"].value;
	var zip = document.forms["cashDonor"]["zipCode"].value;
	var ccn = document.forms["cashDonor"]["contact"].value;
	var cph = document.forms["cashDonor"]["phoneNum"].value;
	var em = document.forms["cashDonor"]["email"].value;
	var amt = document.forms["cashDonor"]["cash"].value;
	// Following insure that the required fields have input
	if (cn == "") {
		alert("Please enter a company name");
		return false;
	}
	if (ccn == "") {
		alert("Please enter a contact name");
		return false;
	}
	if (cph == "") {
		alert("Please enter a contact phone number");
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
	if (amt == "") {
		alert("Please enter an amount to donate");
		return false;
	}
}
