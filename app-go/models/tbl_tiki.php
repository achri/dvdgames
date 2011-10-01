<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

class Tbl_tiki extends CI_Model {

	function __construct() 
	{
		parent::__construct();
	}	
	
	function data_tiki($where=false)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where != false):
			$this->db->where("tiki_id",$where);
		endif;
		$this->db->order_by('tiki_wilayah');
		return $this->db->get("master_tiki");
	}
	
	function data_paket($where=false)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where != false):
			$this->db->where("paket_id",$where);
		endif;
		$this->db->order_by('paket_nama');
		return $this->db->get("master_tiki_paket");
	}
	
	function cek_kelas($tiki_master,$tiki_wilayah="") 
	{
		$this->db->select("tiki_id");
		if ($tiki_wilayah!="")
			$this->db->where("tiki_wilayah",$tiki_wilayah);
		$this->db->where("tiki_master",$tiki_master);
		return $this->db->get("master_tiki")->num_rows();
	}
	
	function nomor_tiki($tiki_master) {
		$this->db->select_max('tiki_kode','nomor');
		$this->db->where('tiki_master',$tiki_master);
		$query = $this->db->get('master_tiki');
		$query_rows = $query->row();
		return $query_rows->nomor;
	}
	
	function tambah_tiki($data)
	{
		return $this->db->insert("master_tiki",$data);
	}
	
	function hapus_tiki($tiki_id)
	{
		$this->db->where("tiki_id",$tiki_id);
		return $this->db->delete("master_tiki");
	}
	
	function ubah_tiki($where,$data)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where != false):
			$this->db->where("tiki_id",$where);
		endif;
		
		return $this->db->update("master_tiki",$data);
	}

}