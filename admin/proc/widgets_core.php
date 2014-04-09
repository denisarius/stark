<?php
class TWidgets
{
	private $current_widget;
	private $widgets=array();
// -----------------------------------------------------------------------------
function __construct()
{
	global $_admin_widgets_path;

	$uri=$_SERVER['REQUEST_URI'];
	$pp=pathinfo($_SERVER['PHP_SELF']);
	$l=strlen($pp['dirname']);
	$uri=substr($uri, $l+1);
	$p=strpos($uri, '?');
	if ($p!==false) $uri=substr($uri, 0, $p);
	$p=strpos($uri, '.');
	if ($p!==false) $uri=substr($uri, 0, $p);
	$this->current_widget=$uri;
	if ($this->current_widget=='') $this->current_widget='index';
	if (is_dir($_admin_widgets_path))
	    if ($d = opendir($_admin_widgets_path))
		{
			$wids=array();
	        while (($file = readdir($d)) !== false)
            	if ($file!='.' && $file!='..' && is_dir("$_admin_widgets_path/$file"))
					array_push($wids, $file);
	        closedir($d);
			sort($wids);
			foreach($wids as $file)
			{
				$path=$file;
				$p=strpos($file, '_');
				if ($p!==false) $file=substr($file, $p+1);
               	$this->widgets[$file]=array('id'=>$file, 'url'=>"{$file}.php", 'path'=>$path);
			}
	    }
	else
		echo 'Can\'t find wingets directory';
	if (!isset($_SESSION['db_checked'])) $_SESSION['db_checked']='no';
}
// -----------------------------------------------------------------------------
public function get_widgets()
{
	return $this->widgets;
}
// -----------------------------------------------------------------------------
public function get_widget($id)
{
    if (array_key_exists($id, $this->widgets))
		return $this->widgets[$id];
	else
		return false;
}
// -----------------------------------------------------------------------------
public function set_widget_object($name, $obj)
{
	$this->widgets[$name]['obj']=$obj;
	$obj->set_id($name);
	$status=$obj->init($this);
	foreach($status as $k => $v)
		$this->widgets[$name][$k]=$v;
}
// -----------------------------------------------------------------------------
public function get_current_section_name()
{
	if(array_key_exists($this->current_widget, $this->widgets))
		return $this->widgets[$this->current_widget];
	else
		return '';
}
// -----------------------------------------------------------------------------
public function show_main_menu()
{
    global $_widgets_sections, $_admin_root_url;

	$processed=array();
	echo '<div class="admin_main_menu" id="admin_main_menu">';
	foreach($_widgets_sections as $section=>$section_modules)
	{
		$section_title_show=false;
		foreach($this->widgets as $widget)
		{
            $ws=$widget['menu_section'];
			$pos=array_search($widget['id'], $section_modules);
			if ($pos!==false) $ws=$section;
			if ($ws==$section)
			{
				if (array_search($widget['id'], $processed)!==false) continue;
				array_push($processed, $widget['id']);
				if($section!='' && !$section_title_show)
				{
					echo "<div><span>$section</span></div>";
					$section_title_show=true;
				}
	            $title=$widget['obj']->process_menu_item_title();
				if (!is_array($title))
				{
					$p=strpos($widget['url'], '.');
					if ($p!==false) $c_url=substr($widget['url'], 0, $p);
					else $c_url='';
					$url=$widget['url'];
					if ($url=='') $url=$_admin_root_url;
					if ($this->current_widget!=$c_url)
						echo "<a href='$url'>$title</a>";
					else
						echo "<a href='$url' class='admin_main_menu_current_item'>$title</a>";
				}
				else
				{
					$url=$widget['url'];
					foreach($title as $tk=>$tv)
					{
						$p=strpos($url[$tk], '&');
						if ($p!==false) $c_url=substr($url[$tk], 0, $p);
						$url[$tk]=$url[$tk];
						if ($url[$tk]=='') $url[$tk]=$_admin_root_url;
						if ($this->current_widget!=$c_url)
							echo "<a href='{$url[$tk]}'>$tv</a>";
						else
							echo "<a href='{$url[$tk]}' class='admin_main_menu_current_item'>$tv</a>";
					}
				}
			}
		}
	}
	echo '</div>';
}
// -----------------------------------------------------------------------------
public function show_content()
{
	$cw=$this->current_widget;
	echo <<<stop
<div class="admin_content_frame">
<div class="admin_work_container">
stop;
	if ($this->check_folders() && $this->check_db() && isset($this->widgets[$cw]))
	{
//		$title=$this->widgets[$cw]['widget_title'];
		$title=$this->widgets[$cw]['obj']->get_title();
		$widget_version=pmVersion2Str($this->widgets[$cw]['obj']->version);
		echo "<h1>$title<div class='admin_widget_version'>version: $widget_version</div></h1><br>";
		$res=$this->widgets[$cw]['obj']->show_start_screen();
	}
	echo <<<stop
</div>
</div><br>
stop;
}
// -----------------------------------------------------------------------------
public function get_js_includes_tags()
{
	global $_admin_widgets_url, $_admin_widgets_path;
	$str=<<<stop
<script type='text/javascript' src='$_admin_widgets_url/common_widget.js'></script>

stop;
	if (isset($this->widgets[$this->current_widget]))
	{
		$widget=$this->widgets[$this->current_widget];
		$fn="$_admin_widgets_path/{$widget['path']}/{$widget['id']}_widget.js";
		if (file_exists($fn))
		$str.=<<<stop
<script type="text/javascript" src="$_admin_widgets_url/{$widget['path']}/{$widget['id']}_widget.js"></script>

stop;
		$js=$widget['obj']->get_addon_js();
		foreach($js as $s)
			$str.=<<<stop
<script type="text/javascript" src="$s"></script>

stop;
	}
	return $str;
}
// -----------------------------------------------------------------------------
public function get_css_includes_tags()
{
	global $_admin_widgets_url, $_admin_widgets_path;
	$str='';
	if (isset($this->widgets[$this->current_widget]))
	{
		$widget=$this->widgets[$this->current_widget];
		$fn="$_admin_widgets_path/{$widget['path']}/{$widget['id']}_widget.css";
		if (file_exists($fn))
			$str.=<<<stop
<link href="$_admin_widgets_url/{$widget['path']}/{$widget['id']}_widget.css" rel="stylesheet" type="text/css">

stop;
	}
	return $str;
}
// -----------------------------------------------------------------------------
private function check_db()
{
	global $_admin_check_db_once;

	if ($_SESSION['db_checked']=='no')
	{
		$tables=array();
		foreach($this->widgets as $w)
		{
			$tbl=$w['obj']->get_widget_tables();
			foreach($tbl as $t)
				if ($t!='') array_push($tables, $t);
		}
		$tables=array_unique($tables);
		if (!admin_check_db_structure($tables))
		{
			echo <<<stop
<h1>Проверка структуры БД</h1><br>
stop;
			if ($this->current_widget=='db_repair')
			{
				echo admin_repair_db_structure($tables);
				if ($_admin_check_db_once) $_SESSION['db_checked']=='yes';
			}
			else
				echo <<<stop
<div class="admin_dev_note"><b>База данных для этого сайта не создана или не соответствует заданной структуре.</b>
<br><br><a href="db_repair.html">Исправить ошибки в структуре БД</a></div>
stop;
			return false;
		}
        else
		{
			if ($_admin_check_db_once) $_SESSION['db_checked']='yes';
			return true;
		}
	}
	else
		return true;
}
// -----------------------------------------------------------------------------
private function check_folders()
{
	$html='';
	foreach($this->widgets as $w)
	{
		$html.=$w['obj']->check_folders();
	}
	if ($html!='')
	{
		echo <<<stop
<h1>Проверка структуры папок</h1><br>
$html
stop;
		return false;
	}
	else
		return true;
}
// -----------------------------------------------------------------------------
} // End of TWidgets

