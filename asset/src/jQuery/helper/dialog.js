var $dlg_content = $('div.dialog-content');

function set_dialog() {
	$dlg_content.dialog('destroy')
	.dialog({
		title: 'INFORMASI',
		autoOpen: false,
		bgiframe: true,
		width: 'auto',
		height: 'auto',
		resizable: false,
		draggable: false,
		modal:true,
		/*position:['right','top'],*/
		position:'center'
	});
	return false;
}

set_dialog();

function show_dialog(content,title,func_button,func_close) {
	set_dialog();
	$dlg_content.html(content)
	.dialog('option',{
		title : title,
		buttons: func_button,
		close: func_close
	}).dialog('open');
}

function informasi(title,dlg_close) {
	set_dialog();
	$dlg_content.html(title)
	.dialog('option',{
		title : 'INFORMASI',
		buttons: {
			'OK' : function() {
				$dlg_content.dialog('close');
			}	
		},
		close: dlg_close
	}).dialog('open');
	return false;
}

function konfirmasi(title,dlg_button,dlg_close) {
	set_dialog();
	$dlg_content.html(title)
	.dialog('option',{
		title : 'KONFIRMASI',
		buttons: dlg_button,
		close: dlg_close
	}).dialog('open');
	return false;
}
