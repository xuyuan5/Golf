$(function() {
    $('#launch-login').click(function() {
        $('#login-form').dialog('open');
    });
    
    $('#launch-register').click(function() {
        $('#register-form').dialog('open');
    });
    
    $('#edit-user').click(function() {
        $('#user-form').dialog('open');
    });
    
    $( "#login-form" ).dialog({
            title: 'Login',
			autoOpen: false,
            height: 120,
			width: 300,
			modal: true,
            resizable: false
	});
    
    $( "#register-form" ).dialog({
            title: 'Register',
			autoOpen: false,
			height: 120,
			width: 300,
			modal: true,
            resizable: false
	});
    
    $( "#user-form" ).dialog({
            title: 'Edit User',
			autoOpen: false,
			height: 120,
			width: 300,
			modal: true,
            resizable: false
	});
    
    $('#form-login').click(function() {
        $('#login-form').dialog('close');
    });
    
    $('#form-register').click(function() {
        $('#register-form').dialog('close');
    });
    
    $('#form-user').click(function() {
        $('#user-form').dialog('close');
    });
});