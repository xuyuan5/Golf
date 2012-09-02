$(function() {
	$('#user-details').position({
		of: $('a#edit-user'),
		my: 'right top',
		at: 'right bottom',
		collision: 'fit fit',
		offset: '0, 3'
	});
    $('#user-details').hide();
	$('a#edit-user').hover(function() {
    	$('#user-details').show();
	});
	$('html').click(function() {
    	$('#user-details').hide();
	});
	$('#user-details').click(function(event) {
		event.stopPropagation();
	});
});
