function Initialize() 
{
	$("#showtime").datetimepicker({
	    allowInputToggle: true,
	    stepping: 30,
	    format: 'LT'
	});

	$(".editshowtime").datetimepicker({
	    allowInputToggle: true,
	    stepping: 30,
	    format: 'LT'
	});

	$("#cmpgn-time-box").datetimepicker({
	    allowInputToggle: true,
	    stepping: 15
	});
}

Initialize();
