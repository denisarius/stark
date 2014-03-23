<?php
// -----------------------------------------------------------------------------
// Обработка пути к pmEngine относительно корня сервера
// $ph - путь от корня сервера
$ph='/pmEngine';
if (($p=mb_strrpos(__FILE__ , '/'))===false) $p=mb_strrpos(__FILE__ , '\\');
$pmPath=mb_substr(__FILE__, 0, $p).$ph;
$pmRootPath=mb_substr(__FILE__, 0, $p).'/';
// -----------------------------------------------------------------------------
$_cms_simple=(int)false;

require_once "{$_SERVER['DOCUMENT_ROOT']}/_config_db.php";

$html_charset='windows-1251';

$_site_domain='stark.ru';
$_site_mail_domain=$_site_domain;
$_site_name=array(
	'ru'=>'Stark'
);
$_site_mail_admin="info@{$_site_domain}";
$_site_mail_admin_name=array(
	'ru'=>"Администрация сайта '{$_site_name['ru']}'",
);

$_scripts_libs_url='http://www.critical.ru/libs/js';

$_base_site_root_path=$_SERVER['DOCUMENT_ROOT'];
$_base_site_proc_path="$_base_site_root_path/proc";
$_base_site_content_path="$_base_site_root_path/data/content";
$_base_site_content_images_path="$_base_site_root_path/data/content/images";
$_base_site_structured_text_images_path="$_base_site_root_path/data/text_parts";
$_base_site_uploader_path="$_base_site_root_path/uploader/uploads";

$_base_site_root_url='';
$_base_site_js_url="$_base_site_root_url/js";
$_base_site_css_url="$_base_site_root_url/css";
$_base_site_images_url="$_base_site_root_url/images";
$_base_site_content_url="$_base_site_root_url/data/content";
$_base_site_content_images_url="$_base_site_root_url/data/content/images";
$_base_site_structured_text_images_url="$_base_site_root_url/data/text_parts";
$_site_main_url="http://{$_SERVER['SERVER_NAME']}/$_base_site_root_url";

$confs = glob("$_base_site_root_path/configs/config_*.php", GLOB_NOSORT|GLOB_NOESCAPE);
foreach($confs as $conf)
	require_once $conf;

$_cms_menus_table='menus';
$_cms_menus_items_table='menus_items';
$_cms_texts_table='texts';
$_cms_constants_table='constants';
$_cms_text_parts='text_parts';
$_cms_directories='directories';
$_cms_directories_data='directories_data';

$_cms_texts_url_prefix='content';

// ID для различных меню
/** Используется при $_simple_mode == true */
$_main_menu_id=null;

// Описание языков сайта
$_languages=array(
	array('id'=>'ru',
		'title'=>'Рус',
		'admin_title'=>'Русский',
        // основное (верхнее) меню
        'top_menu_id' => 1,
        // расширенное меню (плашки)
        'ext_menu_id' => 2,
		'lights_menu_item_id'=>6,
	),
);

?>
