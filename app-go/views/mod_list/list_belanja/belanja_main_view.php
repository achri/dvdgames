<?php 
	if (isset($extraSubHeadContent))
		echo $extraSubHeadContent;
	if ($list_order->num_rows() > 0):
?>

<script language="javascript">
var tab_pembayaran = $('#tab-pembayaran');
function tabs_awal(){
	<?php 
		if ($list_order->row()->status == 0):
	?>
		tab_pembayaran.tabs('select',0);
		tab_pembayaran.tabs('disable',1);
		tab_pembayaran.tabs('disable',2);
	<?php
		elseif ($list_order->row()->status == 1):
	?>
		tab_pembayaran.tabs('select',1);
		tab_pembayaran.tabs('disable',0);
		tab_pembayaran.tabs('disable',2);
	<?php
		else:
	?>
		tab_pembayaran.tabs('select',2);
		tab_pembayaran.tabs('disable',0);
		tab_pembayaran.tabs('disable',1);
	<?php
		endif;
	
	?>
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
		<li><a href="<?php echo site_url($link_controller);?>/rincian_belanja">Rincian Belanja</a></li>
		<li><a href="<?php echo site_url($link_controller);?>/rincian_pengiriman">Rincian Pengiriman</a></li>
		<li><a href="<?php echo site_url($link_controller);?>/rincian_pembayaran">Konfirmasi Pembayaran</a></li>
	</ul>
</div>
</div>
<?php
	else:
		echo "
		<div style='padding: 20%'>
			<div align='center' class='tfont ui-widget-content ui-corner-all'>
			Keranjang Pembelian Kosong !!!<br>
			Silahkan pilih menu daftar DVD untuk melakukan order pembelian
			</div>
		</div>";
	endif;
?>