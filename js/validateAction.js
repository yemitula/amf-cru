// JavaScript Document

$(document).ready(function() {
    $('a.ValidateAction').click(function() {
		var url = $(this).attr('href');
		var msg = $(this).attr('data-confirm-msg');
		var proceed = confirm(msg);
		//var proceed = confirm('Are you sure you want to proceed with this action?');
		if(proceed) {
			//ok to proceed
			window.location.href = url;
		} else {
			//cancel action
			return false;
		}        
    });
});