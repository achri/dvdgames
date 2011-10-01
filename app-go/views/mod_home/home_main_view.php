<?php 
	if (isset($extraSubHeadContent))
		echo $extraSubHeadContent;
?>
<script type="text/javascript">

function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};

$(document).ready(function() {
    $('#mycarousel').load('<?php echo site_url($link_controller)?>/home_best',function(data){
		$(this).jcarousel({
			auto: 5,
			wrap: 'last',
			initCallback: mycarousel_initCallback
		});
	});	
});

</script>
<div style="margin:10px 0 0 10px; width:95%" class="tfont">
<p>
<span style="position:absolute;"><img src="<?php echo base_url()?>asset/images/cd.png" /></span>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Selamat Datang Di <strong>DVDGAMES-ONLINE.COM</strong>
<hr class="ui-state-default"/>
</p>
<br/>
<p align="justify">
Disini anda bisa memesan dvd games terbaru dengan harga relatif murah Rp.30,000 per dvd. 
Anda juga bisa melihat review games,trainer serta cheats tiap game yang kami sertakan. <a href='#' style="">... <font size="1px">selanjutnya</font></a>
</p>
<br/>
<ul id="mycarousel" class="jcarousel-skin-tango" style="margin-left:-15px">

</ul>
</div>