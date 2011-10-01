<?php

class Tbl_sys_counter extends CI_Model
{
	function __construct()
	{
		
	}
	
	function get_nomor($where,$field)
	{	
		if (is_array($where)):
			foreach ($where as $sfield=>$svalue):
				$this->db->where($sfield,$svalue);
			endforeach;
		endif;
		
		$get_counter = $this->db->get('sys_counter');
	
		if ($get_counter->num_rows() > 0):
			$get_no = $get_counter->row_array();
			$set_no = $get_no[$field];
		else:
			$set_no  = 1;
			$dsys['tahun'] = date("Y");
			$dsys['bulan'] = date("n");
			$this->db->insert('sys_counter',$dsys);
		endif;
		
		$ret_no = $set_no;
		
		$usys_no = 1;
		$usys_no += $set_no;
		
		if (is_array($where)):
			foreach ($where as $sfield=>$svalue):
				$this->db->where($sfield,$svalue);
			endforeach;
		endif;
		
		$usys[$field] = $usys_no;
		
		$this->db->update('sys_counter',$usys);
		return $ret_no;
	}
}