<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

class flexi_model extends CI_Model {
	function __construct() {
		parent::__construct();
		$CI =& get_instance();
	}
	
	function generate_sql($sql,$countField='id',$whereOrAnd = FALSE,$flexi_mode=TRUE,$resultText='result',$countText='count') {
		$CI =& get_instance();
		$sql_main = str_replace("{COUNT_STR}"," ",$sql);
		if ($flexi_mode):
			$sql_count = str_replace("{COUNT_STR}",",count($countField) as record_count",$sql);
			$querys['main_query'] = $sql_main;
			$querys['count_query'] = $sql_count;		
			$build_querys = $CI->flexigrid->build_querys($querys,$whereOrAnd); 
			$return[$resultText] = $this->db->query($build_querys['main_query']);
			$return[$countText]  = $this->db->query($build_querys['count_query'])->row()->record_count;
		else:
			$sql_main = str_replace("{SEARCH_STR}"," ",$sql_main);
			$return = $this->db->query($sql_main);
		endif;
		return $return; 
	}
}
?>