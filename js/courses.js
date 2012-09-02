var new_course = '{\
"name": "", \
"9-holes": true, \
"tees": {}\
}';

var new_tee = '{ \
"name": "", \
"slope": "", \
"rating": "", \
"is-ladies": false, \
"par": "", \
"handicap": "" \
}';

var tee_row = '\
<div class="row tee-row"> \
    <div class="four columns"> \
        <a class="nice radius small white button tee">_tee_name_</a> \
    </div> \
    <div class="two columns"> \
        <a class="nice radius small red button delete-tee">X</a> \
    </div> \
</div>';

var course_row = '\
<div class="row course"> \
    <div class="name seven columns">_course_name_</div> \
    <div class="buttons four columns offset-by-one"> \
        <a class="nice radius small white button delete-course">Delete</a><br/> \
        <a class="nice radius small white button edit-course">Edit</a> \
    </div> \
</div>';

var guest_course_row = '\
<div class="row course"> \
    <div class="name seven columns">_course_name_</div> \
</div>';

var currentCourse;
var currentTee;

// TODO-L: error handling
function populate_course(course) {
    $('#course-name').val(course.name);
    if(course.name.length == 0) {
        $('#course-name').removeAttr('disabled');
    } else {
        $('#course-name').attr('disabled', 'disabled');
    }
    
    if(course['9-holes']) {
        $('#9-holes').click();
    } else {
        $('#18-holes').click();
    }
    var height = 251;
    $.each(course.tees, function(index, value) {
        height += 34;
        $('#add-tee-row').before(tee_row.replace('_tee_name_', index));
    });
    $( "#course_form" ).dialog('option', 'height', height);
    
    $('.button.tee').click(function() {
        populate_tee(course.tees[$(this).text()]);
        $('#tee_form').dialog('open');
    });
    
    $('.button.delete-tee').click(function() {
        delete_tee($(this).parent().parent().find('.tee').text());
    });
}

// TODO-L: error handling
function populate_tee(tee) {
    if(currentCourse['9-holes']) {
        $('.back-9').attr('disabled', 'disabled');
    } else {
        $('.back-9').removeAttr('disabled');
    }
    
    $('#tee-name').val(tee.name);
    if(tee.name.length == 0) {
        $('#tee-name').removeAttr('disabled');
    } else {
        $('#tee-name').attr('disabled', 'disabled');
    }
    if(tee['is-ladies'] && $('#ladies-tee').attr('checked') == null) {
        $('#ladies-tee').click();
    }
    if(!tee['is-ladies'] && $('#ladies-tee').attr('checked')) {
        $('#ladies-tee').click();
    }
    $('#slope').val(tee.slope);
    $('#rating').val(tee.rating);
    parArray = tee.par.split(',');
    handicapArray = tee.handicap.split(',');
    
    $('#par-f9').children().each(function(index) {
        $(this).val(parArray[index]);
    });
    $('#handicap-f9').children().each(function(index) {
        $(this).val(handicapArray[index]);
    });
    if(!currentCourse['9-holes']) {
        $('#par-b9').children().each(function(index) {
            $(this).val(parArray[index+9]);
        });
        $('#handicap-b9').children().each(function(index) {
            $(this).val(handicapArray[index+9]);
        });
    }
}

// TODO-L: error handling on form data
function add_tee() {
    currentTee.name = $('#tee-name').val();
    currentTee.slope = Number($('#slope').val());
    currentTee.rating = Number($('#rating').val());
    parArray = new Array();
    handicapArray = new Array();
    $('#par-f9').children().each(function() {
        parArray.push(Number($(this).val()));
    });
    $('#handicap-f9').children().each(function() {
        handicapArray.push(Number($(this).val()));
    });
    if(!currentCourse['9-holes']) {
        $('#par-b9').children().each(function() {
            parArray.push(Number($(this).val()));
        });
        $('#handicap-b9').children().each(function() {
            handicapArray.push(Number($(this).val()));
        });
    }
    currentTee.par = parArray.join(',');
    currentTee.handicap = handicapArray.join(',');
    
    if(currentCourse.tees[currentTee.name] == null) {
        var height = $( "#course_form" ).dialog('option', 'height');
        height += 34;
        $('#add-tee-row').before(tee_row.replace('_tee_name_', currentTee.name));
        $( "#course_form" ).dialog('option', 'height', height);
        
        $('#add-tee-row').prev().find('.button.tee').click(function() {
            populate_tee(currentCourse.tees[$(this).text()]);
            $('#tee_form').dialog('open');
        });
    
        $('#add-tee-row').prev().find('.button.delete-tee').click(function() {
            delete_tee($(this).parent().parent().find('.tee').text());
        });
    }
    
    currentCourse.tees[currentTee.name] = currentTee;
    $('#tee_form').dialog('close');
}

function delete_tee(tee) {
    delete currentCourse.tees[tee];
    $('.button.tee:contains('+tee+')').parent().parent().remove();
    var height = $( "#course_form" ).dialog('option', 'height');
    height -= 34;
    $( "#course_form" ).dialog('option', 'height', height);
}

// TODO-L: error handling
function add_course() {
    currentCourse.name = $('#course-name').val();
    var params = {};
    params['action'] = 'update';
    params['name'] = currentCourse.name;
    params['9holes'] = currentCourse['9-holes'];
    
    var teeNames = new Array();
    $.each(currentCourse.tees, function(index, value) {
        teeNames.push(index);
    });
    params['tees'] = teeNames.join(',');
    
    $.post('data/courses.php', params, function(data) {
        for(var tee in currentCourse.tees) {
            update_tee(currentCourse.tees[tee]);
        }
        // TODO-M: update error in the error reporting box.
        $('#course_form').dialog('close');
        update_courses(data);
    }).error(function(xhr, ajaxOptions, thrownError) {
        alert(xhr.responseText);
    });
}

function update_tee(tee) {
    var params = {};
    params['action'] = 'update';
    params['courseName'] = currentCourse.name;
    params['name'] = tee.name;
    params['isLadies'] = tee['is-ladies'];
    params['slope'] = tee.slope;
    params['rating'] = tee.rating;
    params['par'] = tee.par;
    params['handicap'] = tee.handicap;
    
    $.post('data/tees.php', params, function(data) {
        // TODO-M: do something
        data = $.trim(data);
        if(data.length > 0) {
            alert(data);
        }
    });
}

function update_courses(data) {
    var _courses = $.parseJSON(data);
    $('#courses .course').remove();
    $.each(_courses, function(index, value) {
        $('#courses .footer').before(course_row.replace('_course_name_', value));
    });
    
    $('#courses .edit-course').click(function() {
        $.get('data/courses.php', {name: $(this).parent().siblings('.name').text()}, load_course);
    });
    
    $('#courses .delete-course').click(function() {
        $.post('data/courses.php', {action: 'delete', name: $(this).parent().siblings('.name').text()}, update_courses);
    });
    
    $('#guest-courses .course').remove();
    $.each(_courses, function(index, value) {
        $('#guest-courses h5').after(guest_course_row.replace('_course_name_', value));
    });
}

function load_courses() {
    $.get('data/courses.php', update_courses);
}

function load_course(data) {
    currentCourse = $.parseJSON(data);
    populate_course(currentCourse);
    $('#course_form').dialog('open');
}