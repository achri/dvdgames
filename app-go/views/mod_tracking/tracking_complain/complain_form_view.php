<script type="text/javascript">
var grid_content = jQuery("#newapi_cs"),
	kirim_id = '<?php echo $kirim_id?>';
	
function awal() {
	$('#btn_action').val('Simpan')
	.bind('click', function () { add_cs(); });
	return false;
}
	
function clear() {
	$('.dvd_form').val('');
	show_photo();
	return false;
}

function add_cs() {
	if (validasi('#cs_form')) {
		$.ajax({
			url: '<?php echo site_url($link_controller)?>/simpan_keluhan',
			type: 'POST',
			data: $('#cs_form').serialize(),
			success: function(data) {
				if (data) {
					list_cs(kirim_id);
					clear();
				}
			}
		});
	}
	return false;
}

function edit_cs(csc_id) {
	$.getJSON('<?php echo site_url($link_controller)?>/edit_json/'+csc_id,function(data){
		$.each(data,function(idx,item){
			$('#csc_id').val(item['csc_id']);
			$('#dvd_id').val(item['dvd_id']);
			$('#csc_complain').val(item['csc_complain']);
			$('#gambar').val(item['csc_lampiran']);
			
			$('#btn_action').val('Update')
			.bind('click', function () { update_cs(csc_id); });
		});
	});
	return false;
}

function update_cs(csc_id) {
	/*
	if (validasi('#cs_form')) {
		$.ajax({
			url: '<?php echo site_url($link_controller)?>/edit_keluhan/'+csc_id,
			type: 'POST',
			data: $('#cs_form').serialize(),
			success: function(data) {
				if (data) {
					list_cs(kirim_id);
					clear();
				}
			}
		});
	}
	*/
	alert(csc_id);
	return false;
}

function hapus_cs(csc_id) {
	$.ajax({
		url: '<?php echo site_url($link_controller)?>/hapus_keluhan/'+csc_id,
		type: 'POST',
		success: function(data) {
			if (data) {
				list_cs(kirim_id);
				clear();
			}
		}
	});
	return false;
}

function list_cs(kirim_id) {
	if (kirim_id) {
		$.ajax({
			url: '<?php echo site_url($link_controller)?>/list_complain/'+kirim_id,
			type:'POST',
			success: function(data) {
				if (data) {
					$('#keluhan_result').html(data);
				} else {
					$('#keluhan_result').html('');
				}
			}
		});
	}
	return false;
}

function goback() {
	$('#tab-tracking').tabs('enable',0);
	$('#tab-tracking').tabs('select',0);
	$('#tab-tracking').tabs('remove',1);
	return false;
}

function show_photo() {
	var target = $('#gambar').val();
	if (!target) {
		target = 1;
	}	
	
	$.ajax({
		url: 'admin.php/<?php echo $link_controller?>/show_photo/'+target+'/kategori',
		type: 'POST',
		success: function(data){
			$('#photos').html(data);
			return false;
		}
	});
	return false;
}

// AJAX UPLOAD
$(document).ready(function(){
	var button = $('.photos'), interval,
		upload = new AjaxUpload('#getgambar',{
			action: '<?php echo site_url($link_controller)?>/ajaxupload', 
			name: 'userfile',
			onSubmit : function(file, ext){
				upload.setData({'gambar': $('#gambar').val()});
				if (! (ext && /^(jpg|png|jpeg|gif)$/i.test(ext))){
					informasi('Exstensi File tidak mendukung !!!');
					return false;
				} else {			
					button.text('mengirim');
					this.disable();
					interval = window.setInterval(function(){
						var text = button.text();
						if (text.length < 13){
							button.text(text + '.');					
						} else {
							button.text('mengirim');				
						}
					}, 200);
				}
			},
			onChange: function(file, extension){
			},
			onComplete: function(file, response){
				button.text('Unggah');
				window.clearInterval(interval);
				this.enable();
				$('#gambar').val(response);	
				$('#photos').load('<?php echo site_url($link_controller);?>/show_photo/'+response);
				return false;
			}
		});
});

