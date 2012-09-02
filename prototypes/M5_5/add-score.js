
$(function() {
    $('#add-score-meta').show();
    $('#add-score-form table td').hide();
    $('#add-score-next').hide();
    $('#add-score-next-button').click(continueAddScore);
    
    $('#add-score-form table td input').each(function() {
        $(this).change(function() {
            if($(this).val().length == 1)
            {
                var parent = $(this).parent();
                parent.hide();
                parent.next().show();
            }
            else
            {
                alert("Wrong score, please try again.");
            }
        });
    });
});

function continueAddScore() {
    $('#add-score-meta').hide();
    $('#add-score-form table td').first().show();
    $('#add-score-next').show();
}