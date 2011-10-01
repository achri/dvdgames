<?php
	if (isset($extraSubHeaderContent)) {
		echo $extraSubHeaderContent;
	}
?>
<script type="text/javascript">
var $tab = $('#tabs'), tab_pos = $tab.tabs('option', 'selected');
masking('.number');
$('.number').blur();
$tab.tabs('disable',0);

/*
if ($('li',$tab).length == 4)
	$tab.tabs('add' ,'index.php/<?php echo $link_controller_kategori;?>/','LINK KATEGORI',2);
*/

function tambah_kategori() {
	$dlg_content.load('index.php/<?php echo $link_controller_kategori;?>/index')
	.dialog('option','buttons',{
		"OK" : function() {
		
		}
	}).dialog('open');
	return false;
}

function get_kat_nama(kat_id) {
	
	$.getJSON('index.php/<?php echo $link_controller?>/tree_kat_detail/'+kat_id,function(data){
		if (data.kat_nama) {
			$('#kat_id').val(kat_id);
			$('#kat_kode').val(data.kat_kode);
			$('#k