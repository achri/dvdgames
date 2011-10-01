
<script type="text/javascript">
var tot_page,page;

// MOUSE WHELL
$('#dvd-item-ajax').mousewheel(function(obj,idx){
	var get_status = false;
	switch (Math.ceil(idx)){
		case 0 :
			if (Number(page) < Number(tot_page)) {
				set_page = Number(page) + 1;
				get_status = true;
			}
		break;
		case 1 :
			if (Number(page) > 1) {
				set_page = Number(page) - 1;
				get_status = true;
			}
		break;
	}
	
	if (get_status) {
		$('#dvd_page').val(set_page);
		get_dvd();
	}
	return false;
});

function pagina() {
	tot_page  = $('#tot_page').val();
	//tot_page++;
	
	page = $('#page').val();
	
	$('#dvd_page').val(page);
	
	$('.selpage').text('['+page+'/'+tot_page+']');
	
	$('.pagina ui-icon-seek-prev').show();

	$('.pagina ui-icon-seek-next').show();
	
	$('.pagina ui-icon-seek-start').show();
	$('.pagina ui-icon-seek-end').show();
	
	return false;
}

$('.pagina').click(function() {
	var type = $(this);
	
	if (type.hasClass('ui-icon-seek-start')) 
		set_page = 1;
	else if (type.hasClass('ui-icon-seek-next')) 
		set_page = Number(page) + 1;
	else if (type.hasClass('ui-icon-seek-prev')) 
		set_page = Number(page) - 1;
	else if (type.hasClass('ui-icon-seek-end')) 
		set_page = Number(tot_page);
	
	$('#dvd_page').val(set_page);
	
	get_dvd();
	return false;
});

function get_dvd() {
	try {
		$.ajax({
			url: '<?php echo site_url($link_controller)?>/daftar_dvd',
			type: 'POST',
			data: $('#fcari').formSerialize(),
			success: function(data) {
				$('#dvd-item-ajax').html(data);
			}
		});
	} catch(e) {}
	return false;
}

$('#kat_id').change(function() {
	$('#dvd_nama').val('').focus();
	$('#dvd_page').val(1);
	get_dvd();
	return false;
});

/*
$('#dvd_nama, #dvd_page').keypress(function(event) {
	if (event.which == 13) {
		get_dvd();
	} else {
		return;
	}
});*/

var tOut;

$('#dvd_nama, #dvd_page').keyup(function(event) {
	clearTimeout(tOut);
	tOut = window.setTimeout(get_dvd,1000);
	return false;
});

get_dvd();
load_cart();
$('#dvd_nama').focus();
$('.pagina').css({'float':'left','cursor':'pointer'});
</script>
<form id='fcari' onsubmit='return false;'>
<DIV class="ui-widget-content ui-corner-tl ui-corner-tr">&nbsp;&nbsp;&nbsp;
<frameset>
Kategori :
<select id="kat_id" name="kat_id" class="ui-state-hover ui-corner-tr ui-corner-tl" style="font-size:10px">
<option value="0">[ SEMUA ]</option>
<?php 
if (isset($daftar_kategori)):
	if ($daftar_kategori->num_rows() > 0):
	foreach ($daftar_kategori->result() as $rkat):
?>
	<option value="<?php echo $rkat->kat_id?>" <?php echo ($rkat->kat_id == (isset($kat_id)?($kat_id):('')))?('SELECTED'):('')?>>[ <?php echo $rkat->kat_nama?> ]</option>
<?php
	endforeach;
	endif;
endif;
?>
</select>
</frameset>
&nbsp;
<frameset>
Judul :
<input id="dvd_nama" name="dvd_nama" class="ui-state-hover" style="font-size:10px;height:16px" value="<?php echo (isset($dvd_nama))?($dvd_nama):('')?>"></td>
</frameset>

<table width="" style="float:right" cellpadding=0 cellspacing=0>
<tr>
<td align="center" valign="top">
<input type="hidden" id="dvd_page" name="page">
<a class="pagina ui-icon ui-icon-seek-start"></a>
<a class="pagina ui-icon ui-icon-seek-prev"></a>
<a class="pagina selpage"></a>
<a class="pagina ui-icon ui-icon-seek-next"></a>
<a class="pagina ui-icon ui-icon-seek-end"></a>
</td>
</tr>
</table>

</DIV>
</form>

<div id="dvd-item-ajax">

</div>

<?php		
	//$this->load->view($link_view.'/dvd_list_view');
?>
