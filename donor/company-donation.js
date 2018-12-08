// javascript file for company-donation.html
// Created by Amanda Suydam, 2018

function validateForm() {
	var cn = document.forms["CompanyDonation"]["companyName"].value;
	var ccn = document.forms["CompanyDonation"]["contact"].value;
	var em = document.forms["CompanyDonation"]["email"].value;
	var ph = document.forms["CompanyDonation"]["phoneNum"].value;
	// Following insure that the required fields have input
	if (cn == "") {
		alert("Please enter a Company name");
		return false;
	}
	if (ccn == "") {
		alert("Please enter a contact name");
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