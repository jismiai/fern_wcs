/* client side validation */


$().ready(function() {
	$("#wcsform").validate({
		rules: {
			salutation: "required",
			user_email: "required",
			//user_password: "required",
			firstname: "required",
			lastname: "required",
			phone: "required",
			address1: "required",
			city: "required",
			zip :"required",
			country : "required"
		},
		messages: {
			salutation: "Please enter your salutation",
			firstname: "Please enter your firstname",
			lastname: "Please enter your lastname",
			phone: "Please enter your phone number",
			address1: "Please enter the address line 1",
			city: "Please enter the city",
			zip: "Please enter the postal code",
			country : "Please enter the country"
		}
	});
	
	$("#isresidential").click(function(){
		$("#r_isresidential").removeAttr("checked");
	});
	$("#r_isresidential").click(function(){
		$("#isresidential").removeAttr("checked");
	});
});