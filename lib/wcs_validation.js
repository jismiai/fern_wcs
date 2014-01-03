/* client side validation */


$().ready(function() {
	$("#wcsform").validate({
		rules: {
			salutation: "required",
			user_email: "required",
			//user_password: "required",
			firstname: "required",
			lastname: "required",
			phone: {
				required: true,
				minlength: 7
			},
			address1: "required",
			city: "required",
			zip :"required",
			country : "required"
		},
		messages: {
			salutation: "Please enter your salutation",
			firstname: "Please enter your firstname",
			lastname: "Please enter your lastname",
			phone: {
				required: "Please enter your phone number ",
				minlength: "Please enter at least 7 digits "
			},
			address1: "Please enter the address line 1",
			city: "Please enter the city",
			zip: "Please enter the postal code",
			country : "Please enter the country"
		}
	});
	$("#bookingform").validate({
		rules: {
			appoint_date: "required",
			appoint_time: "required",
			num_companion: "digits"
		}
	});
	$("#isresidential").click(function(){
		$("#r_isresidential").removeAttr("checked");
	});
	$("#r_isresidential").click(function(){
		$("#isresidential").removeAttr("checked");
	});
});