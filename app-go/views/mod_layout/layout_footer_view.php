<script language="javascript">
$(document).ready(function() {
	$('#login-pane').toggle(
		function(){
			$('#footer-left').animate({width:'421px'},1000,function(){
				$('#login').show();
				$('.lrow1').removeClass('ui-icon-circle-arrow-w').addClass('ui-icon-circle-arrow-e').css('float','right');
				$('.lrow2').css('float','right');
			});
		},
		function(){
			$('#login').hide();
			$('#footer-left').animate({width:'85px'},1500,function(){
				$('.lrow1').removeClass('ui-icon-circle-arrow-e').addClass('ui-icon-circle-arrow-w').css('float','left').text('LOGIN');
				$('.lrow2').css('float','left');
			});
		}
	);
});
</script>

	<DIV id="footer-right" class="ui-widget ui-widget-header">
		<p>
				<A href="#" onclick="menu_ajax('list_dvd');">dvd</A> 
				| <A href="#" onclick="menu_ajax('list_belanja');">rincian</A> 
				| <A href="#" onclick="menu_ajax('tracking_data');">tracking</A> 
				| <A href="#" onclick="menu_ajax('list_checklist');">info list</A> 
		</p>
		<p id="owner"><a>&copy; 2011 dvdgames-online.com&trade; All Right Reserved</a></p>
	</DIV>
	
	<DIV id="footer-left" class="ui-widget ui-widget-content">
		<div class="footer-login">
			<button id="login-pane" class="login ui-widget-header ui-corner-all">
			<span style="float:left" class="lrow1 ui-icon ui-icon-circle-arrow-w"></span> SLIDE 
			</button>
			<button class="login ui-widget-header ui-corner-all">
			<span id="signup" style="float:left" class="lrow2 ui-icon ui-icon-person"></span> CS 
			</button>
		</div>
		<DIV id="login">
		<!--
			<table id="footer-ll" border="0" width="" border="0" cellpadding="2" cellspacing="3" class="ui-widget ui-widget-content ui-corner-all">
			<tr>
				<td width="80">&nbsp;email</td>
				<td align="center" width="2">:</td>
				<td align="left" width="96"><input type="text" name="user" size="12"></td>
				<td align="left" rowspan="2"><button class="logedin ui-widget-header ui-corner-all"><span class="ui-icon ui-icon-key"></span></button></td>
			</tr>
			<tr>
				<td align="left">&nbsp;password</td>
				<td align="center">:</td>
				<td align="left"><input type="password" name="password" size="12"></td>
			</tr>
			</table>
			<div id="footer-lr">
				<ul>
				<li><button class="ui-widget-header ui-corner-all">IBCA</button></li>
				<li><button class="ui-widget-header ui-corner-all">IMANDIRI</button></li>
				</ul>
			</div>
		-->
		</DIV>
	</DIV>