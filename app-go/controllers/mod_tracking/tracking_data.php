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

class Tracking_data extends GO_Controller {
	// public variable
	public static $link_controller, $link_controller_cs, $link_view;
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
			"lib_pictures",
		));
		$this->load->model(array(
			"jqgrid_model","tbl_sys_counter",
			"tbl_dvd","tbl_kategori",
			"tbl_penjualan","tbl_penjualan_detail",
			"tbl_tiki","tbl_tarif",
			"tbl_pengiriman",
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
		self::$link_controller = 'mod_tracking/tracking_data';
		self::$link_controller_cs = 'mod_tracking/tracking_complain';
		self::$link_view = 'mod_tracking/tracking_data';
		$output['link_view'] = self::$link_view;
		$output['link_controller'] = self::$link_controller;	
		$output['link_controller_cs'] = self::$link_controller_cs;	
		
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
	
	// @info	: Populate data json to grid
	// @access	: public
	// @params	: POST string
	// @params	: string	$kat_id
	// @return	: JSON array string
	
	public function get_data($type='trace')
	{
		if ($type=='trace'):
			$arrSQL = array();
			$email = $this->input->get_post('email');
			$jual_no= $this->input->get_post('jual_no');
			$resi_no= $this->input->get_post('resi_no');
			
			$SQL = "
				select 
				(bonus * $this->harga_dvd)as sub_bonus,
				(select sum(harga) from penjualan_detail where jual_id = p.jual_id)as sub_total,
				p.jual_tgl,p.jual_id,p.jual_trace,p.jual_no,p.email,p.tot_jumlah,p.tot_dvd,p.bonus,p.tot_harga,p.status,
				k.kirim_id,k.alamat,k.total_biaya,k.no_resi,
				t.tarif_hrg,t.tarif_cas,tk.tiki_wilayah,tkp.paket_nama,
				(
					select count(csc_id)
					from custserv_comp
					where kirim_id = k.kirim_id
				)as keluhan
				{COUNT_STR}
				from pengiriman as k
				inner join master_tarif as t on t.tarif_id = k.tarif_id
				inner join master_tiki as tk on tk.tiki_id = t.tujuan
				inner join master_tiki_paket as tkp on tkp.paket_id = t.paket_id
				inner join penjualan as p on p.jual_id = k.jual_id and p.status != 0
			";
			
			if ($email)
				$arrSQL[] = "p.email = '".$email."'";
			if ($jual_no)
				$arrSQL[] = "p.jual_no = '".$jual_no."'";
			if ($resi_no)
				$arrSQL[] = "k.no_resi = '".$resi_no."'";
			
			if (sizeOf($arrSQL) > 0)
				$SQL .= "Where ".implode(' OR ',$arrSQL);				
			
			//$SQL = "select * {COUNT_STR} from pengiriman as k inner join penjualan as p on p.jual_id = k.jual_id";
			$rs = $this->jqgrid_model->get_data_query($SQL,'k.kirim_id');	
		else:
			$jual_id = $this->input->get_post('sjual_id');
			$table = "penjualan_detail";		
			$rs = $this->jqgrid_model->get_data($table,FALSE,false,FALSE,array(
				array(
					'table'=>'penjualan',
					'join'=>'penjualan.jual_id = '.$table.'.jual_id AND penjualan.jual_id = '.$jual_id,
					'fields'=>array('session_id'),
					'type'=>'INNER'
				),
				array(
					'table'=>'master_dvd',
					'join'=>'master_dvd.dvd_id = '.$table.'.dvd_id',
					'fields'=>array('kat_id','dvd_nama'),
					'type'=>'INNER'
				),
				array(
					'table'=>'master_kategori',
					'join'=>'master_kategori.kat_id = master_dvd.kat_id',
					'fields'=>array('kat_nama'),
					'type'=>'INNER'
				)
			),array(
				'*',
				//'(jumlah * dvd_jumlah * '.$this->harga_dvd.') as harga',
				// FOOTER ROW
				'(select
					sum(jumlah) 
					from penjualan_detail as dd
					where dd.jual_id = '.$table.'.jual_id
				) as udata_tot_jumlah',
				'(select
					sum(dvd_jumlah) 
					from penjualan_detail as dd
					inner join master_dvd as d on d.dvd_id = dd.dvd_id
					where dd.jual_id = '.$table.'.jual_id
				) as udata_tot_dvd_jumlah',
				'(select
					sum(jumlah * dvd.dvd_jumlah * '.$this->harga_dvd.') 
					from penjualan_detail as dd
					inner join master_dvd as dvd on dvd.dvd_id = dd.dvd_id
					where dd.jual_id = '.$table.'.jual_id
				) as udata_tot_harga',
			),FALSE,FALSE,
			array(
				'dvd_nama'=>'<div style="float:right">Total :&nbsp;</div>',
				'dvd_jumlah'=>'udata_tot_dvd_jumlah',
				'jumlah'=>'udata_tot_jumlah',
				'harga'=>'udata_tot_harga',
			));
			
			if (false == empty($result['raw_data']))
				unset($result['raw_data']);
		endif;
		echo json_encode($rs);
	}
	
	// @info	: Indexing
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index() 
	{	
		$output[''] = '';
		$this->load->view(self::$link_view.'/data_tabs_view',$output);
	}
	
	function trace_rt()
	{
		//$jual_no = $this->input->post('jual_no');
		//$no_resi = $this->input->post('no_resi');
		$email = $this->input->post('email');
		//$arr_SQL = array();
		
		$SQL = "
			select k.kirim_id
			from pengiriman as k
			inner join penjualan as p on p.jual_id = k.jual_id
			left join pengiriman_tiki as pk on pk.kirim_id = k.kirim_id
			where p.email = '".$email."'";
		
		/*if ($jual_no)
			$arr_SQL[] = "p.jual_no = ".$jual_no;
			
		if ($no_resi)
			$arr_SQL[] = "pk.no_resi = ".$no_resi;
		
		if (sizeOf($arr_SQL) > 0)
			$SQL .= ' AND '.implode(' AND ',$arr_SQL);
		*/
		$get_data = $this->db->query($SQL);
		if ($get_data->num_rows() > 0):
			$output['email'] = htmlspecialchars($email);
			$this->load->view(self::$link_view.'/data_grid_view',$output);
		else:
			echo '-- Tidak ada data --';
		endif;
	}
	
	function get_json($jual_id)
	{
		$SQL = "
		select *,
		(
		select sum(harga)
		from penjualan_detail
		where jual_id = k.jual_id
		)as tot_hrg
		from pengiriman as k
		inner join master_tarif as t on t.tarif_id = k.tarif_id
		inner join master_tiki as tk on tk.tiki_id = t.tujuan
		inner join master_tiki_paket as tkp on tkp.paket_id = t.paket_id
		inner join penjualan as p on p.jual_id = k.jual_id
		where k.jual_id = $jual_id
		";
		$get_data = $this->db->query($SQL);
		echo json_encode($get_data->result_array());
	}
	
	function cek_keluhan($kirim_id)
	{
		$SQL = "select count(csc_id) as num
		from custserv_comp 
		where kirim_id = $kirim_id
		";
		echo $this->db->query($SQL)->row()->num;
	}
}

/* End of file app_init.php */
/* Location: ./app-imp/controllers/app_init.php */