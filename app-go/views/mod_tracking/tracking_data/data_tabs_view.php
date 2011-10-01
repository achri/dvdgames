<?php 
	if (isset($extraSubHeadContent))
		echo $extraSubHeadContent;
?>
<script language="javascript">
var tab_tracking = $('#tab-tracking');

$(document).ready(function(){	
	tab_tracking.tabs();
});
</script>
<div style="padding: 0 10px 0 10px">
	<div id="tab-tracking" style="height:380px;overflow:auto">
		<ul class="tfont">
			<li><a href="#daftar_kirim">Status Pengiriman</a></li>
		</ul>
		<div id="daftar_kirim">
			<?php echo $this->load->view($link_view.'/data_main_view')?>
		</div>
	</div>
</div>