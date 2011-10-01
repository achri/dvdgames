<script language="javascript">
function debuging(sel) {
	var bg = $(sel).html();
	if (bg && bg != '.') {
		$('#so-debug').text(bg);
	} else {
		$('#so-debug').text('');
	}
		
	return false;
}

$(document).ready(function() {

	$('.category-selectable').selectable({
		selected:function(ev,ui){
			var sel = $('.ui-selected > img',this),
				kat_id = $('.ui-selected',this).attr('id');
			
			$('div.category-sel').html(sel.clone());
			
			menu_ajax('list_dvd',kat_id);
		}
	});
		
	$(".category-accordion").accordion({
		//collapsible: true,
		fillSpace: true,
		//event: "mouseover",
		icons: {
   			header: "ui-icon-circle-arrow-e",
  				headerSelected: "ui-icon-circle-arrow-s"
		}
	});		
});
</script>
<DIV id="cart-box" class="ui-state-highlight ui-corner-all mfont">
<h4><span class="ui-icon ui-icon-cart">CART</span>&nbsp;Rincian Pembelian&nbsp;<a href="#" onclick="menu_ajax('list_belanja');"><span id="tot_beli"></span></a></h4>
	<DIV id="cart-list">
					
	</DIV>
</DIV>
				
<DIV class="category ui-corner-all mfont">
	<div class="category-accordion">
		<h3><a href="#">Berita</a></h3>
		<div class="accordion_content">
			GOOGLE NEWS
		</div>
		<h3><a href="#">Kategori</a></h3>
		<div class="accordion_content">
			<ul class="kategori category-selectable">
				<li id="" class="ui-selected"><?php echo $this->lib_pictures->thumbs_ajax('na.png',200,false,'uploaded/kategori/');?></li>
			<?php
			if (isset($list_kategori)):
				foreach ($list_kategori->result() as $rkat):
				?>
				<li id="<?php echo $rkat->kat_id?>"><?php echo $this->lib_pictures->thumbs_ajax($rkat->kat_gambar,200,false,'uploaded/kategori/');?></li>
				<?php
				endforeach;
			endif;
			?>
			</ul>
		</div>
		<?php if ($this->config->item('debug')):?>
		<h3><a href="#">Debug</a></h3>
		<div class="accordion_content">
			<p>
				<div>Debug <input onkeyup="debuging(this.value)"/></div>
				<div id="so-debug"></div>
			</p>
		</div>
		<?php endif;?>
	</div>
</DIV>