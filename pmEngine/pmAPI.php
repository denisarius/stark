<?php
// -----------------------------------------------------------------------------
// API functions
// -----------------------------------------------------------------------------
function pmSetStatus($status)
{
	global $pmDocument;
	$pmDocument['status']=$status;
}
// -----------------------------------------------------------------------------
function pmHeader($text)
{
	global $pmDocument;
	$pmDocument['head'].="\n$text";
}
// -----------------------------------------------------------------------------
function pmPostBody($text)
{
	global $pmDocument;
	$pmDocument['postBody'].="\n$text";
}
// -----------------------------------------------------------------------------
function pmString2URL($str)
{
	$n='';
	$str=strtolower(trim($str));
	for ($i=0; $i<strlen($str); $i++)
	{
		if (($str[$i]>='a' && $str[$i]<='z') || ($str[$i]>='0' && $str[$i]<='9'))
			$n.=$str[$i];
		else
			if (strlen($n)>0 && $n[strlen($n)-1]!='-') $n.='-';
	}
	return $n;
}
// -----------------------------------------------------------------------------
function pmEchoVars($varList)
{
    $varNames=explode('|', $varList);
    $arg=func_get_args();
    $num=func_num_args();
    for($i=1; $i<$num; $i++)
    {
        $n=$varNames[$i-1];
        $v=$arg[$i];
        echo $n."[$v] ";
    }
    echo '<br>';
}
// -----------------------------------------------------------------------------
function pmImportVars($__names__, $mysql_safe=false)
{
	$__names_list__=explode('|', $__names__);
	$p=strpos($_SERVER['REQUEST_URI'], '?');
	if ($p!==false) parse_str(substr($_SERVER['REQUEST_URI'], $p+1), $__get_vars);
	else $__get_vars=array();
	foreach($__names_list__ as $__n__)
	{
		global $$__n__;
        $__v__=NULL;
		if (array_key_exists($__n__, $_REQUEST)) $__v__=$_REQUEST[$__n__];
		if (array_key_exists($__n__, $__get_vars)) $__v__=$__get_vars[$__n__];
		if (is_null($__v__))
			unset($$__n__);
		else
		{
			if (get_magic_quotes_gpc()) $__v__=stripcslashes($__v__);
			if ($mysql_safe) $__v__=mysql_real_escape_string($__v__);
			$$__n__=$__v__;
		}
	}
}
//------------------------------------------------------------------------------
function pmImportVarsList($__names__, $mysql_safe=false)
{
	$vars=array();
	$__names_list__=explode('|', $__names__);
	$p=strpos($_SERVER['REQUEST_URI'], '?');
	if ($p!==false) parse_str(substr($_SERVER['REQUEST_URI'], $p+1), $__get_vars);
	else $__get_vars=array();
	foreach($__names_list__ as $__n__)
	{
        $__v__=NULL;
		if (array_key_exists($__n__, $_REQUEST)) $__v__=$_REQUEST[$__n__];
		if (array_key_exists($__n__, $__get_vars)) $__v__=$__get_vars[$__n__];
		if (!is_null($__v__))
		{
			if (get_magic_quotes_gpc()) $__v__=stripcslashes($__v__);
			if ($mysql_safe) $__v__=mysql_real_escape_string($__v__);
			$vars[$__n__]=$__v__;
		}
	}
	if (count($vars)==1) $vars=current($vars);
	return $vars;
}
//------------------------------------------------------------------------------
function pmAntiXSS($__names__, $cp)
{
	$__names_list__=explode('|', $__names__);
	foreach($__names_list__ as $__n__)
	{
		global $$__n__;
		$$__n__=pmAntiXSSVar($$__n__, $cp);
	}
}
//------------------------------------------------------------------------------
function pmAntiXSSVar($var, $cp)
{
//	return htmlentities($var, ENT_QUOTES, $cp);
	return htmlspecialchars($var, ENT_QUOTES, $cp);
}
//------------------------------------------------------------------------------
function pmMysqlSafe($var)
{
	return mysql_real_escape_string(addslashes($var));
//	return mysql_real_escape_string($var);
}
//------------------------------------------------------------------------------
function pmConvertRquestString($str)
{
	if (get_magic_quotes_gpc()) $str=stripcslashes($str);
	return $str;
}
//------------------------------------------------------------------------------
function pmExplodeRequest($uri, $varList='')
{
	if ($uri=='') return array();
	if ($uri[strlen($uri)-1]=='/') $uri=substr($uri, 0, -1);
	$uri=str_replace('.php', '', $uri);
	$uri=str_replace('.html', '', $uri);
	$uri=str_replace('.htm', '', $uri);
	$vn___=explode('|', $varList);
	$pr___=explode('/', $uri);
	$res___=array();
	if ($varList!='')
		for($i___=0; $i___<count($vn___); $i___++)
		{
			global $$vn___[$i___];
			$$vn___[$i___]=$pr___[$i___];
		}
	else
		for($i___=0; $i___<count($pr___); $i___++)
			$res___[$i___]=$pr___[$i___];
	return $res___;
}
//------------------------------------------------------------------------------
function pmGetContentFileName($path)
{
	global $pmContentDir;

	$file="{$_SERVER['DOCUMENT_ROOT']}$pmContentDir";
	if (count($path) && $path[0]!='')
		foreach($path as $p)
			$file.="/$p";
	else
		$file.='/index';
	if (substr($file, -1, 1)=='/') $file.='/index';
	return $file;
}
//------------------------------------------------------------------------------
function pmGetContent($pagePath)
{
	global $pmPageTemplate, $pmTemplatesPath;

	$res=pmGetSpecialContent($pagePath);
    if ($res=='')
	{
		$file=pmGetContentFileName($pagePath);
		$res='';
		if (file_exists("$file.php"))
		{
			ob_start();
			$code=file_get_contents("$file.php");
			eval("?>$code");
			$res=ob_get_contents();
			ob_end_clean();
		}
		elseif (file_exists("$file.htm"))
			$res=file_get_contents("$file.htm");
		elseif (file_exists("$file.html"))
			$res=file_get_contents("$file.html");
	}
	if ($res=='') $res=file_get_contents("$pmTemplatesPath/$pmPageTemplate/_page_not_found.php");
	pmRenderHTMLContent($res);
}
//------------------------------------------------------------------------------
function pmShowWarning($format)
{
    $arg=func_get_args();
    $num=func_num_args();
	$code="return sprintf('$format'";
    for($i=1; $i<$num; $i++)
    {
        $v=$arg[$i];
        $code.=", '$v'";
    }
	$code.=');';
	$w=eval($code);
	echo <<<stop
<div class="warning_note">$w</div>
stop;
}
//------------------------------------------------------------------------------
function pmGenerateUniqueID($len)
{
	srand(((int)((double)microtime()*1000003)));
	$i=0;
	$s='';
	while ($i<$len)
	{
		$r=rand();
		$rs=md5($r);
		if ($len-$i<32) $rs=substr($rs, 0, $len-$i);
		$s.=$rs;
		$i+=strlen($rs);
	}
	return $s;
}
//------------------------------------------------------------------------------
function pmIncludePath($file)
{
	global $pmTemplatesPath, $pmPageTemplate;
	if ($file[0]!='!')
		return "$pmTemplatesPath/include/$file";
	else
	{
		$file=substr($file, 1);
		return "$pmTemplatesPath/$pmPageTemplate/include/$file";
	}
}
//------------------------------------------------------------------------------
function pmTemplatePath()
{
	global $pmPageTemplate, $pmTemplatesPath;
	return "$pmTemplatesPath/$pmPageTemplate";
}
//------------------------------------------------------------------------------
function pmTemplateURL()
{
	global $pmPageTemplate, $pmTemplatesURL;
	return "$pmTemplatesURL/$pmPageTemplate";
}
//------------------------------------------------------------------------------
function pmExplodeUseQuotes($delimiter, $str)
{
	$delimiter=substr($delimiter, 0, 1);
	$in_quotes=false;
	$quote_sym='';
	$len=strlen($str);
	$pos=0;
	$res=array();
	$chunk='';
	while($pos<$len)
	{
		$c_char=$str[$pos];
		if (!$in_quotes)
		{
			if ($c_char==$delimiter)
			{
				array_push($res, trim($chunk));
				$chunk='';
				$pos++;
				continue;
			}
			if ($c_char=='\'' || $c_char=='"')
			{
				$in_quotes=true;
                $quote_sym=$c_char;
				$c_char='';
			}
		}
		else
			if ($c_char==$quote_sym) {$in_quotes=false; $c_char='';}
		$chunk.=$c_char;
		$pos++;
	}
	if ($chunk!='') array_push($res, trim($chunk));
	return $res;
}
//------------------------------------------------------------------------------
function pmTextContentTransform($content)
{
    $pos=0;
	$in_tag=$in_pm_tag=$in_quotes=false;
	$quote_sum='"';
	$res='';
	while($pos<strlen($content))
	{
		$c_char=$content[$pos];
    	if ($in_tag)
		{
        	$res.=$c_char;
			if (!$in_quotes)
			{
	            if ($c_char=='>') $in_tag=false;
				if ($c_char=='"' || $c_char=='\'') {$in_quotes=true; $quote_sum=$c_char;}
			}
			else
				if ($c_char==$quote_sum) $in_quotes=false;
			$pos++;
			continue;
		}
		if ($in_pm_tag)
		{
			if (substr($content, $pos, 6)=='&quot;') {$content=substr($content, 0, $pos).'"'.substr($content, $pos+6); $c_char='"';}
			if (substr($content, $pos, 5)=='&#39;') {$content=substr($content, 0, $pos).'\''.substr($content, $pos+5); $c_char='\'';}
        	$tag.=$c_char;
			if (!$in_quotes)
			{
				if ($c_char=='}') { $in_pm_tag=false; $res.=pmTextTagProcess($tag); }
				if ($c_char=='"' || $c_char=='\'') {$in_quotes=true; $quote_sum=$c_char;}
			}
			else
				if ($c_char==$quote_sum) $in_quotes=false;
			$pos++;
			continue;
		}

		if ($c_char=='<') $in_tag=true;
		if (strlen($content)-$pos>5 && $c_char=='{' && $content[$pos+1]=='@' && $content[$pos+2]=='@')
		{
			$tag='{';
			$in_pm_tag=true;
			$c_char='';
		}

       	$res.=$c_char;
		$pos++;
	}
	return $res;
}
//------------------------------------------------------------------------------
function pmTranslateStringResource($text)
{
	$pos=0;
	$in_str_res=false;
	$res='';
	$tag='';
	while($pos<strlen($text))
	{
		$c_char=$text[$pos];
        if ($in_str_res)
		{
			if (strlen($text)-$pos>1 && $c_char=='%' && $text[$pos+1]=='%')
			{
            	$in_str_res=false;
				$res.=pmGetResourceString($tag, $language_id);
				$pos++;
			}
			else
				$tag.=$c_char;
			$pos++;
			continue;
		}

		if (strlen($text)-$pos>5 && $c_char=='%' && $text[$pos+1]=='%')
		{
			$tag='';
            $in_str_res=true;
			$c_char='';
			$pos++;
		}

       	$res.=$c_char;
		$pos++;
	}
	$res=str_replace('@!template@', pmTemplateURL(), $res);
	return $res;
}
//------------------------------------------------------------------------------
function pmTextTagProcess($tag)
{
	global $_cms_texts_table, $_cms_menus_items_table, $_cms_constants_table, $_cms_documents_table;
	global $_base_site_documents_url;

	$tag=trim(substr(substr($tag, 3), 0, -1));
	if (($p=strpos($tag, '('))!==false)
	{
		$tag_name=strtolower(trim(substr($tag, 0, $p)));
		$p1=strrpos($tag, ')');
		$a=substr($tag, $p+1, $p1-$p-1);
		$args=pmExplodeUseQuotes(',', $a);
	}
	else
	{
		$tag_name=$tag;
		$args=array();
	}
	$res='';
	switch($tag_name)
	{
		case 'link':     // тэг ссылки
			$res=$args[2];
			switch (strtolower($args[0]))
			{
				case 'text':	// ссылка на текст
					if (function_exists('get_text_url'))
					{
        				$text=get_data_array('id, title, menu_item', $_cms_texts_table, "signature='{$args[1]}'");
						if ($text!==false)
						{
							$url=get_text_url($text['id'], $text['title'], $text['menu_item']);
							if (isset($args[3]) && $args[3]!='') $alt="title='{$args[3]}'";
							else $alt='';
							$res="<a href='$url' $alt>{$args[2]}</a>";
						}
					}
					break;
				case 'menu':	// ссылка на пункт меню
					if (function_exists('get_menu_url'))
					{
        				$id=get_data('id', $_cms_menus_items_table, "id='{$args[1]}'");
						if ($id!='')
						{
							$url=get_menu_url($args[1]);
							if (isset($args[3]) && $args[3]!='') $alt="title='{$args[3]}'";
							else $alt='';
							$res="<a href='$url' $alt>{$args[2]}</a>";
						}
					}
					break;
				case 'document':
       				$doc=get_data_array('id, real_file', $_cms_documents_table, "id='{$args[1]}'");
					if ($doc!==false)
					{
                        $url="$_base_site_documents_url/{$args[1]}/{$doc['real_file']}";
						if (isset($args[3]) && $args[3]!='') $alt="title='{$args[3]}'";
						else $alt='';
						$res="<a href='$url' $alt>{$args[2]}</a>";
					}
					else
						$res=$args[2];
			}
			break;
		case 'const':	// Тэг константы
			$name=mysql_real_escape_string($args[0]);
			$res=get_data('value', $_cms_constants_table, "name='$name'");
			if ($res===false) $res=$args[0];
			break;
	}
	return $res;
}
//------------------------------------------------------------------------------
function pmExplodePseudoTag($tag)
{
	if (($pos=strpos($tag, '('))===false) return array('tag'=>$tag);
    $res=array();
	$res['tag']=trim(substr($tag, 0, $pos));
	$tag=substr($tag, $pos+1);
	if (($pos=strpos($tag, ')'))===false) return $res;
	$tag=substr($tag, 0, $pos-1);
	$res['args']=pmExplodeUseQuotes(',', $tag);
	return $res;
}
//------------------------------------------------------------------------------
function pmVersion2Str($version)
{
	if (!is_array($version)) return $version;
	$res='';
	foreach($version as $v)
		$res.="$v.";
	if (strlen($res)>0) $res=substr($res, 0, -1);
	return $res;
}
//------------------------------------------------------------------------------
function pmParseHtmlFile($file, $prepare_for_js=true)
{
	$list=array('title'=>'', 'html'=>'');

	preg_match('/<title>(.*)<\/title>/i', $file, $t);
	$list['title']=$t[1];
	preg_match_all('/<script(.*)>(.+)<\/script>/isU', $file, $t);
	foreach($t[0] as $chunk)
		$list['html'].="$chunk\r\n";
	preg_match('/<body(.*)>(.+)<\/body>/isU', $file, $t);
	$list['html'].=$t[2];
	if ($prepare_for_js) $list['html']=str_replace('</script>', '<==script>', $list['html']);
	return $list;
}
//------------------------------------------------------------------------------
function pmGetBoundedImageSize($name, $sx, $sy, $max=false)
{
	$sz=getimagesize($name);
	$dw=$sx/$sz[0];
	$dh=$sy/$sz[1];
	if (!$max)
	{
	    if ($dw>$dh) $d=$dh;
		else $d=$dw;
	}
	else
	{
	    if ($dw<$dh) $d=$dh;
		else $d=$dw;
	}
	return array('x'=>(int)($sz[0]*$d), 'y'=>(int)($sz[1]*$d));
}
// -----------------------------------------------------------------------------
function pmCreateUniqueFileName($path, $mask, $length=16)
{
	$pp=pathinfo($mask);
	if ($length>32) $length=32;
    $prefix=substr(md5(time().rand()), 32-$length, $length);
	$i=0;
	while(file_exists("$path/$prefix$i.{$pp['extension']}"))
		$i++;
	return "$path/$prefix$i.{$pp['extension']}";
}
//------------------------------------------------------------------------------
function pmCreateThumbnail($img_file, $thumb_file, $image_max_dx, $image_max_dy, $thumbnail_size_dx, $thumbnail_size_dy, $jpg_quality=90, $max)
{
	$pp=pathinfo($img_file);
	$ext=strtolower($pp['extension']);
	switch($ext)
	{
		case 'jpg':
		case 'jpeg': $im = imagecreatefromjpeg($img_file); break;
		case 'png':	$im = imagecreatefrompng($img_file); break;
	}
	if ($im!==false)
	{
        if (imagesx($im)>$image_max_dx || imagesy($im)>$image_max_dy)
			pmImageResize($im, $image_max_dx, $image_max_dy, $img_file, $ext, $jpg_quality, $max);
		if ($thumb_file!='') pmImageResize($im, $thumbnail_size_dx, $thumbnail_size_dy, $thumb_file, $ext, $jpg_quality, $max);
		imagedestroy($im);
	}
}
//------------------------------------------------------------------------------
function pmImageResize($im, $max_x, $max_y, $img_file, $ext, $jpg_quality, $max=0)
{
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
		case 'jpeg': imagejpeg($imd, $img_file, $jpg_quality); break;
		case 'png': imagepng($imd, $img_file); break;
	}
	imagedestroy($imd);
}
//------------------------------------------------------------------------------
function pmGetResourceString($id, $language_id='')
{
	global $_string_resources, $language;

	if ($language_id=='') $language_id=$language['id'];
	if (isset($_string_resources[$id][$language_id]))
		return $_string_resources[$id][$language_id];
	else
		return $id;
}
// -----------------------------------------------------------------------------
function pmGetCurrentLanguage()
{
	global $_languages, $pagePath;

	if (isset($pagePath) && array_key_exists('0', $pagePath) && $pagePath[0]!='')
		$l_id=$pagePath[0];
	else
		$l_id=$_SESSION['language_id'];
	foreach($_languages as $language)
		if ($language['id']==$l_id) return $language;
	return $_languages[0];
}
// -----------------------------------------------------------------------------
// обработка строки для включения ее в HTML код в качестве аргумента функции
// в JavaScript. Строка в коде должна быть заключена в апострофы ('') !
//------------------------------------------------------------------------------
function pmStr2js($str)
{
	return strtr($str, array("\""=>"&quot;", "\\"=>"\\\\", "'" => "\\'"));
}
//------------------------------------------------------------------------------
function pmGetNotice($text, $len)
{
	while($text[$len]!=' ' && $text[$len]!=',' && $text[$len]!='.' && $len<strlen($text))
		$len++;
	return substr($text, 0, $len);
}
//------------------------------------------------------------------------------
// возврат данных аттачмента по его ID
function pmGetAttachment($id)
{
	return get_data_array('*', '_attachments', "id='$id'");
}
//------------------------------------------------------------------------------
?>