<?php
class flexi_engine {
	function __construct() {
		$CI =& get_instance();
	}
	function flexi_params($width,$height,$rp,$title,$colresize = false,$tblresize = false,$button=false) {
		return $arr = array(
			'height'=> $height, //default height
			'width'=> $width, //auto width
			'striped'=> true, //apply odd even stripes
			'novstripe'=> false,
			'minwidth'=> $width, //min width of columns
			'minheight'=> $height, //min height of columns
			'resizable'=> $tblresize, //resizable table
			//'url'=> false, //ajax url
			'method'=> 'POST', // data sending method
			'dataType'=> 'json', // type of data loaded
			'errormsg'=> 'ERROR',
			'usepager'=> true, //
			'nowrap'=> false, //
			//'page'=> 1, //current page
			//'total'=> 1, //total pages
			'useRp'=> false, //use the results per page select box
			'rp'=> $rp, // results per page
			'rpOptions'=> '[5,8,10,15,20,25,30,40]',
			'title'=> $title,
			'pagestat'=> 'Ditampilkan: {from} sampai {to} dari total {total} data.',
			'procmsg'=> 'Sedang mempersiapkan ...',
			//'query'=> '',
			//'qtype'=> '',
			'nomsg'=> 'Data kosong',
			//'minColToggle'=> 1, //minimum allowed column to be hidden
			'showToggleBtn'=> $button, //show or hide column toggle popup
			'hideOnSubmit'=> false,
			'autoload'=> true,
			'blockOpacity'=> 0.5,
			'onToggleCol'=> false,
			'onChangeSort'=> false,
			'onSuccess'=> false,
			'onSubmit'=> false, // using a custom populate function
			'draggable'=> false, // drag column by @HR13 ^^
			'resizableCol'=> $colresize, // make column resizable by @HR13 ^^
			//'multisel'=> false,
			'singleSelect'=> true,
			'showButtons' => $button,
			'searchText' => 'Cari',
			'clearText' => 'Bersihkan',
			'pageText' => 'Halaman',
			'ofText' => 'dari',
			//'showSearch' => false
			//'onRowSelect' => 'function(e,r){alert(r[0].id);}'
		);
	}
	
}
?>