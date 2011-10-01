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

class List_dvd extends GO_Controller {
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
			"text",
		));
		$this->load->library(array(
			"lib_pictures","pagination",
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
		$arrayCSS = array (
		);
		
		$arrayJS = array (
			'asset/js/dvd.js',
			//'asset/src/jQuery/helper/dialog.js',
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
		self::$link_controller = 'mod_list/list_dvd';
		self::$link_view = 'mod_list/list_dvd';
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
		$output['daftar_kategori'] = $this->tbl_kategori->data_kategori();
		
		return $output;
	}
	
	// @info	: Load local variable
	// @access	: private
	// @params	: null
	// @return	: array
	function _variable_init()
	{
		// private variable
		$this->harga_dvd = $this->config->item('harga_dvd');
		$this->beli_session = $this->session->userdata('beli_session');
		
		// view variable
		$output['content_title'] = "LIST DVD";		
		
		return $output;
	}
	
	// @info	: Indexing Layout
	// @access	: public
	// @params	: null
	// @return	: [object]
	function index($kat_id = false, $dvd_nama=false) 
	{
		if ($kat_id) $output['kat_id'] = $kat_id; 
		else $output['kat_id'] = 0; 
		
		if ($dvd_nama) $output['dvd_nama'] = $dvd_nama; 
		else $output['dvd_nama'] = ''; 
		
		$this->load->view(self::$link_view.'/dvd_main_view',$output);
	}
	
	function _pagination($kat_id,$dvd_nama,$page = 1,$pos = 8)
	{		
		$count = $this->list_dvd($kat_id,false,false,$dvd_nama,'all')->num_rows();
		
		if( $count > 0 )
            $total_pages = ceil($count/$pos);
        else
            $total_pages = 0;
		
        if ($page > $total_pages)
            $page=$total_pages;

        $limitstart = $pos*$page - $pos; 
        $limitstart = ($limitstart < 0)?0:$limitstart;

        if( !isset($limitstart) || $limitstart == '' )
			$limitstart = 0;
			
        if( isset($limitstart) && !empty($pos) )
			$pos = $limitstart;
		
		$ret['count'] = $count;
		$ret['page'] = $page;
		$ret['pos'] = $pos;
		return $ret;
	}
	
	function daftar_dvd()
	{		
		$kat_id = $this->input->post('kat_id');
		$dvd_nama = $this->input->post('dvd_nama');
		$page = $this->input->post('page');
		
		if (empty($kat_id)):
			$kat_id = 0;
		endif;
	
		if (empty($dvd_nama)):
			$dvd_nama = '';
		endif;
		
		if (empty($page)):
			$page = 1;
		endif;
		
		$output['kat_id'] = $kat_id;
		$output['dvd_nama'] = $dvd_nama;
		
		$output += $this->_pagination($kat_id,$dvd_nama,$page,8);
		$pos = $output['pos'];
		
		$tot_page = ceil($output['count'] / 8);
			
		$output['tot_page'] = $tot_page;
		
		$output['list_dvd'] = $this->list_dvd($kat_id,false,false,$dvd_nama,$pos);
		$this->load->view(self::$link_view.'/dvd_list_view',$output);
	}
	
	function list_dvd($kat_id = false,$dvd_id = false,$cart = true,$like=false,$pos = false)
	{	
		if (!$dvd_id)
			$arrwhere[] = "
				md.dvd_id not in (
					select dvd_id from penjualan_detail as pjd
					inner join penjualan as pj on pj.jual_id = pjd.jual_id
					where pj.session_id = '{$this->beli_session}' AND pj.status = 0
				)
			";
		
		if ($kat_id)
			$arrwhere[] = "md.kat_id = $kat_id";
		if ($dvd_id)
			$arrwhere[] = "md.dvd_id = $dvd_id";
		if ($like)
			$arrwhere[] = "md.dvd_nama LIKE '%$like%'";
			
		$where = implode(' and ',$arrwhere);
		
		$SQL = "
			select * 
			from master_kategori as mk
			inner join master_dvd as md on md.kat_id = mk.kat_id
			where $where 
			order by md.dvd_nama
		";
		
		if (!$dvd_id)
			if ($pos != 'all')
				$SQL .= "limit $pos,8";
			elseif ($pos == false)
				$SQL .= "limit 0,8";
			
		return $this->db->query($SQL);
	}
	
	function detail_dvd($dvd_id,$cart=false) {
		$output['det_dvd'] = $this->list_dvd(false,$dvd_id,$cart);
		$this->load->view(self::$link_view.'/dvd_detail_view',$output);
	}
	
	function tambah_penjualan($dvd_id,$kat_id) {
		$cek_it = true;
		// GET DATA DVD
		$wdvd['dvd_id'] = $dvd_id;
		$get_dvd = $this->tbl_dvd->data_dvd($wdvd);
		$jml_dvd = $get_dvd->row()->dvd_jumlah;
		
		// CEK SESSION BELI
		$wjual['session_id'] = $this->beli_session;
		$cek_wait = $this->tbl_penjualan->data_penjualan($wjual);
		if ($cek_wait->num_rows() > 0)
			if ($cek_wait->row()->status > 0)
				$cek_it = false;
		
		if ($cek_it):
			$wjual['status'] = 0;
			$cek_jual = $this->tbl_penjualan->data_penjualan($wjual);
			if ($cek_jual->num_rows() <= 0):
				$djual['jual_tgl'] = date('Y-m-d H:i:s');
				$djual['session_id'] = $this->beli_session;
				$this->tbl_penjualan->tambah_penjualan($djual);
				$jual_id = $this->db->insert_ID();
			else:
				$jual_id = $cek_jual->row()->jual_id;
			endif;
			
			$wjdet['jual_id'] = $jual_id;
			$wjdet['dvd_id'] = $dvd_id;
			$cek_item = $this->tbl_penjualan_detail->data_penjualan_detail($wjdet);
			if ($cek_item->num_rows() <= 0):
				$wjdet['kat_id'] = $kat_id;
				$djdet['jumlah'] = 1;
				$djdet['harga'] = $jml_dvd * $this->harga_dvd * 1;
				$djdet = array_merge($wjdet,$djdet);
				if ($this->tbl_penjualan_detail->tambah_penjualan_detail($djdet)):
					echo "sukses";
				endif;
			endif;
		else:
			echo "wait";
		endif;
	}
	
	function hapus_penjualan($dvd_id) {
		$wjual['session_id'] = $this->beli_session;
		$wjual['status'] = 0;
		$cek_jual = $this->tbl_penjualan->data_penjualan($wjual);
		if ($cek_jual->num_rows() > 0):
			$wjdet['jual_id'] = $cek_jual->row()->jual_id;
			$wjdet['dvd_id'] = $dvd_id;
			$this->tbl_penjualan_detail->hapus_penjualan_detail($wjdet);
			
			unset($wjdet['dvd_id']);
			$cek_jual_detail = $this->tbl_penjualan_detail->data_penjualan_detail($wjdet);
			if ($cek_jual_detail->num_rows() <= 0)
				$this->tbl_penjualan->hapus_penjualan($wjual);
				
			echo "sukses";
		endif;
	}
	
	/*
		CART
	*/
	
	function list_cart()
	{
		$from = 'penjualan_detail as pd';
		$join['penjualan as p']['inner'] = 'p.jual_id = pd.jual_id and p.session_id = "'.$this->beli_session.'" and p.status = 0';
		$join['master_dvd as d']['inner'] = 'd.dvd_id = pd.dvd_id';
		$order['ASC'] = 'pd.dvd_id';
		$output['list_cart'] = $this->tbl_penjualan->data_penjualan(false,false,false,$order,false,$from,$join);
		$this->load->view(self::$link_view.'/dvd_cart_view',$output);
	}
	
	function add_toCart($dvd_id)
	{
		$SQL = "select * from master_dvd where dvd_id = $dvd_id";
		$output['rcart'] = $this->db->query($SQL)->row();
		$this->load->view(self::$link_view.'/dvd_item_view',$output);
	}
	
	/*
		BEST DVD
	*/
	
	function list_bestdvd()
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
		
		$output['list_bestdvd'] = $this->db->query($SQL);
		
		//echo json_encode($output['list_bestdvd']->result_array());
		$this->load->view(self::$link_view.'/dvd_bestdvd_view',$output);
		
	}
	
}

/* End of file app_init.php */
/* Location: ./app-imp/controllers/app_init.php */