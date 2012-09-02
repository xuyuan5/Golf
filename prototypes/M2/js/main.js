var new_course = '{\
"name": "", \
"9-holes": true, \
"tees": { \
    "Red": { \
        "name": "Red", \
        "slope": 123, \
        "rating": 70, \
        "is-ladies": true, \
        "par": [], \
        "handicap": [] \
    }, \
    "White": { \
        "name": "Red", \
        "slope": 128, \
        "rating": 70, \
        "is-ladies": false, \
        "par": [], \
        "handicap": [] \
    }, \
    "Blue": { \
        "name": "Blue", \
        "slope": 133, \
        "rating": 70, \
        "is-ladies": false, \
        "par": [], \
        "handicap": [] \
    } \
}\
}';

var tee_row = '\
<div class="row tee-row"> \
    <div class="four columns"> \
        <a class="nice radius small white button tee">_tee_name_</a> \
    </div> \
    <div class="two columns"> \
        <a class="nice radius small red button">X</a> \
    </div> \
</div>';

var currentCourse;

$(function() {
    $('.add-course').click(function() {
        currentCourse = $.parseJSON(new_course);
        populate_course(currentCourse);
        $('#course_form').dialog('open');
    });
    
    $('.add-course, .edit-course').click(function() { 
        $('#course_form').dialog('open');
    });
    
    $('.cancel-form').click(function() {
        $('#course_form').dialog('close');
    });
    
    $( "#course_form" ).dialog({
			autoOpen: false,
			//height: 268, // 34 for each row of tee
            height: 336,
			width: 380,
			modal: true,
            resizable: false,
            close: function() {
                $('.tee-row').remove();
            }
	});
    
    $('.cancel-tee-form').click(function() {
        $('#tee_form').dialog('close');
    });
    
    $( "#tee_form" ).dialog({
			autoOpen: false,
			height: 583,
			width: 320,
			modal: true,
            resizable: false
	});
    
    $('#9-holes-label').click(function() {
        currentCourse['9-holes'] = true;
    });
    
    $('#18-holes-label').click(function() {
        currentCourse['9-holes'] = false;
    });
}); 

// TODO: error handling
function populate_course(course) {
    $('#course_name').val(course.name);
    if(course['9-holes']) {
        $('#9-holes').click();
    }
    var height = 234;
    $.each(course.tees, function(index, value) {
        height += 34;
        $('#add-tee-row').before(tee_row.replace('_tee_name_', index));
    });
    $( "#course_form" ).dialog('option', 'height', height);
    
    $('.button.tee,.button.add-tee').click(function() {
        if(course['9-holes']) {
            $('.back-9').attr('disabled', 'disabled');
            populate_tee(course.tees[$(this).text()]);
            $('#tee_form').dialog('open');
        } else {
            $('.back-9').attr('disabled', '');
            populate_tee(course.tees[$(this).text()]);
            $('#tee_form').dialog('open');
        }
    });
}

// TODO: error handling
function populate_tee(tee) {
    if(tee == null) {
        return;
    }
}