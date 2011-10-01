<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Routine
{
	var $go_time_order_expired	= 1000;
	var $go_order_expired		= 1000;
	var $GO;
	var $now;
	
	function __construct()
	{
		$this->GO =& get_instance();
		
		foreach (array('go_time_order_expaired','go_order_expired') as $key)
		{
			$this->$key = $this->GO->config->item($key,'go');
		}
		
		$this->now = $this->_get_time();
		
		$this->_order_expired();
		
	}
	
	function _get_time()
	{
		if (strtolower($this->time_reference) == 'gmt')
		{
			$now = time();
			$time = mktime(gmdate("H", $now), gmdate("i", $now), gmdate("s", $now), gmdate("m", $now), gmdate("d", $now), gmdate("Y", $now));
		}
		else
		{
			$time = time();
		}

		return $time;
	}
	
	function _order_expired()
	{
		if ($this->go_order_expired == 0)
		{
			return;
		}

		srand(time());
		if ((rand() % 100) < $this->go_time_order_expired)
		{
			$expire = $this->now - $this->go_order_expired;

			$this->CI->db->where("akses_terakhir < {$expire}");
			$this->CI->db->delete("penjualan");
		}
	}
}