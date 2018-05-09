$.get('/access-type', function(data) {
	var raw = data;
	for (var i = 0; i < raw.length; i++) {
		var accesslevel = raw[i].name;
		var accessoptions = '<option value="'+accesslevel+'">' + accesslevel + '</option>'
		$(accessoptions).appendTo('#access-level');
	}
});

$('#registrationform').on('submit', function(e) {
	e.preventDefault();
	var $form = $(e.target);
	$.post($form.attr('action'), $form.serialize(), function(result) {
		var status = result.code
		switch(status) {
			case 201:
				$('#user-registered').removeClass('hidden');
				$('#user-registered').delay(1000).fadeOut();
				location.reload();
				break;
			case 202: 
				var duplicate = $('#email-duplicate');
				duplicate.fadeIn();
				duplicate.removeClass('hidden');
				duplicate.delay(1000).fadeOut();
				break;
			default:
				console.log('500 error');
		} 
	}, 'json');
})

	

