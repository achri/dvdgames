<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<HEAD>
	<TITLE>
	<?php 
		if (isset($title)): 
			echo $title;
		else:
			echo "DVD GAME ONLINE";
		endif;
	?>
	</TITLE>
	<?php 
	$meta = array(
		array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv'),
		//array('name' => 'Content-type', 'content' => 'text/html; charset=iso-8859-1', 'type' => 'equiv'),
		//array('name' => 'X-UA-Compatible', 'content' => 'IE=EmulateIE7', 'type' => 'equiv'),
        //array('name' => 'robots', 'content' => 'index,follow'),
		//array('name' => 'googlebot', 'content' => 'index,follow'),
        array('name' => 'description', 'content' => 'Penjualan DVD Games Online'),
        array('name' => 'keywords', 'content' => 'penjualan, dvd, games, online'),
		array('name' => 'author', 'content' => 'Achri Kurniadi'),
        array('name' => 'copyright', 'content' => '2011 by Achri Kurniadi'),
        //array('name' => 'rating', 'content' => 'General'),
        //array('name' => 'distribution', 'content' => 'Global'),
        //array('name' => 'revisit-after', 'content' => '1 weeks'),
        //array('name' => 'expires', 'content' => 'never'),
        //array('name' => 'language', 'content' => 'Indonesian'),
        //array('name' => 'dc.title', 'content' => 'GO-Online'),
    );

	echo meta($meta); 
	echo link_tag('asset/images/layout/dvd1.png', 'shortcut icon', 'image/ico');
	echo link_tag('feed', 'alternate', 'application/rss+xml', 'My RSS Feed');
	
	$link = array(
		'id' => 'ui-themes',
		'href' => 'asset/src/jQuery/themes/'.$this->config->item('themes_default').'/jquery.ui.all.css',
		'rel' => 'stylesheet',
		'type' => 'text/css',
		'media' => 'screen'
	);

	echo link_tag($link);
	
	if (isset($extraHeadContent)):
		echo $extraHeadContent;
	else:
		exit('SITE UNDERCONTRACTION !!!');
	endif;
	?>
	<base href="<?php echo base_url()?>"/>
</HEAD>
<BODY>
<div class="dialog-content"></div>
<div class="dialog-validasi"></div>
<div id="review-dialog">
	<div id="review-content"></div>
</div>

<div id="glare" class="">
	<div id="glare-image"></div>
</div>
<?php
if (!$this->config->item('uc')):
?>
<DIV id=wrap>
	<?php $this->load->view('mod_layout/layout_header_view')?>
	<?php $this->load->view('mod_layout/layout_menu_view')?>
	<?php $this->load->view('mod_layout/layout_content_view')?>
	<?php $this->load->view('mod_layout/layout_footer_view')?>
</DIV>
<?php 
else:
	$this->load->view('mod_info/info_uc_view');
endif;
?>
<div id="glare-foot" class="">
	<div id="glare-foot-image"></div>
</div>
</BODY>
</HTML>
