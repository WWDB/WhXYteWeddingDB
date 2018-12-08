function validateForm() {
	var cn = document.forms["SponsorReg"]["companyName"].value;
	var ccn = document.forms["SponsorReg"]["contact"].value;
	var em = document.forms["SponsorReg"]["email"].value;
	var cpn = document.forms["SponsorReg"]["phoneNum"].value;
	// Following insure that the required fields have input
	if (cn == "") {
		alert("Please enter a company name");
		return false;
	}
	if (ccn == "") {
		alert("Please enter a contact name");
		return false;
	}
	if (em ==""){
		alert("Please enter an email address");
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
	if (cpn ==""){
		alert("Please enter a phone number at which we can reach you");
		return false;
	}
}