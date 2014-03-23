<?php
	$path=$_SERVER['PHP_SELF'];
	for ($i=0; $i<3; $i++)
	{
		$p=strrpos($path, '/');
		if ($p!==false) $path=substr($path, 0, $p);
	}
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
	require_once 'texts_widget_proc.php';
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
	require_once "$_admin_proc_path/common_design.php";

	if (!isset($section) || $section=='') exit;
	$link=connect_db();

//******************************************************************************
//
// Блок процедур для работы с текстами
//
//******************************************************************************
	switch ($section)
	{
		// Генерация оглавления текстов
		case 'textsGetTextsList':
			importVars('menu|page', true);
			if ($page=='') $page=0;
			$list=texts_get_texts_list($menu, $page);
			echo serialize_data('html|page', str_replace("\r\n", ' ', $list['html']), $list['page']);
			break;
		// Генерация блока редактирования текста
		case 'textsGetEditBlock':
			importVars('id|menu', true);
			if (!isset($menu)) $menu=0;
			if (isset($id) && $id!='')
				echo texts_get_text_edit_block_html($id, $menu);
			else
				echo '<br><br>Ошибка обращения к БД';
			break;
		// Проверка сигнатуры на уникальность
		case 'textCheckSignatureUnique':
			importVars('id|signature', true);
			if (isset($id) && $id!='' && isset($signature) && $signature!='')
			{
				if ($id==-1)
					$i=get_data('id', $_cms_texts_table, "signature='$signature'");
				else
					$i=get_data('id', $_cms_texts_table, "signature='$signature' and id!='$id'");
				echo "[$i]";
				echo ($i===false ? 'yes' : 'no');
			}
			else
				echo 'no';
			break;
		// Запись изменений текста
		case 'textsEditTextSave':
			importVars('id|menu|signature|title|kw|descr|content', true);
			if (isset($id) && $id!='' && isset($menu) && $menu!='' && isset($signature) && $signature!='' && isset($title) && $title!='' && isset($content) && $content!='')
			{
				if (!isset($kw)) $kw='';
				if (!isset($descr)) $descr='';
				$title=iconv ('utf-8', $html_charset, $title);
				$kw=iconv ('utf-8', $html_charset, $kw);
				$descr=iconv ('utf-8', $html_charset, $descr);
				$content=iconv ('utf-8', $html_charset, $content);
				if ($id==-1)
				{
					query("insert into $_cms_texts_table (menu_item, date, signature, title, keywords, descr, content, visible) values ('$menu', CURDATE(), '$signature', '$title', '$kw', '$descr', '$content', 1)");
					echo 'Текст записан.';
				}
				else
				{
					query("update $_cms_texts_table set title='$title', keywords='$kw', descr='$descr', content='$content' where id='$id'");
					echo 'Изменения записаны.';
				}
			}
			else
				echo '<br><br>Ошибка записи текста<br><br>';
			break;
		// Получение заголовка текста
		case 'textsGetTextTitle':
			importVars('id', true);
			if (isset($id) && $id!='')
				echo stripslashes(get_data('title', $_cms_texts_table, "id='$id'"));
			break;
		// Удаление текста по ID
		case 'textsTextDelete':
			importVars('id', true);
			if (isset($id) && $id!='')
				query("delete from $_cms_texts_table where id='$id'");
			break;
		// Перемещение текста в другое меню
		case 'textsMoveText':
			importVars('id|menu', true);
			if (isset($id) && $id!='' && isset($menu) && $menu!='')
			{
				$m_id=get_data('id', $_cms_menus_items_table, "id='$menu'");
				if ($m_id==$menu) query("update $_cms_texts_table set menu_item='$menu' where id='$id'");
			}
			break;
		// Генерация уникальной сигнатуры текста
		case 'textsGenerateSignature':
			importVars('name', true);
			if (isset($name) && $name!='')
			{
				$name=str2url(iconv ('utf-8', 'windows-1251', $name));
				$sign=metaphone($name, 6);
				$i=0;
				do {
					$s=$sign.$i;
					$id=get_data('id', $_cms_texts_table, "signature='$s'");
					$i++;
				} while($id!==false);
				echo $s;
			}
			break;
		case 'textsGetEditBlock':
			importVars('id|type', true);
			if (isset($id) && $id!='' && isset($type) && $type!='')
				echo texts_get_text_edit_block_html($id, $type);
			else
				echo '<br><br>Ошибка обращения к БД';
			break;
		case 'textsGetTextListHtml':
			importVars('type|page', true);
			if (isset($type) && $type!='' && isset($page) && $page!='')
			{
				$list=texts_get_texts_list($type, $page, $_cms_texts_admin_list_page_length, false);
				$list['html']=str_replace("\r\n", '',  $list['html']);
				$list['html']=str_replace("\n", '',  $list['html']);
				echo serialize_data('page|html', $list['page'], $list['html']);
			}
			break;
		case 'textsGetTextTitle':
			importVars('id', true);
			if (isset($id) && $id!='')
				echo stripslashes(get_data('title', 'texts', "id='$id'"));
			break;
		case 'textsTextDelete':
			importVars('id', true);
			if (isset($id) && $id!='')
				query("delete from texts where id='$id'");
			break;
		case 'textsUploadHTML':
			$file=file_get_contents($_FILES['html_file']['tmp_name']);
			$list=texts_parse_loaded_html_file($file);
			$list['html']=str_replace("\r\n", "\\n\\\n",  addslashes($list['html']));
			echo <<<stop
<script type="text/javascript">
parent.texts_set_loaded_content("{$list['title']}", "{$list['html']}");
</script>
stop;
 			break;
	}
?>
