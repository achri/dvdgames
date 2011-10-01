<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

/*
 @author		Achri
 @date creation	
 @library
	- 
 @comment
	- 
*/

class Tbl_penjualan extends CI_Model
{
	private $CI;
	function __construct() 
	{
		parent::__construct();
		$this->load->database();
		$this->CI =& get_instance();
	}
	
	function data_penjualan($where=FALSE,$like=FALSE,$select=FALSE,$order=FALSE,$limit=FALSE,$from=FALSE,$join=FALSE,$group=FALSE)
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
			$this->db->like('jual_tgl',$like,'after');
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
			$this->db->order_by('jual_tgl',$order);
		endif;
		
		if (is_array($group)):
			foreach ($group as $grup=>$field):
				$this->db->group_by($field,$grup);
			endforeach;
		elseif ($group):
			$this->db->group_by('jual_id',$order);
		endif;
		
		if (!empty($from)):
			$ret = $this->db->get();
		else:
			$ret = $this->db->get('penjualan');
		endif;
		
		return $ret;
	}
	
	function kode_penjualan($jual_id,$parse=3) 
	{	
		
		$this->db->select_max('jual_no','nomor');
		$get_nomor = $this->db->get('penjualan')->row();
		
		$numcode = $get_nomor->nomor;
		$numcode = substr($numcode,4,3);
		if ($numcode == 0)
			$numcode = 0;
		$numcode++;
		
		$numcode = str_pad($numcode, $parse, "0", STR_PAD_LEFT);
				
		$jual_kode = $numcode;
			
		return $jual_kode;
	}
	
	function tambah_penjualan($data)
	{
		return $this->db->insert('penjualan',$data);
	}
	
	function ubah_penjualan($where,$data)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('jual_id',$where);
		endif;
		return $this->db->update('penjualan',$data);
	}
	
	function hapus_penjualan($where)
	{
		if (is_array($where)):
			foreach ($where as $field=>$value):
				$this->db->where($field,$value);
			endforeach;
		elseif ($where):
			$this->db->where('jual_id',$where);
		endif;
		return $this->db->delete('penjualan');
	}
	
}


/* End of file .php */
/* Location: ./../.php */