<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<2; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once 'common_widget_proc.php';
	require_once "$path/_config.php";
	require_once "$_admin_common_proc_path/variables.php";
	require_once "$_admin_common_proc_path/cms.php";
	require_once "$_admin_common_proc_path/logs.php";
	if (file_exists("$_admin_common_proc_path/user.php")) require_once "$_admin_common_proc_path/user.php";
	require_once "$_admin_common_proc_path/main.php";
	require_once "$_admin_pmEngine_path/pmMain.php";
	require_once "$_admin_pmEngine_path/pmAPI.php";

	importVars('section', false);
	if(!isset($section) || $section=='') exit;

	header("Content-Type: text/html; charset={$html_charset}");

	require_once "$_admin_common_proc_path/db.php";
	require_once "$_admin_proc_path/main.php";

	if (!isset($section) || $section=='') exit;
	$link=connect_db();

//******************************************************************************
//
// Блок процедур для работы с меню
//
//******************************************************************************
	switch ($section)
	{
		// Генерация фрейма со списком меню
		case 'commonGetMenusList':
			importVars('func', true);
			$list=common_get_menus_list($func);
			echo $list;
			break;
		// Генерация фрейма со списком пунктов меню
		case 'commonGetMenuItemsList':
			importVars('func|id', true);
			$list=common_get_menu_items_list($id, $func);
			echo $list;
			break;
		// Генерация карты меню
		case 'commonGetMenusMap':
			importVars('func', true);
			if (isset($func) && $func!='') echo str_replace("\r\n", ' ', common_get_menus_map($func));
			break;
		// Обработка временно закаченного изображения
		case 'commonTempImageProcess':
			importVars('file|sx|sy|max|quality', true);
			if (!isset($sx)) $sx=-1;
			if (!isset($sy)) $sy=-1;
			if (!isset($max) || $max=='') $max=0;
			if (!isset($quality) || $quality=='') $quality=0;
			if (isset($file)) echo common_temp_image_process($file, $sx, $sy, $max, $quality);
			break;
		case 'commonTempFileProcess':
			importVars('file', false);
			if (isset($file)) echo common_temp_file_process($file);
			break;
		case 'commonTempFileDelete':
			importVars('file', false);
			if (isset($file)) echo common_temp_file_delete($file);
			break;
		case 'commonGetLinkTextsList':
			importVars('func', false);
			echo common_get_link_text_list($func);
			break;
		case 'commonGetLinkMenuHTML':
			importVars('func', false);
			echo common_get_link_menu_html($func);
			break;
		case 'commonGetLinkMenuItemsHTML':
			importVars('menu|menu_name|func', true);
			if (isset($menu) && $menu!='' && isset($menu_name) && $menu_name!='')
			{
				$menu_name=iconv ('utf-8', 'windows-1251', $menu_name);
				echo common_get_link_menu_items_list_html($menu, $menu_name, 0, '', $func);
			}
			break;
		// Генерация HTML кода для списка документов
		case 'commonGetLinkDocumentListHTML':
			importVars('func', false);
			echo common_get_documents_list_html($func);
			break;
		// Генерация HTML кода для списка констант
		case 'commonGetConstantsList':
			importVars('func', false);
			echo common_get_constants_list_html($func);
			break;
		case 'commonEditTagEdit':
			importVars('text_block|pos', false);
			$text_block=iconv ('utf-8', $html_charset, $text_block);
			if (isset($text_block) && $text_block!='' && isset($pos) && $pos!='')
				echo common_get_tag_edit_html($text_block, $pos);
			else
				return 'Ошибка при обработке кода тэга';
			break;
		case 'commonGetTextBySignature':
			importVars('signature|pos', false);
			echo common_find_text_by_signature_from_string($signature, $pos);
			break;
		// Генерация HTML кода для диалога выбора раздела
		case 'commonGetMenuItemSelectorHtml':
			importVars('menu_id|menu_items|func|height', true);
			if (!isset($func) || $func=='') return;
			if (!isset($menu_id) || $menu_id=='') $menu_id=-1;
			if (!isset($menu_items)) $menu_items=='';
			if (!isset($height) || $height=='') $height=600;
			echo common_get_menu_item_selector_html($menu_id, $menu_items, $func, $height);
			break;
		// Генерация HTML кода для контейнера подразделов при выборе раздела
		case 'commonGetMenuItemSelectorItemsHtml':
			importVars('id|func', true);
			if (!isset($id) || $id=='') return 'no id';
			echo common_get_menu_item_selector_items_html($id, $func);
			break;
		// Генерация HTML кода для редактирования прицепленного документа
		case 'commonGetAttachmentEditHtml':
			$vars=pmImportVarsList('attachmet_id|func', true);
			echo common_get_attachment_edit_html($vars['attachmet_id'], $vars['func']);
			break;
		// Удаление прикрепленного файла
		case 'commonGetAttachmentDelete':
			$attachmet_id=pmImportVarsList('attachmet_id', true);
			if (!isset($attachmet_id) || $attachmet_id=='') return '';
			common_attachment_delete($attachment_id);
			break;
	}
//------------------------------------------------------------------------------
?>
