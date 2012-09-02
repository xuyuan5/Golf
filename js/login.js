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
            height: 150,
			width: 300,
			modal: true,
            resizable: false
	});
    
    $( "#register-form" ).dialog({
            title: 'Register',
			autoOpen: false,
			height: 180,
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
    
    $('#form-user').click(function() {
        $('#user-form').dialog('close');
    });
    
    $('#form-register').click(function() {
        var params = {};
        params['action'] = 'register';
        params['email'] = $('#register-email').val();
        params['password'] = $('#register-password').val();
        params['passwordc'] = $('#register-confirm-password').val();
        
        $.post('data/login.php', params, function(data) {
            $('#register-form').dialog('close');
            alert('Registration Succeed! Please login to proceed.');
        }).error(function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        });
    });
    
    $('#form-login').click(function() {
        var params = {};
        params['action'] = 'login';
        params['email'] = $('#login-email').val();
        params['password'] = $('#login-password').val();

        $.post('data/login.php', params, function(data) {
            $('#login-form').dialog('close');
            window.location.reload();
        }).error(function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        });
    });
    
    $('#user-form .button').click(function() {
        var params = {};
        params['action'] = 'update';
        params['email'] = $(this).siblings().val();
        
        $.post('data/login.php', params, function(data) {
            alert('success');
        }).error(function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        });
    });
    
    $('#logout-user').click(function() {
        var params = {};
        params['action'] = 'logout';
        
        $.post('data/login.php', params, function(data) {
            window.location.reload();
        }).error(function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        });
    });
});
