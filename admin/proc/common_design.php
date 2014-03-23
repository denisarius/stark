<?php
	require_once "$_admin_pmEngine_path/pmMain.php";

//------------------------------------------------------------------------------
function show_admin_top()
{
	echo <<<stop
stop;
}
//------------------------------------------------------------------------------
function show_admin_content_frame($_admin_current_section)
{
	echo <<<stop
<div class="admin_content_frame">
stop;
}
//------------------------------------------------------------------------------
function show_admin_content_frame_end()
{
	echo '</div><br>';
}
//------------------------------------------------------------------------------
function show_admin_workframe_start($head, $title='')
{
	global $_admin_css_url, $html_charset;
	echo <<<stop
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title>$title</title>
$head
<meta http-equiv="Content-Type" content="text/html; charset=$html_charset">
<link href="$_admin_css_url/admin.css" rel="stylesheet" type="text/css">
</head>
<body class="yscroll" style="display: none;">
<center>
<div class="admin_workframe">
stop;
}
//------------------------------------------------------------------------------
function show_admin_workframe_end()
{
	global $pmEngineVersion, $pmAdminVersion;
	$ve=pmVersion2Str($pmEngineVersion);
	$va=pmVersion2Str($pmAdminVersion);
	echo <<<stop
</div>
<div class="admin_version_signature">pmAdmin v $va</div>
<div class="admin_version_signature">pmEngine v $ve</div>
</center>
</body>
</html>
stop;
}
//------------------------------------------------------------------------------
function show_break()
{
	echo '<br>';
}
//------------------------------------------------------------------------------
function get_admin_pager($total, $page, $pageLen, $pageFunc)
{
	$html='<div class="admin_pager">';
	$totalPages=ceil($total/$pageLen);
	if($totalPages>1)
	{
		for($i=0; $i<$totalPages; $i++)
		{
			$n=$i+1;
			if ($i!=$page)
				$html.=<<<stop
<span onClick="$pageFunc($i);">$n</span>
stop;
			else
				$html.=<<<stop
<span class="admin_pager_page_active">$n</span>
stop;
		}
	}
	$html.='</div>';
	return $html;
}
//------------------------------------------------------------------------------
?>