<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once 'banners_widget_proc.php';
	require_once "$path/_config.php";
	require_once "$_admin_common_proc_path/variables.php";
	require_once "$_admin_common_proc_path/cms.php";
	require_once "$_admin_common_proc_path/logs.php";
	if (file_exists("$_admin_common_proc_path/user.php")) require_once "$_admin_common_proc_path/user.php";
	require_once "$_admin_pmEngine_path/pmMain.php";
	require_once "$_admin_pmEngine_path/pmAPI.php";

	importVars('section', false);
	if(!isset($section) || $section=='') exit;

	header("Content-Type: text/html; charset={$html_charset}");

	require_once "$_admin_common_proc_path/db.php";
	require_once "$_admin_proc_path/main.php";
	require_once "$_admin_proc_path/common_design.php";

	if (!isset($section) || $section=='') exit;
	$link=connect_db();

//******************************************************************************
//
// Блок процедур для работы с баннерами
//
//******************************************************************************
	switch ($section)
	{
		// Генерация HTML кода списка изображений
		case 'bannersGetBannersListHtml':
			importVars('language', true);
			if (!isset($language) || $language=='') return;
			echo banners_get_banners_list_html($language);
			break;
		// Генерация HTML кода Для добавления изображения
		case 'bannersGetBannerAddHtml':
			echo banners_get_banner_add_html();
			break;
		// Обрезание изображения для баннера
		case 'bannersCropImage':
			importVars('file|x|y|w|h', false);
			if (!isset($file) || $file=='' || !isset($x) || $x=='' || !isset($y) || $y=='' || !isset($w) || $w=='' || !isset($h) || $h=='') return;
			echo banners_crop_banner_image("{$_SERVER['DOCUMENT_ROOT']}{$file}", $x, $y, $w, $h);
			break;
		// Сохранение нового баннера
		case 'bannersAddBannerSave':
			importVars('file|language', false);
			if (isset($file) && $file!='' && isset($language) && $language!='')
			{
				banners_banner_add_save($language, $file);
				echo banners_get_banners_list_html($language);
			}
			else
				echo 'error';
			break;
		// Удаление баннера
		case 'bannersDeleteBanner':
			importVars('id|language', true);
			if (isset($id) && $id!='')
			{
                $banner=get_data_array('*', $_cms_banners_table, "id='$id'");
				query("delete from $_cms_banners_table where id='$id'");
				@unlink("$_base_site_banners_images_path/{$banner['file']}");
			}
			echo banners_get_banners_list_html($language);
			break;
	}
	mysql_close($link);
?>