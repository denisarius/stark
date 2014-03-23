<?php
$_cms_objects_table='objects';
$_cms_objects_details='objects_details';

$_cms_objects_image_sx=1024;
$_cms_objects_image_sy=800;

$_base_site_objects_images_path="$_base_site_root_path/data/objects";
$_base_site_objects_images_url="$_base_site_root_url/data/objects";

// Параметры Объектов
// id		- уникальный id параметра
// name		- название параметра
// type		- тип данных параметра (e(enum)|d(digital)|i(image)|c(checkbox)|s(string)|oo(once from objects)|do(once from dir)|dm(multiply from dir)|ff(features))
//			ff - <dir_id>[<id>,<price_change>]..[<>,<>]| 1[1,0][4,-80]|8[5,450][7,0]
//				- e (enun) перечисление. Options содержит строку с перечисленными вариантами разделенным символом '|'. Например: стол|стул|кресло|табуретка
//				- d (digital) число. Десятичное число с возможной дробной частью
//				- i (image) изображение. Имя файа картинки. Фсе изображения содержаться в /data/object. Limit задает максимальное количество изображений для одного объекта
//				- c (checkbox) чекбокс. В БД записываются значения 0-выбран, 1-невыбран
//				- s (string) строка
//				- oo (once from objects). Единичная ссылка на объект. Значение - id объекта. Options задает тип объекта
//				- do (once from dir). Единичная ссылка на справочник. Значение - id справочника. Options задает тип справочника
//				- dm (multiply from dir). Множественная ссылка на справочник. Значение - id справочника. Options задает тип справочника. Может быть несколько таких записей связанных с одним полем одного объекта
//				- ff (features). Характеристика объекта связанная со справочником. Options задает список справочников для выбора параметров. Знаяение - строка в виде: <dir_id>[<id>,<price_change>]..[<>,<>]|<dir_id>[<id>,<price_change>]..[<>,<>] ... Например: 1[1,0][4,-80]|8[5,450][7,0]. Означает: справочник 1 - элемент 1 - изменение параметра '0', элемент 4 - изменение параметра '-80'; справочник 8 - элемент 5 - изменение параметра '450', элемент 7 - изменение параметра '0'
//					поле типа 'ff' может быть только одно !!!
//				- st (structured text). Структурированный текст. Ссылка на поле node таблицы text_parts. Параметры: sx-ширина привызанного изображения sy-высота привязанного изображения
//				- tb (table). Таблица. columns - список колонок таблицы (разделитель '|')
// options	- варианты выбора для типа enum (разделитель '|')
//			- ID справочника для do и dm
//			- список ID справочников для ff (разделитель ',')
//			- тип объектов для oo
// need		- если true то это обязательный для ввода параметр
// limit	- количество данных для признака (например количество изображений для типа 'image')
// sx, sy	- размер изображения для типа 'st'
// width	- размер окна редактирования параметра для типа 'tb'
// columns	- список столбцов таблицы (разделитель '|'). Для типа 'tb'
$_cms_objects_types=array(
	array(
		'id'=>'1', 'name'=>'Рецепты', 'menu_item_id'=>'3,4,5,6,7,8,9,10,11,12,13,14,15,20',
		'sx'=>400, 'sy'=>400,
		'details'=>array(
			array('id'=>'dish_type', 'name'=>'Категория блюда', 'type'=>'do', 'options'=>1, 'need'=>true),
			array('id'=>'author', 'name'=>'Автор', 'type'=>'s'),
			array('id'=>'time', 'name'=>'Время приготовления (м)', 'type'=>'d', 'need'=>true),
			array('id'=>'ingredients', 'name'=>'Ингредиенты', 'type'=>'tb', 'columns'=>'Название ингредиента|Кол-во', 'width'=>600),
			array('id'=>'text', 'name'=>'Текст рецепта', 'type'=>'st', 'sx'=>200, 'sy'=>200),
			array('id'=>'photo', 'name'=>'Финальные фото', 'type'=>'st', 'sx'=>600, 'sy'=>400),
			array('id'=>'rating', 'name'=>'Рейтинг', 'type'=>'d', 'readonly'=>true, 'noshow'=>true),
			array('id'=>'rating_count', 'name'=>'Количество голосовавших', 'type'=>'d', 'readonly'=>true, 'noshow'=>true),
			array('id'=>'rating_ip', 'name'=>'IP последнего голосовавшего', 'type'=>'s', 'readonly'=>true, 'noshow'=>true),
			array('id'=>'is_interest', 'name'=>'Интересное', 'type'=>'c'),
		)),

	array(
		'id'=>'2', 'name'=>'Советы', 'menu_item_id'=>'16',
		'sx'=>400, 'sy'=>400,
		'details'=>array(
			array('id'=>'ingredients', 'name'=>'Ингредиенты', 'type'=>'tb', 'columns'=>'Название ингридиента|Кол-во', 'width'=>600),
			array('id'=>'text', 'name'=>'Текст', 'type'=>'st', 'sx'=>200, 'sy'=>200),
			array('id'=>'photo', 'name'=>'Финальные фото', 'type'=>'st', 'sx'=>600, 'sy'=>400),
			array('id'=>'is_interest', 'name'=>'Интересное', 'type'=>'c'),
		)),

	array(
		'id'=>'3', 'name'=>'Рестораны', 'menu_item_id'=>'17',
		'sx'=>400, 'sy'=>400,
		'details'=>array(
			array('id'=>'photo', 'name'=>'Фото', 'type'=>'st', 'sx'=>600, 'sy'=>400),
			array('id'=>'is_interest', 'name'=>'Интересное', 'type'=>'c'),
		)),
	array(
		'id'=>'4', 'name'=>'Интересное', 'menu_item_id'=>'21',
		'sx'=>400, 'sy'=>400,
		'details'=>array(
			array('id'=>'photo', 'name'=>'Фото', 'type'=>'st', 'sx'=>600, 'sy'=>400),
		)),
);
?>