<?php
	require_once 'service_db_widget_proc.php';

class TService_db extends TWidget
{
	public $version=array(1, 0, 2);
	protected $widget_title='Обслуживание БД', $menu_section='Тех. обслуживание', $menu_name='Обслуживание БД';

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
	echo <<<stop
<script type="text/javascript">
$(document).ready(function(){
	$("iframe").load(function() {
		this.style.height = this.contentWindow.document.body.offsetHeight + 'px';
	});
});
</script>
<div class="admin_input">
<h2>Восстановление БД</h2>
stop;
	$this->show_db_restore_form();
	echo <<<stop
</div><br><br>
<div class="admin_input">
<h2>Сохранение БД</h2>
stop;
	$this->show_db_backup_form();
	echo '</div>';
}
// -----------------------------------------------------------------------------
private function show_db_backup_form()
{
	echo <<<stop
<form action="service.php" method="post" target="service_create_frame" id="service_backup_form" onSubmit="return service_db_buckup_create()">
<input type="hidden" name="action" value="create_backup">
<table width="98%" border="0">
stop;
	$column=4; $c=0;
	$res=query('show tables');
	while ($r=mysql_fetch_array($res))
	{
	    if ($c==0) echo '<tr>';
	    echo "<td width='25%'><label><input name='tbls[]' type='checkbox' id='tbls' value='{$r[0]}' checked>&nbsp;&nbsp;{$r[0]}</label></td>";
	    if ($c==$column-1) echo '</tr>';
	    $c++;
	    if ($c==$column) $c=0;
	}
	mysql_free_result($res);
	if ($c!=0)
	{
		for ($c; $c<$column; $c++)
	    	echo '<td width="25%">&nbsp</td>';
		echo '</tr>';
	}
	echo <<<stop
</table>
<hr width="98%" size="1" noshade>
<input type="button" class="admin_tool_button" onClick="service_db_select_all();" value="Выбрать все">
&nbsp;&nbsp;&nbsp;
<input type="button" class="admin_tool_button" onClick="service_db_select_none();" value="Снять выбор">
<br><br>
<label><input name="include_autoincrement" type="checkbox" id="include_autoincrement" value="1" checked>&nbsp;&nbsp;Включать 'autoincrement' поля</label><br>
<label><input name='to_file' type='checkbox' id='to_file' value='1' checked>&nbsp;&nbsp;Сохранить на диск</label>
<br><br>
<div id="service_backup_submit_block"><input type="submit" id="db_save_submit" value="Создать резервную копию БД"></div>
<iframe width="100%" height="0" src="" name="service_create_frame" id="service_create_frame" frameborder="no" marginwidth="0" marginheight="0" scrolling="no"></iframe>
</form>
stop;
}
// -----------------------------------------------------------------------------
private function show_db_restore_form()
{
	global $_admin_backup_path, $_admin_backup_url;
	echo '<div id="restore_db_debug_img"></div><div id="restore_db_debug"></div>';
	$i=0;
	if (($dh=opendir("$_admin_backup_path")) !==false)
	{
		$html='';
		while (($file = readdir($dh)) !== false)
		{
			$fn="$_admin_backup_path/$file";
			$fparts=pathinfo($fn);
			if (strtolower($fparts["extension"])=='sql')
			{
				if (!$i) $html.=<<<stop
<table class="admin_table" id="service_db_restore_table">
<thead><tr>
<td>Date</td>
<td>File name</td>
<td>File size</td>
<td></td>
</tr></thead>
stop;
				$size=filesize($fn);
				$date=date('d-m-Y', filectime($fn));
				$html.=<<<stop
<tr>
<td>$date</td>
<td><a href="$_admin_backup_url/$file"><b>$file</b></a></td>
<td>$size</td>
<td><input type="button" onClick="service_restore_db('$fn')" value="Восстановить БД"></td>
</tr>
stop;
				$i++;
			}
		}
		closedir($dh);
		if ($i) $html.=<<<stop
</table>
<iframe width="100%" height="0" src="" id="service_restore_frame" frameborder="no" marginwidth="0" marginheight="0" scrolling="no"></iframe>
stop;
	}
	if ($i) echo $html;
}
// -----------------------------------------------------------------------------
}
?>