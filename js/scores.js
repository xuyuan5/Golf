var score_row = '\
<div class="row score" data-id="_score_id_"> \
	<div class="row info"> \
		<div class="nine columns"> \
			<div class="row date"> \
				_score_date_ \
			</div> \
			<div class="row course-name"> \
				_course_name_ \
			</div> \
		</div> \
		<a class="one columns offset-by-two delete-game"></a> \
	</div> \
	<div class="row score-details"> \
		<table class="score" contenteditable> \
		</table> \
	</div> \
</div>';

$(function() {
    getScores();
    
    $('#add-score-form').dialog({
        title: 'Add New Score',
        autoOpen: false,
        height: 220,
        width: 250,
        modal: true,
        resizable: false
	});
    
    $('input.date').datepicker({dateFormat: 'yy-mm-dd'});
    
    $('#add-score-button').click(function() {
        prepareAddScore();
    });
    
    $('#submit-score-button').click(function() {
        submitScore();
        $('#add-score-form').dialog('close');
    });
});

function updateScore(id, score) {
    var params = {};
    params['action'] = 'update';
    params['id'] = id;
    params['score'] = score;
    $.post('data/scores.php', params).error(function(xhr, ajaxOptions, thrownError) {
        alert(xhr.responseText);
    });
}

function submitScore() {
    var params = {};
    params['action'] = 'add';
    params['courseID'] = $('#add-score-courses-list li.selected').attr('data-id');
    params['date'] = $('#add-score-form .date').val();
    params['teeID'] = $('#add-score-tees-list li.selected').attr('data-id');
    var scores = new Array();
    $('#add-score-form .score .front-9 td input').each(function() {
        scores.push($(this).val());
    });
    if($('#add-score-courses-list li.selected').attr('data-9-holes') == 0) {
        $('#add-score-form .score .back-9 td input').each(function() {
            scores.push($(this).val());
        });
    }
    params['score'] = scores.join(',');
    $.post('data/scores.php', params, function(data) {
        var score = $.parseJSON(data);
        addScore(score);
    }).error(function(xhr, ajaxOptions, thrownError) {
        alert(xhr.responseText);
    });
}

function clearAddScoreForm() {
    $('#add-score-form .date').val("");
    $('#add-score-form .score .front-9 td input').each(function() {
        $(this).val("");
    });
    $('#add-score-form .score .back-9 td input').each(function() {
        $(this).val("");
    });
    $('#add-score-form .course-dropdown .current').text('Select A Course');
    $('#add-score-form .tee-dropdown .current').text('Select A Tee');
    
    $('#add-score-meta').show();
    $('#add-score-form table td').hide();
    $('#add-score-next').hide();
    $('#add-score-form table td input').each(function() {
        $(this).off('change');
    });
    
    $('#add-score-next-button').attr('disabled');
    $('#add-score-next-button').off('click');
}

function continueAddScore() {
    $('#add-score-meta').hide();
    $('#add-score-form table td').first().show();
    $('#add-score-next').show();
}

function prepareAddScore() {
    clearAddScoreForm();
    var params = {};
    params['all'] = 'all';
    
    $.get('data/courses.php', params, function(data) {
        var coursesAndTees = $.parseJSON(data);
        $('#add-score-form').dialog('open');

        $('#add-score-courses-list li').remove();
        for(i in coursesAndTees) {
            var course = coursesAndTees[i];
            var new_item = '<li data-id='+course.id+' data-9-holes='+course['9-holes']+' data-tees="';
            for(j in course.tees) {
                var tee = course.tees[j];
                new_item += tee.name + ',' + tee.id + ';';
            }
            new_item += '">' + course.name + '</li>';
            $('#add-score-courses-list').append(new_item);
        }
        $('#add-score-courses-list li').click(function() {
            var tees = $(this).attr('data-tees').split(';');
            $('#add-score-tees-list li').remove();
            for(i in tees) {
                var nameAndID = tees[i].split(',');
                if(nameAndID.length >= 2) {
                    var new_item = '<li data-id='+nameAndID[1]+">"+nameAndID[0]+"</li>";
                    $('#add-score-tees-list').append(new_item);
                }
            }
            
            var selector = '#add-score-form table td input';
            if($(this).attr('data-9-holes') != 0) {
                selector = '#add-score-form table .front-9 td input'
            }
    
            $(selector).each(function() {
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
    
            $('#add-score-next-button').click(continueAddScore);
            $('#add-score-next-button').removeAttr('disabled');
        });
    }).error(function(xhr, ajaxOptions, thrownError) {
        alert(xhr.responseText);
    });
}

function getScores()
{
    $.get('data/scores.php', function(data) {
        var scores = $.parseJSON(data);
        for(i in scores)
        {
            addScore(scores[i]);
        }
    }).error(function(xhr, ajaxOptions, thrownError) {
        alert(xhr.responseText);
    });
}

function addScore(score)
{
    $('#main').append(score_row.replace('_score_date_', score.date).replace('_course_name_', score.name).replace('_score_id_', score.id));
    var holes = score.score.split(',');
    var nineHoles = '<tr class="front-9">';
    for(var j = 0; j < 9; j++) {
        if(holes[j]) {
            nineHoles += '<td>'+holes[j]+'</td>';
        } else {
            nineHoles += '<td/>';
        }
    }
    $('.score[data-id='+score.id+'] .score-details table').append(nineHoles);
    if(holes.length >= 18)
    {
        nineHoles = '<tr class="back-9">';
        for(var j = 9; j < 18; j++) {
            if(holes[j]) {
                nineHoles += '<td>'+holes[j]+'</td>';
            } else {
                nineHoles += '<td/>';
            }
        }
        $('.score[data-id='+score.id+'] .score-details table').append(nineHoles);
    }
    $('.score[data-id='+score.id+'] .score-details table').blur(function() {
        var scores = new Array();
        $(this).find('td').each(function() {
            scores.push($(this).text());
        });
        updateScore($(this).parents('.score').attr('data-id'), scores.join(','));
    });
    $('.score[data-id='+score.id+'] .delete-game').click(function() {
        // TODO-M: ask for confirmation
        var id = $(this).parents('.score').attr('data-id');
        $.post('data/scores.php', {action: 'delete', id: id}, function(data) {
            data = $.trim(data);
            if(data.length == 0) {
                $('.score[data-id='+id+']').remove();
            }
        }).error(function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        });
    });
}