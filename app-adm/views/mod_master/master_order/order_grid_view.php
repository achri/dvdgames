<script type="text/javascript">
var grid_content = jQuery("#newapi_order"),
	lastsel,jual_id;

$(document).ready(function() {

	//dtgLoadButton();

	var grid = grid_content.jqGrid({   
		url: "admin.php/<?php echo $link_controller;?>/get_data",
		//editurl: "admin.php/<?php echo $link_controller;?>/set_data",
		colModel: [ 
			{name:"kirim_id",key:true,hidden:true},
			{name:"jual_id",editable:true,hidden:true},
			{name:"jual_tgl",label:"Tgl Order",width:55,align:"center",formatter:'date',formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"d-M-Y H:i:s"},searchoptions:{dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd'});}} },
			{name:"jual_no",label:"No Order",width:40,align:"center"},
			{name:"total_biaya",label:"Biaya",width:30,align:"right",formatter:'currency', formatoptions:{prefix:"Rp. ",thousandsSeparator:","}},	
			{name:"no_resi",label:"No Resi",width:50,align:"center"},
			
			{name:"scan_resi",hidden:true},
			{name:"paket_nama",hidden:true},
			{name:"tiki_wilayah",hidden:true},
			{name:"alamat",hidden:true},
			{name:"tarif_hrg",hidden:true},
			{name:"tarif_cas",hidden:true},
			{name:"total_biaya",hidden:true},
			{name:"status",width:25,align:'center',editable:true,edittype:'select',editoptions:{value:"0:Belum Diproses;1:Data Tiki;2:Belum Dibayar;3:Sudah Dikirim;4:Batal;5:Hubungi Kami",size:10},editrules:{edithidden:true,required:true,integer:true}, stype:'select'},
			{name:"scan_resi_awal",hidden:true},
			
		],
		gridComplete: function(){ 
			
			var id = grid_content.jqGrid('getDataIDs'); 
			for(var i=0;i < id.length;i++){ 
				var cl = id[i],
					status = grid_content.getRowData(cl).status,
					scan_resi = grid_content.getRowData(cl).scan_resi,
					arr_stat = new Array('Belum Diproses','Data Tiki','Belum Dibayar','Sudah Dikirim','Batal','Hubungi Kami');
				grid_content.jqGrid('setRowData',cl,{status:arr_stat[status],scan_resi_awal:scan_resi});
			}
			
			return;
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		pager: "#pnewapi_order", 
		sortname: 'jual_tgl', 
		sortorder: "DESC",
		//viewrecords: true,
		loadError :function(xhr,status, err){ 
			try {
				jQuery.jgrid.info_dialog(jQuery.jgrid.errors.errcap,'<div class="ui-state-error">'+ xhr.responseText +'</div>', jQuery.jgrid.edit.bClose,{buttonalign:'right'});
			} 
			catch(e) { alert(xhr.responseText);} 
		},
		onSelectRow: function(id) {
			var gsr = grid_content.jqGrid('getGridParam','selrow'); 
			grid_content.jqGrid('GridToForm',gsr,"#order_form"); 
			show_photo();
			return;
		},
		/*ondblClickRow: function(id){
			var gridwidth = grid_content.width();
			
			gridwidth = gridwidth / 2;
			grid.editGridRow(id, {
				closeAfterEdit:true,
				mtype:'POST'
			});
			return;
		},*/
		datatype:'json',
		rowNum:10,
		rowList:[10,20,30,40],
		rownumbers:true,
		hiddengrid:false,
		autowidth:true,
		forceFit:true,
		shrinkToFit:true,
		height:'75%',
		caption:'DAFTAR PEMESANAN',
		
		subGrid: true,
		subGridRowExpanded: function(subgrid_id, row_id) { 
			var subgrid_table_id = subgrid_id+"_t", 
				pager_id = "p_"+subgrid_table_id,
				jual_id = grid_content.getRowData(row_id).jual_id;
				
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"'></table><div id='"+pager_id+"'></div>"); 
			
			jQuery("#"+subgrid_table_id).jqGrid({   
				url: "admin.php/<?php echo $link_controller;?>/get_data/barang",
				colModel: [ 
					{name:"id",key:true,hidden:true},
					{name:"jual_id",hidden:true},
					{name:"dvd_id",hidden:true},
					{name:"kat_id",hidden:true,editable:true},
					{name:"dvd_kode",label:"Kode",width:100,align:"center"},
					{name:"dvd_nama",label:"DVD",width:200,align:"left"},
					{name:"dvd_jumlah",label:"Jml",width:30,align:"center"},
					{name:"jumlah",label:"Ord",width:30,align:"right",editable:true,editrules:{edithidden:true,number:true,required:true,minValue:1,maxValue:50}}, 
					{name:"harga",label:"Harga",width:100,align:"right",formatter:'currency', formatoptions:{prefix:"Rp. ",thousandsSeparator:","}},	
					
				],
				gridComplete: function(){ 
					
				},
				jsonReader : {
					root:"data",
					repeatitems: false
				},
				postData:{'sjual_id':jual_id},
				pager: pager_id, 
				sortname: 'dvd_nama', 
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
					var $dvd_id = jQuery("#"+subgrid_table_id).getRowData(id).dvd_id,
						$dvd_nama = jQuery("#"+subgrid_table_id).getRowData(id).dvd_nama;
					rev_call($dvd_nama,$dvd_id);
				},
				datatype:'json',
				rowNum:5,
				rowList:[5,10,20],
				rownumbers:true,
				hiddengrid:false,
				autowidth:true,
				forceFit:true,
				shrinkToFit:true,
				height:'auto',
				//caption:'DAFTAR ORDER',
				footerrow : true,
				userDataOnFooter : true,
			}); 
					
			// NAV BUTTON
			jQuery("#"+subgrid_table_id).jqGrid('navGrid',
				'#'+pager_id,
				{view:false,edit:false, add:false,del:false,search:false,refresh:false},
				{closeAfterEdit:true,mtype: 'POST'},
				{closeAfterAdd:true,mtype: 'POST'}, 
				{mtype: 'POST'}, 
				{sopt:['eq','cn','ge','le'],overlay:false,mtype: 'POST'}
			);
			
			jQuery("#"+subgrid_table_id).jqGrid('navButtonAdd',
				'#'+pager_id,
				{
					caption:"Search",
					title:"Toggle Search Toolbar",
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
					caption:"Clear",
					title:"Clear Search",
					buttonicon :'ui-icon-refresh', 
					onClickButton:function(){ 
						jQuery("#"+subgrid_table_id)[0].clearToolbar() 
					} 
				}
			); 
			jQuery("#"+subgrid_table_id).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false})[0].toggleToolbar();
			
		}, 
		subGridRowColapsed: function(subgrid_id, row_id) { 
			var subgrid_table_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			jQuery("#"+subgrid_table_id).remove(); 
		}	
		
	}); 
	
	grid_content.jqGrid('navGrid',"#pnewapi_order",{view:true,edit:false, add:false,del:false,search:false,refresh:false},
		{closeAfterEdit:true,mtype: 'POST'},
		{closeAfterAdd:true,mtype: 'POST'}, 
		{mtype: 'POST'}, 
		{sopt:['eq','cn','ge','le'],overlay:false,mtype: 'POST'}
	);
	
	grid_content.jqGrid('navButtonAdd',
		"#pnewapi_order",
		{
			caption:"Search",
			title:"Toggle Search Toolbar",
			buttonicon :'ui-icon-pin-s', 
			onClickButton:function(){ 
				grid_content[0].toggleToolbar() 
			} 
		}
	);
			
	grid_content
		.jqGrid('navButtonAdd',
		"#pnewapi_order",
		{
			caption:"Clear",
			title:"Clear Search",
			buttonicon :'ui-icon-refresh', 
			onClickButton:function(){ 
				grid_content[0].clearToolbar() 
			} 
		}
	); 
	grid_content.jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false})[0].toggleToolbar();
	
	//grid_content.jqGrid('gridResize',{minWidth:635,maxWidth:635,minHeight:80, maxHeight:350});
	/*
	$("#savedata").click(function(){ 
		var kirim_id= $("#order_form #kirim_id").val(); 
		if(kirim_id) { 
			grid_content.jqGrid('FormToGrid',kirim_id,"#order_form"); 
		} 
	})
	*/;
});
</script>
<table id="newapi_order" class="scroll"></table>
<div id="pnewapi_order" class="scroll" style="text-align:center;"></div>
