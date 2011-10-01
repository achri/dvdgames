<?php 
	if (isset($extraSubHeadContent))
		echo $extraSubHeadContent;
?>

<script language="javascript">
var tab_pembayaran = $('#tab-list');

$(document).ready(function(){	
	tab_pembayaran.tabs();
});
</script>
<div style="padding: 0 10px 0 10px">
<div id="tab-list" style="height:380px;overflow:auto">
	<ul class="tfont">
		<li><a href="#ldvd">Daftar DVD</a></li>
		<li><a href="#lcd">Daftar CD</a></li>
	</ul>
	<div id="ldvd">
		<?php echo $this->load->view($link_view.'/checklist_dvdgrid_view');?>
	</div>
	<div id="lcd">
		<?php echo $this->load->view($link_view.'/checklist_cdgrid_view');?>
	</div>
</div>
</div>