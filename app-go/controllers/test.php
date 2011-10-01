<?php
class Test extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('inflector');
	}
	
	function index() {
		// GET REVISI.TXT
		if (file_exists('list/dvdgames.txt')) {
			$get = file('list/dvdgames.txt');
			
			foreach ($get as $num => $data):
				$cek = explode(',,',$data);
				$a = sizeOf($cek);
				
				// DVD
				$arrLine = explode(',',$cek[0]);
				
				$insert['ldvd_nama']   = humanize($arrLine[1]);
				$insert['ldvd_jumlah'] = $arrLine[2];
				$kat = $arrLine[3];
				$SQL = "select lkat_id from master_listkat where lkat_nama = '".$kat."'";
				$gkat = $this->db->query($SQL);
				if ($gkat->num_rows() > 0)
					$insert['lkat_id'] = $gkat->row()->lkat_id;		
				$this->db->insert('master_listdvd',$insert);
				
				// CD
				if (sizeOf($cek) == 2):
					$arrLines = explode(',',$cek[1]);
					
					$inserts['lcd_nama']   = humanize($arrLines[0]);
					$inserts['lcd_jumlah'] = $arrLines[1];
					$kats = $arrLines[2];
					$SQLy = "select lkat_id from master_listkat where lkat_nama = '".$kats."'";
					$gkats = $this->db->query($SQLy);
					if ($gkats->num_rows() > 0)
						$inserts['lkat_id'] = $gkats->row()->lkat_id;		
					$this->db->insert('master_listcd',$inserts);
				endif;
				
				/*
				// KATEGORI
				$kat = $arrLine[3];
				$SQL = "select * from master_listkat where lkat_nama = '".$kat."'";
				if ($this->db->query($SQL)->num_rows == 0)
					$this->db->insert('master_listkat',array('lkat_nama'=>$kat));
				*/
				
				
			endforeach;
		}
	}
}