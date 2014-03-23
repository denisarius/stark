<?php
//------------------------------------------------------------------------------
function str_exec($code)
{
	ob_start();
	eval("?>$code");
	$res=ob_get_contents();
	ob_end_clean();
	return $res;
}
//------------------------------------------------------------------------------
function str2url($name)
{
	$rus_tr=array('й'=>'y', 'ц'=>'c', 'у'=>'u', 'к'=>'k', 'е'=>'e', 'н'=>'n', 'г'=>'g', 'ш'=>'sh', 'щ'=>'sh', 'з'=>'z', 'х'=>'h', 'ъ'=>'', 'ф'=>'f', 'ы'=>'y', 'в'=>'v', 'а'=>'a', 'п'=>'p', 'р'=>'r', 'о'=>'o', 'л'=>'l', 'д'=>'d', 'ж'=>'zh', 'э'=>'e', 'я'=>'ya', 'ч'=>'ch', 'с'=>'s', 'м'=>'m', 'и'=>'i', 'т'=>'t', 'ь'=>'', 'б'=>'b', 'ю'=>'yu', 'ё'=>'yo');

	$name=mb_strtolower(trim($name));
    $name=strtr($name, $rus_tr);
	$n='';
	for ($i=0; $i<mb_strlen($name); $i++)
	{
		if (($name[$i]>='a' && $name[$i]<='z') || ($name[$i]>='0' && $name[$i]<='9'))
			$n.=$name[$i];
		else
			if (mb_strlen($n)>0 && $n[mb_strlen($n)-1]!='-') $n.='-';
	}
	if (mb_strlen($n)>100) $n=mb_substr($n, 0, 100);
	return $n;
}
//------------------------------------------------------------------------------
function str2html($str)
{
	$safeTrans=array('<'=>'&lt;', '>'=>'&gt;', '"'=>'&quot;', '\''=>'&#039;', "\n"=>' ');
	return strtr($str, $safeTrans);
}
//------------------------------------------------------------------------------
function date2string($date)
{
	global $language, $_strings_month_names;
	// yyyy-mm-dd
	$d=(int)mb_substr($date, 8, 2);
	$m=(int)mb_substr($date, 5, 2);
	$y=(int)mb_substr($date, 0, 4);
	$mn=$_strings_month_names[$language['id']][1][$m-1];
	return "$d $mn $y";
}
//------------------------------------------------------------------------------
function date2str($date)
{
	global $language, $_strings_month_names;
	// yyyy-mm-dd
	$delimiter='.';
	$d=(int)mb_substr($date, 8, 2);
	$m=(int)mb_substr($date, 5, 2);
	$y=(int)mb_substr($date, 0, 4);
	return "$d{$delimiter}$m{$delimiter}$y";
}
//------------------------------------------------------------------------------
function get_pager($total, $page, $pageLen, $pageFunc)
{
	$html='<div class="admin_pager">';
	$totalPages=ceil($total/$pageLen);
	for($i=0; $i<$totalPages; $i++)
	{
		$n=$i+1;
		if ($i!=$page)
			$html.=<<<stop
<span onClick="$pageFunc($i);">$n</span>
stop;
		else
			$html.=<<<stop
<span class="pager_page_active">$n</span>
stop;
	}
	$html.='</div>';
	return $html;
}
//------------------------------------------------------------------------------
function p2br($str)
{
	if (strpos($str, '<p>')===false) return $str;
	$str=trim(strtr($str, array('&nbsp;'=>' ', '<br>'=>'<br />', '<p>'=>' ', '</p>'=>'<br />',)));
	if (mb_substr($str, -6)=='<br />') $str=mb_substr($str, 0, -6);
	return $str;
}
//------------------------------------------------------------------------------
function get_first_p($str)
{
	$p0=strpos($str, '<p>');
	if ($p0===false) $p0=strpos($str, '<P>');
	if ($p0===false) $p0=strpos($str, '<p >');
	if ($p0===false) $p0=strpos($str, '<P >');
	if ($p0===false) return $str;
	$p1=strpos($str, '</p>');
	if ($p1===false) $p1=strpos($str, '</P>');
	if ($p1===false) return substr($str, $p0+3);
	return substr($str, $p0+3, $p1-$p0-3);
}
//------------------------------------------------------------------------------
function send_feedbak($name, $email, $text)
{
	global $_site_mail_admin;

	$html=<<<stop
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<body bgcolor="#ffffff" text="#000000">
<h3>Обращение на сайте</h3>
<table cellpadding="5">
<tr><td>Имя</td><td><b>$name</b></td></tr>
<tr><td>EMail</td><td><b><a href="mailto:$email">$email</a></b></td></tr>
<tr><td colspan="2">Текст обращения</td></tr>
<tr><td colspan="2">$text</td></tr>
</table>
</body></html>
stop;
	$mail = new PHPMailer(true);
	$mail->IsSendmail();
	try {
		$mail->AddReplyTo($_site_mail_admin, 'Сайт GurmanLove.ru');
		$mail->SetFrom($_site_mail_admin, 'Сайт GurmanLove.ru');
		$mail->Subject = "Обращение на сайте";
		$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
		$mail->CharSet = 'windows-1251';
		$mail->MsgHTML($html);
		$mail->AddAddress($_site_mail_admin);
		$mail->Send();
	} catch (phpmailerException $e) { echo 'error'; }

}
//------------------------------------------------------------------------------
?>
