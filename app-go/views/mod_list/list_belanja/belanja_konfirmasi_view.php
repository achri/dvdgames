<?php
$this->load->view($link_view.'/belanja_rekening_view');
if (isset($data_pengiriman) && $data_pengiriman->num_rows() > 0):
$rkirim = $data_pengiriman->row();
?>
<br>
<fieldset class="tfont ui-widget-content ui-corner-all">
	<legend class="ui-state-active ui-corner-tr ui-corner-br">&nbsp; Rincian Pengiriman &nbsp;</legend>
	<table width="93%" class="tfont ui-widget-content" style="margin-left:15px;border:0">
		<tr valign="top">
			<td width="23%">No. kirim</td><td>:</td>
			<td>
				<?php echo $rkirim->jual_no?>
				<input id="kirim_id" type="hidden" name="kirim_id" value="<?php echo $rkirim->kirim_id?>"/>
			</td>
		</tr>
		<tr valign="top">
			<td>Email</td><td>:</td><td><?php echo $rkirim->email?></td>
		</tr>
		<tr valign="top">
			<td>Alamat</td><td>:</td><td><?php echo $rkirim->alamat?></td>
		</tr>
		
		<tr valign="top">
			<td colspan=3 width="100%">
				<br>
				<fieldset class="tfont ui-widget-content ui-corner-tr">
					<legend class="ui-state-hover ui-corner-tr ui-corner-br">&nbsp; Rincian Pembelian &nbsp;</legend>
					<table width="93%" class="tfont ui-widget-content" style="margin-left:15px;border:0">
						<tr valign="top">
							<td width="20%">Jumlah</td><td>:</td><td><?php echo $rkirim->tot_jumlah?> item(s) = <?php echo $rkirim->tot_dvd?> dvd</td>
						</tr>
						<tr valign="top">
							<td>Potongan</td><td>:</td><td><?php echo $rkirim->bonus?> dvd = Rp. <?php echo number_format(($rkirim->bonus != 0)?($rkirim->bonus * $harga_dvd):(0),2)?></td>
						</tr>
						<tr valign="top">
							<td>Harga</td><td>:</td><td>Rp. <?php echo number_format($rkirim->tot_harga,2)?></td>
						</tr>
					</table>
				</fieldset>
				<br>
				<fieldset class="tfont ui-widget-content">
					<legend class="ui-state-hover ui-corner-tr ui-corner-br">&nbsp; Rincian Biaya Pengiriman (Tiki) &nbsp;</legend>
					<table width="93%" class="tfont ui-widget-content" style="margin-left:15px;border:0">
						<tr valign="top">
							<td width="20%">Paket</td><td>:</td><td><?php echo $rkirim->paket_nama?></td>
							<td valign="middle" align="right" rowspan=4><img src="<?php echo base_url()?>asset/images/tikijne.png" height="100px"/></td>
						</tr>
						<tr valign="top">
							<td>Wilayah</td><td>:</td><td><?php echo $rkirim->tiki_wilayah?></td>
						</tr>
						<tr valign="top">
							<td>Biaya</td><td>:</td><td>Rp. <?php echo number_format($rkirim->tarif_hrg,2)?></td>
						</tr>
						<tr valign="top">
							<td>Cas</td><td>:</td><td>Rp. <?php echo number_format($rkirim->tarif_cas,2)?></td>
						</tr>
					</table>
				</fieldset>
				<br>
				<fieldset class="tfont ui-widget-content ui-corner-br ui-corner-bl">
					<legend class="ui-state-hover ui-corner-tr ui-corner-br">&nbsp; Total keseluruhan &nbsp;</legend>
					<table width="93%" class="tfont ui-widget-content" style="margin-left:15px;border:0">
						<tr valign="top">
							<td align="right" style="font-size:18px;"><strong>Rp. <?php echo number_format($rkirim->total_biaya,2)?></strong></td>
						</tr>
						<tr valign="top">
							<td align="right" style="font-size:12px;"><i>(<?php echo terbilang($rkirim->total_biaya)?>)</i></td>
						</tr>
						<tr><td><hr/></td></tr>
						<tr>
							<td align="left" style="font-size:12px;">
								<div style="text-decoration: blink;">Informasi: </div>
								&nbsp; - Setiap pembelian kelipatan 5 dvd mendapat potongan harga gratis 1 dvd.
								<br>&nbsp; - Berapa rupiah dari total biaya diatas bertujuan untuk 
								<br>&nbsp;&nbsp;&nbsp; system mengkonfirmasi transaksi dan transfer anda.
								<br>&nbsp; - Untuk melihat status pengiriman silahkan masuk ke menu tracking, utk keluhan hubungi kami.
								<br>&nbsp; - Transaksi yang belum dibayar akan dibatalkan secara otomatis setelah 5 hari oleh system.
								<br>&nbsp; - Pengiriman barang akan dilayani langsung sebelum jam sibuk tiki (08:00 s/d 16:00)
								<br>&nbsp;&nbsp;&nbsp; setelahnya dikirim esok hari.
							</td>
						</tr>
					</table>
				</fieldset>
				<br>
			</td>
		</tr>
		</table>
</fieldset>
<?php 
endif;
?>