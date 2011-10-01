<?php
if (isset($daftar_cs)):
	if ($daftar_cs->num_rows() > 0):
	$no = 1;
	foreach ($daftar_cs->result() as $row):
		$SQL = "
			select *
			from custserv_res as cr
			where cr.csc_id = ".$row->csc_id."
		";
					
		$respon = $this->db->query($SQL);
?>
	<li>
		<div class="ui-widget-content" style="border-right: 1px dotted;border-bottom: 1px dotted; border-top:none; border-left:none">
		<table width="100%">
		<tbody valign="top">
		<tr>
			<td width="5px" align="right" rowspan=6 style="border-right: 1px dotted"><?php echo $no?>.</td>
			<td width="80px">Tanggal</td><td width="5px">:</td><td><?php echo date_format(date_create($row->csc_tgl),'d-M-Y H:i:s')?></td>
			<td width="100px" align="center" valign="middle" rowspan=6 style="border-left: 1px dotted">
			<?php 
				if ($row->csc_lampiran != '')
					echo $this->lib_pictures->thumbs_ajax($row->csc_lampiran,100,100,'uploaded/cs/');
				else
					echo "Screen Shot";
			?>
			</td>
			<?php if ($respon->num_rows() <= 0):?>
			<td width="5px" valign="bottom" rowspan=6 style="border-left: 1px dotted">
			<a href="#" class="ui-icon ui-icon-pencil" onclick="edit_cs(<?php echo $row->csc_id?>);"></a>
			<a href="#" class="ui-icon ui-icon-trash" onclick="hapus_cs(<?php echo $row->csc_id?>);"></a>
			</td>
			<?php endif;?>
		</tr>
		<tr>
			<td>DVD</td><td>:</td><td><?php echo $row->dvd_nama?></td>
		</tr>
		<tr>
			<td>Keluhan</td><td>:</td><td><?php echo $row->csc_complain?></td>
		</tr>
		<tr>
			<td colspan=3>
				<table width="100%">
				<tbody valign="top">
				<?php 
					if ($respon->num_rows() > 0):
					foreach ($respon->result() as $rrow):
				?>
				<tr>
					<td colspan=3><hr/></td>
				</tr>
				<tr>
					<td width="80px">Tanggal</td><td width="5px">:</td><td><?php echo date_format(date_create($rrow->csr_tgl),'d-M-Y H:i:s')?></td>
				</tr>
				<tr>
					<td>Respon</td><td>:</td><td><?php echo $rrow->csr_result?></td>
				</tr>
				<?php
					endforeach;
					endif;
				?>
				</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan=3>&nbsp;</td>
		</tr>
		<tr>
			<td colspan=3>&nbsp;</td>
		</tr>
		</tbody>
		</table>
		</div>
	</li>
<?php
	$no++;
	endforeach;
	endif;
endif;
?>