awal();
list_cs(kirim_id);
</script>
<fieldset class="tfont ui-widget-content ui-corner-all">
	<!--legend class="ui-state-active ui-corner-tr ui-corner-br">&nbsp; Form Keluhan &nbsp;</legend-->
	<table width="93%" class="tfont ui-widget-content" style="margin-left:15px;border:0">
		<tr valign="top">
			<td width="100%">
				<br>
				<fieldset class="tfont ui-widget-content ui-corner-br ui-corner-bl">
					<legend class="ui-state-hover ui-corner-tr ui-corner-br">&nbsp; Daftar Keluhan &nbsp;</legend>
					<table width="93%" class="tfont ui-widget-content" style="margin-left:15px;border:0">
						<tr valign="top">
							<td width="100%" align="center">
								<ul id="keluhan_result" style="list-style:none">
								<!-- AJAX -->
								</ul>
								<br>
							</td>
					</table>
				</fieldset>
				<br>
				<form id="cs_form" onsubmit="return false;">
				<fieldset class="tfont ui-widget-content ui-corner-tr">
					<legend class="ui-state-hover ui-corner-tr ui-corner-br">&nbsp; Form Keluhan &nbsp;</legend>
					<table width="93%" class="tfont ui-widget-content" style="margin-left:15px;border:0">
						<!--tr valign="top">
							<td width="20%">No Order</td><td>:</td>
							<td>
								<input name="jual_no">
							</td>
						</tr-->
						<tr valign="top">
							<td width="100px">Judul DVD</td><td>:</td>
							<td>
								<input type="hidden" name="kirim_id" value="<?php echo $kirim_id?>"/>
								<input type="hidden" name="csc_id" value=""/>
								<select id="dvd_id" name="dvd_id" class="dvd_form">
									<option value=''>[Tdk Disertai]</option>
								<?php
									foreach ($daftar_dvd->result() as $dvd):
								?>
									<option value="<?php echo $dvd->dvd_id?>"><?php echo $dvd->dvd_nama?></option>
								<?php 
									endforeach;
								?>
								</select>
							</td>
							<td rowspan=3 align="center" valign="middle">
								<div id="photos" class="photos" style="border:1px dotted;width:150px;height:150px">
									<!-- AJAX PHOTO -->
								</div>
							</td>
						</tr>
						<tr valign="top">
							<td>Keluhan</td><td>:</td>
							<td>
								<textarea id='csc_complain' name="csc_complain" class="required dvd_form" title="Keluhan" style="width:300px;height:100px"></textarea>
							</td>
						</tr>
						<tr valign="top">
							<td>Screen Shot</td><td>:</td>
							<td>
								<!--input name="csc_lampiran" class="" title="Screen Shot"-->
								<input type="button" id="getgambar" value="Pilih" class="ui-corner-all ui-widget-header"> 
								<input class="dvd_form" readonly="true" name="csc_lampiran" type="text" id="gambar" size="20"/>
								<input type="hidden" id="gambar_awal" name="csc_lampiran_awal" class="dvd_form">
							</td>
						</tr>
						<tr><td colspan=4></td></tr>
						<tr><td colspan=4><hr/></td></tr>
						<tr valign="top">
							<td colspan=4 align="right">
								<input id="btn_add" type="button" value="Simpan" class="ui-state-hover ui-corner-all">
								<input id="btn_edit" type="button" value="Update" class="ui-state-hover ui-corner-all">
							</td>
						</tr>
						<tr><td></td></tr>
					</table>
				</fieldset>
				</form>
			</td>
		</tr>
		<tr>
			<td align="center"><input type="button" class="ui-state-hover ui-corner-all" onclick="goback();" value="Kembali" style="cursor:pointer"></td>
		</tr>
	</table>
</fieldset>	