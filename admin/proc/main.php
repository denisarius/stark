<?php
//------------------------------------------------------------------------------
function numeral_form($number, $titles=array('êîììåíòàğèé','êîììåíòàğèÿ','êîììåíòàğèåâ'))
{
    $cases = array (2, 0, 1, 1, 1, 2);
    return $titles[ ($number%100 >4 && $number%100< 20)? 2 : $cases[min($number%10, 5)] ];
}
//------------------------------------------------------------------------------
function create_unique_file_name($_base_site_galleries_path, $mask, $length=16)
{
	$pp=pathinfo($mask);
	if ($length>32) $length=32;
    $prefix=substr(md5(time().rand()), 32-$length, $length);
	$i=0;
	while(file_exists("$_base_site_galleries_path/$prefix$i.{$pp['extension']}"))
		$i++;
	return "$_base_site_galleries_path/$prefix$i.{$pp['extension']}";
}
//------------------------------------------------------------------------------
function create_thumbnail($img_file, $thumb_file, $ext, $image_max_dx=-1, $image_max_dy=-1, $thumbnail_size=-1)
{
	global $_gallery_image_max_dx, $_gallery_image_max_dy, $_gallery_thumbnail_size;

	if ($image_max_dx==-1) $image_max_dx=$_gallery_image_max_dx;
	if ($image_max_dy==-1) $image_max_dy=$_gallery_image_max_dy;
	if ($thumbnail_size==-1) $thumbnail_size=$_gallery_thumbnail_size;
	$ext=strtolower($ext);
	switch($ext)
	{
		case 'jpg':
		case 'jpeg':
			$im = imagecreatefromjpeg($img_file);
			break;
		case 'png':
			$im = imagecreatefrompng($img_file);
			break;
	}
	if ($im!==false)
	{
        if (imagesx($im)>$image_max_dx || imagesy($im)>$image_max_dy)
			image_resize($im, $image_max_dx, $image_max_dy, $img_file, $ext);
		image_resize($im, $thumbnail_size, $thumbnail_size, $thumb_file, $ext);
		imagedestroy($im);
	}
}
//------------------------------------------------------------------------------
function image_resize($im, $max_x, $max_y, $img_file, $ext, $max=0)
{
	global $_cms_images_jpeg_quality;

	$w=imagesx($im);
	$h=imagesy($im);
	$dx=$w/$max_x;
	$dy=$h/$max_y;
	if (!$max) $d=max($dx, $dy);
	else $d=min($dx, $dy);
	$wn=$w/$d;
	$hn=$h/$d;
	$imd = imagecreatetruecolor($wn, $hn);
	imagecopyresampled($imd, $im, 0, 0, 0, 0, $wn, $hn, $w, $h);
	switch($ext)
	{
		case 'jpg':
		case 'jpeg':
			imagejpeg($imd, $img_file, $_cms_images_jpeg_quality);
			break;
		case 'png':
			imagepng($imd, $img_file);
			break;
	}
	imagedestroy($imd);
}
//------------------------------------------------------------------------------
function delete_temp_image($file)
{
	@unlink("$_admin_uploader_path/temp/$file");
	query("delete from _temp_files where file='$file'");
}
//------------------------------------------------------------------------------
function get_local_path($path)
{
	return substr($path, strlen($_SERVER['DOCUMENT_ROOT'])+1);
}
//------------------------------------------------------------------------------
function copy_image_to_temp($src)
{
	global $_admin_uploader_path;

	$pp=pathinfo($src);
	@copy($src, "$_admin_uploader_path/temp/{$pp['basename']}");
	query("insert into _temp_files (file, created) values ('{$pp['basename']}', CURDATE())");
}
//------------------------------------------------------------------------------
function queryImportVars($__names__, $__mysql_safe__)
{
	$query=$_SERVER['QUERY_STRING'];
	$p=strpos($query, '?');
	if ($p===false) return;
	$query=substr($query, $p+1);
	parse_str($query, $__request__);
	$__names_list__=explode('|', $__names__);
	foreach($__names_list__ as $__n__)
	{
		global $$__n__;
		if (isset($__request__[$__n__]))
		{
			$__v__=$__request__[$__n__];
			if (get_magic_quotes_gpc()) $__v__=stripcslashes($__v__);
			if ($__mysql_safe__) $__v__=mysql_real_escape_string($__v__);
			$$__n__=$__v__;
		}
		else
			unset($$__n__);
	}
}
//------------------------------------------------------------------------------
function widget_exists($id)
{
	global $_admin_widgets_path;

    if ($d = opendir($_admin_widgets_path))
	{
        while (($file = readdir($d)) !== false)
           	if ($file!='.' && $file!='..' && is_dir("$_admin_widgets_path/$file"))
			{
				$p=strpos($file, '_');
				if ($p!==false) $file=substr($file, $p+1);
				if ($file==$id) return true;
    		}
        closedir($d);
    }
	else
		return false;
}
//------------------------------------------------------------------------------
function date2mysql($date)
{
	return substr($date, 6).'-'.substr($date, 3, 2).'-'.substr($date, 0, 2);
}
//------------------------------------------------------------------------------
function mysql2date($date)
{
	return substr($date, 8, 2).'-'.substr($date, 5, 2).'-'.substr($date, 0, 4);
}
//------------------------------------------------------------------------------
function mysql_get_fields_list($table, $exclude)
{
	$exclude=explode('|', strtolower($exclude));
	$res=query("show columns from $table");
		$fields_list='';
	while($r=mysql_fetch_assoc($res))
		if (!in_array(strtolower($r['Field']), $exclude))
			$fields_list.="{$r['Field']},";
	mysql_free_result($res);
	$fields_list=substr($fields_list, 0, -1);
	return $fields_list;
}
//------------------------------------------------------------------------------
// îáğàáîòêà ñòğîêè äëÿ âêëş÷åíèÿ åå â HTML êîä â êà÷åñòâå àğãóìåíòà ôóíêöèè
// â JavaScript. Ñòğîêà â êîäå äîëæíà áûòü çàêëş÷åíà â àïîñòğîôû ('') !
//------------------------------------------------------------------------------
function str2js($str)
{
	return strtr($str, array("\""=>"&quot;", "\\"=>"\\\\", "'" => "\\'"));
}
//------------------------------------------------------------------------------
?>