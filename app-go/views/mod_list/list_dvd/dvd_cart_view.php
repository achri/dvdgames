<?php
	if (isset($extraSubHeadContent))
		echo $extraSubHeadContent;
?>
<ul class="product ui-helper-reset ui-helper-clearfix">
<?php 
if (isset($list_cart) && $list_cart->num_rows() > 0):
	foreach ($list_cart->result() as $rcart):
?> 
	<li style="display: list-item; width: 48px;" id="<?php echo $rcart->dvd_id?>" class="ui-widget-content product-corner"> 
	<h5 class="ui-widget-header"><?php echo character_limiter($rcart->dvd_nama,10,' ...')?></h5> 
	<table width="100%" style="height: 48px; max-height: 85px; max-width: 85px;" border="0" cellpadding="0" cellspacing="0"> 
	<tbody>
		<tr>
			<td align="center">
				<img style="display: inline-block; width: 90%;" src="uploaded/dvd/<?php echo ($rcart->dvd_gambar)?($rcart->dvd_gambar):('na.png')?>" alt="<?php echo $rcart->dvd_nama?>" judul="<?php echo $rcart->dvd_nama?>"> 
			</td> 
		</tr> 
	</tbody>
	</table> 
	<a href="uploaded/dvd/<?php echo $rcart->dvd_gambar?>" title="Spesifikasi" class="ui-icon ui-icon-zoomin">View larger</a> 
	<a href="#" title="Batal" class="ui-icon ui-icon-refresh">Return this item</a>
	</li>
<?php
	endforeach;
?>
<script language="javascript">$('#tot_beli').text('<?php echo $list_cart->num_rows()?> item(s)')</script>
<?php
endif;
?>
</ul>