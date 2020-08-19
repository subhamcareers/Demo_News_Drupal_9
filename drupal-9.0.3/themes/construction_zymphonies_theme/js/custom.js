/** 
	* Filename:     custom.js
	* Version:      1.0.0 (2019-09-20)
	* Website:      https://www.zymphonies.com
	* Description:  Global script
	* Author:		Development team
					info@zymphonies.com
**/

function themeMenu(){

	// Main menu
	jQuery('#main-menu').smartmenus();
	
	// Mobile menu toggle
	jQuery('.navbar-toggle').click(function(){
		jQuery('.region-primary-menu').addClass('expand');
	});
	jQuery('.navbar-toggle-close').click(function(){
		jQuery('.region-primary-menu').removeClass('expand');
	});

	// Mobile dropdown menu
	if ( jQuery(window).width() < 767) {
		jQuery(".region-primary-menu li a:not(.has-submenu)").click(function () {
			jQuery('.region-primary-menu').hide();
	    });
	}

}

function themeHome(){
	jQuery('.flexslider').flexslider({
    	animation: "slide"	
    });
}

jQuery(document).ready(function($){
	themeMenu();
	themeHome();
});