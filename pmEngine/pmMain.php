<?php
	global $pmPath, $pmEngineVersion, $pmAdminVersion;

    require_once "$pmPath/pmConfig.php";
    require_once "$pmPath/pmAPI.php";
	$sf="$pmPath/pmStrings_$pmLanguage.php";
	if (!file_exists($sf)) $sf="$pmPath/pmStrings_en.php";
	require_once $sf;

	$pmDocument=array('status'=>'', 'preHead'=>'', 'head'=>'', 'body'=>'', 'postBody'=>'');
// -----------------------------------------------------------------------------
function pmExecuteTemplate($template)
{
	global $pmPath, $pmTemplatesPath, $pmTemplatesURL, $pmDocument;
	global $_pmStringTemplateFileNotFound;

	$template_file="$pmTemplatesPath/$template/template.php";
	if (!file_exists($template_file))
	{ echo sprintf($_pmStringTemplateFileNotFound, $template); return; }
	ob_start();
	$code=file_get_contents($template_file);
	eval("?>$code");
	$in=ob_get_contents();
	ob_end_clean();
	$css_file="$pmTemplatesURL/$template/css/template.css";
    $pmDocument['head'].="\n<link href='$css_file' rel='stylesheet' type='text/css'>";
	$processing=true;
	$state='preHead';
	$templateURI="$pmTemplatesURL/$template";
	$template_pos=0; $template_length=strlen($in);
	while($template_pos<$template_length)
	{
		$tag=pmGetNextPMTagFromFile($in, $template_pos, $template_length);
		$tag['tag']=str_replace('@!template@', $templateURI, $tag['tag']);
		$tag['prefix']=str_replace('@!template@', $templateURI, $tag['prefix']);
		$t=trim(substr(strtolower($tag['tag']), 1, -1));
		if ($t!='head' && $t!='/head' && $t!='/html')
		{
			$pmDocument[$state].=$tag['prefix'];
			if ($t=='script') $processing=false;
			if ($t=='/script') $processing=true;
			if ($t!='' && ($t[0]!='@' || !$processing))
				$pmDocument[$state].=$tag['tag'];
			else
				$pmDocument[$state].=pmTagProcessing($template, $tag['tag'], $templateURI);
		}
		else
        {
			switch($t)
			{
				case 'head':
					$state='head';
					break;
				case '/head':
					$state='body';
					break;
			}
		}
	}
	$pmDocument['body']=pmTranslateStringResource(pmTextContentTransform($pmDocument['body']));
	$pmDocument['head']=pmTranslateStringResource($pmDocument['head']);
	if ($pmDocument['status']!='') header($pmDocument['status'], true);
	echo <<<stop
{$pmDocument['preHead']}
<head>{$pmDocument['head']}
</head>{$pmDocument['body']}
{$pmDocument['postBody']}</html>
stop;
/*
	$pmDocument['preHead']=htmlentities($pmDocument['preHead'], ENT_QUOTES);
	echo "<h1>preHead</h1>{$pmDocument['preHead']}";
	$pmDocument['head']=htmlentities($pmDocument['head'], ENT_QUOTES);
	echo "<h1>head</h1>{$pmDocument['head']}";
	$pmDocument['body']=htmlentities($pmDocument['body'], ENT_QUOTES);
	echo "<h1>body</h1>{$pmDocument['body']}";
*/
}
// -----------------------------------------------------------------------------
function pmGetNextPMTagFromFile($in, &$template_pos, $template_length)
{
	$tag=array('prefix'=>'', 'tag'=>'');
	$ch='';	$in_tag=false; $in_quotes=false; $quotes_type='';
	while($template_pos<$template_length && $ch!='>' && $ch!==false)
	{
		$ch=$in[$template_pos];
		$template_pos++;
		if ($template_pos<=$template_length)
		{
			if($in_quotes)
			{
				if ($ch==$quotes_type) $in_quotes=false;
			}
			else
			{
				if ($ch=='\'' || $ch=='"') { $in_quotes=true; $quotes_type=$ch; }
				if ($ch=='<') $in_tag=true;
			}
			if ($in_tag)
				$tag['tag'].=$ch;
			else
				$tag['prefix'].=$ch;
		}
	}
	return $tag;
}
// -----------------------------------------------------------------------------
function pmTagProcessing($template, $tag, $templateURI)
{
	global $pmPath, $pmTemplatesPath, $_pmTag;
	global $_pmStringModuleFileNotFound;

    $tag=trim(substr($tag, 2, -1));
	$tag=pmExplodeTag($tag);
	if (!isset($tag['tag'])) return '';
	if ($tag['tag'][0]!='!')
		$mod_file="$pmTemplatesPath/include/{$tag['tag']}.php";
	else
	{
        $t=substr($tag['tag'], 1);
		$mod_file="$pmTemplatesPath/$template/include/$t.php";
	}
	if (!file_exists($mod_file))
		return sprintf($_pmStringModuleFileNotFound, $tag['tag']);
    $_pmTag=$tag;
	ob_start();
	$code=file_get_contents($mod_file);
	eval("?>$code");
	$res=ob_get_contents();
	ob_end_clean();
	$res=str_replace('@!template@', $templateURI, $res);
	return $res;
}
// -----------------------------------------------------------------------------
function pmExplodeTag($tag)
{
	$t=array();
	$p=0; $in_quotes=false; $quotes_type='';
	$l=strlen($tag);
	$s='';
	while($p<$l)
	{
		$ch=$tag[$p];
		if($in_quotes)
		{ if ($ch==$quotes_type) $in_quotes=false; }
		else
		{ if ($ch=='\'' || $ch=='"') { $in_quotes=true; $quotes_type=$ch; } }
		if ($ch!=' ') $s.=$ch;
		else
			if ($in_quotes) $s.=$ch;
			else
			{
				pmExplodeTagParam($t, $s);
				$s='';
			}
		$p++;
	}
	if ($s!='') pmExplodeTagParam($t, $s);
	return $t;
}
// -----------------------------------------------------------------------------
function pmExplodeTagParam(&$t, $s)
{
	if (!count($t))
	{
		$t['tag']=$s;
		return;
	}
	$p=strpos($s, '=');
	if ($p===false)
		$t[$s]=true;
	else
	{
		$tag=substr($s, 0, $p);
		$s=substr($s, $p+1);
		if (($s[0]=='\'' || $s[0]=='"') && $s[0]==$s[strlen($s)-1]) $s=substr($s, 1, -1);
		$t[$tag]=$s;
	}
}
// -----------------------------------------------------------------------------
function pmGetHTMLTag($str, $i)
{
	$tag='';
	$i++;
	while($i<strlen($str)-1 && $str[$i]==' ') $i++;
	while($i<strlen($str)-1 && $str[$i]!=' ' && $str[$i]!='>')
	{
		$tag.=$str[$i];
		$i++;
	}
	return strtolower($tag);
}
//------------------------------------------------------------------------------
function pmFindTagEnd($str, $i, $render)
{
	while ($i<strlen($str)-1 && $str[$i]!='>')
	{
		if ($render) echo $str[$i];
		$i++;
	}
	if ($i<strlen($str))
		if ($render) echo $str[$i];
	return $i+1;
}
//------------------------------------------------------------------------------
function pmRenderHTMLContent($str)
{
	if (strpos($str, '<body')===false) $in_block=true;
	else $in_block=false;
	$in_comments=false;
	$skip_char=false;
	$len=strlen($str);
	for($i=0; $i<$len; $i++)
	{
		if (!$in_comments && $i<$len-4 && $str[$i]=='<' && $str[$i+1]=='!' && $str[$i+2]=='-' && $str[$i+3]=='-') $in_comments=true;
		if ($in_comments && $i<$len-3 && $str[$i]=='-' && $str[$i+1]=='-' && $str[$i+2]=='>')
		{
			$in_comments=false;
			$i+=3;
		}
		if ($str[$i]=='<' && !$in_comments)
		{
			$tag=pmGetHTMLTag($str, $i);
			if ($tag=='script') $in_block=true;
			if ($tag=='/script')
			{
				$in_block=false;
				$i=pmFindTagEnd($str, $i, true);
			}
			if ($tag=='body')
			{
				$in_block=true;
				$i=pmFindTagEnd($str, $i, false);
			}
			if ($tag=='/body') $in_block=false;
		}
		if (!$skip_char && $in_block && !$in_comments) echo $str[$i];
		$skip_char=false;
	}
	return $res;
}
// -----------------------------------------------------------------------------
function pmGetSpecialContent($pagePath)
{
	if (function_exists('pmProcessSpecialContent'))
		return pmProcessSpecialContent($pagePath);
	else
		return '';
}
// -----------------------------------------------------------------------------
?>