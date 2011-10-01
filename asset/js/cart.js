function load_cart() {
	$('#cart-list').load('index.php/mod_list/list_dvd/list_cart');
	return false;
}

$(document).ready(function() {	
	load_cart();
});