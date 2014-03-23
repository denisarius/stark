<?php
	if (isset($_SESSION)) exit('Global session error');
	require_once '_config.php';
	require_once "$_admin_common_proc_path/db.php";
	require_once "$_admin_common_proc_path/variables.php";

	session_start();
	$link=connect_db(false);

	importVars('action', false);
	switch($action)
	{
		case 'create_backup':
			importVars('include_autoincrement|to_file', false);
			create_buckup($include_autoincrement, $_REQUEST['tbls'], $to_file);
			break;
		case 'restore_backup':
			importVars('file|start|starttime|frame', false);
			if (isset($file) && $file!='')
			{
				if (!isset($start) || $start=='') $start=0;
				if (!isset($frame) || $frame=='') $frame=0;
				if (!isset($starttime) || $starttime=='') $starttime=time();
                restore_db_backup($file, $start, $starttime, $frame);
			}
			break;
		case 'custom_print':
			importVars('id', true);
			if (isset($id) && $id!='')
				customs_print_custom($id);
			else
				echo '<h1>Ошибка вызова процедуры печати.</h1>';
			break;
	}

	mysql_close($link);
// -----------------------------------------------------------------------------
function customs_print_custom($id)
{
	global $_cms_shop_orders, $_cms_shop_orders_goods, $_cms_users_table;
	global $_cms_tree_node_table, $_order_statuses;

	$order=get_data_array('*', $_cms_shop_orders, "id='$id'");
	if ($order!==false) $title="Данные заказа {$order['public_id']} от {$order['date']}";
	else $title='Ошибка вызова процедуры печати.';
	echo <<<stop
<html>
<head>
<title>$title</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="$_admin_css_url/cms.css" rel="stylesheet" type="text/css">
</head>
<body>
stop;
	if (is_array($order))
	{
		$user=get_data_array('*', $_cms_users_table, "id='{$order['user']}'");
		echo <<<stop
<h1>Заказ {$order['public_id']} от {$order['date']}</h1>
Статус заказа: <b>{$_order_statuses[$order['status']]}</b><br>
<h3>Данные заказчика</h3>
<b>{$user['surname']} {$user['name']} {$user['fathername']}</b><br>
EMail: {$user['email']}<br>
Тел. {$user['phone']}<br>
Адрес: {$user['address']}<br>
Паспорт: {$user['pass_serie']} № {$user['pass_number']}<br>
<div class="customs_custom_block" style="border: none;">
<table>
<thead>
<tr><td colspan="5" style="text-align:center;">Заказанные товары</td></tr>
<tr>
<td style="width:50px; text-align: center;">Артикул</td>
<td>Название</td>
<td style="width:70px; text-align: center;">Количество</td>
<td style="width:50px; text-align: center;">Цена</td>
<td style="width:50px; text-align: center;">Сумма</td>
</tr></thead>
stop;
		$res=query("select * from $_cms_shop_orders_goods where `order`='{$order['id']}'");
		$total=0;
		while($r=mysql_fetch_assoc($res))
		{
			$good=get_data_array('name, code', $_cms_tree_node_table, "id='{$r['good']}'");
			$summ=$r['price']*$r['qty'];
			echo <<<stop
<tr>
<td align="center">{$good['code']}</td>
<td>{$good['name']}</td>
<td align="center">{$r['qty']}</td>
<td align="center" id="order_price_{$r['id']}">{$r['price']}</td>
<td align="center" id="order_summ_{$r['id']}">$summ</td>
</tr>
stop;
			$total+=$summ;
		}
		mysql_free_result($res);
		echo <<<stop
<tr><td colspan="4" style="text-align:right; font-weight:bold; padding-right:5px;">Общая сумма</td>
<td id="order_total" style="text-align:center; font-weight:bold;">$total</td>
</tr>
</table>
</div>
<script type="text/javascript">
	window.print();
</script>
stop;
    }
	else
		echo 'Ошибка вызова процедуры печати.';
	echo <<<stop
</body>
</html>
stop;
}
// -----------------------------------------------------------------------------
function buckup_write_string($str)
{
	global $gz_buf, $save_to_file, $out_file;

	$gz_buf.=$str."\n";
	if (strlen($gz_buf)>20480)
	{
		if ($save_to_file='')
		{
			echo gzencode($gz_buf);
			flush();
		}
		else
			fwrite($out_file, $gz_buf);
		$gz_buf='';
	}
}
// -----------------------------------------------------------------------------
function create_buckup($include_autoincrement, $tbls, $to_file)
{
	global $gz_buf, $save_to_file, $out_file, $_admin_backup_path;

	set_time_limit (1000);
	header('HTTP/1.1 200 OK');
//	header("Status: 200\n");

    $save_to_file=$to_file;
	if ($to_file=='')
	{
		header('Content-type: application/zip');
		header('Content-Disposition: attachment; filename=backup_'.date('Y_m_d').'.gz');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0');
		header('Pragma: no-cache');
	}
	else
	{
		echo <<<stop
<html>
<head>
<title>$title</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="$_admin_css_url/cms.css" rel="stylesheet" type="text/css">
</head>
<body>
stop;
		$d=date("Y_m_d");
		$i=0;
		$file="backup_$d.sql";
		while(file_exists("$_admin_backup_path/$file"))
		{
			$i++;
			$file="backup_{$d}_$i.sql";
		}
    	$out_file=fopen("$_admin_backup_path/$file", 'w+');
	}

	$gz_buf='';

	if ($include_autoincrement=='' || $include_autoincrement=='0')
		$include_autoincrement=false;
	else
		$include_autoincrement=true;
	for ($i=0; $i<count($tbls); $i++)
	    buckup_process_table($tbls[$i], $include_autoincrement);

	if($to_file=='')
	{
		if (strlen($gz_buf)>0)
		{
			echo gzencode($gz_buf);
			flush();
		}
	}
	else
	{
		fwrite($out_file, $gz_buf);
		fclose($out_file);
		echo '<b>Резервная копия БД создана</b></body></html>';
	}
}
// -----------------------------------------------------------------------------
function buckup_process_table($tbl, $include_auto_increment)
{
    buckup_write_string("--\n-- Table structure for table '$tbl'\n--\n");
	buckup_write_string("DROP TABLE IF EXISTS $tbl;");
	$res=mysql_query("show create table  $tbl");
	$r=mysql_fetch_assoc($res);
	$s="{$r['Create Table']};\n";
	mysql_free_result($res);
	buckup_write_string($s);
	$res=mysql_query("describe $tbl");
	$fields=array(); $i=0;
	while ($r=mysql_fetch_assoc($res))
	{
	    $fields[$i][0]=$r['Field'];
	    if (strpos($r['Extra'], 'auto_increment')===false)
	        $fields[$i][1]=false;
		else
	        $fields[$i][1]=true;
		$i++;
	}
	$fields_count=$i-1;
	mysql_free_result($res);
	$res=mysql_query("select count(*) from $tbl");
	$r=mysql_fetch_array($res);
	mysql_free_result($res);
	$rec_count=$r[0];
	if ($res_count>0) buckup_write_string("--\n-- Data for table '$tbl'\n--\n");

	for ($j=0; $j<=$fields_count; $j++)
	{
	    if ($fields[$j][1])
		{
		    if ($include_auto_increment)
				$f.="`{$fields[$j][0]}`, ";
		}
		else
			$f.="`{$fields[$j][0]}`, ";
	}
	$f=substr($f, 0, strlen($f)-2);

	$rec_limit=100;
	$size_limit=200000;
	for ($i=0; $i<$rec_count; $i+=$rec_limit)
	{
	    $res=mysql_query("select * from $tbl limit $i, $rec_limit");
		$s='';
	    while ($r=mysql_fetch_assoc($res))
	    {
            if ($s=='') $s="insert into $tbl ($f) values ";
			$v='';
			for ($j=0; $j<=$fields_count; $j++)
			{
			    if ($fields[$j][1])
				{
				    if ($include_auto_increment)
				    {
						$vv=mysql_escape_string($r[$fields[$j][0]]);
						$v.="'$vv', ";
					}
				}
				else
				{
					$vv=mysql_escape_string($r[$fields[$j][0]]);
					$v.="'$vv', ";
				}
			}
			$v=substr($v, 0, strlen($v)-2);
			$s.="($v),";
			if (strlen($s)>$size_limit)
			{
				$s=substr($s, 0, strlen($s)-1).';';
				buckup_write_string($s);
				$s='';
			}
	    }
		$s=substr($s, 0, strlen($s)-1).';';
		buckup_write_string($s);
	    mysql_free_result($res);
	}
	buckup_write_string('');
}
// -----------------------------------------------------------------------------
function restore_db_backup($file, $start, $starttime, $frame)
{
	global $_admin_css_url;

	$baseframe=500000;
	set_time_limit (500);

	if (!isset($frame) || $frame==0) $frame=$baseframe;
	$fsize=filesize($file);
	if (($fh=fopen($file, 'r'))==false) exit("Невозможно открыть файл резервной копии: $file<br>");
	fseek ($fh, $start);
	$buf=fread($fh, $frame);
	fclose($fh);
	$i=0; $lsp=0; $s='';
	$trans = array("\n" => ' ', "\r" => ' ');
	while($i<$frame)
	{
		$s0='';
		while ($i<$frame)
		{
			$s0.=$buf[$i];
			$i++;
			if ($buf[$i-1]=="\n") break;
		}
		$s0=trim($s0);
        if (substr($s0, 0, 2)=='--') $s0='';
		$s.=$s0;
		$s=trim($s);
		if ($s=='') $lsp=$i;
        if (substr($s, -1, 1)==';')
		{
			// обрабатываем строку
			$lsp=$i;
			if (strlen($s)>1)
			{
				strtr($s, $trans);
				query($s);
			}
			$s='';
		}
		flush();
		$laststr=substr($buf, -500, 500);
	}

	if (!$lsp)
	{
		$frame+=$baseframe/2;
		$fr="&frame=$frame";
	}
	else
		$fr='';
	$start+=$lsp;

	if ($start>=$fsize)
	{
		$t=gmdate('H:i:s', time()-$starttime);
		$laststr=nl2br($laststr);
		if (($fh=fopen($file, 'r'))!==false)
		{
			fseek ($fh, -500, SEEK_END);
			$buf=nl2br(fread($fh, $frame));
		}
		fclose($fh);

		echo <<<stop
<script type='text/javascript'>
parent.service_restore_db_show_end_state();
</script>
<b>Восстановление закончено</b>
<table border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse;">
<tr><td>File size</td><td>$fsize</td></tr>
<tr><td>Total&nbsp;processed&nbsp;bytes</td><td>$fsize</td></tr>
<tr><td>Total time</td><td>$t</td></tr>
<tr><td>Last&nbsp;processed&nbsp;bytes</td><td>$laststr</td></tr>
<tr><td>File last bytes</td><td>$buf</td></tr>
<table>
stop;
	}
	else
	{
		$t=gmdate('H:i:s', time()-$starttime);
		$ds=(float)$fsize/$start-1;
		$t1=(int)((time()-$starttime)*$ds);
		$t1=gmdate('H:i:s', $t1);
		$cmd="action=restore_backup&start=$start&file=$file&starttime=$starttime$fr";
		$progress=(float)$start/$fsize;
		$state=<<<stop
<br><table border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse; float:left;">
<tr><td>File length</td><td>$fsize</td></tr>
<tr><td>Last start position</td><td>$start</td></tr>
<tr><td>Frame</td><td>$frame</td></tr>
</table>
<table border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse; float:left; margin-left: 15px;">
<tr><td>Process time</td><td>$t</td></tr>
<tr><td>Time left</td><td>$t1</td></tr>
</table>
<br>
stop;
		$state=str_replace("\r\n", ' ', $state);
		echo <<<stop
<script type='text/javascript'>
parent.service_restore_db_show_state('$state', $progress);
parent.service_restore_db_iteration('$cmd');
</script>
stop;
	}
}
// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
?>