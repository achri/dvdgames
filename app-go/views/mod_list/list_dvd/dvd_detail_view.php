<?php
if (isset($det_dvd)):
	$rdvd = $det_dvd->row();
?>

<div style="width:650px;">
	<div style="float:left;height:100%;width:50%" class="mfont ui-widget-content ui-corner-all">
		<table width="100%" height="100%" cellpadding=5 cellspacing=5>
		<tr>
			<td align="center" valign="middle">
				<?php echo $this->lib_pictures->thumbs_ajax($rdvd->dvd_gambar,300);?>
			</td>
		</tr>
		</table>
	</div>

	<div style="float:right;height:100%;width:47%" class="ui-widget-content ui-corner-all">
		<table width="100%" cellpadding=10 cellspacing=10 class="tfont ui-widget-content ui-corner-all" style="border:0">
		<tbody align="left" valign="top">
		<tr>
			<td width="65px">Jml. Disc</td>
			<td width="5px">:</td>
			<td ><?php echo $rdvd->dvd_jumlah?> DVD</td>
		</tr>
		<tr>
			<td>Kategori</td>
			<td>:</td>
			<td><?php echo $rdvd->kat_nama?></td>
		</tr>
		<tr>
			<td>Release</td>
			<td>:</td>
			<td><?php echo ($rdvd->dvd_release!='0000-00-00')?(date_format(date_create($rdvd->dvd_release),'d-m-Y')):('-');?></td>
		</tr>
		<!--tr>
			<td>Serial</td>
			<td>:</td>
			<td><//?php echo ($rdvd->dvd_serial)?($rdvd->dvd_serial):('-')?></td>
		</tr-->
		<tr>
			<td>Cheat</td>
			<td>:</td>
			<td><?php echo ($rdvd->dvd_cheat)?($rdvd->dvd_cheat):('-')?></td>
		</tr>
		<tr>
			<td>Link</td>
			<td>:</td>
			<td><?php echo ($rdvd->dvd_link)?($rdvd->dvd_link):('-')?></td>
		</tr>
		</tr>
			<td>Rating</td>
			<td>:</td>
			<td>
				<img src="<?php echo "asset/images/stars/".$rdvd->dvd_rating.".png";?>" width="100"/>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div style="clear:both;"></div>
	<div style="margin-top:10px" width="100%" align="left">
		<fieldset style="padding: 5px" class="ui-widget-content ui-corner-all">
		<legend>Review</legend>
		<?php echo ($rdvd->dvd_review)?($rdvd->dvd_review):('-')?>
		</fieldset>
	</div>
</div>
<?php
endif;
?>