class TWidget
{
	public $version=array(0, 0, 0);
	protected $widget_id='', $widget_title='', $menu_section='', $menu_name='', $url='*n/a*';
	protected $widget_tables=array();
	protected $widget_folders=array();
	protected $parent=NULL;

// -----------------------------------------------------------------------------
public function init($parent=NULL)
{
	$this->parent=$parent;
	$status=array('widget_title'=>$this->widget_title,
			'menu_section'=>$this->menu_section,
			'menu_name'=>$this->menu_name
		);
	if (isset($this->url) && $this->url!='*n/a*') $status['url']=$this->url;
	return $status;
}
// -----------------------------------------------------------------------------
public function set_id($id)
{
	$this->widget_id=$id;
}
// -----------------------------------------------------------------------------
public function get_version()
{
	return pmVersion2Str($this->version);
}
// -----------------------------------------------------------------------------
public function process_menu_item_title()
{
	return $this->menu_name;
}
// -----------------------------------------------------------------------------
public function get_index_html()
{
	return '';
}
// -----------------------------------------------------------------------------
public function get_db_structure()
{
	return '';
}
// -----------------------------------------------------------------------------
public function get_addon_js()
{
	return array();
}
// -----------------------------------------------------------------------------
public function get_title()
{
	return $this->widget_title;
}
// -----------------------------------------------------------------------------
public function get_widget_tables()
{
	return $this->widget_tables;
}
// -----------------------------------------------------------------------------
public function check_folders()
{
	global $_admin_uploader_path;

	$html='';
	foreach($this->widget_folders as $folder)
		$html.=$this->check_folder($folder);
	if ($html!='')
		$html=<<<stop
<div style="margin-bottom: 10px">
<b>Модуль {$this->widget_title}</b>
<ul>
$html
</ul>
</div>
stop;
	return $html;
}
// -----------------------------------------------------------------------------
public function repair_folders()
{
}
// -----------------------------------------------------------------------------
protected function check_folder($path)
{
	clearstatcache ();
	$local_path=get_local_path($path);
	$html='';
	if (!is_dir($path))
		$html="<li>отсутствует папка: <b>$local_path</b></li>";
	elseif (!is_writable($path))
		$html="<li>не правильные атрибуты папки: <b>$local_path</b> (0x".sprintf("%X", fileperms($path)&0x0FFF).')</li>';
	return $html;
}
// -----------------------------------------------------------------------------
protected function get_property($property)
{
	if ($this->parent===NULL) return '';
	$w=$this->parent->get_widget($this->widget_id);
	if (isset($w)) return $w[$property];
	else return '';
}
// -----------------------------------------------------------------------------
} // End of TWidget


?>