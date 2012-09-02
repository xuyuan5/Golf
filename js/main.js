$(function() {
    $('.add-course').click(function() {
        currentCourse = $.parseJSON(new_course);
        populate_course(currentCourse);
        $('#course_form').dialog('open');
    });
    
    $('.update-course').click(function() {
        add_course();
    });
    
    $('.cancel-form').click(function() {
        $('#course_form').dialog('close');
    });
    
    $( "#course_form" ).dialog({
			autoOpen: false,
            height: 251,
			width: 380,
			modal: true,
            resizable: false,
            close: function() {
                $('.tee-row').remove();
            }
	});
    
    $('.add-tee').click(function() {
        currentTee = $.parseJSON(new_tee);;
        populate_tee(currentTee);
        $('#tee_form').dialog('open');
    });
    
    $('.update-tee').click(function() {
        add_tee();
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
    
    $('#ladies-tee-label').click(function() {
        currentTee['is-ladies'] = currentTee['is-ladies'] ? false : true;
    });
    
    load_courses();
});

function getCookie(c_name)
{
    var i,x,y,ARRcookies=document.cookie.split(";");
    for (i=0;i<ARRcookies.length;i++)
    {
        x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
        y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
        x=x.replace(/^\s+|\s+$/g,"");
        if (x==c_name)
        {
            return unescape(y);
        }
    }
}

function setCookie(c_name,value,exdays)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}