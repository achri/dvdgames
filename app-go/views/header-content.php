<?php
	// CSS PATH
	$arrayCSS = array (
		// JQUERY.UI.THEMESROOLER
		//'css/jquery/ui.themes/smoothness/ui.all.css',
		// JQUERY.PLUGIN
		//'css/jquery/plugin/jquery.jcarousel.css',
		'css/jquery/plugin/jquery.tooltip.css',
		'css/jquery/plugin/jquery.jcarousellite.css',
		'css/jquery/plugin/jquery.lavalamp.css',
		
		// CSS GENERAL
		/*
		'css/base.css',
		'css/header.css',
		'css/content.css',
		'css/footer.css'
		*/
		'css/default.css',
		'css/product.css',
		'css/category.css'
	);
		
	// JAVASCRIPT PATH
	$arrayJS = array (
		// JQUERY
		'js/jquery/core/jquery-1.3.2.js',
		// JQUERY.UI
		'js/jquery/ui/ui.core.js',
		'js/jquery/ui/ui.accordion.js',
		//'js/jquery/ui/ui.datepicker.js',
		'js/jquery/ui/ui.dialog.js',
		'js/jquery/ui/ui.draggable.js',
		'js/jquery/ui/ui.droppable.js',
		//'js/jquery/ui/ui.progressbar.js',
		'js/jquery/ui/ui.resizable.js',
		'js/jquery/ui/ui.selectable.js',
		//'js/jquery/ui/ui.slider.js',
		//'js/jquery/ui/ui.sortable.js',
		//'js/jquery/ui/ui.tabs.js',
		// JQUERY.PLUGIN
		// -- Menu
		'js/jquery/plugin/jquery.lavalamp.js',
		'js/jquery/plugin/jquery.easing.js',
		// -- Image Slide
		'js/jquery/plugin/jquery.jcarousel.js',
		'js/jquery/plugin/jquery.jcarousellite.js',
		// -- Tooltips
		'js/jquery/plugin/jquery.bgiframe.js',
		'js/jquery/plugin/jquery.dimensions.js',
		'js/jquery/plugin/jquery.tooltip.js',
		
		// JS GENERAL
		'js/general.js',
		'js/product.js',
		'js/menu.js'
	);
		
	$headercontent = '';
		
	foreach ($arrayCSS as $css):
		$headercontent .= '<link type="text/css" rel="stylesheet" href="'.base_url().$css.'"/>';
	endforeach;
	
	foreach ($arrayJS as $js):
		$headercontent .= '<script type="text/javascript" src="'.base_url().$js.'"/></script>';
	endforeach;
	
	// HEADER CSS AND JS PATH
	echo $headercontent;
	
	// HEADER CONTENT INTERNAL
	echo '<META HTTP-EQUIV="Pragma" CONTENT="no-cache">';
	echo '<META content="text/html; charset=iso-8859-1" http-equiv=Content-Type>';
	echo '<META name=Description content="Web Programing &amp; Development">';
	echo '<META name=Keywords content="">';
	echo '<META name=Distribution content=Global>';
	echo '<META name=Author content="Achri Kurniadi achrikurniadi@yahoo.com">';
	echo '<META name=Robots content=index,follow>';
	echo '<META name=GENERATOR content="MSHTML 8.00.6001.18702">';
	echo '<base href="'.base_url().'"/>';
	echo '<link rel="shortcut icon" href="'.base_url().'sierra.png" type="image/x-icon"/>';
	
	// EXTRA HEADER CONTENT
	if (!empty($extraheadercontent)):
		echo $extraheadercontent;
	endif;
?>
