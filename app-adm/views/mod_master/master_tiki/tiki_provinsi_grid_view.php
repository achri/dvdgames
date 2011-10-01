<script type="text/javascript">
$(document).ready(function() {	
	var prov_kode,kat_del;
	//dtgLoadButton();
	
	var provgrid = provgrid_content.jqGrid({
		ajaxGridOptions : {
			type:"POST"
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		ondblClickRow: function(id){
			var gridwidth = provgrid_content.width();
			
			gridwidth = gridwidth / 2;
			provgrid.editGridRow(id, {
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
		postData: {'tiki_level': 1},
		loadComplete: function() {
			$.ajax({
				url: 'admin.php/<?php echo $link_controller;?>/set_tiki_kode/provinsi/0',
				type: 'POST',
				success: function(data) {
					if (data) { 
						prov_kode = data;
					}
				}
			});
		},
		onSelectRow: function(prov_id){
			
		},
		subGrid: true,
		subGridRowExpanded: function(subgrid_id, row_id) { 
			var subgrid_table_id, 
				pager_id, 
				subgrid_table_id = subgrid_id+"_t", 
				pager_id = "p_"+subgrid_table_id,
				tiki_id = row_id,
				kota_kode,
				prov_nama = provgrid_content.getRowData(tiki_id).tiki_wilayah;
				
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>"); 
			
			jQuery("#"+subgrid_table_id).jqGrid({
				ajaxGridOptions : {
					type:"POST"
				},
				jsonReader : {
					root:"data",
					repeatitems: false
				},
				ondblClickRow: function(id){
					var gridwidth = jQuery("#"+subgrid_table_id).width();
					
					gridwidth = gridwidth / 2;
					jQuery("#"+subgrid_table_id).editGridRow(id, {
						closeAfterEdit:true,
						mtype:'POST'
					});
					return;
				},
				loadError :function(xhr,status, err){ 
					try {
						jQuery.jgrid.info_dialog(jQuery.jgrid.errors.errcap,'<div class="ui-state-error">'+ xhr.responseText +'</div>', jQuery.jgrid.edit.bClose,{buttonalign:'right'});
					} 
					catch(e) { alert(xhr.responseText);} 
				},
				postData: {'tiki_level': 2, 'tiki_master': tiki_id},
				loadComplete: function() {
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
					
				},
				url:'admin.php/<?php echo $link_controller;?>/get_data/'+tiki_id,
				editurl:'admin.php/<?php echo $link_controller;?>/set_data/',
				datatype:'json',
				rowNum:5,
				rowList:[5,10,20,50],
				rownumbers:true,
				hiddengrid:false,
				scroll:false,
				viewrecords: true,
				autowidth: true,
				viewsortcols:true,
				sortable:true,
				//forceFit:'true',
				//width:'400',
				height:'auto',
				pager: pager_id,
				//caption:"Daftar Kota dari ( "+prov_nama+" )",
				colModel:[
					{name:'tiki_id',label:'id' ,search:false,hidden:true,key:true  },
					{name:'tiki_kode',label:'Kode' ,width:50,sortable:true  },
					{name:'tiki_wilayah',label:'Kota' ,width:300,editable:true,sortable:true,edittype:'text',editrules:{required:true}  },
				]	
			});
					
			/* NAV BUTTON */
			jQuery("#"+subgrid_table_id).jqGrid('navGrid',
				'#'+pager_id,
				{view:false,edit:true, add:true,del:true,search:true,refresh:true},
				{closeAfterEdit:true,mtype: 'POST'}/*edit options*/,
				{closeAfterAdd:true,mtype: 'POST',
					beforeSubmit: function(id) {
						return [true];
					},
					onclickSubmit: function(eparams) {
						var retarr = {}; 
						retarr = {"tiki_kode":kota_kode,"tiki_master":tiki_id,"tiki_level":2};
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
			
			/*	
			jQuery("#"+subgrid_table_id)
				.jqGrid('navButtonAdd',
					'#'+pager_id,
					{
						//caption:"Search",
						//title:"Toggle Search Toolbar",
						buttonicon :'ui-icon-pin-s', 
						onClickButton:function(){ 
							jQuery("#"+subgrid_table_id)[0].toggleToolbar() 
						} 
					}
				);
			jQuery("#"+subgrid_table_id)
				.jqGrid('navButtonAdd',
					'#'+pager_id,
					{
						//caption:"Clear",
						//title:"Clear Search",
						buttonicon :'ui-icon-refresh', 
						onClickButton:function(){ 
							jQuery("#"+subgrid_table_id)[0].clearToolbar() 
						} 
					}
				); 
			jQuery("#"+subgrid_table_id).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : true})[0].toggleToolbar();
			*/
			
			//jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{edit:false,add:false,del:false}) 
			
		}, 
		subGridRowColapsed: function(subgrid_id, row_id) { 
			var subgrid_table_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			jQuery("#"+subgrid_table_id).remove(); 
		}	
		<?php echo $prov_grid['table'];?>
	});
	
	/* NAV BUTTON */
	provgrid_content.jqGrid('navGrid',
		'#pnewapi<?php echo $prov_grid['gridname'];?>',
		{view:false,edit:true, add:true,del:true,search:true,refresh:true},
		{closeAfterEdit:true,mtype: 'POST'}/*edit options*/,
		{closeAfterAdd:true,mtype: 'POST',
			onclickSubmit: function(eparams) {
				var retarr = {}; 
				retarr = {"tiki_kode":prov_kode};
				return retarr;
			}
		} /*add options*/,
		{mtype: 'POST',
			beforeSubmit: function(id) {
				return cek_hapus_tiki('provinsi',id);
			}
		} /*delete options*/,
		{sopt:['eq','cn','ge','le'],overlay:false,mtype: 'POST'}/*search options*/
	);
	<?php //echo $prov_grid['showsearch'];?>;
});
</script>

<table id="newapi<?php echo $prov_grid['gridname'];?>"></table>
<div id="pnewapi<?php echo $prov_grid['gridname'];?>"></div>
