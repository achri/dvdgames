<form id="order_form" method="post" name="order_form"> 
<fieldset class="ui-widget-content ui-corner-all"> 
	<legend>FORM ORDER</legend> 
	<table width="100%" class="ui-widget-content" border=0 style="border:0px"> 
		<tbody valign="top"> 
			<tr> 
				<td width="15%">No. Order</td> 
				<td>:</td>
				<td>
					<input type="hidden" name="kirim_id" id="kirim_id"/>
					<input type="hidden" name="jual_id" id="jual_id"/>
					<input type="text" name="jual_no" readonly="true"/>
				</td>
				<td>&nbsp;</td>
				<td>Scan Slip</td>
				<td>:</td>
				<td>
					<input type="button" id="getscan" value="Pilih" class="ui-corner-all ui-widget-header"> 
					<input readonly="true" name="scan_resi" type="text" id="gambar_scan" size="11"/>
					<input type="hidden" id="gambar_awal" name="scan_resi_awal">
				</td> 				
			</tr> 
			<tr> 
				<td width="15%">Tgl. Order</td> 
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="jual_tgl"/>
				</td>
				<td>&nbsp;</td>
				<td>No. Resi</td>
				<td>:</td>
				<td>
					<input type="text" name="no_resi"/>
				</td> 				
			</tr>
			<tr> 
				<td width="15%">Jenis Paket</td> 
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="paket_nama"/>
				</td>
				<td>&nbsp;</td>
				<td>Wilayah</td>
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="tiki_wilayah"/>
				</td> 				
			</tr>
			<tr> 
				<td width="15%" rowspan=2>Alamat</td> 
				<td rowspan=2>:</td>
				<td rowspan=2>
					<textarea readonly="true" name="alamat"></textarea>
				</td>
				<td>&nbsp;</td>
				<td>Biaya</td>
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="tarif_hrg"/>
				</td> 				
			</tr>
			<tr> 
				<td>&nbsp;</td>
				<td>Cas</td>
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="tarif_cas"/>
				</td> 				
			</tr>
			<tr> 
				<td width="15%">Status</td> 
				<td>:</td>
				<td>
					<select name="status">
						<option value=2>Belum Dibayar</option>
						<option value=3>Sudah Dikirim</option>
						<option value=4>Batal</option>
					</select>
				</td>
				<td>&nbsp;</td>
				<td>Total Harga</td>
				<td>:</td>
				<td>
					<input readonly="true" type="text" name="total_biaya"/>
				</td> 				
			</tr>
			<tr><td colspan=7><hr></td></tr>
			<tr> 
				<td><input type="button" id="savedata" value="Simpan" onclick="ubah_order()"/></td>
			</tr> 
		</tbody> 
	</table> 
</fieldset> 
</form>