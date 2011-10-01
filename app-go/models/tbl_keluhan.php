<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @library
	- 
 @comment
	- 
*/

class Tbl_keluhan extends CI_Model
{
	private $CI;
	function __construct() 
	{
		parent::__construct();
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function data_keluhan($where=FALSE,$like=FALSE,$select=FALSE)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('csc_id',$where);
		endif;
		
		$this->db->order_by('csc_complain');
		
		return $this->db->get('custserv_comp');
	}

	function tambah_keluhan($data)
	{
		return $this->db->insert('custserv_comp',$data);
	}
	
	function ubah_keluhan($where,$data)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('csc_id',$where);
		endif;
		return $this->db->update('custserv_comp',$data);
	}
	
	function hapus_keluhan($where)
	{
		if (FALSE === empty($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('csc_id',$where);
		endif;
		return $this->db->delete('custserv_comp');
	}
}


/* End of file .php */
/* Location: ./../.php */