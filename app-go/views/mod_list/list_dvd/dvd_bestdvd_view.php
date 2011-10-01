<?php if (isset($list_bestdvd) && $list_bestdvd->num_rows() > 0): ?>
<script type="text/javascript">
function show(kat_id,dvd_id,judul) {
	rev_call(judul,dvd_id,kat_id);
	return false;
}

$(document).ready(function() {	
	$('.content-best-dvd').jCarouselLite({
        auto:5000,
        speed:500,
        visible:<?php echo (isset($list_bestdvd))?(($list_bestdvd->num_rows() <= 5)?($list_bestdvd->num_rows()):(5)):(1)?>,
        btnNext: ".jc-next img",
        btnPrev: ".jc-prev img",
        mouseWheel:true,
    });
	
	$(".list-best-dvd li a img").tooltip({
		delay: 1200,
		showURL: false,
		//track:true,
		extraClass:'ui-corner-all',
		bodyHandler: function() {
			return $("<img/>").css({
				//width: '400px', height: '300px'
			}).attr("src", this.src);
		}
	});
});
</script>
<ul class="list-best-dvd">
<?php 
	foreach ($list_bestdvd->result() as $rbest):
?> 
	<li>
	<a onclick="show('<?php echo $rbest->kat_id?>','<?php echo $rbest->dvd_id?>','<?php echo $rbest->dvd_nama?>');" href="#">
		<img src="uploaded/dvd/<?php echo $rbest->dvd_gambar?>" alt="<?php echo $rbest->dvd_nama?>" class="ui-widget-content ui-corner-all" width="50px" height="50px"> 
	</a> 
	</li>
<?php
	endforeach;
?>
</ul>
<?php endif;;


