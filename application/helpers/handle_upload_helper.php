<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	
	function handle_upload($namafile,$path = '',$newfilename = "")
	{
		
		$CI =& get_instance();
		$max_size = 5000000;
		$max_width = 2048;
		$max_height = 2400;
		//die("masuk sini".$CI->settings['site.max_img_size']);
		if($path==""){
			$path=$CI->settings_lib->item('site.pathuploaded');	
		}
		$config['upload_path']		= $path;//$CI->settings_lib->item('site.pathuploaded');
		$config['allowed_types']	= 'png|jpg|jpeg|image/jpeg|image/JPEG|JPEG|image/pjpeg|jpeg|image/png|image/PNG|image/JPG|image/x-png|jpe|xls|xlsx|doc|docx|pdf';
		//$config['allowed_types']	= '*';
		if($newfilename!= ""){
		$config['file_name']		= $newfilename;
		}
		$config['max_size']			= intval($max_size);
		$config['max_width']		= intval($max_width);
		$config['max_height']		= intval($max_height);

		$CI->load->library('upload', $config);
		 
		if ( ! $CI->upload->do_upload($namafile))
		{
			 //die("masuk");
			return array('error'=>$CI->upload->display_errors());
		}
		else
		{
			$data = $CI->upload->data();
			$max_width		= intval($max_width);
			$max_height 	= intval($max_height);
			$img_width 		= intval($data['image_width']);
			$img_height 	= intval($data['image_height']);
			 
			return array('data'=>$data);
		}
	}
	function handle_video($namafile,$path = '')
	{
		
		$CI =& get_instance();
		$max_size = 50000000;
		$max_width = 1024;
		$max_height = 1000;
		//die("masuk sini".$CI->settings['site.max_img_size']);
		if($path==""){
			$path=$CI->settings_lib->item('site.pathuploaded');	
		}
		$config['upload_path']		= $path;//$CI->settings_lib->item('site.pathuploaded');
		$config['allowed_types']	= '*';//xls|xlsx|gif|png|jpg|jpeg|mp3|ppt|pptx|zip|pdf|application/vnd.openxmlformats-officedocument.presentationml.presentation|docx
		
		$config['max_size']			= intval($max_size);
		$config['max_width']		= intval($max_width);
		$config['max_height']		= intval($max_height);

		$CI->load->library('upload', $config);
		 
		if ( ! $CI->upload->do_upload($namafile))
		{
			 //die("masuk");
			return array('error'=>$CI->upload->display_errors());
		}
		else
		{
			$data = $CI->upload->data();
			$max_width		= intval($max_width);
			$max_height 	= intval($max_height);
			$img_width 		= intval($data['image_width']);
			$img_height 	= intval($data['image_height']);
			 
			return array('data'=>$data);
		}
	}
	function deletefile( $images,$file_dir="")
	{
		$CI =& get_instance();
		if($file_dir==""){
			$file_dir = $CI->settings_lib->item('site.pathuploaded');
		}
		$promo_image = $images;
		if (file_exists( $file_dir . DIRECTORY_SEPARATOR . $images) )
		{
			$deleted = unlink( $file_dir . DIRECTORY_SEPARATOR .$images);
			if ( $deleted === false )
			{
				Template::set_message('Problem deleting promo_image file:' . $images, 'error');
				log_message('error', 'Problem deleting promo_image file:' . $images );
			}
			unset ( $deleted );
		}

		if ( isset($promo_image['image_thumb']) && file_exists( $file_dir .DIRECTORY_SEPARATOR .$promo_image['image_thumb']))
		{
			$deleted = unlink($file_dir . DIRECTORY_SEPARATOR  . $promo_image['image_thumb'] );
			if ( $deleted === false )
			{
				Template::set_message('Problem deleting promo_image file:' . $promo_image['image_thumb'], 'error');
				log_message('error', 'Problem deleting promo_image file:' . $promo_image['image_thumb'] );
			}

		}
	}
