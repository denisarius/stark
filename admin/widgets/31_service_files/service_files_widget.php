<?php
	require_once 'service_files_widget_proc.php';

class TService_files extends TWidget
{
	public $version=array(1, 0, 2);
	protected $widget_title='Контроль файлов', $menu_section='Тех. обслуживание', $menu_name='Контроль файлов';
	private $out_change, $out_new, $out_old, $files_counter, $unchaged_counter;
	private $file_extensions	=array('', 'htm', 'html', 'php', 'txt', 'htaccess', 'js');
	private $file_inc_extensions=array('', 'htm', 'html', 'php', 'txt', 'htaccess', 'js');

// -----------------------------------------------------------------------------
function __construct()
{
}
// -----------------------------------------------------------------------------
public function init($parent)
{
	return parent::init($parent);
}
// -----------------------------------------------------------------------------
public function show_start_screen()
{
	global $action;

	importVars('action', false);
	switch($action)
	{
		case 'update':
			$this->update_files_db();
			break;
		default:
			clearstatcache();
			echo <<<stop
<input type="button" id="files_update_state" value="Обновить базу данных фалов" onClick="service_files_update()"><br><br>
<h3>Список измененных фалов</h3>
<table width="100%" border="1" cellpadding="3" cellspacing="0" class="admin_table" id="service_files_changed_table">
<thead><tr>
    <td><b>File path</b></td>
	<td><b>Size&nbsp;(file/db)</b></td>
	<td><b>Mod.&nbsp;time&nbsp;(file/db)</b></td>
	<td><b>Status</b></td>
</tr></thead>
stop;
			$this->out_change='';
			$this->out_new='';
			$this->out_old='';
			$this->files_counter=0;
			$this->unchaged_counter=0;
			$this->pass_files($_SERVER['DOCUMENT_ROOT']);
			if ($this->out_change=='' && $this->out_new=='') $this->out_change=<<<stop
<tr><td colspan="5">Измененных файлов нет</td></tr>
stop;
			if ($this->out_old=='') $this->out_old=<<<stop
<tr><td colspan="4">Неизмененных файлов нет</td></tr>
stop;
			echo <<<stop
{$this->out_change}
{$this->out_new}
</table><br>
<input type="button" class="admin_tool_button" value="Показать неизмененные файлы ({$this->unchaged_counter})" onClick="service_fiels_show_unchanged()">
<br><br>
<div id="service_files_non_changed_files" style="display:none">
<h3>Список неизмененных фалов</h3>
<table width="100%" border="1" cellpadding="3" cellspacing="0" class="admin_table" id="service_files_nonchanged_table">
<thead><tr>
    <td><b>File path</b></td>
	<td><b>Size&nbsp;(file/db)</b></td>
	<td><b>Mod.&nbsp;time&nbsp;(file/db)</b></td>
</tr></thead>
{$this->out_old}
</table>
</div>
stop;
			break;
	}
}
// -----------------------------------------------------------------------------
private function update_files_db()
{
	query('delete from _files_data');
	clearstatcache();
	$this->pass_files_update($_SERVER['DOCUMENT_ROOT']);
	$r=get_data_array('count(*) as fn, sum(size) as fs', '_files_data');
	echo <<<stop
<h3>Данные о файлах обновлены</h3><br><br>
На сайте в данный момент расположено {$r['fn']} файлов занимающих {$r['fs']} байт.
stop;
}
// -----------------------------------------------------------------------------
private function pass_files_update($dir)
{
	$d=opendir($dir);
	while (false !== ($file = readdir($d)))
	{
		if ($file!='.' && $file!='..')
		{
			$fullname="$dir/$file";
			if (is_file($fullname))
			{
				$fi=pathinfo($fullname);
				$ext=strtolower($fi['extension']);
				if (in_array($ext, $this->file_extensions))
				{
					$size=filesize($fullname);
					$time=filemtime($fullname);
					$md5=md5_file($fullname);
					if (in_array($ext, $this->file_inc_extensions))
						$content = mysql_real_escape_string(file_get_contents($fullname));
					else
						$content='';
					query("insert into _files_data (path, size, timestamp, md5, content) values ('$fullname', '$size', '$time', '$md5', '$content')");
				}
			}
			if (is_dir($fullname)) $this->pass_files_update("$dir/$file");
		}
    }
	closedir($d);
}
// -----------------------------------------------------------------------------
private function pass_new($dir)
{
	$d=opendir($dir);
	$l=strlen($_SERVER['DOCUMENT_ROOT'])+1;
	while (false !== ($file = readdir($d)))
	{
		if ($file!='.' && $file!='..')
		{
			$fullname="$dir/$file";
			if (is_file($fullname))
			{
				$fi=pathinfo($fullname);
				$ext=strtolower($fi['extension']);
				if (in_array($ext, $this->file_extensions))
				{
					$res=get_data('id', '_files_data', "path='$fullname'");
					if ($res===false)
					{
						$this->files_counter++;
						$size=filesize($fullname);
						$time=date('d-m-Y\&\n\b\s\p\;H:i:s', filemtime($fullname));
						$fn=substr($fullname, $l);
						$this->out_new.=<<<stop
<tr id='tr_n_$files_counter'>
	<td>$fn</td>
	<td align='center'>$size</td>
	<td align='center'>$time</td>
	<td align='center'><span class='red_text_bold'>new</span></td>
	<td align='center'><input type='button' class='admin_tool_button' value='Удалить' onClick='service_fiels_delete_new("tr_n_$files_counter", "$fn")'></td>
</tr>
stop;
					}
				}
			}
			if (is_dir($fullname)) $this->pass_new("$dir/$file");
		}
    }
	closedir($d);
}
// -----------------------------------------------------------------------------
private function pass_files($dir)
{
	$l=strlen($_SERVER['DOCUMENT_ROOT']);
// выводим информацию о файлах которые есть в БД
	$res=query('select * from _files_data order by path');
	while ($r=mysql_fetch_assoc($res))
	{
		$this->files_counter++;
		$err='';
		$fn=substr($r['path'], $l);
		$out="<tr id='tr_e_{$this->files_counter}'><td>$fn</td>";
		$fi=pathinfo($r['path']);
		$ext=strtolower($fi['extension']);
		if (!file_exists($r['path']))
		{
			$err='not found';
			$out.="<td align='center'>{$r['size']}</td>";
			$d1=date('d-m-Y\&\n\b\s\p\;H:i:s', $r['timestamp']);
			$out.="<td align='center'>$d1</td>";
		}
		else
		{
			$real_size=filesize($r['path']);
			if ($real_size!=$r['size'])
			{
				$err='changed';
				$out.="<td align='center'><b>$real_size/{$r['size']}</b></td>";
			}
			else
				$out.="<td align='center'>{$r['size']}</td>";
			if (filemtime($r['path'])!=$r['timestamp'])
			{
				$err='changed';
				$d1=date('d-m-Y\&\n\b\s\p\;H:i:s', filemtime($r['path']));
				$d2=date('d-m-Y\&\n\b\s\p\;H:i:s', $r['timestamp']);
				$out.="<td align='center'><b>$d1&nbsp;/ $d2</b></td>";
			}
			else
			{
				$d1=date('d-m-Y\&\n\b\s\p\;H:i:s', $r['timestamp']);
				$out.="<td align='center'>$d1</td>";
			}
			if (md5_file($r['path'])!= $r['md5']) $err='changed';
		}
		if ($err=='')
		{
//			$out.='<td><span class="green_text_bold">good</span></td><td>&nbsp;</td>';
			$this->unchaged_counter++;
		}
		else
		{
			if (in_array($ext, $this->file_inc_extensions))
				$button="<input type='button' class='admin_tool_button' value='Восстановить' onClick='service_fiels_restore_changed(\"tr_e_{$this->files_counter}\", \"{$r['id']}\")'>";
			else $button='&nbsp;';
			$out.="<td align='center'><span class='red_text_bold'>$err</span></td><td align='center'>
$button</td>";
		}
		$out.='</tr>';
		if ($err=='')
			$this->out_old.=$out;
		else
			$this->out_change.=$out;
	}
// выводим информацию о новых файлах которых нет в БД
	$this->pass_new($dir);
}
// -----------------------------------------------------------------------------
}
?>