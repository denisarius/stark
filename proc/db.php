<?php
	include_once("{$_SERVER['DOCUMENT_ROOT']}/proc/logs.php");

$_last_sql_query='';
//------------------------------------------------------------------------------
function show_db_global_error()
{
	echo "<h2>Database engine don't configured.</h2>";
	exit;
}
//------------------------------------------------------------------------------
function show_db_error($err)
{
	echo "data base engine error - $err";
	exit;
}
// -----------------------------------------------------------------------------
function connect_db($show_error=true)
{
	global $link, $db_host, $db_login, $db_pass, $db_name, $db_charset;
    if (!isset($link))
    {
    	@$link=mysql_connect($db_host, $db_login, $db_pass);
		if ($link===false && $show_error) show_db_error(mysql_error());
	    @$sel=mysql_select_db($db_name);
		if ($sel===false && $show_error)
		{
			echo "Could not select database";
			exit;
		}
	}
	if ($link!==false) query("set names $db_charset");
	return $link;
}
//------------------------------------------------------------------------------
function query($q)
{
	global $db_debug, $db_full_debug, $_last_sql_query, $db_debug_to_log;
	$err='';
	$_last_sql_query=$q;
	$res=mysql_query($q); $err=mysql_error();
	if ($db_debug && $err!='')
	{
		if (isset($db_debug_to_log) && $db_debug_to_log && function_exists('logs_save_event'))
        	logs_save_event("$q ($err)", 'db');
		else
			echo "$q<br>$err<br>";
	}
	else
		if ($db_full_debug)
		{
			if (isset($db_debug_to_log) && $db_debug_to_log && function_exists('logs_save_event'))
        		logs_save_event($q, 'db');
			else
				echo "<br>[$q]<br>";
		}
	return $res;
}
//------------------------------------------------------------------------------
function get_data($field, $tables='', $condition='')
{
	if ($condition!='') $condition="where $condition";
	if ($tables!='') $tables="from $tables";
	$res=query("select $field $tables $condition");
	if (mysql_num_rows($res))
		$r=mysql_fetch_array($res);
	else
		$r=array(false);
	mysql_free_result($res);
	return $r[0];
}
//------------------------------------------------------------------------------
function get_data_query($field, $query)
{
	$res=query($query);
	if (mysql_num_rows($res))
		$r=mysql_fetch_assoc($res);
	else
		$r[$field]=false;
	mysql_free_result($res);
	if (!isset($r[$field])) $r[$field]=false;
	return $r[$field];
}
//------------------------------------------------------------------------------
function get_data_array($field, $tables, $condition='')
{
	if ($condition!='') $condition="where $condition";
	$res=query("select $field from $tables $condition");
	if (mysql_num_rows($res))
		$r=mysql_fetch_array($res);
	else
		$r=false;
	mysql_free_result($res);
	return $r;
}
//------------------------------------------------------------------------------
function get_data_array_query($query)
{
	$res=query($query);
	if (mysql_num_rows($res))
		$r=mysql_fetch_array($res);
	else
		$r=false;
	mysql_free_result($res);
	return $r;
}
//------------------------------------------------------------------------------
function mysql_safe($str)
{
	return mysql_real_escape_string($str);
}
//------------------------------------------------------------------------------
?>