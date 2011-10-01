<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

class Tbl_tarif extends CI_Model {

	function __construct() 
	{
		parent::__construct();
	}	
	
	function data_tarif($where=false)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where != false):
			$this->db->where("tarif_id",$where);
		endif;
		$this->db->order_by('tarif_id');
		return $this->db->get("master_tarif");
	}
	
	function tambah_tarif($data)
	{
		return $this->db->insert("master_tarif",$data);
	}
	
	function hapus_tarif($tarif_id)
	{
		$this->db->where("tarif_id",$tarif_id);
		return $this->db->delete("master_tarif");
	}
	
	function ubah_tarif($where,$data)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where != false):
			$this->db->where("tarif_id",$where);
		endif;
		
		return $this->db->update("master_tarif",$data);
	}

}