$(function() {
    loadHandicap();
    $('#user-details').hide();
    $('a#edit-user').hover(function() {
        $('#user-details').show();
        $('#user-details').position({
            of: $('a#edit-user'),
            my: 'right top',
            at: 'right bottom',
            collision: 'fit fit',
            offset: '0, 3'
        });
    });
    $('html').click(function() {
        $('#user-details').hide();
    });
    $('#user-details').click(function(event) {
        event.stopPropagation();
    });
});

function loadHandicap() {
    var params = {};
    params['action'] = 'handicap-overall';
    $.post('data/users.php', params, function(data) {
        $('#handicap-overall').text(data);
    }).error(function(xhr, ajaxOptions, thrownError) {
        alert(xhr.responseText);
    });
}
