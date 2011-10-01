<?php if (!defined('BASEPATH')) exit('PERINGATAN !!! TIDAK BISA DIAKSES SECARA LANGSUNG ...'); 
class Lib_files {
	private $CI;
	
	function __construct() 
	{
		$this->CI =& get_instance();
		$this->CI->load->library(array('upload'));
		$this->CI->load->helper(array('file'));
	}
	
	// @info	: UPLOAD WITH CI AND RESIZE
	// @params	: $imgname = Desire name
	// @params	: $img_path= Target folder
	// @return	: STRING (File Name)
	function upload_this($imgname,$img_path) 
	{
		$thumb_folder	= $img_path;
		$temp_folder	= './uploaded/temp/';
		if ($CI->upload->do_upload()):
			$image = $CI->upload->data();
			$imgfile = $imgname.$image['file_ext'];
			$config['source_image'] = $temp_folder.$image['file_name'];
			$config['new_image'] = $thumb_folder.$imgfile ;
			$this->CI->load->library('image_lib',$config);
			if ($this->CI->image_lib->resize()):
				unlink($temp_folder.$image['file_name']);
				return $imgfile;
			endif;
		endif;
	}
	
	// @info	: UPLOAD FILE WITH AJAX
	// @params	: The second target folder 
	// @default	: Folder temp
	// @return	: STRING (File Name)
	function upload_ajax($folder='temp') {
		$target_folder	= 'uploaded/'.$folder.'/';
		
		$filebefore = $this->CI->input->post('gambar');
		$filename = basename($_FILES['userfile']['name']);
		
		$ext = strrchr($filename,'.');
		
		$rand = mktime();
		$md = md5($rand);
		$filename = substr($md,rand(0,strlen($md)-10),10).$ext;
		
		if ($filebefore != '')
			@unlink($target_folder.$filebefore);
		
		if (@move_uploaded_file($_FILES['userfile']['tmp_name'], $target_folder.$filename)) {
			return $filename;	
		} 
	}
	
	function copy_image($image,$folder='dvd') {
		$target_folder	= 'uploaded/'.$folder.'/';
		$source_folder	= 'uploaded/temp/';
		$config['image_library'] = 'gd2';
		$config['source_image'] = $source_folder.$image;
		$config['new_image'] = $target_folder.$image;
		//$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = 300;
		$config['height'] = 300;
		$this->CI->load->library('image_lib',$config);
		if ($this->CI->image_lib->resize()):
			unlink($source_folder.$image);
			return $image;
		endif;
	}
	
	function delete_image($image,$folder='temp') {
		$target_folder	= 'uploaded/'.$folder.'/';
		if ($image != ''):
			if (read_file($target_folder.$image)):
				unlink($target_folder.$image);
			endif;			
		endif;
		return $target_folder.$image;
	}
	
	function delete_all_images($folder='temp') {
		$target_folder	= 'uploaded/'.$folder.'/';
		$dir = directory_map($target_folder);
		foreach ($dir as $idx=>$file):
			unlink($target_folder.$file);
		endforeach;
	}

}
?>