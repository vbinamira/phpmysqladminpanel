// ===========================
// LOGIN/LOGOUT FUNCTIONALITY
// ===========================
// Fix this issue 
$('#signin').click(function(event) {
	var password = $('#password').val();
	var email = $('#email').val();
	// var hash = CryptoJS.MD5(password);
	// console.log(hash);
	$.ajax({
		url: '/login',
		type: 'POST',
		data: {email: email, password: password },
	})
	.done(function(data) {
		var status = data.code;
		console.log(status);
		if(status == 201) {
			location.href('admin/index.php')
		}
		else if(status == 202) {
			$('#login-no-match').removeClass('hidden');
		}
		else {
			$('#login-no-email').removeClass('hidden');
			$('#login-no-match').addClass('hidden');
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
});