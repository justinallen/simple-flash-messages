// Simple Flash Messages WP Plugin JavaScript

// read cookie value using cookie.js methods
var showMeNot = Cookies.get('simple_flash_messages_display');
console.log("cookie value: " + showMeNot);

jQuery(document).ready(function(){

	// get cookie value from data attribute
	var showAfterLength = jQuery('#simple-flash').data('show-after');
	console.log("cookie days: " + showAfterLength);

	// close message when button is clicked
	jQuery('#close-simple-flash').on('click', function(){
		if (showAfterLength > 0) {
			Cookies.set('simple_flash_messages_display', 'hide', {
				expires: showAfterLength,
				path: '/'
			});
		} else {
			Cookies.set('simple_flash_messages_display', 'hide', {
				path: '/'				
			});
		}
		jQuery("#simple-flash").slideUp();
		jQuery("#wrapper").animate({
			top: 0
		}, 500);
	});

});

