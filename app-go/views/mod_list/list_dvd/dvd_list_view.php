<?php
	if (isset($extraSubHeadContent))//&&isset($list_cart))
		echo $extraSubHeadContent;
?>
<script language="javascript">
$(document).ready(function() {	
	// SCROLLBAR
	/*
	$(".scrollbar").jScrollPane({
		showArrows : true,
		wheelSpeed : 30,
	});
	*/
	
	pagina();
});
</script>
<input type="hidden" id="tot_page" value="<?php echo (isset($tot_page))?($tot_page):('');?>">
<input type="hidden" id="page" value="<?php echo (isset($page))?($page):('');?>">
<div id="product-box" class="scrollbar">
	<ul id="product-list" class="product ui-helper-reset ui-helper-clearfix" style="height=100%">
	<?php 
	if (isset($list_dvd)):
		foreach ($list_dvd->result() as $rdvd):
	?>
		<li id="<?php echo $rdvd->dvd_id?>" kat_id="<?php echo $rdvd->kat_id?>" class="ui-widget-content product-corner">
			<h5 class="ui-widget-header"><?php echo character_limiter($rdvd->dvd_nama,10,' ...')?></h5>
			<table width="100%" border=0 cellpadding=0 cellspacing=0>
			<tr>
				<td align="center">
					<img src="uploaded/dvd/<?php echo ($rdvd->dvd_gambar)?($rdvd->dvd_gambar):('na.png')?>" alt="<?php echo $rdvd->dvd_nama?>" judul="<?php echo $rdvd->dvd_nama?>"/>
				</td>
			</tr>
			</table>
			<a href="uploaded/dvd/<?php echo $rdvd->dvd_gambar?>" title="Spesifikasi" class="ui-icon ui-icon-zoomin">View larger</a>
			<span style="position:absolute;margin-left:-25px"><img src="<?php echo "asset/images/stars/".$rdvd->dvd_rating.".png";?>" width="50"/></span>
			<a href="#" title="Order" class="ui-icon ui-icon-cart">Delete image</a>
		</li>
	<?php
		endforeach;
	endif;
	?>
	</ul>
</div>