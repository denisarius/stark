<?php
// -----------------------------------------------------------------------------
function importVars($__names__, $mysql_safe=false)
{
	$__names_list__=explode('|', $__names__);
	foreach($__names_list__ as $__n__)
	{
		global $$__n__;
		if (isset($_REQUEST[$__n__]))
		{
			$__v__=$_REQUEST[$__n__];
			if (get_magic_quotes_gpc()) $__v__=stripslashes($__v__);
			if ($mysql_safe) $__v__=mysql_safe($__v__);
			$$__n__=$__v__;
		}
		else
			unset($$__n__);
	}
}
// -----------------------------------------------------------------------------
function serialize_data($names)
{
    $varNames=explode('|', $names);
    $arg=func_get_args();
    $num=func_num_args();
	$str='{';
    for($i=1; $i<=count($varNames); $i++)
    {
    	$str.="\"{$varNames[$i-1]}\":";
        $v=addslashes($arg[$i]);
		$v=strtr($v, array("\n"=>' ', "\r"=>' ', '/'=>"\\/"));
		$str.="\"$v\",";
    }
	$str=substr($str, 0, -1).'}';
	return $str;
}
// -----------------------------------------------------------------------------
function html_safe($var)
{
	return strtr($var, array('<'=>'&lt;', '>'=>'&gt;', '"'=>'&quot;', '\''=>'&#039;'));
}
// -----------------------------------------------------------------------------

?>