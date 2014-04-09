<?php
// -----------------------------------------------------------------------------
function admin_check_db_structure($tables)
{
	global $_admin_db_structure, $_admin_root_path;

    if (!isset($_SESSION['db_config_crc'])) $_SESSION['db_config_crc']='no crc';
	$crc=md5_file("$_admin_root_path/_config_db.php");
	if ($_SESSION['db_config_crc']==$crc) return true;
	$db_name=get_data('database()');
	if ($db_name=='') return false;
	foreach($_admin_db_structure as $table)
	{
        if (in_array($table['name'], $tables))
		{
			if (!admin_db_check_table_exists($table['name'])) return false;
			foreach($table['fields'] as $field)
				if (admin_db_check_table_field($table['name'], $field)!=1) return false;
			if (array_key_exists('indexes', $table))
				foreach($table['indexes'] as $index)
					if (admin_db_check_table_index($table['name'], $index)!=1) return false;
		}
	}
	if (!admin_db_check_unnecessary_tables(true, $tables)) return false;
	if (!admin_db_check_unnecessary_fields(true, $tables)) return false;
	if (!admin_db_check_unnecessary_indexes(true, $tables)) return false;
	return true;
}
// -----------------------------------------------------------------------------
function admin_db_check_table_exists($name)
{
	$t=get_data_query('Name', "show table status like '$name'");
	if ($t===false) return false;
	return true;
}
// -----------------------------------------------------------------------------
function admin_db_check_table_field($name, $field)
{
	$t=get_data_array_query("show columns from $name like '{$field['name']}'");
	if ($t===false) return -1;
	if (strtolower($t['Type'])!=strtolower($field['type'])) return -2;
	if ($field['primary'] && $t['Key']!='PRI' && strpos($t['Extra'], 'auto_increment')===false) return -5;
	if ($field['primary'] && $t['Key']!='PRI') return -3;
	if ($field['primary'] && strpos($t['Extra'], 'auto_increment')===false) return -4;
	return 1;
}
// -----------------------------------------------------------------------------
function admin_db_check_table_index($name, $index)
{
	$res=query("show index from `$name`");
    while($r=mysql_fetch_assoc($res))
	{
		if (isset($index['fulltext']) && $index['fulltext']) $index_type='FULLTEXT';
		else $index_type='BTREE';
		if ($r['Key_name']==$index['name'] && $r['Index_type']==$index_type) return 1;
		if ($r['Key_name']==$index['name'] && $r['Index_type']!=$index_type) return -2;
	}
//	echo "Не хватает индекса {$index['name']} в таблице $name<br>";
	return -1;
}
// -----------------------------------------------------------------------------
function admin_db_check_unnecessary_tables($only_check, $tables)
{
	global $_admin_db_structure;
	$res=query('show tables');
	$tbl=array();
	foreach($_admin_db_structure as $table)
		if (in_array($table['name'], $tables)) array_push($tbl, $table['name']);
	while ($r=mysql_fetch_array($res))
	{
		if (!in_array($r[0], $tbl) || !in_array($r[0], $tables))
		{
//			echo "лишняя таблица - {$r[0]}<br>";
			if ($only_check) return false;
			query("drop table `{$r[0]}`");
		}
	}
	mysql_free_result($res);
	return true;
}
// -----------------------------------------------------------------------------
function admin_db_check_unnecessary_fields($only_check, $tables)
{
	global $_admin_db_structure;
	$res=query('show tables');

	while ($r=mysql_fetch_array($res))
	{
		foreach($_admin_db_structure as $table)
			if ($table['name']==$r[0] && in_array($table['name'], $tables)) $fields=$table['fields'];
		if (isset($fields))
		{
			$fld=array();
			foreach($fields as $field)
				array_push($fld, $field['name']);
			$resC=query("show columns from `{$r[0]}`");
			while ($rc=mysql_fetch_assoc($resC))
				if (!in_array($rc['Field'], $fld))
				{
//					echo "лишнее поле {$rc['Field']} в таблице {$r[0]}<br>";
					if ($only_check) return false;
					query("alter table `{$r[0]}` drop `{$rc['Field']}`");
					echo "Удалено поле '{$rc['Field']}' из таблицы {$r[0]}<br>";
				}
		}
	}
	mysql_free_result($res);
	return true;

}
// -----------------------------------------------------------------------------
function admin_db_check_unnecessary_indexes($only_check, $tables)
{
	global $_admin_db_structure;
	$res=query('show tables');

	while ($r=mysql_fetch_array($res))
	{
		unset($indexes);
		foreach($_admin_db_structure as $table)
			if ($table['name']==$r[0] && in_array($table['name'], $tables) && array_key_exists('indexes', $table)) $indexes=$table['indexes'];
		if (isset($indexes))
		{
			$idx=array();
			foreach($indexes as $index)
				array_push($idx, $index['name']);
			$resC=query("show index from `{$r[0]}`");
			while ($rc=mysql_fetch_assoc($resC))
				if ($rc['Key_name']!='PRIMARY' && !in_array($rc['Key_name'], $idx))
				{
					echo "лишний индекс {$rc['Key_name']} в таблице {$r[0]}<br>";
					if ($only_check) return false;
					query("alter table `{$r[0]}` drop index `{$rc['Key_name']}`");
					echo "Удален индекс '{$rc['Key_name']}' из таблицы {$r[0]}<br>";
				}
		}
	}
	mysql_free_result($res);
	return true;

}
// -----------------------------------------------------------------------------
function admin_repair_db_structure($tables)
{
	global $db_name, $db_charset, $db_collation, $_admin_db_structure;

	ob_start();
	echo '<div class="admin_input"><h3>Восстановление структуры БД</h3>';
    // Проверяем существование БД
	$db=get_data('database()');
	if ($db=='')
	{
		// Создаем БД
		query("create database `$db_name` default character set $db_charset default collate $db_collation");
		mysql_select_db($db_name);
		echo "Создана БД '$db_name'<br>";
	}
	admin_db_check_unnecessary_indexes(false, $tables);
	foreach($_admin_db_structure as $table)
	{
		if (in_array($table['name'], $tables))
		{
			// Проверяем наличие таблицы
			if (!admin_db_check_table_exists($table['name']))
			{
				// Создаем таблицу
	            $query=admin_db_get_create_table_sql($table);
				query($query);
				echo "Создана таблица '{$table['name']}'<br>";
			}
			foreach($table['fields'] as $field)
				if (($code=admin_db_check_table_field($table['name'], $field))!=1)
				{
					// Редактируем параметры поля таблицы
					switch($code)
					{
						case -1:
							// Поле не существует - создаем его
							$query=admin_db_get_create_field_sql($table['name'], $field);
							query($query);
							echo "Добавлено поле '{$field['name']}' в таблицу {$table['name']}<br>";
							break;
						case -2:
							// Тип поля не совпадает - изменяем тип
							$query=admin_db_get_alter_field_sql($table['name'], $field);
							query($query);
							echo "Изменен тип поля '{$field['name']}' в таблице {$table['name']}<br>";
							break;
						case -5:
							$query=admin_db_get_alter_field_primary_sql($table['name'], $field);
							query($query);
							$query=admin_db_get_alter_field_auto_sql($table['name'], $field);
							query($query);
							echo "Испарвлены параметры поля '{$field['name']}' в таблице {$table['name']}<br>";
							break;
						case -3:
							// Отсутствует праймари индекс
							$query=admin_db_get_alter_field_primary_sql($table['name'], $field);
							query($query);
							echo "Добавлен праймари индекс '{$field['name']}' в таблицу {$table['name']}<br>";
							break;
						case -4:
							// Отсутствует auto_increment
							$query=admin_db_get_alter_field_auto_sql($table['name'], $field);
							query($query);
							echo "Изменены параметры поля '{$field['name']}' в таблице {$table['name']}<br>";
							break;
					}
				}
			if (array_key_exists('indexes', $table))
				foreach($table['indexes'] as $index)
					if (($idx_check=admin_db_check_table_index($table['name'], $index))!=1)
					{
						if ($idx_check==-2) // Индекс есть, но тип не совпадает
							query("alter table `{$table['name']}` drop index `{$index['name']}`");
						if (!isset($index['fulltext']) || !$index['fulltext'])
							query("alter table `{$table['name']}` add index `{$index['name']}` ({$index['index']})");
						else
							query("alter table `{$table['name']}` add FULLTEXT `{$index['name']}` ({$index['index']})");
						echo "Добавлен индекс '{$index['name']} ({$index['index']})' в таблицу {$table['name']}<br>";
					}
		}
	}
	admin_db_check_unnecessary_tables(false, $tables);
	admin_db_check_unnecessary_fields(false, $tables);
	echo '</div><br><br>';
	$html=ob_get_contents();
	ob_end_clean();
    $_SESSION['db_config_crc']=$crc;
	return $html;
}
// -----------------------------------------------------------------------------
function admin_db_get_alter_field_primary_sql($table, $field)
{
	$q="alter table `$table` add primary key (`{$field['name']}`)";
	return $q;
}
// -----------------------------------------------------------------------------
function admin_db_get_alter_field_auto_sql($table, $field)
{
	if (!array_key_exists('default', $field) && $field['null']) $null='null';
	else $null='not null';
	$q="alter table `$table` modify `{$field['name']}` {$field['type']} $null auto_increment";
	if (array_key_exists('default', $field)) $q.=" default '{$field['default']}'";
	return $q;
}
// -----------------------------------------------------------------------------
function admin_db_get_alter_field_sql($table, $field)
{
	if (!array_key_exists('default', $field) && $field['null']) $null='null';
	else $null='not null';
	$q="alter table `$table` modify `{$field['name']}` {$field['type']} $null";
	if (array_key_exists('default', $field)) $q.=" default '{$field['default']}'";
	return $q;
}
// -----------------------------------------------------------------------------
function admin_db_get_create_field_sql($table, $field)
{
	if (!array_key_exists('default', $field) && $field['null']) $null='null';
	else $null='not null';
	$q="alter table `$table` add `{$field['name']}` {$field['type']} $null";
	if (array_key_exists('default', $field)) $q.=" default '{$field['default']}'";
	if ($field['primary']) $q.=' auto_increment primary key';
	return $q;
}
// -----------------------------------------------------------------------------
function admin_db_get_create_table_sql($table)
{
	global $db_charset;
	$q="create table `{$table['name']}` (";
	$pr='';
	foreach($table['fields'] as $field)
	{
		if (!array_key_exists('default', $field) && $field['null']) $null='null';
		else $null='not null';
		$q.="`{$field['name']}` {$field['type']} $null";
		if (array_key_exists('default', $field)) $q.=" default '{$field['default']}'";
		if ($field['primary'])
		{
			$pr=$field['name'];
			$q.=' auto_increment';
		}
		$q.=', ';
	}
	if ($pr!='') $q.="primary key (`$pr`), ";
	if (array_key_exists('indexes', $table))
		foreach($table['indexes'] as $index)
		{
			if (!isset($index['fulltext']) || !$index['fulltext'])
				$q.="index `{$index['name']}` ({$index['index']}), ";
			else
				$q.="FULLTEXT ({$index['index']}), ";
		}
	$q=substr($q, 0, -2);
	$q.=") engine=myisam default charset=$db_charset";
	return $q;
}
// -----------------------------------------------------------------------------
?>