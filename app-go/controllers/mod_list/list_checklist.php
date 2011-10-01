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

class List_checklist extends GO_Controller {
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
		$output += $this->_data_init();
		
		$this->load->vars($output);
		
		log_message('debug', "Class $class init success");
	}
	
	// @info	: Loader class model,helper,config,library
	// @params	: null
	// @return	: null
	function _loader_class()
	{	
		$this->load->helper(array(
		
		));
		$this->load->library(array(
		
		));
		$this->load->model(array(
			"jqgrid_model",
		));
		return false;
	}
	
	// @info	: Extra Sub Header Content for JS & CSS
	// @access	: private
	// @params	: null
	// @return	: array	
	function _content_init()
	{
		$arrayCSS = array (
			
		);
		
		$arrayJS = array (
			'asset/src/jQuery/general/tabs.js',
		);
		
		$content = "";
		
		if (is_array($arrayCSS))
		foreach ($arrayCSS as $css):
			$content .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"".base_url().$css."\"/>\n";
		endforeach;
		
		if (is_array($arrayJS))
		foreach ($arrayJS as $js):
			$content .= "<script type=\"text/javascript\" src=\"".base_url().$js."\"/></script>\n";
		endforeach;
		
		$output['extraSubHeadContent'] = $content;
		
		return $output;
	}
	
	// @info	: Load public variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _public_init()
	{
		// public variable
		self::$link_controller = 'mod_list/list_checklist';
		self::$link_view = 'mod_list/list_checklist';
		$output['link_view'] = self::$link_view;
		$output['link_controller'] = self::$link_controller;	
		
		return $output;
	}
	
	// @info	: Load local data
	// @access	: private
	// @params	: null
	// @return	: array
	function _data_init()
	{
		// view variable
		$output[''] = '';
		
		return $output;
	}
	
	// @info	: Load local variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _variable_init()
	{
		
		// view variable
		$output['content_title'] = "LIST CD DVD ALL";		
		
		return $output;
	}
	
	// @info	: Populate data json to grid
	// @access	: public
	// @params	: POST string
	// @params	: string	$kat_id
	// @return	: JSON array string
	
	public function get_data($type='dvd')
	{
		if ($type=='dvd'):
			$SQL = "
				select * {COUNT_STR} 
				from master_listdvd as mld
				inner join master_listkat as mlk on mlk.lkat_id = mld.lkat_id
			";
			
			$rs = $this->jqgrid_model->get_data_query($SQL,'ldvd_id');	
		else:
			$SQL = "
				select * {COUNT_STR} 
				from master_listcd as mlc
				inner join master_listkat as mlk on mlk.lkat_id = mlc.lkat_id
			";
			
			$rs = $this->jqgrid_model->get_data_query($SQL,'lcd_id');	
		endif;
		echo json_encode($rs);
	}
	
	// @info	: Indexing Layout
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index() 
	{
		$output[''] = '';		
		$this->load->view(self::$link_view.'/checklist_main_view',$output);
	}
	
	function list_dvd()
	{
		$output[''] = '';		
		$this->load->view(self::$link_view.'/checklist_dvdgrid_view',$output);
	}
	
	function list_cd()
	{
		$output[''] = '';		
		$this->load->view(self::$link_view.'/checklist_cdgrid_view',$output);
	}
}