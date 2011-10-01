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

class App_init extends GOA_Controller {
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
		$this->load->library(array(
			"lib_login",
		));
		
		return false;
	}
	
	// @info	: Extra Sub Header Content for JS & CSS
	// @access	: private
	// @params	: null
	// @return	: array	
	function _content_init()
	{
		$content = doctype('xhtml1-trans')."\n"; // XML TRADITIONAL
		
		$arrayCSS = array (
			// JQUERY
			'asset/src/jQuery/themes/dark-hive/jquery.ui.all.css',
			'asset/css/admin/general.css',
			
			'asset/src/jQuery/plugins/tables/jquery.jqGrid/css/ui.jqgrid.css',
			//'asset/src/jQuery/plugins/tables/datagrid/datagrid.css',
			'asset/css/jqgrid.patch.css',
		);
		
		$arrayJS = array (
			// JQUERY
			'asset/src/jQuery/core/jquery-1.5.2.js',
			'asset/src/jQuery/ui/jquery-ui-1.8.12.custom.js',
			
			'asset/src/jQuery/plugins/tables/jquery.jqGrid/src/i18n/grid.locale-en.js',
			'asset/src/jQuery/plugins/tables/jquery.jqGrid/js/jquery.jqGrid.min.js',
			//'asset/src/jQuery/plugins/tables/datagrid/datagrid.js',
			'asset/src/jQuery/plugins/form/jquery.form.js',
			'asset/src/jQuery/plugins/form/jquery.autoNumeric.js',
			'asset/src/jQuery/plugins/form/ajaxupload/ajaxupload.js',
			
			// ADDITIONAL
			'asset/src/jQuery/helper/ui.jquery.helper.js',
			'asset/src/jQuery/helper/autoNumeric.js',
			'asset/src/jQuery/helper/validasi.js',
			'asset/src/jQuery/helper/dialog.js',
			
			// ADDITIONAL
			'asset/js/admin/general.js',
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
			<script language=\"javascript\">
				// JQGRID
				$.jgrid.no_legacy_api = true;
				$.jgrid.useJSON = true;
				$.jgrid.defaults = $.extend($.jgrid.defaults,{loadui:\"enable\"});
				
				// HELPER
				//masking('.number');
				//set_kalender('.kalender');
			</script>";
		
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
		self::$link_view = 'mod_dvd';
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
		$output['title'] = "DVDGAMES-ONLINE Store (Admin)";
		$output['header_title'] = "DVDGames-Online.COM";
		$output['header_subtitle'] = "Pembelian DVD Games Online";
		
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
	
	function log_out()
	{
		$this->lib_login->log_out();
	}
}

/* End of file app_init.php */
/* Location: ./app-imp/controllers/app_init.php */