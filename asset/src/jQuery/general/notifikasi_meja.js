// AJAX TABLE

var ajax_refresh,
	debug_no = 0;
	
function refresh_now() {
	refresh_stop();
	ajax_refresh = window.setInterval(refresh_content,10000);
	//return false;
}

function refresh_stop() {
	clearInterval(ajax_refresh);
	//return false;
}

function refresh_content() {
	refresh();
	
	// DEBUG
	//$('#debug-cafe').append(debug_no+'.'+Date()+'<br>');
	return false;
}

function table_prepare() {
	for (no_meja = 1; no_meja <= jml_meja; no_meja++) {
		table_content(no_meja);
		table_icon(no_meja,0);
	}
	
	if (refresh_type == 'kitchen') {
		$('li.meja-item').hide();
	} else {
		$('li.meja-item').show();
	}
	
	if (refresh_type == 'order') {
		// DRAGABLE
		$('.meja-menu > li').draggable({
			containment: "#drag_box",
			revert: "invalid",
			helper: "clone",
			cursor: "move",
			opacity: 0.80
		})
		.css({"cursor": "move"})
		.dblclick(function(){
			var no_meja = $(this).attr('no_meja');
				tambah_order(no_meja);
		});
	}
	
	return false;
}

function table_content(no_meja) {
	var meja_content;
	
	if (refresh_type == 'order' || refresh_type == 'show_order') {
		meja_content = "<li id=\"meja_"+no_meja+"\" class=\"drag_this ui-widget-content ui-corner-tr\" no_meja=\""+no_meja+"\">"+
							"<div id=\"meja-menu_"+no_meja+"\" style=\"height:91px;overflow:auto\" class=\"ui-widget-header\">"+
								"<table width=\"100%\" height=\"100%\"><tr align=\"center\"><td style=\"font-size:35px;\">"+no_meja+"</td></tr></table>"+
							"</div>"+
							"<div id=\"meja-menu_"+no_meja+"_icon\"></div>"+
						"</li>";
	} else if (refresh_type == 'kitchen') {
		meja_content = "<li id=\"meja_"+no_meja+"\" class=\"meja-item ui-widget-content ui-corner-tr\" no_meja=\""+no_meja+"\">"+
							"<h5 class=\"ui-state-error\">MEJA "+no_meja+"</h5>"+
							"<div id=\"meja-menu_"+no_meja+"\" style=\"height:150px;overflow:auto\" class=\"ui-state-error\">"+
								"<div id=\"meja-menu_"+no_meja+"_status\" style=\"margin-top:4px;font-size:12px\">KOSONG</div>"+
							"</div>"+
							"<div id=\"meja-menu_"+no_meja+"_icon\"></div>"+
						"</li>";
	}
	
	$('ul.meja-menu').append(meja_content);
	return false;
}

function table_icon(no_meja,order_id) {
	var icon;
	
	switch(refresh_type) {
		case 'order'   :
			if (order_id != 0)
				icon = "<a title=\"Tambah Menu Meja ke-"+no_meja+"\" onclick=\"tambah_order("+no_meja+");\" class=\"ui-icon ui-icon-document\" style=\"float:left;cursor:pointer;\">&nbsp;</a>"+
					   "<a title=\"Hapus Order Meja ke-"+no_meja+"\" onclick=\"hapus_order("+order_id+","+no_meja+");\" class=\"ui-icon ui-icon-trash\" style=\"float:right;cursor:pointer;\">&nbsp;</a>";
			else
				icon = "<a title=\"Tambah Order Meja ke-"+no_meja+"\" onclick=\"tambah_order("+no_meja+");\" class=\"ui-icon ui-icon-pencil\" style=\"float:left;cursor:pointer;\">&nbsp;</a>";
		break;
		case 'kitchen' :
			icon = "<a title=\"Selesai disiapkan\" id=\"meja-menu_"+no_meja+"_right\" onclick=\"bill_order("+order_id+","+no_meja+")\"class=\"ui-icon ui-icon-circle-check\" style=\"float:right;cursor:pointer;\">&nbsp;</a>";
		break;
	}
	$('div#meja-menu_'+no_meja+'_icon').html(icon);
	return false;
}

function table_set(show,no_meja,order_id) {
	if (show) {
		$("li#meja_"+no_meja).show();
	} else {
		$("li#meja_"+no_meja).hide();
	}
	return false;
}

function table_status(no_meja,order_id) {
	$("div#meja-menu_"+no_meja+"_status").load('index.php/'+controller+'/data_order_menu/'+order_id);
	return false;
}

function table_detail_content(no_meja,order_id) {
	$("td#meja-menu_"+no_meja+"_content").load('index.php/'+controller+'/data_meja_menu/'+order_id);
	return false;
}

function table_detail(status,no_meja) {
	var meja_content;
	
	if (status == true) {
		meja_content = "<h5 class=\"ui-widget-header\">MEJA "+no_meja+"</h5>"+
					"<div id=\"meja-menu_"+no_meja+"\" style=\"height:70px;overflow:auto\" class=\"ui-state-default\">"+
					"<table width=\"100%\" height=\"100%\"><tr align=\"center\"><td id=\"meja-menu_"+no_meja+"_content\"></td></tr></table>"+
					"</div>"+
					"<div id=\"meja-menu_"+no_meja+"_icon\"></div>";
	} else {
		meja_content = "<div id=\"meja-menu_"+no_meja+"\" style=\"height:80px;overflow:auto\" class=\"ui-widget-header\">"+
					"<table width=\"100%\" height=\"100%\"><tr align=\"center\"><td style=\"font-size:35px;\">"+no_meja+"</td></tr></table>"+
					"</div>"+
					"<div id=\"meja-menu_"+no_meja+"_icon\"></div>";
	}
	
	$('li#meja_'+no_meja).html(meja_content);
	return false;
}

function refresh() {
	$.getJSON('index.php/'+controller+'/cek_meja_ajax/'+refresh_type, function(data) {
		if (data != null) {
			$.each(data, function(entryIndex, entry) {
				var order_id = entry['order_id'],
					no_meja = entry['no_meja'];
				
				if (refresh_type == 'order' || refresh_type == 'show_order') {
					table_detail(true,no_meja);
					table_detail_content(no_meja,order_id);
				} else if (refresh_type == 'kitchen') {
					table_status(no_meja,order_id);
					table_set(true,no_meja,order_id);
				}
				
				table_icon(no_meja,order_id);
			});
		}
	});
	
	debug_no++;
	return false;
}