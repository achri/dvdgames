<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	13/12/2010
 @model
	- dynatree_model
	- tbl_tiki
 @view
	- main_view
	- kategori_list_view
	- kategori_form_view
	- kategori_add_view
 @library
    - JS
		- dynatree
		- jquery.form
    - PHP
 @comment
	- 
	
*/

class Master_tiki extends GOA_Controller {
	public static $link_view, $link_controller, $link_controller_tiki, $link_controller_kelas, $link_controller_grup;
	
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
		
		log_message('debug', "IMP -> Class $class init success");
	}
	
	// @info	: Loader class model,helper,config,library
	// @params	: null
	// @return	: null
	function _loader_class()
	{
		$this->load->library(array(
			"datagrid",
		));
		$this->load->model(array(
			"dynatree_model","jqgrid_model",
			"tbl_tiki","tbl_tarif",
		));
		return false;
	}
	
	// @info	: Extra Sub Header Content for JS & CSS
	// @access	: private
	// @params	: null
	// @return	: array	
	function _content_init()
	{
		$content = "";
		
		$arrayCSS = array (
		);
		
		$arrayJS = array (			
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
		$content .= "";
		
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
		self::$link_controller = 'mod_master/master_tiki';
		self::$link_view = 'mod_master/master_tiki';
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
		$output['page_title'] = "Master Tiki";
		
		return $output;
	}
	
	function _jqGrid_ajax($status="provinsi")
	{
		//$this->load->library('datagrid');
		$grid  = $this->datagrid;
		
		$id['search'] = 'false';
		$id['hidden'] = 'true';
		$id['key'] = 'true';
		$grid->addField('tiki_id');
		$grid->label('id');
		$grid->params($id);
		
		$kode['width'] = 50;
		$kode['sortable'] = 'true';
		$grid->addField('tiki_kode');
		$grid->label('Kode');
		$grid->params($kode);
			
		$nama['width'] = 300;
		$nama['editable'] = 'true';
		$nama['sortable'] = 'true';
		$nama['edittype'] = "'text'";
		$nama['editrules'] = '{required:true}';
		$grid->addField('tiki_wilayah');
		$grid->params($nama);
		
		switch ($status):
			case "provinsi" : 
				$grid->label('Provinsi');
				#table title
				$grid->setTitle('Daftar Provinsi');
				$grid->setgridName('prov');
				//$grid->setHiddenGrid('true');
			break;
			case "kota" : 
				$grid->label('Kota');
				#table title
				$grid->setTitle('Daftar Kota');
				$grid->setgridName('kota');
				//$grid->setHiddenGrid('true');
			break;
		endswitch;
		
		#show paginator
		$grid->showpager(true);
		$grid->showsearch(true);

		#width
		$grid->setWidth('400');
            
		#height
		$grid->setHeight('150');

		#show/hide navigations buttons
		$grid->setAdd(true);
		//$grid->setEdit(false);
		$grid->setDelete(true);
		
		//$grid->setView(true);
		//$grid->setSearch(false);
		$grid->setRefresh(false);

		$grid->setRowNum(5);
		
		$grid->setRowList('5,10,20,50');

		#export buttons
		//$grid->setExcel(false);
		//$grid->setPdf(true,array('title' => 'Unit Measure'));
		
		#GET url
		$grid->setUrlget(site_url(self::$link_controller.'/get_data'));

		#Set url
		$grid->setUrlput(site_url(self::$link_controller.'/set_data'));

		return $grid->deploy();
	}
	
	// @info	: Populate data json to grid
	// @access	: public
	// @params	: POST string
	// @params	: string	$tiki_id
	// @return	: JSON array string
	public function get_data($tiki_id = FALSE, $tipe = 'wilayah')
	{
		if ($tipe == 'wilayah'):
			$tiki_level = $this->input->get_post('tiki_level');
			$where['tiki_level'] = $tiki_level;
			
			$table = "master_tiki";		
			if (FALSE === $tiki_id):
				if ($tiki_level > 1):
					$rs = $this->jqgrid_model->get_data($table,FALSE,array('tiki_level'=>99));
				else:
					$rs = $this->jqgrid_model->get_data($table,FALSE,$where);
				endif;
			else:
				$where['tiki_master'] = $tiki_id;
				$rs = $this->jqgrid_model->get_data($table,FALSE,$where);
			endif;
		else:
		
			$SQL = "
			select *,mtd.tiki_wilayah as tiki_dari,mtt.tiki_wilayah as tiki_tujuan {COUNT_STR}
			from master_tarif as mtf
			inner join master_tiki_paket as mtp on mtp.paket_id = mtf.paket_id
			inner join master_tiki as mtd on mtd.tiki_id = mtf.dari
			inner join master_tiki as mtt on mtt.tiki_id = mtf.tujuan
			";
			
			$rs = $this->jqgrid_model->get_data_query($SQL,'tarif_id');	
		endif;
			
		echo json_encode($rs);
	}
	
	// @info	: Manipulate data from grid
	// @access	: public
	// @params	: POST string
	// @params	: string	$tiki_id
	// @return	: JSON array string
	public function set_data($tipe = 'wilayah')
	{
		if ($tipe == 'wilayah'):
			$id_field = "tiki_id";
			$table = "master_tiki";	
		else:
			$id_field = "tarif_id";
			$table = "master_tarif";
		endif;
		echo $this->jqgrid_model->set_data($table,$id_field);
	}
	
	// @info	: Indexing Layout
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index() {
		$output['prov_grid'] = $this->_jqGrid_ajax("provinsi");
		
		$get_paket = $this->tbl_tiki->data_paket();
		foreach ($get_paket->result() as $rows):
			$arr_paket[] = $rows->paket_id.":".$rows->paket_nama;
		endforeach;
		$output['data_paket'] = implode(';',$arr_paket);
		
		$where['tiki_level'] = 2;
		$get_tiki = $this->tbl_tiki->data_tiki($where);
		foreach ($get_tiki->result() as $rows):
			$arr_tiki[] = $rows->tiki_id.":".$rows->tiki_wilayah;
		endforeach;
		$output['data_tiki'] = implode(';',$arr_tiki);
		
		$this->load->view(self::$link_view."/tiki_main_view",$output);
	}
	
	function set_tiki_kode($status,$tiki_master=FALSE) {
		$set_kode = 0;
		switch ($status):
			case 'provinsi':
				$numcode = $this->tbl_tiki->nomor_tiki($tiki_master);
				$numcode++;
				$set_kode = str_pad($numcode, 2, "0", STR_PAD_LEFT);
			break;
			case 'kota':
				$where['tiki_id'] = $tiki_master;
				$tiki_kode= $this->tbl_tiki->data_tiki($where)->row()->tiki_kode;
				$numcode = $this->tbl_tiki->nomor_tiki($tiki_master);
				$numcode = substr($numcode,3,2);
				if ($numcode == 0)
					$numcode = 0;
				$numcode++;
				
				$numcode = str_pad($numcode, 2, "0", STR_PAD_LEFT);
				$set_kode = $tiki_kode.'.'.$numcode;
			break;
		endswitch;
		
		echo $set_kode;
	}
	
	function cek_hapus_tiki($status,$tiki_master=FALSE) {
		$where['tiki_master'] = $tiki_master;
		$get_tiki = $this->tbl_tiki->data_tiki($where);
		if ($get_tiki->num_rows() > 0)
			echo 'terpakai';
	}
	
	function get_last_id() {
		$this->db->order_by('tiki_id','DESC');
		$get = $this->db->get('master_tiki');
		if ($get->num_rows() > 0):
			echo $get->row()->tiki_id;
		endif;
	}

}

/* End of file master_tiki.php */
/* Location: ./app/controllers/mod_master/master_tiki.php */