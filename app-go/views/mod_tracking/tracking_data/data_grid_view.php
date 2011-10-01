<script type="text/javascript">
var grid_content = jQuery("#newapi_track"),
	lastsel,jual_id;
	
function cs_pengiriman(kirim_id,jual_no) {
	alert(kirim_id,jual_no);
	return false;
}

function komplain(kirim_id,jual_no) {
	$('#tab-tracking').tabs('select',0);
	$('#tab-tracking').tabs('remove',1);
	$('#tab-tracking').tabs('add','<?php echo site_url($link_controller_cs)?>/daftar_complain/'+kirim_id ,'Form Keluhan ('+jual_no+')',1);	
	$('#tab-tracking').tabs('select',1);
	$('#tab-tracking').tabs('disable',0);
	return false;
}

$(document).ready(function() {

	//dtgLoadButton();

	var grid = grid_content.jqGrid({   
		url: "<?php echo site_url($link_controller);?>/get_data",
		colModel: [ 
			{name:"jual_id",key:true,hidden:true},
			{name:"kirim_id",hidden:true},
			{name:"keluhan",hidden:true},
			{name:"no_resi",label:"No Resi",width:80,align:"center",hidden:true},
			{name:"jual_no",hidden:true},
			{name:"jual_tgl",label:"Order Tgl",width:70,align:"center",formatter:'date',formatoptions:{srcformat:"Y-m-d H:i:s",newformat:"d-M-Y H:i:s"},searchoptions:{dataInit:function(el){$(el).datepicker({dateFormat:'yy-mm-dd'});} }},
			{name:"tot_dvd",label:"Jml.Dvd",width:30,align:"center"},
			{name:"bonus",label:"Bonus",width:30,align:"center"},
			{name:"tot_jumlah",label:"Jml.Item",width:30,align:"center"},
			{name:"sub_total",label:"Sub.Total",width:50,align:"right",formatter:'currency', formatoptions:{prefix:"Rp. ",thousandsSeparator:","}},	
			{name:"sub_bonus",label:"Sub.Bonus",width:50,align:"right",formatter:'currency', formatoptions:{prefix:"Rp. ",thousandsSeparator:","}},	
			{name:"total_biaya",label:"Total",width:50,align:"right",formatter:'currency', formatoptions:{prefix:"Rp. ",thousandsSeparator:","}},	
			{name:"status",label:"Status",width:50,align:"center",editable:true,edittype:'select',editoptions:{value:"0:Belum Diproses;1:Data Tiki;2:Belum Dibayar;3:Sudah Dikirim;4:Batal;5:Hubungi Kami",size:10},editrules:{edithidden:true,required:true,integer:true}, stype:'select'},
			{name:"act",label:"Keluhan",width:20,align:'center'},
		],
		gridComplete: function(){ 
			var id = grid_content.jqGrid('getDataIDs'); 
			for(var i=0;i < id.length;i++){ 
				var cl = id[i],
					jual_no = grid_content.getRowData(cl).jual_no,
					kirim_id = grid_content.getRowData(cl).kirim_id,
					status = grid_content.getRowData(cl).status,
					keluhan = grid_content.getRowData(cl).keluhan,
					arr_stat = new Array('Belum Diproses','Data Tiki','Belum Dibayar','Sudah Dikirim','Batal','Hubungi Kami'),
					komplain = "<a href=\"#\" class=\"ui-icon ui-icon-comment\" onclick=\"komplain('"+kirim_id+"','"+jual_no+"')\">"+keluhan+" Keluhan</a>";
				grid_content.jqGrid('setRowData',cl,{status:arr_stat[status],act:komplain});
			}
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		postData:{'email':'a','jual_no':'a','resi_no':'a'},
		pager: "#pnewapi_track", 
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
			return;
		},
		ondblClickRow: function(id){	
			var kirim_id = grid_content.getRowData(id).kirim_id,
				jual_no = grid_content.getRowData(id).jual_no;
			cs_pengiriman(kirim_id,jual_no);
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
		height:'188',
		caption:'DAFTAR PEMESANAN',
		toolbar: [true,"top"],
		subGrid: true,
		scroll: true,
		subGridRowExpanded: function(subgrid_id, row_id) { 
			var subgrid_table_id = subgrid_id+"_t", 
				pager_id = "p_"+subgrid_table_id,
				jual_id = row_id;
			
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"'></table><div id='"+pager_id+"'></div>"); 
			
			jQuery("#"+subgrid_table_id).jqGrid({   
				url: "<?php echo site_url($link_controller);?>/get_data/barang",
				colModel: [ 
					{name:"id",key:true,hidden:true},
					{name:"jual_id",hidden:true},
					{name:"dvd_id",hidden:true},
					{name:"kat_id",hidden:true,editable:true},
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
					
			/* NAV BUTTON */
			jQuery("#"+subgrid_table_id).jqGrid('navGrid',
				'#'+pager_id,
				{view:false,edit:false, add:false,del:false,search:true,refresh:true},
				{closeAfterEdit:true,mtype: 'POST'}/*edit options*/,
				{closeAfterAdd:true,mtype: 'POST'} /*add options*/,
				{mtype: 'POST',} /*delete options*/,
				{sopt:['eq','cn','ge','le'],overlay:false,mtype: 'POST'}/*search options*/
			);
			
			jQuery("#"+subgrid_table_id).jqGrid('navButtonAdd','#'+pager_id,
				{
					caption:"Cari",
					title:"Mode pencarian", 
					buttonicon :'ui-icon-pin-s', 
					onClickButton:function(){ 
						jQuery("#"+subgrid_table_id)[0].toggleToolbar() 
					}
				}
			);
			
			jQuery("#"+subgrid_table_id).jqGrid('navButtonAdd','#'+pager_id,
				{
					caption:"Bersihkan",
					title:"Kembalikan data awal",
					buttonicon :'ui-icon-refresh', 
					onClickButton:function(){ 
						jQuery("#"+subgrid_table_id)[0].clearToolbar() 
					} 
				}
			);
			
			jQuery("#"+subgrid_table_id).jqGrid('filterToolbar',
				{stringResult: true,searchOnEnter : false}
			)[0].toggleToolbar();
			
		}, 
		subGridRowColapsed: function(subgrid_id, row_id) { 
			var subgrid_table_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			jQuery("#"+subgrid_table_id).remove(); 
		}	
	}); 
	
	grid_content.jqGrid('navGrid',"#pnewapi_track",{view:true,edit:false, add:false,del:false,search:false,refresh:false},
				{closeAfterEdit:true,mtype: 'POST'}/*edit options*/,
				{closeAfterAdd:true,mtype: 'POST'} /*add options*/,
				{mtype: 'POST',} /*delete options*/,
				{sopt:['eq','cn','ge','le'],overlay:false,mtype: 'POST'}/*search options*/
			);
	
	//grid_content.jqGrid('gridResize',{minWidth:635,maxWidth:635,minHeight:80, maxHeight:350});
	
	// ADDITIONAL TOOLBAR
	var toolbar = jQuery("div#t_newapi_track"),
		t_content = "<form id='trace_form' onsubmit='return false;' style='float:right'>"+
			"no.Resi : <input id='resi_no' name='resi_no' type='text' size='15' class='required number' title='No Resi'> &nbsp;"+
			"no.Order : <input id='jual_no' name='jual_no' type='text' size='15' class='required number' title='No Order'> &nbsp;"+
			"email : <input id='email' name='email' type='text' size='15' class='required email' title='Email'> &nbsp;"+
			"<input id='cari' type='submit' value='Cari' style='height:20px;font-size:-3;border:0px' class='t_btn ui-state-hover ui-corner-br'/>&nbsp;"+
			"</form>";
	toolbar.append(t_content).addClass('ui-priority-secondary');
	
	// PROCESS
	$(':input',toolbar).eq(3).click(function() {
		var email = $('#email').val(),
			jual_no = $('#jual_no').val();
			resi_no = $('#resi_no').val();
		grid_content.jqGrid('setGridParam',{postData:{'email':email,'jual_no':jual_no,'resi_no':resi_no}}).trigger("reloadGrid");
	});
	
	grid_content.jqGrid('navButtonAdd',"#pnewapi_track",
		{
			caption:"Cari",
			title:"Mode pencarian", 
			buttonicon :'ui-icon-pin-s', 
			onClickButton:function(){ 
				grid_content[0].toggleToolbar() 
			}
		}
	);
	
	grid_content.jqGrid('navButtonAdd',"#pnewapi_track",
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
	
	// HIDE FIELD
	jQuery("#gs_act,#gs_sub_total,#gs_sub_bonus").hide();
});
</script>
<table id="newapi_track" class="scroll"></table>
<div id="pnewapi_track" class="scroll" style="text-align:center;"></div>
<p style="margin-left:12px" class="tfont">- Untuk keluhan transaksi silahkan klik icon komen pada colom keluhan</p>