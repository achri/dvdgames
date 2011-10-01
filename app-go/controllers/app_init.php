<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	30/03/2011
 @model
	- 
 @view
	- 
 @library
    - JS		
    - PHP
 @comment
	- Class First Loader
*/

class App_init extends GO_Controller {
	// public variable
	public static $link_controller, $link_view;
	
	// constructor
	function __construct () 
	{
		parent::__construct();	
	
		$class = get_class($this);
		
		$this->_loader_class();
		
		$output = array();
		$output += $this->_content_init();
		$output += $this->_public_init();
		$output += $this->_variable_init();
		
		$this->load->vars($output);
		
		log_message('debug', "Class $class init success");
	}
	
	// @info	: Loader class model,helper,config,library
	// @params	: null
	// @return	: null
	function _loader_class()
	{	
		$this->load->helper(array(
			"text",
		));
		$this->load->library(array(
			"lib_pictures","session","lib_sweeping",
		));
		$this->load->model(array(
			"tbl_dvd","tbl_kategori",
			"tbl_penjualan","tbl_penjualan_detail",
		));
		return false;
	}
	
	// @info	: Extra Sub Header Content for JS & CSS
	// @access	: private
	// @params	: null
	// @return	: array	
	function _content_init()
	{
		$content = '';//doctype('xhtml1-strict');//doctype('xhtml1-trans')."\n"; // XML TRADITIONAL
		
		$arrayCSS = array (
			// JQUERY
			//'asset/src/jQuery/themes/ui-darkness/jquery.ui.all.css',
			'asset/src/jQuery/plugins/other/jquery.tooltip.css',
			'asset/src/jQuery/plugins/tables/jquery.jqGrid/css/ui.jqgrid.css',
			'asset/src/jQuery/plugins/image/jcarousel/skins/tango/skin.css',
			// ADDITIONAL
			'asset/css/jqgrid.patch.css',
			'asset/css/best_seller.css',
			'asset/css/menu.css',
			'asset/css/general.css',
			'asset/css/default.css',
			'asset/css/category.css',
			'asset/css/dvd.css',
			'asset/css/cart.css',
			//'asset/src/jQuery/plugins/other/jquery.jscrollpane/jscrollpane.css',
			//'asset/src/jQuery/plugins/other/jquery.jscrollpane/themes.css',
		);
		
		$arrayJS = array (
			// JQUERY
			'asset/src/jQuery/core/jquery-1.5.2.js',
			'asset/src/jQuery/ui/jquery-ui-1.8.12.custom.js',
			
			'asset/src/jQuery/plugins/other/jquery.lavalamp.js',
			'asset/src/jQuery/plugins/other/jquery.easing.js',
			'asset/src/jQuery/plugins/other/jquery.jcarousellite.js',
			'asset/src/jQuery/plugins/other/jquery.bgiframe.js',
			//'asset/src/jQuery/plugins/other/jquery.dimensions.js', // CONFLIC
			'asset/src/jQuery/plugins/other/jquery.tooltip.js',
			'asset/src/jQuery/plugins/other/jquery.mousewheel.js',
			//'asset/src/jQuery/plugins/other/jquery.jscrollpane.js',
			'asset/src/jQuery/plugins/tables/jquery.jqGrid/src/i18n/grid.locale-en.js',
			'asset/src/jQuery/plugins/tables/jquery.jqGrid/js/jquery.jqGrid.min.js',
			'asset/src/jQuery/plugins/content/jquery.blockui.js',
			'asset/src/jQuery/plugins/image/jcarousel/jquery.jcarousel.js',
			'asset/src/jQuery/plugins/form/jquery.form.js',
			// ADDITIONAL
			'asset/src/jQuery/helper/dialog.js',
			'asset/js/general.js',
			'asset/js/menu.js',
			'asset/js/menu_lava.js',
			'asset/js/dvd_act.js',
			'asset/js/cart.js',
			'asset/js/best_seller.js',
		);
		
		if (is_array($arrayCSS))
		foreach ($arrayCSS as $css):
			$content .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url().$css."\"/>\n";
		endforeach;
		
		if (is_array($arrayJS))
		foreach ($arrayJS as $js):
			$content .= "<script type=\"text/javascript\" src=\"".base_url().$js."\"/></script>\n";
		endforeach;
		
		// BIND OPTIONAL JS HERE =========>
		$content .= "
			<script type=\"text/javascript\">
				$.jgrid.no_legacy_api = true;
				$.jgrid.useJSON = true;
				$.jgrid.defaults = $.extend($.jgrid.defaults,{loadui:\"enable\"});
			</script>\n";
		
		$output['extraHeadContent'] = $content;
		
		return $output;
	}
	
	// @info	: Load public variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _public_init()
	{
		// public variable
		self::$link_controller = 'app_init';
		self::$link_view = 'mod_home';
		$output['link_view'] = self::$link_view;
		$output['link_controller'] = self::$link_controller;	
		
		return $output;
	}
	
	// @info	: Load local variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _variable_init()
	{
		// private variable
		$output['title'] = "DVDGAMES-ONLINE Store";
		$output['header_title'] = "DVDGames-Online.COM";
		$output['header_subtitle'] = "Pembelian DVD Games Online";
		
		$output['list_kategori'] = $this->tbl_kategori->data_kategori();
		
		return $output;
	}
	
	// @info	: Indexing Layout
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index() 
	{
		$output[''] = '';
		$this->load->view('index',$output);
	}
	
	function content_page()
	{
		$this->load->view('mod_layout/layout_main_view');
	}
	
	
	function home_best()
	{
		$SQL = "
		select
		distinct(p.dvd_id),
		p.kat_id,
		md.dvd_nama,
		md.dvd_gambar,
		mk.kat_nama
		from penjualan_detail as p
		inner join penjualan as pj on pj.jual_id = p.jual_id
		inner join master_dvd as md on md.dvd_id = p.dvd_id
		inner join master_kategori as mk on mk.kat_id = p.kat_id
		where
		pj.status = 3
		AND
		(
			select 
			sum(jumlah)
			from penjualan_detail as pp
			inner join penjualan as pjj on pjj.jual_id = pp.jual_id
			where pp.dvd_id = p.dvd_id and pjj.status = 3
		)
		=
		(
			select
			max((
				select 
				sum(jumlah)
				from penjualan_detail as pp
				inner join penjualan as pjj on pjj.jual_id = pp.jual_id
				where pp.dvd_id = pd.dvd_id and pjj.status = 3
			))
			from penjualan_detail as pd
			inner join penjualan as pjj on pjj.jual_id = pd.jual_id
			where pd.kat_id = p.kat_id and pjj.status = 3
		)
		group by p.kat_id
		order by mk.kat_nama
		";
		
		//$max = $this->db->query($SQL)->num_rows();
		
		//$limit = rand(1,$max-2);
		
		//$SQL .= " limit $limit,2";
		
		$output['list_bestdvd'] = $this->db->query($SQL);
		$this->load->view('mod_home/home_best_view',$output);
	}
	
	function home()
	{
		$SQL = "
		select
		distinct(p.dvd_id),
		p.kat_id,
		md.dvd_nama,
		md.dvd_gambar,
		mk.kat_nama
		from penjualan_detail as p
		inner join penjualan as pj on pj.jual_id = p.jual_id
		inner join master_dvd as md on md.dvd_id = p.dvd_id
		inner join master_kategori as mk on mk.kat_id = p.kat_id
		where
		pj.status = 3
		AND
		(
			select 
			sum(jumlah)
			from penjualan_detail as pp
			inner join penjualan as pjj on pjj.jual_id = pp.jual_id
			where pp.dvd_id = p.dvd_id and pjj.status = 3
		)
		=
		(
			select
			max((
				select 
				sum(jumlah)
				from penjualan_detail as pp
				inner join penjualan as pjj on pjj.jual_id = pp.jual_id
				where pp.dvd_id = pd.dvd_id and pjj.status = 3
			))
			from penjualan_detail as pd
			inner join penjualan as pjj on pjj.jual_id = pd.jual_id
			where pd.kat_id = p.kat_id and pjj.status = 3
		)
		group by p.kat_id
		order by mk.kat_nama
		";
		
		//$max = $this->db->query($SQL)->num_rows();
		
		//$limit = rand(1,$max-2);
		
		//$SQL .= " limit $limit,2";
		
		$output['list_bestdvd'] = $this->db->query($SQL);
		//$output[''] = '';
		$this->load->view('mod_home/home_main_view',$output);
	}
	
	function cs_page()
	{	
		$output[''] = '';
		$this->load->view('mod_info/info_hubungi_view',$output);
	}
	
	function expired() 
	{
		$this->lib_sweeping->cek_expired();
	}
	
}

/* End of file app_init.php */
/* Location: ./app-imp/controllers/app_init.php */