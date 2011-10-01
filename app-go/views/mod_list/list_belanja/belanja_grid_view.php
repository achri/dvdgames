<script type="text/javascript">
var grid_content = jQuery("#newapi_cart"),
	lastsel,jual_id;
	
function hapus_order(dvd_id) {		
	var cart_trash_icon = '<a href="#" title="Order" class="ui-icon ui-icon-cart">Add to Cart</a>',
	cart_item, $dlg_btn = {
			"HAPUS": function() {
				/*$('#cart-list li').filter(function(idx) {
					if ($(this).is("#"+dvd_id)){
						cart_item = $(this);
					}
				});
				
				if (cart_item) {*/
					$.ajax({
						url: '<?php echo site_url($link_controller);?>/hapus_penjualan/'+dvd_id,
						type: 'POST',
						success: function(data) {
							if (data) {
								/*
								cart_item.fadeOut(function() {
									cart_item.find('a.ui-icon-refresh').remove();
									cart_item.css('width','86px')
									.append(cart_trash_icon)
									.find('img').css({ 'width':'99%' })
									.end()
									.find('table').css({ 'height': '85px' })
									.end()
									.appendTo('#product-list').fadeIn();
								}).removeClass('ui-state-hover').focus();
								*/
								grid_content.trigger('reloadGrid');
								$dlg_content.dialog('close');
							}
						}
					});
				//}
		
			},
			"BATAL": function() {
				$(this).dialog('close');
			}
		};
	
	konfirmasi("Batal order untuk DVD ini ???",$dlg_btn);	
	return false;
}

$(document).ready(function() {

	//dtgLoadButton();

	var grid = grid_content.jqGrid({   
		url: "<?php echo site_url($link_controller)?>/get_data",
		editurl: "<?php echo site_url($link_controller)?>/set_data",
		colModel: [ 
			{name:"id",key:true,hidden:true},
			{name:"jual_id",hidden:true},
			{name:"dvd_id",hidden:true},
			{name:"kat_id",hidden:true,editable:true},
			{name:"dvd_nama",label:"DVD",width:200,align:"left"},
			{name:"dvd_jumlah",label:"Jml.dvd",width:30,align:"center"},
			{name:"dvd_jumlah",hidden:true,editable:true},
			{name:"jumlah",label:"Ord",width:30,align:"right",editable:true,editrules:{edithidden:true,number:true,required:true,minValue:1,maxValue:50}}, 
			{name:"harga",label:"Harga",width:100,align:"right",formatter:'currency', formatoptions:{prefix:"Rp. ",thousandsSeparator:","}},	
			{name:"opsi",label:" ",width:20,sortable:false,align:"center"},
		],
		gridComplete: function(){ 
			var id = grid_content.jqGrid('getDataIDs'); 
			for(var i=0;i < id.length;i++){ 
				var cl = id[i],
					dvd_id = grid_content.getRowData(cl).dvd_id;
				de = "<a alt='Delete' style='cursor:pointer' onclick='hapus_order("+dvd_id+");' class='ui-icon ui-icon-trash'></a>"; 
				grid_content.jqGrid('setRowData',cl,{opsi:de});
			}
			jual_id = grid_content.getRowData(cl).jual_id;
			//$('.ui-jqgrid .ui-jqgrid-hdiv > *').addClass('tfont');
		},
		jsonReader : {
			root:"data",
			repeatitems: false
		},
		pager: "#pnewapi_cart", 
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
			grid_content.jqGrid('restoreRow',lastsel); 
			grid_content.jqGrid('editRow',id,true,
				function(rowID){
					return;
				},
				function() {
					grid_content.trigger('reloadGrid');
				}
			);
			lastsel = id;
			return;
		},
		ondblClickRow: function(id){
			var $dvd_id = grid_content.getRowData(id).dvd_id,
				$dvd_nama = grid_content.getRowData(id).dvd_nama;
			rev_call($dvd_nama,$dvd_id);
		},
		datatype:'json',
		rowNum:15,
		rowList:[5,10,15,20,30],
		rownumbers:true,
		hiddengrid:false,
		autowidth:true,
		forceFit:true,
		shrinkToFit:true,
		height:'166',
		caption:'DAFTAR ORDER',
		toolbar: [true,"top"],
		footerrow : true,
		userDataOnFooter : true,
	}); 
	
	grid_content.jqGrid('navGrid',"#pnewapi_cart",{edit:false,add:false,del:false});
	
	//grid_content.jqGrid('gridResize',{minWidth:635,maxWidth:635,minHeight:80, maxHeight:350});
	
	// ADDITIONAL TOOLBAR
	var toolbar = jQuery("div#t_newapi_cart"),
		t_content = "<input type='button' id='batal' value='Batal Order' style='height:20px;font-size:-3;border:0px;float:left;' class='t_btn ui-widget-header ui-corner-bl'/>&nbsp;"+
			"<form id='beli_form' style='float:right'>"+
			"email : <input id='email' name='email' type='text' size='15' class='required email' title='Email'> &nbsp;"+
			"<input type='submit' value='Proses Pembayaran' style='height:20px;font-size:-3;border:0px' class='t_btn ui-widget-header ui-corner-br'/>&nbsp;"+
			"</form>";
	toolbar.append(t_content).addClass('ui-priority-secondary');
	
	// PROCESS
	$('#batal',toolbar).eq(0).click(function() {
		//batal_order(jual_id);
	});
	
	// DELETE
	$(':input',toolbar).eq(2).click(function() {
	/*$('form#beli_form').validate({
		rules: {
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			email: {
				required: "We need your email address to contact you",
				email: "Your email address must be in the format of name@domain.com"
			}
		},
		submitHandler: function(form) {*/
			var $dlg_btn = {
				"PROSES": function() {
						$.ajax({
							url: '<?php echo site_url($link_controller)?>/proses_order/'+jual_id,
							type: 'POST',
							data: $('#beli_form').formSerialize(),
							success: function(data) {
								if (data) {
									$dlg_content.dialog('close');
									menu_ajax('list_belanja');
									//grid_content.trigger('reloadGrid');
									load_cart();
								} else {
									informasi("Format email salah !!!");
								}
							}
						});
					
				},
				"BATAL": function() {
					$(this).dialog('close');
				}
			};
			
			if (validasi("#beli_form")) {
				konfirmasi("Order DVD akan di proses ke pembayaran ???",$dlg_btn);
			}
			return false;
		//}
	});
	
});
</script>
<div style="padding: 0 10px 0 10px">
	<table id="newapi_cart" class="scroll"></table>
	<div id="pnewapi_cart" class="scroll" style="text-align:center;"></div>
</div>
<p class="tfont" style="margin-left:20px">- Untuk menambah jumlah pemesanan, klik item lalu enter.</p>