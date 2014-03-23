<?php

class TIndex extends TWidget
{
	public $version=array(1, 0, 0);

// -----------------------------------------------------------------------------
function __construct()
{
	$this->widget_title='�������';
	$this->menu_section='';
	$this->menu_name='�������';
	$this->url='';
}
// -----------------------------------------------------------------------------
public function init($parent)
{
	global $_admin_uploader_path;
    $this->widget_tables=array('_files_data', '_temp_files');
	$this->widget_folders=array($_admin_uploader_path, "$_admin_uploader_path/temp");
	return parent::init($parent);
}
// -----------------------------------------------------------------------------
public function show_start_screen()
{
	global $_site_main_url;
	
	echo <<<stop
<div class="admin_index_block">

<input type="button" onClick="admin_go_to_url('$_site_main_url')" value="������� ����">

</div>
stop;
	foreach($this->parent->get_widgets() as $w)
	{
		$html=$w['obj']->get_index_html();
		if ($html!='') echo "<div class='index_widget_block'>$html</div>";
	}
}
// -----------------------------------------------------------------------------
private function show_global_admin_information()
{
	global $_admin_main_menu;

	if(!admin_check_db_structure())
	{
		foreach($_admin_main_menu as $item)
			if ($item['id']=='service_db') break;
    	echo '���� ������ ��� ����� ����� �� ������� ��� �� ������������� �������� ���������.';
		if ($item['id']!='service_db')
		{
			echo '<br><br>����������������� ���� �� �������� ������ "������������ ��"<br>���������� � �������������� �����.';
			return;
		}
		echo <<<stop
<br><br>�� ������ ������� ��� �������� �� � ������� <a href="{$item['url']}">"������������ ��"</a>.
stop;
	}
}
// -----------------------------------------------------------------------------
}

?>
