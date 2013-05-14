$(document).ready( function() {
	$('#form').submit( function() {
		if ( $('#email').val() == '' || $('#password').val() == '' ) {
			$('#login-errors').html('Введите все данные.');
			$('#login-errors').show();
			return false;
		} else {
			$('#login-errors').hide();
		}
	} );
} );