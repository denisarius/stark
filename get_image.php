<?php
	require_once '_config.php';
	require_once 'proc/variables.php';

	importVars('f|type', false);
	if (!isset($f) || $f=='' || !isset($type) || $type=='')
	{
		header('HTTP/1.1 410 Not found');
		exit;
	}
	$pathinfo = pathinfo($f);
	switch(strtolower($pathinfo['extension']))
	{
		case 'jpg':
		case 'jpeg':
			$mime_type='Content-type: image/jpeg';
			break;
		case 'png':
			$mime_type='Content-type: image/png';
			break;
	}
	header('HTTP/1.1 200 OK');
	header ("Content-type: $mime_type");
    $file="$_cms_goods_images_path/";
	if ($type=='t') $file.='thumbs/';
	$file.=$f;
	readfile($file);
?>