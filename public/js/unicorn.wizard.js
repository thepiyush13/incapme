/**
 * Unicorn Admin Template
 * Diablo9983 -> diablo9983@gmail.com
**/
$(document).ready(function(){
	
	$("#activity-wizard").formwizard({ 
		formPluginEnabled: true,
		validationEnabled: true,
		focusFirstInput : true,
		disableUIStyles : true,
	
		formOptions :{
			success: function(data){$("#status").fadeTo(500,1,function(){ $(this).html("<span>Form was submitted!</span>").fadeTo(5000, 0); })},
			beforeSubmit: function(data){$("#submitted").html("<span>Form was submitted with ajax. Data sent to the server: " + $.param(data) + "</span>");},
			dataType: 'json',
			resetForm: true
		},
		validationOptions : {
			rules: {
				activity_name: "required",
				
				activitybeneficiaries_number:{required:true, min:0},
				activityoutcome_mentees:"required",
				activityoutcome_mentors:"required",
				activitybeneficiaries_institute:{required:true, min:0},
				activitybeneficiaries_fees:{required:true, min:0},
				activityplacetime_duration:{required:true, min:0},
				activityplacetime_date_start: "required",
				username: "required",
				password: "required",
				password2: {
					equalTo: "#password"
				},
				email: { required: true, email: true },
				activityoutcome_instructor:{required:true,min:0, max:5},
				activityoutcome_org:{required:true, min:0,max:5},
				activityoutcome_course:{required:true,min:0, max:5},
				eula: "required"
			},
			messages: {
				username: "Please enter your name or username",
				activityoutcome_mentors:"Please enter valid no. of mentors",
				activityoutcome_mentees:"Please enter valid no. of mentees",
				activitybeneficiaries_number:"Please enter valid no. of participants",
				activitybeneficiaries_institute:"Please enter valid no. of Institutes",
				activityoutcome_instructor:"Please rate Out of 5",
				activityoutcome_org:"Please rate Out of 5",
				activityoutcome_course:"Please rate Out of 5",
				activitybeneficiaries_fees:"Please enter valid Fees",
				activityplacetime_date_start:"Please select start date",
				activityplacetime_duration: "Please provide valid time",
				activity_name: "Please enter activity name",
				email: { required: "Please, enter your email", email: "Correct email format is name@domain.com" },
				eula: "You must accept the eula"
			},
			errorClass: "help-inline",
			errorElement: "span",
			highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.control-group').removeClass('error');
			}
		}
	});	
});
$(document).ready(function(){
	
	$("#contributor").formwizard({ 
		formPluginEnabled: true,
		validationEnabled: true,
		focusFirstInput : true,
		disableUIStyles : true,
	
		
		validationOptions : {
			rules: {
				activity_name: "required",
				
				activitybeneficiaries_number:{required:true, min:0},
				activityoutcome_mentees:"required",
				activityoutcome_mentors:"required",
				activitybeneficiaries_institute:{required:true, min:0},
				activitybeneficiaries_fees:{required:true, min:0},
				activityplacetime_duration:{required:true, min:0},
				activityplacetime_date_start: "required",
				username: "required",
				password: "required",
				password2: {
					equalTo: "#password"
				},
				email: { required: true, email: true },
				activityoutcome_instructor:{required:true,min:0, max:5},
				activityoutcome_org:{required:true, min:0,max:5},
				activityoutcome_course:{required:true,min:0, max:5},
				eula: "required"
			},
			messages: {
				username: "Please enter your name or username",
				activityoutcome_mentors:"Please enter valid no. of mentors",
				activityoutcome_mentees:"Please enter valid no. of mentees",
				activitybeneficiaries_number:"Please enter valid no. of participants",
				activitybeneficiaries_institute:"Please enter valid no. of Institutes",
				activityoutcome_instructor:"Please rate Out of 5",
				activityoutcome_org:"Please rate Out of 5",
				activityoutcome_course:"Please rate Out of 5",
				activitybeneficiaries_fees:"Please enter valid Fees",
				activityplacetime_date_start:"Please select start date",
				activityplacetime_duration: "Please provide valid time",
				activity_name: "Please enter activity name",
				email: { required: "Please, enter your email", email: "Correct email format is name@domain.com" },
				eula: "You must accept the eula"
			},
			errorClass: "help-inline",
			errorElement: "span",
			highlight:function(element, errorClass, validClass) {
			$(element).parents('.control-group').addClass('error');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parents('.control-group').removeClass('error');
			}
		}
	});	
});
