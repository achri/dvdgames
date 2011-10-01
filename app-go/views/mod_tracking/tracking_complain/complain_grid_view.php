<script type="text/javascript">
$(document).ready(function() {

	//dtgLoadButton();

	var grid = grid_content.jqGrid({   
		url: "<?php echo site_url($link_controller);?>/get_data",
		colModel: [ 
			{name:"csc_id",key:true,hidden:true},
			{name:"csc_tgl",label:"Tgl",width:70,align:"center",formatter:'date',formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"d-M-Y H:i:s"},searchoptions:{dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd'});} }},
			{name:"dvd_nama",label:"DVD"},
			{name:"csc_complain",label:"Keluhan"},
			{name:"csc_status",label:"Status"},
		],
		gridComplete: function(){ 
			/*var id = grid_content.jqGrid('getDataIDs'); 
			for(var i=0;i < id.length;i++){ 
				var cl = id[i],
					status = grid_content.getRowData(cl).status,
					arr_stat = new Array('Belum Diproses','Data Tiki','Belum Dibayar','Sudah Dikirim','Batal','Hubungi Kami');
				grid_content.jqGrid('setRowData',cl,{status:arr_stat[status]});
			}*/
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		postData:{'kirim_id':'<?php echo $kirim_id?>'},
		pager: "#pnewapi_cs", 
		//sortname: 'jual_tgl', 
		//sortorder: "DESC",
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
		rowNum:10,
		rowList:[10],
		rownumbers:true,
		hiddengrid:false,
		autowidth:true,
		forceFit:true,
		shrinkToFit:true,
		height:'100%',
		caption:'DATA KELUHAN',
		scroll: true,	
	}); 
	
	grid_content.jqGrid('navGrid',"#pnewapi_cs",{view:true,edit:false, add:false,del:false,search:false,refresh:false},
				{closeAfterEdit:true,mtype: 'POST'}/*edit options*/,
				{closeAfterAdd:true,mtype: 'POST'} /*add options*/,
				{mtype: 'POST',} /*delete options*/,
				{sopt:['eq','cn','ge','le'],overlay:false,mtype: 'POST'}/*search options*/
			);
	
	grid_content.jqGrid('navButtonAdd',"#pnewapi_cs",
		{
			caption:"Cari",
			title:"Mode pencarian", 
			buttonicon :'ui-icon-pin-s', 
			onClickButton:function(){ 
				grid_content[0].toggleToolbar() 
			}
		}
	);
	
	grid_content.jqGrid('navButtonAdd',"#pnewapi_cs",
		{
			caption:"Bersihkan",
			title:"Kembalikan data awal",
			buttonicon :'ui-icon-refresh', 
			onClickButton:function(){ 
				grid_content[0].clearToolbar() 
			} 
		}
	);
	
	grid_content.jqGrid('filterToolbar',
		{stringResult: true,searchOnEnter : false}
	)[0].toggleToolbar();
	
});
</script>
<table id="newapi_cs" class="scroll"></table>
<div id="pnewapi_cs" class="scroll" style="text-align:center;"></div>
