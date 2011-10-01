<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 @author		Achri
 @date creation	
 @model
	- 
 @view
	- 
 @library
    - JS		
    - PHP
 @comment
	- 
*/

class GO_Controller extends CI_Controller {

	function __construct() 
	{
		parent::__construct();
		$GO =& get_instance();
		
		if ($GO->session):
			$beli_session = $GO->session->userdata('session_id');
			$get_beli_session = $GO->session->userdata('beli_session');
			if (isset($get_beli_session))
				$GO->session->set_userdata(array('beli_session'=>$beli_session));
		endif;
			
		// HAPUS DATA PENJUALAN YG EXPIRED 3 HARI
		$GO->load->library(array("lib_sweeping"));
		$GO->lib_sweeping->cek_expired();
	}
}

/* End of file Security.php */
/* Location: ./application/libraries/security.php */