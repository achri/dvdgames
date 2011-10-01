<?php
	if (isset($extraSubHeadContent))
		echo $extraSubHeadContent;
?>
<script type="text/javascript">
function show_photo() {
	var target = $('#gambar_scan').val();
	if (!target) {
		target = 1;
	}	
	
	$.ajax({
		url: 'admin.php/<?php echo $link_controller?>/show_photo/'+target+'/tiki',
		type: 'POST',
		success: function(data){
			$('#show_scan_resi').html(data);
			return false;
		}
	});
	return false;
}

/* EDIT RECORD */
function ubah_order() {
	//if (validasi("form#kat_form")) {
		$.ajax({
			url: 'admin.php/<?php echo $link_controller?>/ubah_order',
			type: 'POST',
			data: $('form#order_form').formSerialize(),
			success: function(data) {
				if (data) {
					grid_content.trigger('reloadGrid');
				}
			}
		});
	//}
	
	return false;
}

// AJAX UPLOAD
$(document).ready(function(){
	var button = $('#show_scan_resi'), interval,
		upload = new AjaxUpload('#getscan',{
			action: 'admin.php/<?php echo $link_controller?>/ajaxupload', 
			name: 'userfile',
			onSubmit : function(file, ext){
				upload.setData({'gambar': $('#gambar_scan').val()});
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
				$('#gambar_scan').val(response);	
				$('#show_scan_resi').load('admin.php/<?php echo $link_controller?>/show_photo/'+response);
				return false;
			}
		});
	
});
</script>
<div style="display:table;width:99%">
	<table width="100%" border=0>
	<tr valign="top">
		<td width="100%">
			<?php $this->load->view($link_view.'/order_form_view')?>
		</td>
		<td align="center" rowspan=2>
			<fieldset class="ui-widget-content ui-corner-all" style="width:300px; height:247px;">
				<legend>Faktur Tiki</legend>
				<table width="100%" height="100%">
				<tr>
					<td align="center" valign="middle">
						<div id="show_scan_resi">
							<!-- AJAX PHOTO -->
						</div>
					</td>
				</tr>
				</table>
			</fieldset>
		</td>
	</tr>
	<tr valign="top">
		<td colspan=2>
			<?php $this->load->view($link_view.'/order_grid_view')?>
		</td>
	</tr>
	</table>
</div>
