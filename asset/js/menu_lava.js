$(document).ready(function() {
var lava = $("ol.lavalamp");
function move_lava(el) {
	var $back = $('.backLava',lava),
		bx=0, by=0;	

	if (!jQuery.browser.msie) {
		bx = ($back.outerWidth() - $back.innerWidth())/2;
		by = ($back.outerHeight() - $back.innerHeight())/2;
	}
	
	$back.css({
		left: el.offsetLeft-bx,
		top: el.offsetTop-by,
		width: el.offsetWidth,
		height: el.offsetHeight
	});
}

function module_lava(module) {
	var ce = $("li a[module='"+module+"']",lava);
	move_lava(ce);
}

function set_lava(number) {
	var	$li = $('li', lava);
	
	$li.removeClass('selectedLava');
	
	var $selected = $($li[number]),
		ce = $('li.selectedLava', this)[0] || $($selected).addClass('selectedLava')[0];
	
	move_lava(ce);
}

});