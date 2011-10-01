<script type="text/javascript">
$(document).ready(function() {
	var kota_kode,kota_del;
	//dtgLoadButton();
	
	var kotagrid = kotagrid_content.jqGrid({
		ajaxGridOptions : {
			type:"POST"
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		ondblClickRow: function(id){
			var gridwidth = kotagrid_content.width();
			
			gridwidth = gridwidth / 2;
			kotagrid.editGridRow(id, {
				closeAfterEdit:true,
				mtype:'POST'
			});
			return;
		},
		viewrecords: true,
		autowidth: true,
		loadError :function(xhr,status, err){ 
			try {
				jQuery.jgrid.info_dialog(jQuery.jgrid.errors.errcap,'<div class="ui-state-error">'+ xhr.responseText +'</div>', jQuery.jgrid.edit.bClose,{buttonalign:'right'});
			} 
			catch(e) { alert(xhr.responseText);} 
		},
		postData: {'tiki_level': 2},
		loadComplete: function() {
			var tiki_id = provgrid_content.getGridParam('selrow'); 
			if (tiki_id > 0) {
				$.ajax({
					url: 'admin.php/<?php echo $link_controller;?>/set_tiki_kode/kota/'+tiki_id,
					type: 'POST',
					success: function(data) {
						if (data) { 
							kota_kode = data;
						}
					}
				});
			}
		},
		onSelectRow: function(kota_id){
			
		}
		<?php echo $kota_grid['table'];?>
	});
	
	/* NAV BUTTON */
	kotagrid_content.jqGrid('navGrid',
		'#pnewapi<?php echo $kota_grid['gridname'];?>',
		{view:false,edit:false, add:true,del:true,search:false,refresh:false},
		{closeAfterEdit:true,mtype: 'POST'}/*edit options*/,
		{closeAfterAdd:true,mtype: 'POST',
			beforeSubmit: function(id) {
				var retarr = {}; 
				var prov_id = provgrid_content.getGridParam('selrow'); 
				if(!prov_id > 0) { 
					return [false,"Pilih provinsi terlebih dahulu !!!"];
				}else {
					return [true];
				}
			},
			onclickSubmit: function(eparams) {
				var retarr = {}; 
				var prov_id = provgrid_content.getGridParam('selrow'); 
				if(prov_id > 0) { 
					retarr = {"tiki_kode":kota_kode,"tiki_master":prov_id,"tiki_level":2};
				}
				return retarr;
			}
		} /*add options*/,
		{mtype: 'POST',
			beforeSubmit: function(id) {
				return cek_hapus_tiki('kota',id);
			}
		} /*delete options*/,
		{sopt:['eq','cn','ge','le'],overlay:false,mtype: 'POST'}/*search options*/
	);
	<?php echo $kota_grid['showsearch'];?>;
});

</script>

<table id="newapi<?php echo $kota_grid['gridname'];?>"></table>
<div id="pnewapi<?php echo $kota_grid['gridname'];?>"></div>
