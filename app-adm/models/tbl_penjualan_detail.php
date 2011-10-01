<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @library
	- 
 @comment
	- 
*/

class Tbl_penjualan_detail extends CI_Model
{
	private $CI;
	function __construct() 
	{
		parent::__construct();
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function data_penjualan_detail($where=FALSE,$like=FALSE,$select=FALSE,$order=FALSE,$limit=FALSE,$from=FALSE,$join=FALSE)
	{
		if (!empty($select)):
			$this->db->select($select);
		endif;
		
		if (!empty($from)):
			$this->db->from($from);
		endif;
		
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('jual_id',$where);
		endif;
		
		if (is_array($like)):
			foreach ($like as $field=>$value):
				if (is_array($value)):
					foreach ($value as $type=>$value):
						$this->db->like($field,$value,$type);
					endforeach;
				else:
					$this->db->like($field,$value,'after');
				endif;
			endforeach;
		elseif ($like):
			$this->db->like('jual_id',$like,'after');
		endif;
		
		if (is_array($join)):
			foreach ($join as $field=>$value):
				if (is_array($value)):
					foreach ($value as $type=>$value):
						$this->db->join($field,$value,$type);
					endforeach;
				else:
					$this->db->join($field,$value);
				endif;
			endforeach;
		endif;
		
		if (is_array($order)):
			foreach ($order as $sort=>$field):
				$this->db->order_by($field,$sort);
			endforeach;
		elseif ($order):
			$this->db->order_by('jual_id',$order);
		endif;
		
		if (!empty($from)):
			$ret = $this->db->get();
		else:
			$ret = $this->db->get('penjualan_detail');
		endif;
		
		return $ret;
	}
	
	function tambah_penjualan_detail($data)
	{
		return $this->db->insert('penjualan_detail',$data);
	}
	
	function ubah_penjualan_detail($where,$data)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('jual_id',$where);
		endif;
		return $this->db->update('penjualan_detail',$data);
	}
	
	function hapus_penjualan_detail($where)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('jual_id',$where);
		endif;
		return $this->db->delete('penjualan_detail');
	}
}


/* End of file .php */
/* Location: ./../.php */