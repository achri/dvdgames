<script type="text/javascript">
var dvdgrid_content = jQuery("#newapi_dvd"),
	lastsel,jual_id;

$(document).ready(function() {

	//dtgLoadButton();

	var dvdgrid = dvdgrid_content.jqGrid({   
		url: "<?php echo site_url($link_controller)?>/get_data",
		editurl: "<?php echo site_url($link_controller)?>/set_data",
		colModel: [ 
			{name:"ldvd_id",key:true,label:"Kode",width:20,align:"center"},
			{name:"lkat_id",hidden:true},
			{name:"lkat_nama",label:"Kategori",width:100,align:"left"},
			{name:"ldvd_nama",label:"DVD",width:200,align:"left"},
			{name:"ldvd_jumlah",label:"Jumlah",width:50,align:"center"},
			//{name:"opsi",label:" ",width:20,sortable:false,align:"center"},
		],
		gridComplete: function(){ 
			//dvdgrid_content.jqGrid('groupingRemove',true);
			/*var id = dvdgrid_content.jqGrid('getDataIDs'); 
			for(var i=0;i < id.length;i++){ 
				var cl = id[i],
					dvd_id = dvdgrid_content.getRowData(cl).dvd_id;
				de = "<a alt='Delete' style='cursor:pointer' onclick='hapus_order("+dvd_id+");' class='ui-icon ui-icon-trash'></a>"; 
				dvdgrid_content.jqGrid('setRowData',cl,{opsi:de});
			}
			jual_id = dvdgrid_content.getRowData(cl).jual_id;
			//$('.ui-jqgrid .ui-jqgrid-hdiv > *').addClass('tfont');*/
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		pager: "#pnewapi_dvd", 
		sortname: 'ldvd_nama', 
		sortorder: "ASC",
		//viewrecords: true,
		loadError :function(xhr,status, err){ 
			try {
				jQuery.jgrid.info_dialog(jQuery.jgrid.errors.errcap,'<div class="ui-state-error">'+ xhr.responseText +'</div>', jQuery.jgrid.edit.bClose,{buttonalign:'right'});
			} 
			catch(e) { alert(xhr.responseText);} 
		},
		onSelectRow: function(id) {
		
			return;
		},
		ondblClickRow: function(id){
			
			return;
		},
		datatype:'json',
		rowNum:1500,
		rowList:[5,10,15,20,30,50,100,500,1000,1500,2000],
		rownumbers:true,
		hiddengrid:false,
		//autowidth:true,
		width:680,
		forceFit:true,
		shrinkToFit:true,
		height:'205',
		caption:'DAFTAR DVD',
		scroll: true,
		grouping:true, 
		groupingView : 
		{ 
			groupField : ['lkat_nama'], 
			groupColumnShow : [true], 
			groupText : ['<b>{0} ({1})</b>'], 
			groupCollapse : true, 
			groupOrder: ['asc'],
			groupDataSorted : true
		},
	}); 
	
	dvdgrid_content.jqGrid('navGrid',"#pnewapi_dvd",{edit:false,add:false,del:false,refresh:false,search:false});
	/*
	dvdgrid_content.jqGrid('navButtonAdd',"#pnewapi_dvd",
		{
			caption:"Kategori",
			title:"Grup berdasarkan kategori", 
			buttonicon :'ui-icon-pin-s', 
			onClickButton:function(){ 
				dvdgrid_content.jqGrid('groupingGroupBy','lkat_nama');
			}
		}
	);
	*/
	dvdgrid_content.jqGrid('navButtonAdd',"#pnewapi_dvd",
		{
			caption:"Cari",
			title:"Mode pencarian", 
			buttonicon :'ui-icon-pin-s', 
			onClickButton:function(){ 
				dvdgrid_content[0].toggleToolbar() 
			}
		}
	);
	
	dvdgrid_content.jqGrid('navButtonAdd',"#pnewapi_dvd",
		{
			caption:"Bersihkan",
			title:"Kembalikan data awal",
			buttonicon :'ui-icon-refresh', 
			onClickButton:function(){ 
				dvdgrid_content[0].clearToolbar() 
			} 
		}
	);
	
	dvdgrid_content.jqGrid('filterToolbar',
		{stringResult: true,searchOnEnter : false}
	)[0].toggleToolbar();
	
});
</script>
<div style="padding: 0 10px 0 10px">
	<table id="newapi_dvd" class="scroll"></table>
	<div id="pnewapi_dvd" class="scroll" style="text-align:center;"></div>
</div>
