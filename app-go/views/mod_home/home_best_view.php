<script type="text/javascript">
$(document).ready(function() {
	$("#mycarousel li a img").tooltip({
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
<?php 
	foreach ($list_bestdvd->result() as $rdvd):
?> 	
	<li>
	<a onclick="show('<?php echo $rdvd->kat_id?>','<?php echo $rdvd->dvd_id?>','<?php echo $rdvd->dvd_nama?>');" href="#">
		<img src="uploaded/dvd/<?php echo $rdvd->dvd_gambar?>" height="150px" alt="<?php echo $rdvd->dvd_nama?>" class="ui-widget-content ui-corner-all" > 
	</a>
	</li>
<?php
	endforeach;
?>