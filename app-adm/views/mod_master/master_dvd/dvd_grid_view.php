<script language="javascript">
$(document).ready(function() {
	
	//dtgLoadButton();
	
	var dvd_grid = dvd_grid_content.jqGrid({
		ajaxGridOptions : {
			type:"POST"
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		ondblClickRow: function(id){
			var gridwidth = dvd_grid_content.width();
			
			gridwidth = gridwidth / 2;
			dvd_grid.editGridRow(id, {
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
		onSelectRow: function(kat_id){
			var gsr = dvd_grid_content.jqGrid('getGridParam','selrow'); 
			dvd_grid_content.jqGrid('GridToForm',gsr,"#dvd_form"); 
			dvd_selrow_dvd();
		},
		/*
		gridComplete: function(){ 
			var ids = grid_content.jqGrid('getDataIDs'); 
			for(var i=0;i < ids.length;i++){ 
				var cl = ids[i]; 
				be = "<a alt='Edit' style='cursor:pointer' onclick=\"tabs_edit('"+cl+"');\" class='ui-icon ui-icon-document'></a>"; 
				de = "<a alt='Hapus' style='cursor:pointer' onclick=\"hapus_produk('"+cl+"');\" class='ui-icon ui-icon-trash'></a>";//<img border='0' src='<?php echo base_url()?>asset/images/icons/trash.png'></a>"; 
				//ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#rowed2').restoreRow('"+cl+"');\" />"; 
				grid_content.jqGrid('setRowData',ids[i],{act:be+de}); 
			} 
		},
		*/
		caption: 'Daftar DVD',
		url : 'admin.php/<?php echo $link_controller;?>/get_data',
		datatype : "JSON",
		//colNames:['Kode','Nama', 'Jumlah'], 
		colModel:[ 
			{name:'dvd_id',label:'dvd_id', hidden:true, key:true}, 
			{name:'kat_id',label:'kat_id', hidden:true, width:90},
			{name:'dvd_kode',label:'Kode', width:90, align:'center', sortable: true}, 
			{name:'kat_nama',label:'Kategori', width:90, sortable: true},
			{name:'dvd_nama',label:'Nama', width:200, sortable: true}, 
			{name:'dvd_jumlah',label:'Jumlah', width:80, align:"right", sortable: true}, 
			{name:'dvd_review',label:'Review'}, 
			{name:'dvd_release',label:'Release', hidden:true, formatter:'date', formatoptions:{srcformat:"Y-m-d",newformat:"d-M-Y"}}, 
			{name:'dvd_serial',label:'Serial'},
			{name:'dvd_cheat',label:'Cheat', hidden:true},
			{name:'dvd_link',label:'Link', hidden:true},
			{name:'dvd_rating',label:'Rating', hidden:true},
			{name:'dvd_gambar',label:'Cover', hidden:true},
			{name:'keterangan',label:'Keterangan', hidden:true}
		], 
		sortname : 'dvd_kode',
		sortorder : 'ASC',
		rowNum:5, 
		rowList:[5,10,20,30],
		height: 'auto',
		rownumbers: true,
		//viewsortcols : true,
		sortable : true,
		viewrecords: true,
		autowidth: true,
		forceFit: true,
		shrinkToFit: true,
		pager: dvd_grid_paging,
		
	});
	
	dvd_grid_content.jqGrid('navGrid','#'+dvd_grid_paging,
		{view:false,edit:false,add:false,del:false,add:false,refresh:false,search:false},
		{closeAfterEdit:true,reloadAfterSubmit:true,mtype: 'POST'}/*edit options*/,
		{closeAfterAdd:true,reloadAfterSubmit:true,mtype: 'POST'} /*add options*/,
		{reloadAfterSubmit:true,mtype: 'POST'} /*delete options*/,
		{sopt:['eq','cn','ge','le'],
		overlay:false,mtype: 'POST'}/*search options*/
	);
	
	dvd_grid_content.jqGrid('navButtonAdd',
		'#'+dvd_grid_paging,
		{
			caption:"Search",
			title:"Toggle Search Toolbar",
			buttonicon :'ui-icon-pin-s', 
			onClickButton:function(){ 
				dvd_grid_content[0].toggleToolbar() 
			} 
		}
	);
			
	dvd_grid_content
		.jqGrid('navButtonAdd',
		'#'+dvd_grid_paging,
		{
			caption:"Clear",
			title:"Clear Search",
			buttonicon :'ui-icon-refresh', 
			onClickButton:function(){ 
				dvd_grid_content[0].clearToolbar() 
			} 
		}
	); 
	dvd_grid_content.jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false})[0].toggleToolbar();
});
</script>

<table id="newapi_<?php echo $class_name?>"></table>
<div id="pnewapi_<?php echo $class_name?>"></div>