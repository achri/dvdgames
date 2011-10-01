<script language="javascript">
var tab_pembayaran = $('#tab-pembayaran');
function tabs_awal(){
	$.ajax({
		url:'<?php echo site_url($link_controller)?>/cek_tab_pengiriman',
		type:'POST',
		success: function(data) {
			if (data) {
				tab_pembayaran.tabs('select',1);
				tab_pembayaran.tabs('disable',0);
			} else {
				tab_pembayaran.tabs('select',0);
				tab_pembayaran.tabs('disable',1);
			}
		}
	});
	return false;
}

$(document).ready(function(){	
	tab_pembayaran.tabs();
	tabs_awal();
});
</script>
<div style="padding: 0 10px 0 10px">
<div id="tab-pembayaran" style="height:380px;overflow:auto">
	<ul class="tfont">
		<li><a href="<?php echo site_url($link_controller);?>/rincian_pengiriman">Rincian Pengiriman</a></li>
		<li><a href="<?php echo site_url($link_controller);?>/rincian_pembayaran">Konfirmasi Pembayaran</a></li>
	</ul>
</div>
</div>