<script type="text/javascript">
function change_paket(paket_id) {
	var jual_id = $('#jual_id').val();
	$('#tarif_id').val('');
	$('#biaya, #cas, #total_harga').text('-');
	$('input#wilayah').val('').unautocomplete().autocomplete('<?php echo site_url($link_controller);?>/list_autocomplate',{
		minChars: 2,
		matchCase: true,
		max: 10,
		extraParams: {'paket_id':paket_id,'jual_id':jual_id},
	}).result(function(event,item) {
		$('#tarif_id').val(item[1]);
		$('#biaya').text(item[2]);
		$('#cas').text(item[3]);
		$('#total_harga').text(item[4]);
		$('#total_biaya').val(item[5]);
	});
	return false;
}

function cek_term() {
	var term = $('#term:checked').length,
		alamat = $('#alamat').val(),
		tarif_id = $('#tarif_id').val(),
		paket_id = $('#paket_id').val();
	if (term) {
		if (!alamat||!tarif_id||!paket_id){
			informasi("Alamat pengiriman harus lengkap !!!");
			$('#term').attr("checked",false);
		} else {
			$('#next').attr('disabled',false).removeClass('ui-state-deactive').addClass('ui-state-hover');
		}
	} else {
		$('#next').attr('disabled',true).removeClass('ui-state-hover').addClass('ui-state-deactive');
	}
	return false;
}

$(document).ready(function() {
	$('#transfer_form').submit(function(){
		var form = '#'+$(this).attr('id'),
			$dlg_btn = {
				"SETUJU" : function() {
					$.ajax({
						url: '<?php echo site_url($link_controller);?>/proses_pengiriman',
						type:'POST',
						data:$('#transfer_form').formSerialize(),
						success: function(data) {
							//alert(data);
							if (data) {
								//tab_pembayaran.tabs('select',1);
								//tab_pembayaran.tabs('disable',0);
								$dlg_content.dialog('close');
								menu_ajax('list_belanja');
							}
						}
					});
				},
				"TUNGGU" : function() {
					$(this).dialog('close');
				}
			}
		
		if (validasi(form)) {
			konfirmasi("Setuju melanjutkan proses pembayaran ???<br> periksa kembali alamat anda apakah sudah sesuai",$dlg_btn);
		}
	});
});
</script>

<form id="transfer_form" onsubmit="return false;">

<!-- DATA PENJUALAN -->
<?php
if (isset($data_penjualan)):
$rorder = $data_penjualan->row();
?>
<fieldset class="tfont ui-widget-content ui-corner-tr">
	<legend class="ui-state-hover ui-corner-tr ui-corner-br">&nbsp; Rincian Pembelian &nbsp;</legend>
	<table class="tfont ui-widget-content" style="margin-left:15px;border:0">
		<tr valign="top" style="">
			<td width="80px">No. Order</td><td>:</td>
			<td>
				<?php echo $rorder->jual_no?>
				<input id="jual_id" type="hidden" name="jual_id" value="<?php echo $rorder->jual_id?>"/>
			</td>
		</tr>
		<tr valign="top">
			<td width="80px">Email</td><td>:</td><td><?php echo $rorder->email?></td>
		</tr>
		<tr valign="top">
			<td>Jumlah</td><td>:</td><td><?php echo $rorder->tot_jumlah?> item(s) = <?php echo $rorder->tot_dvd?> dvd </td>
		</tr>
		<tr valign="top">
			<td>Potongan</td><td>:</td><td><?php echo $rorder->bonus?> dvd = Rp. <?php echo number_format(($rorder->bonus != 0)?($rorder->bonus * $harga_dvd):(0),2)?></td>
		</tr>
		<tr valign="top">
			<td>Harga</td><td>:</td><td>Rp. <?php echo number_format($rorder->tot_harga,2)?></td>
		</tr>
	</table>
</fieldset>
<?php 
endif;
?>
<br>
<!-- ALAMAT -->
<fieldset class="tfont ui-widget-content">
	<legend class="ui-state-hover ui-corner-tr ui-corner-br">&nbsp; Alamat &nbsp;</legend>
	<table width="90%" class="tfont ui-widget-content" style="margin-left:15px;border:0">
		<tr valign="top">
			<td width="80px">Paket</td><td>:</td>
			<td>
			<select id="paket_id" onchange="change_paket(this.value);" class="required select" title="Paket">
			<option value="">-= Pilih =-</option>
			<?php 
			if (isset($tiki_paket)):
				foreach ($tiki_paket->result() as $rpaket):
			?>
				<option value="<?php echo $rpaket->paket_id?>"><?php echo $rpaket->paket_nama?></option>
			<?php
				endforeach;
			endif;
			?>
			</select>
			</td>
			<td valign="middle" align="right" rowspan=5><img src="<?php echo base_url()?>asset/images/tikijne.png" height="100px"/></td>
		</tr>
		<tr valign="top">
			<td>Wilayah</td><td>:</td>
			<td>
				<input id="wilayah" type="text"/>
				<input id="tarif_id" type="hidden" name="tarif_id" class="required" title="Wilayah">
			</td>
		</tr>
		<tr valign="top">
			<td>Alamat</td><td>:</td>
			<td>
				<textarea id="alamat" name="alamat" class="required textarea" title="Alamat"></textarea>
			</td>
		</tr>
		<tr valign="top">
			<td>Biaya</td><td>:</td>
			<td>
				<span id="biaya">-</span>
			</td>
		</tr>
		<tr valign="top">
			<td>Cas</td><td>:</td>
			<td>
				<span id="cas">-</span>
			</td>
		</tr>
	</table>
</fieldset>
<br>
<fieldset class="tfont ui-widget-content ui-corner-br ui-corner-bl">
	<legend class="ui-state-hover ui-corner-tr ui-corner-br">&nbsp; Pembayaran &nbsp;</legend>
	<table class="tfont ui-widget-content" style="margin-left:15px;border:0" width="93%">
		<tr valign="top" style="font-style:italic">
			<td width="80px" >Total Harga</td><td width="5px">:</td>
			<td>
				<span id="total_harga" >-</span>
				<input id="total_biaya" name="total_biaya" type="hidden">
			</td>
		</tr>
		<tr><td colspan=3><hr></td></tr>
		<tr style="font-style:italic">
			<td colspan=3>
				<input id="term" type="checkbox" onclick="cek_term();">&nbsp;Saya setuju dan akan melanjutkan
			</td>
		</tr>
		<tr valign="top">
			<td width="80px" colspan=3 align="right">
				<input disabled id="next" type="submit" value="Lanjut Pembayaran" class="ui-state-deactive ui-corner-all" style="cursor:hand"/>
			</td>
		</tr>
		<tr><td></td></tr>
	</table>
</fieldset>

</form>