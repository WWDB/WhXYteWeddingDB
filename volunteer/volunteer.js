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