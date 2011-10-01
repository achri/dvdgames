function ui_switch(themes) {
	$('#ui-themes').attr('href','asset/src/jQuery/themes/'+themes+'/jquery.ui.all.css');
}

function rev_call(title,dvd_id,kat_id) {
	$('#review-content').load('index.php/mod_list/list_dvd/detail_dvd/'+dvd_id,function() {
		$('#review-dialog').dialog('destroy').dialog({
			title: title,
			autoOpen: false,
			bgiFrame: true,
			modal: true,
			resizable:false,
			sizeable:false,
			width: 'auto',
			height: 'auto',
			buttons: {
				"ORDER" : function(){
					add_toCart(dvd_id,kat_id);
					$(this).dialog('close');
				},
				"KELUAR": function(){
					$(this).dialog('close');
				}
			},
			close: function() {
				$(this).dialog('destroy');
			}
		}).dialog('open');
	});
	return false;
}

$(document).ready(function(){
	// BLOCK IT
	var block_opt = {
		message: 'Sedang Proses ...',
		theme: true,
		draggable: false,
		//title: 'Loading',
		forceIframe: true,
		fadeIn:  0,
		fadeOut:  0,
		timeout: 0,
		showOverlay: true,
		focusInput: false,
		quirksmodeOffsetHack: 4
		
	}		
	
	//AJAX LOADING
	var loader = $('<span style="position:relative; margin-left:10px; z-index:10; color:white; text-align:center;"><img src="asset/images/layout/progressbar_microsoft.gif" height="10"></span>')
		.appendTo("#slogan")
		.hide();
	var error = $('<span style="position:absolute; margin-left:10px; z-index:10; color:white; text-align:center;"><h4>halaman tidak tersedia</h4></span>')
		.appendTo("#slogan")
		.hide();
	
	// AJAX HANDLING
	$('*').ajaxStart(function() {
		error.hide();
		loader.show();
		//$('#content-wrap').block(block_opt);
	}).ajaxStop(function() {
		loader.hide();
	}).ajaxSend(function(evt, request, settings) {
		loader.show();
	}).ajaxComplete(function(request, settings){
		//$('#content-wrap').unblock();
	}).ajaxSuccess(function(evt, request, settings){
		error.hide();
		loader.hide();
	}).ajaxError(function(event, request, settings) {
		error.show();
		loader.show();
		//throw settings;
	});
	
	// ALL BUTTON MUST CURSORED POINTER
	$(':button').css('cursor','pointer');
	
	/* DISABLE FUNCTION BROWSER BACK BUTTON */
	window.history.forward(1);
});
