<?php
	if (isset($_REQUEST['_SESSION'])) die('Global error. Engine stopped.');

	global $pmPath, $pmRootPath, $__pmAction, $language, $pagePath;

	session_start();
    require_once "_config.php";
    require_once "$pmPath/pmMain.php";

	pmImportVars('__pmAction');

	setlocale(LC_CTYPE, 'ru_RU.CP1251');
	setlocale(LC_COLLATE, 'ru_RU.CP1251');
	switch ($__pmAction)
	{
		case '':	// Все файлы кроме .css, .jpg, .gif и .png
		case 'show_module':
		    require_once "$pmRootPath/_strings.php";
		    require_once "$pmRootPath/proc/db.php";
		    if (file_exists("$pmRootPath/proc/user.php"))require_once "$pmRootPath/proc/user.php";
		    if (file_exists("$pmRootPath/proc/manager.php"))require_once "$pmRootPath/proc/manager.php";
		    if (file_exists("$pmRootPath/proc/shop.php"))require_once "$pmRootPath/proc/shop.php";
		    require_once "$pmRootPath/proc/main.php";
		    require_once "$pmRootPath/proc/cms.php";
		    require_once "$pmRootPath/proc/variables.php";
		    require_once "$pmRootPath/proc/urls.php";
			if (file_exists("$pmRootPath/proc/content.php")) include_once "$pmRootPath/proc/content.php";

			pmImportVars('page');
			$pagePath=pmExplodeRequest($page);
			if (!count($pagePath)) $pagePath[0]='';

			$language=pmGetCurrentLanguage();
			$_SESSION['language_id']=$language['id'];

			$link=connect_db();
			if ($__pmAction=='')
			{
				ob_start();
				pmExecuteTemplate($pmPageTemplate);
				ob_end_flush();
			}
			else
				require_once("$pmTemplatesPath/include/$module");
		    mysql_close($link);
			break;
		case 'transULR':	// Корректировка URL в файлах .css
            global $file;
		    require_once "$pmPath/pmConfig.php";

            pmImportVars('file');
			$fn="$pmRootPath/$file";
			if (file_exists($fn))
			{
				$pp=pathinfo($fn);
				switch(strtolower($pp['extension']))
				{
					case 'css':
						header('Content-Type: text/css');
						break;
				}
				$templateURI="$pmTemplatesURL/$pmPageTemplate";
		    	$file=file_get_contents($fn);
				echo str_replace('@!template@', $templateURI, $file);
			}
			break;
		case 'transResources':
            global $file;
		    require_once "$pmPath/pmConfig.php";
		    require_once "$pmRootPath/_strings.php";

			$language=pmGetCurrentLanguage();
            pmImportVars('file');
			$fn="$pmRootPath/$file";
			if (file_exists($fn))
			{
				$pp=pathinfo($fn);
				switch(strtolower($pp['extension']))
				{
					case 'js':
						header('Content-Type: text/javascript');
						break;
				}
		    	$file=file_get_contents($fn);
				echo pmTranslateStringResource($file);
			}
			break;
		case 'transContentImage':	// Выдача с корректировкой пути к каталогу контента
            global $file;
		    require_once "$pmPath/pmConfig.php";

            pmImportVars('file');
			$fn="$pmRootPath/$pmContentDir/$file";
			if (file_exists($fn))
			{
				$pp=pathinfo($fn);
				switch(strtolower($pp['extension']))
				{
					case 'jpg':
						header('Content-Type: image/jpeg');
						break;
					case 'gif':
						header('Content-Type: image/gid');
						break;
					case 'png':
						header('Content-Type: image/png');
						break;
				}
		    	echo file_get_contents($fn);
			}
			break;
		case 'documentsProcess':	// Обработка ссылок на загруженные документы
		    require_once "$pmRootPath/proc/db.php";
		    require_once "$pmRootPath/_strings.php";
			$link=connect_db();
			$uri=$_SERVER['REQUEST_URI'];
			$type=pmImportVarsList('type');
//			echo "[$type]<br>";
			if ($type=='document' || $type=='')
			{
				$base_url=$_base_site_documents_url;
				$documents_table=$_cms_documents_table;
				$base_dir=$_base_site_documents_path;
			}
			elseif ($type=='attachment')
			{
				$base_url=$_base_site_attachments_url;
				$documents_table='_attachments';
				$base_dir=$_base_site_attachments_path;
			}
//			echo "base url: $base_url<br>";
//			echo "table: $documents_table<br>";
			$uri=substr($uri, strlen($base_url)+1);
//			echo "uri [$uri]<br>";
//			$p=strpos($uri, '/');
//			echo "p [$p]<br>";
/*			if ($p!==false)
			{
				$id=mysql_real_escape_string(substr($uri, 0, $p));
				$doc=get_data_array('file, real_file', $documents_table, "id='$id'");
			}
			else
			{
*/
				$id=mysql_real_escape_string($uri);
				$doc=get_data_array('file, real_file', $documents_table, "file='$id'");
//            }
//			print_r($doc);
			if ($id==-1 || $doc===false)
			{
			    header('HTTP/1.0 404 Not Found');
				echo <<<stop
<h1>$_string_404_not_found</h1>
stop;
			}
			else
			{
				$fn="$base_dir/{$doc['file']}";
				$len=filesize($fn);
				$pp=pathinfo($doc['real_file']);
				$type=$_cms_documents_file_content_types[$pp['extension']];
				if ($type=='') $type='application/octet-stream';
				header("Content-type: $type;");
				header("Content-Disposition: attachment; filename=\"{$doc['real_file']}\"");
				header("Content-Length: $len");
//				echo "file: [$fn]<br>";
				readfile($fn);
			}
			mysql_close($link);
			break;
	}
?>