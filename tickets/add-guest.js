// script page for the additional guest registration .html page for the WWDB
// Created by Amanda Suydam
	function validateForm() {
		var fn = document.forms["AddGuestReg"]["first_name"].value;
		var ln = document.forms["AddGuestReg"]["last_name"].value;
		var st = document.forms["AddGuestReg"]["street"].value;
		var ct = document.forms["AddGuestReg"]["city"].value;
		var zip = document.forms["AddGuestReg"]["zipCode"].value;
		var em = document.forms["AddGuestReg"]["email"].value;
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
	}
