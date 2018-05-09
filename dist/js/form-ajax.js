//GLOBAL VARIABLES
var cmpgnid = $('#cmpgnid').val();
//====================
// CREATE 
// ===================
//=== NEW CAMPAIGN ==//
$('#btn-create-cmpgn').click(function(event) {
	var fromname = $('#campaign-from').val();
	var replyto = $('#campaign-reply').val();
	var title = $('#campaign-title').val();
	var subject = $('#subject-line').val();
	var segment = $('#segments option:selected').val();
	$.ajax({
		url: '../includes/create-email-campaign',
		type: 'POST',
		data: {
			fromname: fromname,
			replyto: replyto,
			title: title,
			subject: subject,
			segment: segment,
		},
	})
	.done(function() {
		$('#cmpgn-add-success').removeClass('hidden');
	})
	.fail(function() {
		$('#cmpgn-add-error').removeClass('hidden');
	})
	.always(function() {
		setTimeout(function () { location.reload(true); }, 1000);
	});
});
//==== NEW CONTACT ===//
$('#create_new_contact').click(function(event) {
	var test = $('#create_subscriber_form').serialize();
	$.ajax({
		url: '../includes/create-contact',
		type: 'POST',
		data: test,
	})
	.done(function() {
		$('#contact-add-success').removeClass('hidden');
	})
	.fail(function() {
		$('#contact-add-failed').removeClass('hidden');
	})
	.always(function() {
		// setTimeout(function () { location.replace('contacts.php'); }, 1000);
	});	
});
//== SCHEDULE CAMPAIGN ==//
$('#btn-cmpgn-sched').click(function(event) {
	var datetime = $('#cmpgn-time').val();
	var scheduletime = moment(new Date(datetime)).utc().format();
	$.ajax({
		url: '../includes/schedule-campaign',
		type: 'POST',
		data: {
			cmpgnid : cmpgnid,
			scheduletime: scheduletime,
		}
	})
	.done(function(data) {
		$('#cmpgn-sched-success').removeClass('hidden');
	})
	.fail(function() {
		$('#cmpgn-sched-failed').removeClass('hidden');
	})
	.always(function() {
		setTimeout(function () { location.replace('email-templates.php'); }, 1000);
		$('#cmpgn-sched-msg').addClass('hidden');
	});
});
//==================
// EDIT
//==================
//== EDIT CONTENT ==//
$('#btn-edit-content').click(function(event) {
	// GET RAW HTML
	var htmlraw = CKEDITOR.instances.editorbrett.getData();
	// PARSE HTML TO JSON STRING
	var htmlcode = JSON.stringify(htmlraw);
	if(htmlcode=="") 
	{
		$('#cmpgn-edit-empty').removeClass('hidden');
	}
	else
	{
		$('#cmpgn-edit-empty').addClass('hidden');
		$.ajax({
			url: '../includes/update-campaign',
			type: 'POST',
			data: {
				cmpgnid: cmpgnid,
				htmlcode: htmlcode,
			},
		})
		.done(function() {
			$('#cmpgn-edit-success').removeClass('hidden');
		})
		.fail(function() {
			$('#cmpgn-edit-error').removeClass('hidden');
		})
		.always(function() {
			setTimeout(function () { location.reload(true); }, 1000);
		});
	}
});
//== EDIT CONTACT ==//
$('#edit_contact').click(function(event) {
	var test = $('#edit_subscriber_form').serialize();
	console.log(test);
	$.ajax({
		url: '../includes/edit-contact',
		type: 'POST',
		data: test,
	})
	.done(function() {
		$('#contact-edit-success').removeClass('hidden');
	})
	.fail(function() {
		$('#contact-edit-failed').removeClass('hidden');
	})
	.always(function() {
		// setTimeout(function () { location.replace('contacts.php'); }, 1000);
	});	
});
// ==================
// 	GET
// ==================
//== CAMPAIGN STATUS ==//
$('#check-list').click(function(event) {
	$.ajax({
		url: '../includes/get-checklist',
		type: 'POST',
		data: {
			cmpgnid : cmpgnid,
		}
	})
	.done(function(data) {
		var rawdata = data;
		var parsed = $.parseJSON(rawdata);
		var status = parsed.is_ready;
		if(status)
		{
			$('#checklist-success-label').removeClass('hidden');
			$('#cmpgn-sched-btn').removeClass('disabled');
			$('#cmpgn-time').removeAttr('disabled');
		}
		else
		{
			$('#checklist-failed-label').removeClass('hidden');
		}
	})
	.fail(function() {
		console.log("Function Failed");
	})
	.always(function() {
		$('#check-list').addClass('hidden');
	});
});
//========================
//	VALIDATION
//========================
// CONTACT FORM VALIDATION
$('#create_subscriber_form').bootstrapValidator({
    framework: 'bootstrap',
    /**
     * Validators and corresponding messages
     */
    fields: {
        last_name: {
            validators: {
                notEmpty: {message: 'Please supply a last name'},
                regexp: {
                    regexp: /^[a-z\s]+$/i,
                    message: 'Please use alphabetical characters and spaces only'
                }
            }
        },
        first_name: {
            validators: {
                notEmpty: {message: 'Please supply a first name'},
                regexp: {
                    regexp: /^[a-z\s]+$/i,
                    message: 'Please use alphabetical characters and spaces only'
                }
            }
        },
        email: {
            validators: {
                notEmpty: {message: 'Please supply an email address'},
                emailAddress: {message: 'This value is not valid'}
            }
        }
    }
});
//======================
//	MESSAGES
//======================
//SEND TEST ADD MESSAGE
$('#send-test-btn').click(function(event) {
	var testmail = $('#send-test-email').val();
	var warning_msg =  "<p id='warning-msg'>Send email to <b>" + testmail + "</b>?</p>";
	$(warning_msg).appendTo('#test-email-msg');
});
//SEND TEST REMOVE MESSAGE
$('#btn-msg-cancel').click(function(event) {
	$('#warning-msg').remove();
});
// SEND TEST EMAIL
$('#btn-send-test').click(function(event) {
	var testmail = $('#send-test-email').val();
	$.ajax({
		url: '../includes/send-test-email',
		type: 'POST',
		data: {
			cmpgnid : cmpgnid,
			testmail: testmail,
		}
	})
	.done(function(data) {
		$('#test-email-success').removeClass('hidden');
	})
	.fail(function() {
		$('#test-email-failed').removeClass('hidden');
	})
	.always(function() {
		setTimeout(function () { location.reload(true); }, 1000);
		$('#test-email-msg').addClass('hidden');
	});
});
// SCHEDULE REMOVE MESSAGE
$('#cmpgn-sched-cancel').click(function(event) {
	$('#sched-msg').remove();
});
// SCHEDULE ADD MESSAGE
$('#cmpgn-sched-btn').click(function(event) {
	var datetime = $('#cmpgn-time').val();
	var warning_msg =  "<p id='sched-msg'> Do you want to schedule Campaign on <b>" + datetime + "</b>?</p>";
	$(warning_msg).appendTo('#cmpgn-sched-msg');
});
//========================
//	MISCELLANEOUS
//========================
//== PREVIEW CAMPAIGN ==//
$('#btn-preview-content').click(function(event) {
	var htmlcode = CKEDITOR.instances.editorbrett.getData();
	$('#cmpgn-preview').html(htmlcode);
	if(htmlcode=="") 
	{
		$('#cmpgn-edit-empty').removeClass('hidden');
	}
	else
	{
		$('#cmpgn-edit-empty').addClass('hidden');
	}
});