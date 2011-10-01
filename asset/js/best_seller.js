/* TIAP 1 JAM SEKALI UPDATE BESTDVD */

function load_bestdvd() {
	$('.content-best-dvd').load('index.php/mod_list/list_dvd/list_bestdvd');
	return false;
}

var ajax_refresh;

$(document).ready(function(){	

	function refresh_stop() {
		clearInterval(ajax_refresh);
		//return false;
	}
	
	function refresh_now() {
		refresh_stop();
		load_bestdvd();
		ajax_refresh = window.setInterval(refresh_now,3600000);
		//ajax_refresh = window.setInterval(refresh_now,100);
		//return false;
	}
	
	refresh_now();
});