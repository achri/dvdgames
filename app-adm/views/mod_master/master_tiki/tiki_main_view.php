<?php 
if (isset($extraSubHeaderContent)) 
	echo $extraSubHeaderContent;	
?>

<script language="javascript">
	var provgrid_content = jQuery("#newapi<?php echo $prov_grid['gridname'];?>"),
		tarifgrid_content = jQuery("#newapi_tarif"),
		delret;
		
	function cek_hapus_tiki(tipe,id) {
		var stipe;
		switch (tipe){
			case 'provinsi' : stipe = "Nama Provinsi ini sedang dipakai !!!"; break;
			case 'kota' : stipe = "Nama Kota ini sedang dipakai !!!"; break;
		}
		
		$.post('index.php/<?php echo $link_controller;?>/cek_hapus_tiki/'+tipe+'/'+id,function(data) {
			if (!data) {
				delret = [true];
			} else {
				delret = [false,stipe];;
			}
		});
		
		return delret;
	}
</script>

<div align="center">
	<table width="100%" border=0>
	<tr align="center">
		<td width="48%">
		<?php $this->load->view($link_view.'/tiki_provinsi_grid_view')?>
		</td>
		<td width="48%">
		<?php $this->load->view($link_view.'/tiki_tarif_grid_view')?>
		</td>
	</tr>
	</table>
</div>