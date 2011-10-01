<?php
class Dynatree {
	public static $CI;
	function __construct() {
		self::$CI =& get_instance();
	}
	
	function generate_kategori_tipe() {
		$arrTipe = array('','menu','bumbu');
		$json = '[';
		
		for ($i = 1; $i < sizeOf($arrTipe); $i++):
			$json .= '{';
			$json .= '"title": "'.strtoupper($arrTipe[$i]).'"';
			$json .= ',"key": "tipe"';
			$json .= ',"tipe": "'.$arrTipe[$i].'"';
			$json .= ',"isFolder": true';
			$json .= ',"isLazy": true';
			$json .= '}';
			if ($i < (sizeOf($arrTipe)-1))
				$json .= ',';
		endfor;
		$json .= ']';
		return $json;
	}
	
	function generate_kategori_tree($kat_id,$kat_level=array('1','2','3')) {
		$qlevel = self::$CI->dynatree_model->set_kategori($kat_id,$kat_level);
		$maxs = $qlevel->num_rows();
		$row = 1;		
		$json = '[';
		foreach($qlevel->result() as $rows):
			$json .= '{';
			$json .= '"title": "'.htmlspecialchars($rows->kat_nama).'"';
			$json .= ',"key": "'.$rows->kat_id.'"';			
			$json .= ',"lvl": "'.$rows->kat_level.'"';
			
			$qlevel2 = self::$CI->dynatree_model->set_kategori($rows->kat_id,$kat_level);
			
			if ($qlevel2->num_rows() > 0):
				$json .= ',"isFolder": true';
				$json .= ',"isLazy": true';
			else:
				$json .= ',"isFolder": false';
				$json .= ',"isLazy": false';
			endif;
			
			$json .= ',"addClass": "edit_group"';
					
			$json .= '}';
			if ($row < $maxs):
				$json .= ',';
			endif;
			$row++;
		
		endforeach;
		$json .= ']';
		return $json;
	}
}
?>