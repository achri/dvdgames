<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 

	function bilangRatusan($x)
	{
	   // function untuk membilang bilangan pada setiap kelompok
	 
	   $kata = array('', 'satu ', 'dua ', 'tiga ' , 'empat ', 'lima ', 'enam ', 'tujuh ', 'delapan ', 'sembilan ');
	 
	   $string = '';
	 
	   $ratusan = floor($x/100);
	   $x = $x % 100;
	   if ($ratusan > 1) $string .= $kata[$ratusan]."ratus "; // membentuk kata '... ratus'
	   else if ($ratusan == 1) $string .= "seratus "; // membentuk kata khusus 'seratus '
	 
	   $puluhan = floor($x/10);
	   $x = $x % 10;
	   if ($puluhan > 1)
	   {
		  $string .= $kata[$puluhan]."puluh "; // membentuk kata '... puluh'
		  $string .= $kata[$x]; // membentuk kata untuk satuan
	   }
	   else if (($puluhan == 1) && ($x == 1)) $string .= "sebelas ";
	   else if (($puluhan == 1) && ($x > 0)) $string .= $kata[$x]."belas "; // kejadian khusus untuk bilangan yang berbentuk kata '... belas'
	   
	   else if (($puluhan == 1) && ($x == 0)) $string .= $kata[$x]."sepuluh "; // kejadian khusus untuk bilangan 10 
	   else if ($puluhan == 0) $string .= $kata[$x];	 // membentuk kata untuk satuan	
	 
	   return $string;
	}
	 
	function terbilang($x,$digit = 0,$curr = 'rupiah')
	{
		// membentuk format bilangan XXX.XXX.XXX.XXX.XXX
		$x = number_format($x, $digit);
		 
		// memecah kelompok ribuan berdasarkan tanda ','
		$pecah = explode(",", $x);
		
		// memecah kelompok ribuan berdasarkan tanda '.'
		$desimal = explode(".", $x);
		 
		$string = "";
		 
		// membentuk format terbilang '... trilyun ... milyar ... juta ... ribu ...'
		//for($sel = 0; $sel <= 1; $sel++){
		//if ($sel == 0) {
		$set = $pecah;
		//}
		//else {$set = $desimal[1];}
		
		for($i = 0; $i <= count($set)-1; $i++)
		{
		   if ((count($set) - $i == 5) && ($set[$i] != 0)) $string .= bilangRatusan($set[$i])."triliyun "; // membentuk kata '... trilyun'
		   else if ((count($set) - $i == 4) && ($set[$i] != 0)) $string .= bilangRatusan($set[$i])."milyar "; // membentuk kata '... milyar'
		   else if ((count($set) - $i == 3) && ($set[$i] != 0)) $string .= bilangRatusan($set[$i])."juta "; // membentuk kata '... juta'
		   else if ((count($set) - $i == 2) && ($set[$i] == 1)) $string .= "seribu "; // kejadian khusus untuk bilangan dalam format 1XXX (yang mengandung kata 'seribu')
		   else if ((count($set) - $i == 2) && ($set[$i] != 0)) $string .= bilangRatusan($set[$i])."ribu "; // membentuk kata '... ribu'
		   else if ((count($set) - $i == 1) && ($set[$i] != 0)) $string .= bilangRatusan($set[$i]); 
		}
		
		$string .= ' '.$curr;
		
		//}
		return $string;
	}