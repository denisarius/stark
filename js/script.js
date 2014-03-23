$(document).ready(function(){
	$(".content_menu_submenu ul").hover(
		function() {
			$(this).parent(".content_menu_submenu").addClass("current");
		}, function() {
			$(this).parent(".content_menu_submenu").removeClass("current");
		}
	);
});