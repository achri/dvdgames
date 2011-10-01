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

class Tracking_complain extends GO_Controller {
	// public variable
	public static $link_controller, $link_view;
	// private variable
	private $harga_dvd,$beli_session;
	
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
	private function _loader_class()
	{	
		$this->load->helper(array(
			"text",
		));
		$this->load->library(array(
			"lib_pictures","lib_files"
		));
		$this->load->model(array(
			"jqgrid_model","tbl_sys_counter",
			"tbl_dvd","tbl_kategori",
			"tbl_penjualan","tbl_penjualan_detail",
			"tbl_tiki","tbl_tarif",
			"tbl_pengiriman",
			"tbl_keluhan",
		));
		return false;
	}
	
	// @info	: Extra Sub Header Content for JS & CSS
	// @access	: private
	// @params	: null
	// @return	: array	
	private function _content_init()
	{		
		$arrayCSS = array (
			//'asset/src/jQuery/themes/ui-darkness/jquery.ui.all.css',
			'asset/src/jQuery/plugins/form/autocomplete/jquery.autocomplete.css',
		);
		
		$arrayJS = array (
			//'asset/src/jQuery/core/jquery-1.5.1.js',
			//'asset/src/jQuery/ui/jquery-ui-1.8.11.custom.js',
			'asset/src/jQuery/plugins/form/autocomplete/jquery.autocomplete.js',
			'asset/src/jQuery/plugins/form/jquery.form.js',
			'asset/src/jQuery/plugins/form/ajaxupload/ajaxupload.js',
			// HELPER
			'asset/src/jQuery/helper/dialog.js',
			'asset/src/jQuery/helper/validasi.js',
			'asset/src/jQuery/general/tabs.js',
			// ADDITIONAL
			
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
		
		// BIND OPTIONAL JS HERE =========>
		$content .= "";
		
		$output['extraSubHeadContent'] = $content;
		
		return $output;
	}
	
	// @info	: Load public variable
	// @access	: private
	// @params	: null
	// @return	: array
	private function _public_init()
	{
		// public variable
		self::$link_controller = 'mod_tracking/tracking_complain';
		self::$link_view = 'mod_tracking/tracking_complain';
		$output['link_view'] = self::$link_view;
		$output['link_controller'] = self::$link_controller;	
		
		return $output;
	}
	
	// @info	: Load local variable
	// @access	: private
	// @params	: null
	// @return	: array
	private function _variable_init()
	{
		// private variable
		$this->beli_session = $this->session->userdata('beli_session');
		$this->harga_dvd	= $this->config->item('harga_dvd');
		
		// view variable
		$output['content_title'] = "LIST DVD";	
		return $output;
	}
	
	// @info	: Indexing
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index() 
	{	
		$output[''] = '';
		$this->load->view(self::$link_view.'/complain_main_view',$output);
	}
	
	function get_data()
	{
		$kirim_id = $this->input->get_post('kirim_id');
		$SQL = "
			select * {COUNT_STR}
			from custserv_comp as c
			left join master_dvd as md on md.dvd_id = c.dvd_id
			where c.kirim_id = $kirim_id
		";
		
		$rs = $this->jqgrid_model->get_data_query($SQL,'c.csc_id');	
		
		echo json_encode($rs);
	}
	
	function daftar_complain($kirim_id)
	{
		$SQL = "
			select md.dvd_id, md.dvd_nama
			from pengiriman as p
			inner join penjualan_detail as pd on pd.jual_id = p.jual_id
			inner join master_dvd as md on md.dvd_id = pd.dvd_id
			where p.kirim_id = $kirim_id
			order by md.dvd_nama
		";
		
		$output['kirim_id'] = $kirim_id;
		$output['daftar_dvd'] = $this->db->query($SQL);
		$this->load->view(self::$link_view.'/complain_main_view',$output);
	}
	
	function list_complain($kirim_id)
	{
		$SQL = "
			select *
			from custserv_comp as cp
			left join master_dvd as md on md.dvd_id = cp.dvd_id
			where cp.kirim_id = $kirim_id
		";
		
		$output['kirim_id'] = $kirim_id;
		$output['daftar_cs'] = $this->db->query($SQL);
		$this->load->view(self::$link_view.'/complain_list_view',$output);
	}
	
	function simpan_keluhan()
	{
		$kirim_id = $this->input->post('kirim_id');
		$dvd_id = $this->input->post('dvd_id');
		$csc_lampiran = $this->input->post('csc_lampiran');
		
		$this->lib_files->copy_image($csc_lampiran,'cs');
		$this->lib_files->delete_image($csc_lampiran);
		
		$data = $_POST;
		
		unset($data['csc_lampiran_awal']);
		
		if ($this->tbl_keluhan->tambah_keluhan($data)):
			echo 'sukses';
		endif;
	}
	
	function edit_json($csc_id)
	{
		$gdata = $this->tbl_keluhan->data_keluhan(array('csc_id'=>$csc_id));
		if ($gdata->num_rows() > 0)
			echo json_encode($gdata->result_array());
	}
	
	function edit_keluhan($csc_id)
	{
		$kirim_id = $this->input->post('kirim_id');
		$dvd_id = $this->input->post('dvd_id');
		$csc_lampiran = $this->input->post('csc_lampiran');
		$csc_lampiran_awal = $this->input->post('csc_lampiran_awal');
		
		if ($csc_lampiran != $csc_lampiran_awal):
			$this->lib_files->copy_image($csc_lampiran,'cs');
			$this->lib_files->delete_image($csc_lampiran);
		endif;
		
		$data = $_POST;
		
		unset($data['csc_lampiran_awal']);
		
		$data = $_POST;
		$this->tbl_keluhan->ubah_keluhan(array('csc_id'=>$csc_id),$data);
		echo 'sukses';
	}
	
	function hapus_keluhan($csc_id)
	{		
		$SQL = "select csc_lampiran from custserv_comp where csc_id = $csc_id";
		$gambar = $this->db->query($SQL)->row()->csc_lampiran;
		
		$this->lib_files->delete_image($gambar,'cs');
		$this->tbl_keluhan->hapus_keluhan(array('csc_id'=>$csc_id));
		echo 'sukses';
	}
	
	function ajaxupload()
	{
		if ($file_name = $this->lib_files->upload_ajax())
			echo $file_name;
	}
	
	function show_photo($filename,$folder = 'temp') {
		echo $this->lib_pictures->thumbs_ajax($filename,150,150,'uploaded/'.$folder.'/');
	}
}

/* End of file app_init.php */
/* Location: ./app-imp/controllers/app_init.php */