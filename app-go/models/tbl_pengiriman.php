<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

class Tbl_pengiriman extends CI_Model {

	function __construct() 
	{
		parent::__construct();
	}	
	
	function data_pengiriman($where=false)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where != false):
			$this->db->where("kirim_id",$where);
		endif;
		$this->db->order_by('kirim_id');
		return $this->db->get("pengiriman");
	}
	
	function tambah_pengiriman($data)
	{
		return $this->db->insert("pengiriman",$data);
	}
	
	function hapus_pengiriman($kirim_id)
	{
		$this->db->where("kirim_id",$kirim_id);
		return $this->db->delete("pengiriman");
	}
	
	function ubah_pengiriman($where,$data)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where != false):
			$this->db->where("kirim_id",$where);
		endif;
		
		return $this->db->update("pengiriman",$data);
	}

}