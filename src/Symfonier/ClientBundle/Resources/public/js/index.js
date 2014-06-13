$(document).ready(function(e) {
	$('#search-housing-form').submit(function(e) {
		console.log('submittt');
		$.ajax({
			type: 'post',
			url: $(this).attr('action'),
			data: $(this).serialize(),
			success: function(data) {
				$.fancybox({
					content: data
				});
			}
		});
		e.preventDefault();
	});
});
