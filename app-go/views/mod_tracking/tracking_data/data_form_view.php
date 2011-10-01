<script type="text/javascript">
var email = '';
$('#tracking_form').submit(function() {
	if (validasi('#tracking_form')){
		email = $('#email').val();
		
		/*$.ajax({
			url: 'index.php/<?php echo $link_controller?>/trace_rt',
			type:'POST',
			data:$('#tracking_form').formSerialize(),
			success: function(data) {
				$('#tracking_result').html(data);
			}
		});
		*/
	}
});
</script>
<fieldset class="tfont ui-widget-content ui-corner-all">
	<!--legend class="ui-state-active ui-corner-tr ui-corner-br">&nbsp; Tracking Order &nbsp;</legend-->
	<table width="93%" class="tfont ui-widget-content" style="margin-left:15px;border:0">
		<tr valign="top">
			<td width="100%">
				<br>
				<form id="tracking_form" onsubmit="return false;">
				<fieldset class="tfont ui-widget-content ui-corner-tr">
					<legend class="ui-state-hover ui-corner-tr ui-corner-br">&nbsp; Form Pencarian &nbsp;</legend>
					<table width="93%" class="tfont ui-widget-content" style="margin-left:15px;border:0">
						<!--tr valign="top">
							<td width="20%">No Order</td><td>:</td>
							<td>
								<input name="jual_no">
							</td>
						</tr>
						<tr valign="top">
							<td>No Resi</td><td>:</td>
							<td>
								<input name="no_resi">
							</td>
						</tr-->
						<tr valign="top">
							<td>Email</td><td>:</td>
							<td>
								<input name="email" class="required" title="Email">
							</td>
						</tr>
						<tr><td colspan=3><hr/></td></tr>
						<tr valign="top">
							<td colspan=3 align="right">
								<input type="submit" value="Cari" class="ui-state-hover ui-corner-all">
							</td>
						</tr>
						<tr><td></td></tr>
					</table>
				</fieldset>
				</form>
				<br>
				<fieldset class="tfont ui-widget-content ui-corner-br ui-corner-bl">
					<legend class="ui-state-hover ui-corner-tr ui-corner-br">&nbsp; Hasil Pencarian &nbsp;</legend>
					<table width="93%" class="tfont ui-widget-content" style="margin-left:15px;border:0">
						<tr valign="top">
							<td width="100%" id="tracking_result" align="center">
							<?php $this->load->view($link_view.'/data_grid_view')?>
							</td>
					</table>
				</fieldset>
				<br>
			</td>
		</tr>
	</table>
</fieldset>	