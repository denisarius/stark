<?php
// -----------------------------------------------------------------------------
function banners_get_single_banner_html($r)
{
	global $_base_site_banners_images_path, $_base_site_banners_images_url;

	$sz=pmGetBoundedImageSize("$_base_site_banners_images_path/{$r['file']}", 700, 200);
	echo <<<stop
<div class="banners_banner_list_node">
<img src="$_base_site_banners_images_url/{$r['file']}" style="width: {$sz['x']}px; height: {$sz['y']}px;" />
<hr>
<div style="text-align: left;">
<input type="button" value="Удалить" onClick="banners_banner_delete({$r['id']})"/>
</div>
</div>
stop;
}
// -----------------------------------------------------------------------------
function banners_get_banners_list_html($language)
{
	global $_cms_banners_table;

	$res=query("select * from $_cms_banners_table where language='$language' order by sort desc, id desc");
	$html='';
	while($r=mysql_fetch_assoc($res))
		$html.=banners_get_single_banner_html($r);
	mysql_free_result($res);
	return $html;
}
// -----------------------------------------------------------------------------
function banners_get_banner_add_html()
{
	echo <<<stop
<input type="hidden" id="banners_add_banner_image_file"/>
<div class="banners_add_banner_image_container">
<img src="" id="banners_add_banner_image" />
<input type="button" value="Загрузить изображение" onClick="banners_banner_add_image_load()"/>
</div>
<input type="button" value="Сохранить изображение" onClick="banners_banner_add_save()" style="margin-right: 20px;"/>
<input type="button" value="Отмена" onClick="banners_banner_add_cancel()"/>
<script type="text/javascript">
	$("#banners_add_banner_image").load(function(){
		admin_info_center();
	});
</script>
stop;
}
// -----------------------------------------------------------------------------
function banners_crop_banner_image($file, $x, $y, $w, $h)
{
	global $_cms_banners_crop_jpg_quality, $_admin_uploader_path, $_admin_uploader_url;

	$pp=pathinfo($file);
	switch (strtolower($pp['extension']))
	{
		case 'jpg':
		case 'jpeg':
			$im=imagecreatefromjpeg($file);
			break;
		case 'png':
			$im=imagecreatefrompng($file);
			break;
	}
	$dest=imagecreatetruecolor($w, $h);
	imagecopy($dest, $im, 0, 0, $x, $y, $w, $h);
	imagedestroy($im);
	$dfile=create_unique_file_name("$_admin_uploader_path/temp", $file);
	switch (strtolower($pp['extension']))
	{
		case 'jpg':
		case 'jpeg':
			imagejpeg($dest, $dfile, $_cms_banners_crop_jpg_quality);
			break;
		case 'png':
			imagepng($dest, $dfile);
			break;
	}
	imagedestroy($dest);
	$pp=pathinfo($dfile);
	$dfile=$pp['basename'];
	query("insert into _temp_files (file, created) values ('$dfile', CURDATE())");
	return serialize_data('img|file', "$_admin_uploader_url/temp/$dfile", $dfile);
}
// -----------------------------------------------------------------------------
function banners_banner_add_save($language, $file)
{
	global $_admin_uploader_path, $_base_site_banners_images_path, $_cms_banners_table;

	rename("$_admin_uploader_path/temp/$file", "$_base_site_banners_images_path/$file");
	$cnt=get_data('max(id)', $_cms_banners_table)+1;
    query("insert into $_cms_banners_table (language, file, sort, visible) values ('$language', '$file', '$cnt', 1)");
}
// -----------------------------------------------------------------------------
?>