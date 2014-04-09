<?php
$_cms_tree_node_table='shop_tree_nodes';
$_cms_tree_node_details='shop_tree_node_details';
$_cms_shop_orders='shop_orders';
$_cms_shop_orders_goods='shop_orders_goods';
$_cms_directories='shop_directories';
$_cms_directories_data='shop_directories_data';

$_cms_goods_list_page_length=20;
$_cms_goods_images_url="$_base_site_root_url/data/goods_images";
$_cms_goods_images_path="$_base_site_root_path/data/goods_images";
$_cms_goods_images_jpeg_quality=85;
$_cms_goods_images_thumbnail_size=190;
$_cms_goods_images_size_x=400;
$_cms_goods_images_size_y=400;

$_admin_good_title_suffix='collection|article';

// ID меню магазина (пунктами меню являются категории товаров)
$_shop_menu_id=2;

// Параметры товаров
// id		- уникальный id параметра
// name		- название параметра
// type		- тип данных параметра (e(enum)|d(digital)|i(image)|c(char))
// options	- варианты выбора для типа enum (разделитель '|')
// need		- если true то это обязательный для ввода параметр
// limit	- количество данных для признака (например количество изображений для типа 'image')
$_cms_good_types=array(
	array('id'=>'1', 'name'=>'Диваны и кресла', 'details'=>array(
		array('id'=>'section', 'name'=>'Разделы', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'Артикул', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'Название коллекции', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'Производитель', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'Габаритные размеры', 'type'=>'s'),
		array('id'=>'size_bed', 'name'=>'Спальное место', 'type'=>'s'),
		array('id'=>'mechanism', 'name'=>'Механизм', 'type'=>'do',  'options'=>'3'),
		array('id'=>'box', 'name'=>'Бельевой ящик', 'type'=>'c'),
		array('id'=>'material', 'name'=>'Материал', 'type'=>'dm',  'options'=>'4'),
		array('id'=>'filler', 'name'=>'Наполнитель', 'type'=>'do',  'options'=>'5'),
		array('id'=>'color', 'name'=>'Цвет', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'Цена', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"Цена от"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'Цена по акции', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'В наличии', 'type'=>'c'),
		array('id'=>'image', 'name'=>'Фотографии', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'2', 'name'=>'Мебель для гостиной', 'details'=>array(
		array('id'=>'section', 'name'=>'Разделы', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'Артикул', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'Название коллекции', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'Производитель', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'Габаритные размеры', 'type'=>'s'),
		array('id'=>'material', 'name'=>'Материал', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'Цвет', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'Цена', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"Цена от"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'Цена по акции', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'В наличии', 'type'=>'c'),
		array('id'=>'image', 'name'=>'Фотографии', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'3', 'name'=>'Мебель для спальни', 'details'=>array(
		array('id'=>'section', 'name'=>'Разделы', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'Артикул', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'Название коллекции', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'Производитель', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'Габаритные размеры', 'type'=>'s'),
		array('id'=>'size_bed', 'name'=>'Спальное место', 'type'=>'s'),
		array('id'=>'material', 'name'=>'Материал', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'Цвет', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'Цена', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"Цена от"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'Цена по акции', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'В наличии', 'type'=>'c'),
		array('id'=>'image', 'name'=>'Фотографии', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'4', 'name'=>'Мебель для прихожей', 'details'=>array(
		array('id'=>'section', 'name'=>'Разделы', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'Артикул', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'Название коллекции', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'Производитель', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'Габаритные размеры', 'type'=>'s'),
		array('id'=>'material', 'name'=>'Материал', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'Цвет', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'Цена', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"Цена от"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'Цена по акции', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'В наличии', 'type'=>'c'),
		array('id'=>'image', 'name'=>'Фотографии', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'5', 'name'=>'Столы и стулья', 'details'=>array(
		array('id'=>'section', 'name'=>'Разделы', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'Артикул', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'Название коллекции', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'Производитель', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'Габаритные размеры', 'type'=>'s'),
		array('id'=>'material', 'name'=>'Материал', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'Цвет', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'Цена', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"Цена от"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'Цена по акции', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'В наличии', 'type'=>'c'),
		array('id'=>'image', 'name'=>'Фотографии', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'6', 'name'=>'Малые формы', 'details'=>array(
		array('id'=>'section', 'name'=>'Разделы', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'Артикул', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'Название коллекции', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'Производитель', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'Габаритные размеры', 'type'=>'s'),
		array('id'=>'material', 'name'=>'Материал', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'Цвет', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'Цена', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"Цена от"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'Цена по акции', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'В наличии', 'type'=>'c'),
		array('id'=>'image', 'name'=>'Фотографии', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'7', 'name'=>'Светильники', 'details'=>array(
		array('id'=>'section', 'name'=>'Разделы', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'Артикул', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'Название коллекции', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'Производитель', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'Габаритные размеры', 'type'=>'s'),
		array('id'=>'material', 'name'=>'Материал', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'Цвет', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'Цена', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"Цена от"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'Цена по акции', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'В наличии', 'type'=>'c'),
		array('id'=>'image', 'name'=>'Фотографии', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'8', 'name'=>'Предметы интерьера', 'details'=>array(
		array('id'=>'section', 'name'=>'Разделы', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'Артикул', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'Название коллекции', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'Производитель', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'Габаритные размеры', 'type'=>'s'),
		array('id'=>'material', 'name'=>'Материал', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'Цвет', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'Цена', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"Цена от"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'Цена по акции', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'В наличии', 'type'=>'c'),
		array('id'=>'image', 'name'=>'Фотографии', 'type'=>'i', 'limit'=>'3'),
	)),
);

$_order_statuses=array(0=>'Поставлен в очередь на обработку', 1=>'Ожидание поступления оплаты', 2=>'В процессе формирования', 3=>'Готов к выдаче', 999=>'Получен заказчиком');

$_cms_goods_admin_list_page_length=20;
?>