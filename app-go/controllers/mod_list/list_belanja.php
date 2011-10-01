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

class List_belanja extends GO_Controller {
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
			"text","rupiah","email"
		));
		$this->load->library(array(
			"lib_pictures","lib_sweeping","lib_mail",
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
		self::$link_controller = 'mod_list/list_belanja';
		self::$link_view = 'mod_list/list_belanja';
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
		$output['harga_dvd'] = $this->harga_dvd;
		return $output;
	}
	
	// @info	: Populate data json to grid
	// @access	: public
	// @params	: POST string
	// @params	: string	$kat_id
	// @return	: JSON array string
	
	public function get_data()
	{
		$table = "penjualan_detail";		
		$result = $this->jqgrid_model->get_data($table,FALSE,false,FALSE,array(
			array(
				'table'=>'penjualan',
				'join'=>'penjualan.jual_id = '.$table.'.jual_id AND penjualan.session_id = "'.$this->beli_session.'" AND penjualan.status = 0',
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
				sum(harga) 
				from penjualan_detail as dd
				where dd.jual_id = '.$table.'.jual_id
			) as udata_tot_harga',
			/*'(select
				sum(jumlah * dvd.dvd_jumlah * '.$this->harga_dvd.') 
				from penjualan_detail as dd
				inner join master_dvd as dvd on dvd.dvd_id = dd.dvd_id
				where dd.jual_id = '.$table.'.jual_id
			) as udata_tot_harga',*/
		),FALSE,FALSE,
		array(
			'dvd_nama'=>'<div style="float:right">Total :&nbsp;</div>',
			'dvd_jumlah'=>'udata_tot_dvd_jumlah',
			'jumlah'=>'udata_tot_jumlah',
			'harga'=>'udata_tot_harga',
		));
		
		if (false == empty($result['raw_data']))
			unset($result['raw_data']);
			
		echo json_encode($result);
	}
	
	// @info	: Manipulate data from grid
	// @access	: public
	// @params	: POST string
	// @return	: JSON array string
	public function set_data()
	{
		$id_field = "id";
		$table = "penjualan_detail";	
		
		$extra = false;
		
		$dvd_jumlah = $this->input->get_post('dvd_jumlah');
		$dvd_order = $this->input->get_post('jumlah');
		$dvd_harga = $this->harga_dvd;
		$extra['harga'] = ($dvd_order * $dvd_jumlah) * $dvd_harga;
		
		$this->jqgrid_model->set_data($table,$id_field,FALSE,FALSE,$extra);
	}
	
	// @info	: Indexing
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index() 
	{	
		// CEK JIKA MASIH ADA ORDER YG BELUM DI PROSES
		$where['session_id'] = $this->beli_session;
		$output['list_order'] = $this->tbl_penjualan->data_penjualan($where);
		$this->load->view(self::$link_view.'/belanja_main_view',$output);
	}
	
	function rincian_belanja()
	{
		$output[''] = '';
		$this->load->view(self::$link_view.'/belanja_grid_view',$output);
	}
	
	function proses_order($jual_id)
	{
		$wsys['tahun'] = date("Y");
		$wsys['bulan'] = date("n");
		$str_thn = date("y");
		$str_bln = date("m");
		
		// VALID EMAIL CAN BE ACCEPABLE
		$email = $this->input->post('email');
		if (!valid_email($email))
			//return false;
		
		$set_no = $this->tbl_sys_counter->get_nomor($wsys,'jual_no');
		
		$jual_no     =  str_pad($set_no, 4, "0", STR_PAD_LEFT);
		
		// TRACE NO PENJUALAN MELALUI HARGA PEMBELIAN PER HARI
		$get_trace_no = $this->tbl_penjualan->set_trace_no();
		
		// BELI NUMBER
		$str_jual_no =  $str_thn."/".$str_bln."/SL".$jual_no;
			
		$where['jual_id'] = $jual_id;
		$select = "
			sum(jumlah) as tot_jumlah, 
			(select
				sum(d.dvd_jumlah*dd.jumlah) 
				from penjualan_detail as dd
				inner join master_dvd as d on d.dvd_id = dd.dvd_id
				where dd.jual_id = ".$jual_id."
			) as tot_dvd,
			(select sum(pd.jumlah*dvd.dvd_jumlah*".$this->harga_dvd.")
			from penjualan_detail as pd
			inner join master_dvd as dvd on dvd.dvd_id = pd.dvd_id
			where pd.jual_id = ".$jual_id.") as tot_harga
		";
		$get_jual = $this->tbl_penjualan_detail->data_penjualan_detail($where,FALSE,$select)->row();
		
		// KALKULASI BONUS DVD
		$dvd_bonus = floor($get_jual->tot_dvd/5);
		$bonus_hrg = $this->harga_dvd * $dvd_bonus;
		
		$data['email'] = $email;
		$data['jual_no'] = $str_jual_no;
		$data['jual_trace'] = $get_trace_no['trace_no']; // TRACE NO PENJUALAN MELALUI HARGA PEMBELIAN PER HARI
		$data['tot_jumlah'] = $get_jual->tot_jumlah;
		$data['tot_dvd'] = $get_jual->tot_dvd;
		$data['bonus'] = $dvd_bonus;
		$data['tot_harga'] = $get_jual->tot_harga - $bonus_hrg;// + $get_trace_no['trace_str'];
		$data['status'] = 1;
		//$data['session_id'] = '';
		$this->tbl_penjualan->ubah_penjualan($where,$data);
		
		// HAPUS DATA PENJUALAN YANG SUDAH EXPIRED HARI SEBELUMNYA
		//$this->lib_sweeping->cek_expired();
		
		
		// SEND EMAIL
		//$this->lib_mail->mail_rincian($email);
		echo 'sukses';
	}
	
	function hapus_penjualan($dvd_id){
		// CEK CART
		$jual_id = $this->tbl_penjualan_detail->data_penjualan_detail(array('dvd_id'=>$dvd_id))->row()->jual_id;
		$max = $this->tbl_penjualan_detail->data_penjualan_detail(array('jual_id'=>$jual_id))->num_rows();
		if ($max > 1):
			$this->tbl_penjualan_detail->hapus_penjualan_detail(array('dvd_id'=>$dvd_id));
		else:
			$this->tbl_penjualan_detail->hapus_penjualan_detail(array('dvd_id'=>$dvd_id));
			$this->tbl_penjualan->hapus_penjualan(array('jual_id'=>$jual_id));
		endif;
		echo 'OK';
	}
	
	/* 
		FORM PENGIRIMAN
	*/
	
	function cek_tab_pengiriman() {
		$session_id = $this->beli_session;
		$SQL = '
			select *
			from pengiriman as k
			inner join penjualan as p on p.jual_id = k.jual_id and p.session_id = "'.$session_id.'"
		';
		if ($this->db->query($SQL)->num_rows() > 0)
			echo 'ada';
	}
	
	function rincian_pengiriman() {
		$where['session_id'] = $this->beli_session;
		$output['data_penjualan'] = $this->tbl_penjualan->data_penjualan($where);
		$output['tiki_paket'] = $this->tbl_tiki->data_paket();
		
		$output['harga_dvd'] = $this->harga_dvd;
		$this->load->view(self::$link_view.'/belanja_rincian_view',$output);
	
	}
	
	function proses_pengiriman() {
		$data['jual_id'] = $this->input->post('jual_id');
		$data['tarif_id'] = $this->input->post('tarif_id');
		$data['total_biaya'] = $this->input->post('total_biaya');
		$data['alamat'] = $this->input->post('alamat');
		//$data['status'] = 0;
		if ($this->tbl_pengiriman->tambah_pengiriman($data)):
			$where = $data['jual_id'];
			$pdata['status'] = 2;
			$this->tbl_penjualan->ubah_penjualan($where,$pdata);
			// SEND EMAIL
			$this->lib_mail->mail_rincian();
			echo 'sukses';
		endif;
	}
	
	function list_autocomplate() 
	{		
		$q = $this->input->get('q');
		$paket_id = $this->input->get('paket_id');
		
		// GET DATA PENJUALAN AND TRACE NO PENJUALAN
		$where['jual_id'] = $this->input->get('jual_id');
		$get_jual = $this->tbl_penjualan->data_penjualan($where)->row();
		$order_harga = $get_jual->tot_harga;
		
		//$digit = '0.'; // not required now
		$digit = '';
		$trace_no = $digit.str_pad($get_jual->jual_trace, 2, "0", STR_PAD_LEFT);
		
		$SQL = '
			select * from master_tarif as mtf
			inner join master_tiki as mti on mti.tiki_id = mtf.tujuan
		';
		$SQL .= 'where mti.tiki_wilayah like "' . $q . '%"';
			
		if ($paket_id != 0):
			$SQL .= ' AND mtf.paket_id = ' . $paket_id;
		endif;
		
		$qres = $this->db->query($SQL);
		
		if ($qres->num_rows() > 0):
			foreach ($qres->result() as $rows):
				$grand_total = $order_harga + $rows->tarif_hrg + $rows->tarif_cas + $trace_no;
				if (strpos(strtolower($rows->tiki_wilayah), strtolower($q)) !== false):
					echo $rows->tiki_wilayah."|".$rows->tarif_id."|Rp. ".number_format($rows->tarif_hrg,2)."|Rp. ".number_format($rows->tarif_cas,2)."|Rp. ".number_format($grand_total,2)."|".$grand_total."\n";
				endif;
			endforeach;
		endif;	
	}
	
	/*
		FORM PEMBAYARAN
	*/
	
	function rincian_pembayaran() {
		$session_id = $this->beli_session;
		$SQL = '
			select p.jual_trace,p.jual_no,p.email,p.tot_jumlah,p.tot_dvd,p.bonus,p.tot_harga,k.kirim_id,k.alamat,k.total_biaya,t.tarif_hrg,t.tarif_cas,tk.tiki_wilayah,tkp.paket_nama
			from pengiriman as k
			inner join master_tarif as t on t.tarif_id = k.tarif_id
			inner join master_tiki as tk on tk.tiki_id = t.tujuan
			inner join master_tiki_paket as tkp on tkp.paket_id = t.paket_id
			inner join penjualan as p on p.jual_id = k.jual_id and p.session_id = "'.$session_id.'"
		';		
		$output['data_pengiriman'] =  $this->db->query($SQL);
		$output['harga_dvd'] = $this->harga_dvd;
		$this->load->view(self::$link_view.'/belanja_konfirmasi_view',$output);
	}
	
	
}

/* End of file app_init.php */
/* Location: ./app-imp/controllers/app_init.php */