<?php 
	if (isset($extraSubHeadContent))
		echo $extraSubHeadContent;
		
	$this->load->view($link_view.'/complain_form_view');
?>