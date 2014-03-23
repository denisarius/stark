<?php
//------------------------------------------------------------------------------
function set_page_title($text)
{
	global $_site_name, $language;

	$text=trim($text);
	if($text!='') $text="$text - ";
	pmHeader("<title>{$text}{$_site_name[$language['id']]}</title>");
}
//------------------------------------------------------------------------------
function get_pager_html($total, $page, $pageLen, $url)
{
	global $_strings_pager_previous_page, $_strings_pager_next_page;
	$totalPages=ceil($total/$pageLen);
	$html='<div class="pager">';
	if ($totalPages>1)
	{
		if ($page>0)
		{
			$u=str_replace('@_page_@', (string)($page-1), $url);
			if (isset($_strings_pager_previous_page) && $_strings_pager_previous_page!='')
				$html.="<a href='$u'>$_strings_pager_previous_page</a>";
		}
		for($i=0; $i<$totalPages; $i++)
		{
			$n=$i+1;
			$u=str_replace('@_page_@', $i, $url);
			if ($i<$page) $as='class="_pager_page_before"';
			else  $as='class="_pager_page_after"';
			if ($i!=$page)
				$html.="<span><a href='$u'>$n</a></span>";
			else
				$html.="<span><a href='$u' class='active'>$n</a></span>";
		}
		if ($page<$totalPages-1)
		{
			$u=str_replace('@_page_@', (string)($page+1), $url);
			if (isset($_strings_pager_next_page) && $_strings_pager_next_page!='')
				$html.="<a href='$u'>$_strings_pager_next_page</a>";
		}
	}
	$html.='<br></div>';
	return $html;
}
//------------------------------------------------------------------------------
function show_content_404()
{
	pmSetStatus('HTTP/1.0 404 Not Found');
	set_page_title('%%_content_not_found_title%%');
	echo <<<stop
<h2>%%_content_not_found%%</h2>
stop;
}
//------------------------------------------------------------------------------
function show_404_text()
{
    header('HTTP/1.0 404 Not Found');
	echo <<<stop
<div class="not_found_warning">
<img src="@!template@/images/404_not_found_stop.png">
<h1>%%_content_not_found%%</h1>
</div>
stop;
}
//------------------------------------------------------------------------------
?>