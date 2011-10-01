<?php 
	if (isset($extraSubHeadContent))
		echo $extraSubHeadContent;
		
	$this->load->view($link_view.'/data_grid_view');
?>