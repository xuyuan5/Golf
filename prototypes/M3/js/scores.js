$(function() {
    $('.score').blur(function() {
        var scores = new Array();
        $(this).find('td').each(function() {
            scores.push($(this).text());
        });
        alert(scores.join(','));
	});
    
    $('#add-score-form').dialog({
        title: 'Add New Score',
        autoOpen: false,
        height: 265,
        width: 700,
        modal: true,
        resizable: false
	});
    
    $('input.date').datepicker();
    
    $('#add-score-button').click(function() {
        $('#add-score-form').dialog('open');
    });
    
    $('#submit-score-button').click(function() {
        $('#add-score-form').dialog('close');
    });
});