<script type="text/javascript">
$(document).ready(function() {	
	var tarif_kode,kat_del;
	//dtgLoadButton();
	
	var tarifgrid = tarifgrid_content.jqGrid({
		ajaxGridOptions : {
			type:"POST"
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		ondblClickRow: function(id){
			var gridwidth = tarifgrid_content.width();
			
			gridwidth = gridwidth / 2;
			tarifgrid.editGridRow(id, {
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
		loadComplete: function() {
			
		},
		onSelectRow: function(tarif_id){
			
			
		},
		gridComplete: function(){ 
			var ids = tarifgrid_content.jqGrid('getDataIDs'); 
			for(var i=0;i < ids.length;i++){ 
				var cl = ids[i],
					paket_nama = tarifgrid_content.getRowData(cl).paket_nama,
					tiki_dari = tarifgrid_content.getRowData(cl).tiki_dari,
					tiki_tujuan = tarifgrid_content.getRowData(cl).tiki_tujuan;
				tarifgrid_content.jqGrid('setRowData',ids[i],{paket_id:paket_nama,dari:tiki_dari,tujuan:tiki_tujuan});
			} 
		},
		url:'admin.php/<?php echo $link_controller?>/get_data/false/tarif/',
		editurl:'admin.php/<?php echo $link_controller?>/set_data/tarif/',
		datatype:'json',
		rowNum:'5',
		rowList:[5,10,20,50],
		rownumbers:true,
		hiddengrid:false,
		scroll:false,
		viewsortcols:true,
		sortable:true,
		forceFit:true,
		viewrecords: true,
		autowidth: true,
		width:400,
		height:150,
		pager: '#pnewapi_tarif',
		caption:'Daftar Tarif',
		colModel:[
			{name:'tarif_id',search:false,hidden:true,key:true},
			{name:'dari',label:'Dari',width:200,sortable:true,editable:true,edittype:'select',editoptions:{value:"<?php echo $data_tiki;?>",size:10},editrules:{edithidden:true,required:true,integer:true}},
			{name:'tiki_dari',search:false,hidden:true},
			{name:'tujuan',label:'Tujuan',width:200,sortable:true,editable:true,edittype:'select',editoptions:{value:"<?php echo $data_tiki;?>",size:10},editrules:{edithidden:true,required:true,integer:true}},
			{name:'tiki_tujuan',search:false,hidden:true},
			{name:'paket_id',label:'Jenis',width:200,sortable:true,editable:true,edittype:'select',editoptions:{value:"<?php echo $data_paket;?>",size:10},editrules:{edithidden:true,required:true,integer:true}},
			{name:'paket_nama',search:false,hidden:true},
			{name:'tarif_hrg',label:'Tarif',width:200,align:'right',editable:true,sortable:true,edittype:'text',editrules:{required:true},formatter:'currency',formatoptions:{prefix:"Rp. ",thousandsSeparator:","}},
			{name:'tarif_cas',label:'Cas',width:200,align:'right',editable:true,sortable:true,edittype:'text',editrules:{required:true},formatter:'currency',formatoptions:{prefix:"Rp. ",thousandsSeparator:","}},
		],
		grouping:true, 
		groupingView : { 
			groupField : ['tiki_dari'],
			groupText : ['<i>{0} - {1} data</i>'],
			groupColumnShow : [false],
			groupCollapse : true, 
			//groupDataSorted : true, error with sort
			//groupOrder: ['ASC'],
			//groupSummary : [true], 
			//showSummaryOnHide: true, 
		},
	});
		
	/* NAV BUTTON */
	tarifgrid_content.jqGrid('navGrid',
		'#pnewapi_tarif',
		{view:false,edit:true, add:true,del:true,search:true,refresh:true},
		{closeAfterEdit:true,mtype: 'POST'}/*edit options*/,
		{closeAfterAdd:true,mtype: 'POST',
			onclickSubmit: function(eparams) {
				var retarr = {}; 
				//retarr = {"tiki_kode":tarif_kode};
				return retarr;
			}
		} /*add options*/,
		{mtype: 'POST',
			beforeSubmit: function(id) {
				//return cek_hapus_tiki('provinsi',id);
			}
		} /*delete options*/,
		{sopt:['eq','ne','lt','le','gt','ge','bw','bn','in','ni','ew','en','cn','nc'],overlay:false,mtype: 'POST'}/*search options*/
	);
	
	jQuery("#newapi_tarif").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false})[0].toggleToolbar();
});
</script>

<table id="newapi_tarif"></table>
<div id="pnewapi_tarif"></div>
