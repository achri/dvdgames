<DIV id="header" class="ui-widget-header">
	<div id="banner-warp">
		<div id="logo"><H1>DVDGames-Online.COM</H1></div>
		<div id="slogan"><a>Pembelian DVD Games Online</a></div>
	</div>
	<div id="themes">themes 
		<select onChange="ui_switch(this.value);" class="ui-widget-select ui-widget-content">
			<?php
				$url_themes = directory_map('./asset/src/jQuery/themes/', TRUE);
				$no = 1;
				foreach ($url_themes as $themes): 
			?>
					<option value="<?php echo $themes?>" <?php echo ($themes==$this->config->item('themes_default'))?('SELECTED'):('')?>>skin <?php echo $no?></option>
			<?php
				$no++;
				endforeach;
			?>
		</select>
	</div>
</DIV>