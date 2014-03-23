<?php
	$_logs_path="{$_SERVER['DOCUMENT_ROOT']}/_logs";
    $_logs_full_trace=false;

//------------------------------------------------------------------------------
function logs_save_event($text, $logName='main')
{
	global $_logs_path, $_logs_full_trace;
	$tr=debug_backtrace();
	$file="$_logs_path/$logName.log";
	$out=fopen($file, 'a+');
	$d=date("Y-m-d H:i:s ");
	$f='';
//	$idx=count($tr)-1;
	$idx=0;
	$tr[$idx]['file']=mb_substr($tr[$idx]['file'], mb_strlen($_SERVER['DOCUMENT_ROOT'])+1);
	$f="({$tr[$idx]['file']} [{$tr[$idx]['line']}]) ";
	$str="$d $f$text";
	fwrite($out, "$str\n");
	fclose($out);
}
//------------------------------------------------------------------------------
function logs_last_sql_query()
{
	global $_last_sql_query;
	return $_last_sql_query;
}
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
?>