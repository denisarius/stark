<?php
	require_once '_config.php';

	$uri=$_SERVER['REQUEST_URI'];
	$fn="{$_SERVER['DOCUMENT_ROOT']}{$uri}";
	$p=strpos($fn, '?');
	if ($p!==false) $fn=substr($fn, 0, $p);
	if (file_exists($fn))
	{
		$pp=pathinfo($fn);
		header('HTTP/1.1 200 OK');
		$pp['extension']=strtolower($pp['extension']);
		$content_types=array(
'js' 	=> 'text/javascript',
'css'	=> 'text/css',
);
		$is_processing=true;
		$widget_id=$widget_path='';
		if (substr($uri, 0, strlen($_admin_widgets_url))==$_admin_widgets_url)
		{
			$is_processing=true;
			$widget_id=substr($uri, strlen($_admin_widgets_url)+1);      //
			$p=strpos($widget_id, '/');                                  //   xx_name/name_widget.js  => xx_widget
			if ($p!==false) $widget_id=substr($widget_id, 0, $p);
			$widget_path=$widget_id;
			$p=strpos($widget_id, '_');                                  //
			if ($p!==false) $widget_id=substr($widget_id, $p+1);         //	  xx_name => name
		}
		if (isset($content_types[$pp['extension']]))
		{
			header ("Content-type: {$content_types[$pp['extension']]}");
			$file=file_get_contents($fn);
		    switch($pp['extension'])
			{
				case 'js':
				case 'css':
					if ($is_processing)
					{
						$file=str_replace('{@widgets_url@}', $_admin_widgets_url, $file);
						$file=str_replace('{@widget_id@}', $widget_id, $file);
						$file=str_replace('{@widget_path@}', $widget_path, $file);
						$file=str_replace('{@admin_url@}', $_admin_root_url, $file);
						$file=str_replace('{@admin_uploader_url@}', $_admin_uploader_url, $file);

						if (isset($_news_image_sx)) $file=str_replace('{@news_image_sx@}', $_news_image_sx, $file);
						if (isset($_news_image_sy)) $file=str_replace('{@news_image_sy@}', $_news_image_sy, $file);

                        if (isset($_gallery_thumbnail_size)) $file=str_replace('{@gallery_thumbnail_size@}', $_gallery_thumbnail_size, $file);
						if (isset($_gallery_thumbnail_size)) $file=str_replace('{@gallery_item_width@}', $_gallery_thumbnail_size+10, $file);
						if (isset($_gallery_thumbnail_size)) $file=str_replace('{@gallery_item_height@}', $_gallery_thumbnail_size+100, $file);

						if (isset($_cms_objects_image_sx)) $file=str_replace('{@object_image_sx@}', $_cms_objects_image_sx, $file);
						if (isset($_cms_objects_image_sy)) $file=str_replace('{@object_image_sy@}', $_cms_objects_image_sy, $file);

						if (isset($_actions_image_sx)) $file=str_replace('{@actions_image_sx@}', $_actions_image_sx, $file);
						if (isset($_actions_image_sy)) $file=str_replace('{@actions_image_sy@}', $_actions_image_sy, $file);

						if (isset($_cms_banners_image_sx)) $file=str_replace('{@banners_image_sx@}', $_cms_banners_image_sx, $file);
						if (isset($_cms_banners_image_sy)) $file=str_replace('{@banners_image_sy@}', $_cms_banners_image_sy, $file);

						if (isset($_cms_attachments_file_types)) $file=str_replace('{@attachments_file_types@}', $_cms_attachments_file_types, $file);
					}
					break;
			}
			echo $file;
		}
		else
		    header('HTTP/1.0 404 Not Found');
	}
	else
	    header('HTTP/1.0 404 Not Found');
?>
