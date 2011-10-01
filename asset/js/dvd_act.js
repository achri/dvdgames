//$(document).ready(function() {
	function add_toCart(dvd_id,kat_id,$item) {
		// AJAX SAVE
		$.ajax({
			url: 'index.php/mod_list/list_dvd/tambah_penjualan/'+dvd_id+'/'+kat_id,
			type: 'POST',
			success: function(data) {
				
				if (data == 'sukses') {
					
					// REFRESH DVD
					try {
						load_cart();
						get_dvd();
					}
					catch(e) {
						
					}
				}else if (data == 'wait') {
					var $dlg_close = function() {
						menu_ajax('list_belanja');
					};
					
					informasi("Order sebelumnya masih menunggu !!!",$dlg_close);
				}
			}
		});
		
		return false;
	}

	function add_toList(dvd_id,$item) {
		// AJAX DELETE
		$.ajax({
			url: 'index.php/mod_list/list_dvd/hapus_penjualan/'+dvd_id,
			type: 'POST',
			success: function(data) {
				if (data) {
					try {
						load_cart();
						get_dvd();
						grid_content.trigger('reloadGrid');
					}
					catch(e) {
						
					}
				}
			}
		});
		
		return false;
	}
//});