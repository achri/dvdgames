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

class Master_order extends GOA_Controller {
	public static $link_view, $link_controller;
	
	private $harga_dvd;
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
			"datagrid","lib_files","lib_pictures","lib_mail",
		));
		$this->load->model(array(
			"metadata","dynatree_model","jqgrid_model",
			"tbl_penjualan","tbl_pengiriman",
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
		self::$link_controller = 'mod_master/master_order';
		self::$link_view = 'mod_master/master_order';
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
		$this->harga_dvd = $this->config->item('harga_dvd');
		// private variable
		$output['page_title'] = "Master Order";
		
		return $output;
	}
	
	// @info	: Populate data json to grid
	// @access	: public
	// @params	: POST string
	// @params	: string	$tiki_id
	// @return	: JSON array string
	public function get_data($type='trace')
	{
		if ($type=='trace'):
			/*$SQL = "
				select *,p.jual_id,p.jual_trace,p.jual_no,p.email,p.tot_jumlah,p.tot_harga,k.kirim_id,k.alamat,k.total_biaya,k.status,t.tarif_hrg,t.tarif_cas,mk.tiki_wilayah,mtp.paket_nama {COUNT_STR}
				from pengiriman as k
				inner join master_tarif as t on t.tarif_id = k.tarif_id
				inner join master_tiki as mk on mk.tiki_id = t.tujuan
				inner join master_tiki_paket as mtp on mtp.paket_id = t.paket_id
				inner join penjualan as p on p.jual_id = k.jual_id
				order by k.kirim_tgl desc
			";	
			$rs = $this->jqgrid_model->get_data_query($SQL,'k.kirim_id');	*/
			
			$table = "pengiriman";		
			$rs = $this->jqgrid_model->get_data($table,FALSE,FALSE,FALSE,array(
				array(
					'table'=>'master_tarif',
					'join'=>'master_tarif.tarif_id = '.$table.'.tarif_id',
					'fields'=>array('tarif_hrg','tarif_cas'),
					'type'=>'INNER'
				),
				array(
					'table'=>'master_tiki',
					'join'=>'master_tiki.tiki_id = master_tarif.tujuan',
					'fields'=>array('tiki_wilayah'),
					'type'=>'INNER'
				),
				array(
					'table'=>'master_tiki_paket',
					'join'=>'master_tiki_paket.paket_id = master_tarif.paket_id',
					'fields'=>array('paket_nama'),
					'type'=>'INNER'
				),
				array(
					'table'=>'penjualan',
					'join'=>'penjualan.jual_id = '.$table.'.jual_id',
					'fields'=>array('jual_tgl','jual_id','jual_trace','jual_no','email','tot_jumlah','tot_harga','status'),
					'type'=>'INNER'
				),
			),array(
				//'pengiriman.status',
				'pengiriman.kirim_id',
				'pengiriman.kirim_tgl',
				'pengiriman.total_biaya',
				'pengiriman.alamat',
				'pengiriman.no_resi',
			),FALSE,FALSE);
		else:
			$jual_id = $this->input->get_post('sjual_id');
			$table = "penjualan_detail";		
			$rs = $this->jqgrid_model->get_data($table,FALSE,FALSE,FALSE,array(
				array(
					'table'=>'penjualan',
					'join'=>'penjualan.jual_id = '.$table.'.jual_id AND penjualan.jual_id = '.$jual_id.' AND penjualan.status in (1,2,3,5)',
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
				'(jumlah * dvd_jumlah * '.$this->harga_dvd.') as harga',
				// FOOTER ROW
				'(select
					sum(jumlah) 
					from penjualan_detail as dd
					where dd.jual_id = '.$table.'.jual_id
				) as udata_tot_jumlah',
				'(select
					sum(jumlah * dvd.dvd_jumlah * '.$this->harga_dvd.') 
					from penjualan_detail as dd
					inner join master_dvd as dvd on dvd.dvd_id = dd.dvd_id
					where dd.jual_id = '.$table.'.jual_id
				) as udata_tot_harga',
			),FALSE,FALSE,
			array(
				'dvd_nama'=>'<div style="float:right">Total :&nbsp;</div>',
				'jumlah'=>'udata_tot_jumlah',
				'harga'=>'udata_tot_harga',
			));
			
		endif;
		
		if (false == empty($rs['raw_data']))
			unset($rs['raw_data']);
		echo json_encode($rs);
	}
	
	// @info	: Manipulate data from grid
	// @access	: public
	// @params	: POST string
	// @params	: string	$tiki_id
	// @return	: JSON array string
	public function set_data()
	{
		$id_field = "kirim_id";
		$table = "pengiriman";
		$this->jqgrid_model->set_data($table,$id_field);
		
		// COPY UPLOAD NO RESI
		$scan_resi = $this->input->get_post('scan_resi');
		$scan_resi_awal = $this->input->get_post('scan_resi_awal');
		if ($scan_resi_awal != '' && $scan_resi_awal != $scan_resi):
			$this->lib_files->delete_image($scan_resi_awal,'tiki');
			$this->lib_files->copy_image($scan_resi,'tiki');
			$this->lib_files->delete_image($scan_resi);
		elseif ($kategori_gambar_awal == ''):
			$this->lib_files->copy_image($scan_resi,'tiki');
			$this->lib_files->delete_image($scan_resi);
		endif;
		
		// UBAH DATA PENJUALAN
		if ($this->input->get_post('status') > 0):
			$where['jual_id'] = $this->input->get_post('jual_id');
			$data['session_id'] = '';
			$data['status'] = 3;
			$this->tbl_penjualan->ubah_penjualan($where,$data);
		endif;
	}
	
	// @info	: Indexing Layout
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index() {
		$output[''] = '';
		
		$this->load->view(self::$link_view."/order_main_view",$output);
	}
	
	function ubah_order()
	{
		// METADATA FIELDs TABLE
		$get_field = $this->metadata->list_field('pengiriman');
		echo json_encode($_POST);
		foreach ($_POST as $name=>$value):
			$value = trim($value);
			// SYNC POSTs AND FIELDs
			if ($value != '' && in_array($name,$get_field)):
				switch ($name):
					case 'scan_resi': 
						$scan_resi_awal = $this->input->post('scan_resi_awal');
						if ($scan_resi_awal != '' && $scan_resi_awal != $value):
							$this->lib_files->delete_image($kategori_gambar_awal,'tiki');
							$data[$name] = $this->lib_files->copy_image($value,'tiki');
							$this->lib_files->delete_image($value);
						elseif ($scan_resi_awal == ''):
							$data[$name] = $this->lib_files->copy_image($value,'tiki');
							$this->lib_files->delete_image($value);
						endif;
					break;
					default: $data[$name] = $value; break;
				endswitch;
			endif;
		endforeach;
		
		$where['kirim_id'] = $data['kirim_id'];
		$kirim_id = $data['kirim_id'];
		unset ($data['kirim_id']);
		
		$this->tbl_pengiriman->ubah_pengiriman($where,$data);
		
		$pdata['session_id'] = '';
		$pdata['status'] = 3;
		$pwhere['jual_id'] = $data['jual_id'];
		$this->tbl_penjualan->ubah_penjualan($pwhere,$pdata);
		
		// SEND MAIL
		$this->lib_mail->mail_resi($kirim_id);
		echo "sukses";

	}
	
	function ajaxupload()
	{
		if ($file_name = $this->lib_files->upload_ajax())
			echo $file_name;
	}
	
	function show_photo($filename,$folder = 'temp') {
		echo $this->lib_pictures->thumbs_ajax($filename,225,225,'uploaded/'.$folder.'/');
	}
	
	function test() {
		$this->lib_mail->mail_resi(2);
	}

}

/* End of file master_tiki.php */
/* Location: ./app/controllers/mod_master/master_tiki.php */