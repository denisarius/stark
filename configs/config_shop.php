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

// ID ���� �������� (�������� ���� �������� ��������� �������)
$_shop_menu_id=2;

// ��������� �������
// id		- ���������� id ���������
// name		- �������� ���������
// type		- ��� ������ ��������� (e(enum)|d(digital)|i(image)|c(char))
// options	- �������� ������ ��� ���� enum (����������� '|')
// need		- ���� true �� ��� ������������ ��� ����� ��������
// limit	- ���������� ������ ��� �������� (�������� ���������� ����������� ��� ���� 'image')
$_cms_good_types=array(
	array('id'=>'1', 'name'=>'������ � ������', 'details'=>array(
		array('id'=>'section', 'name'=>'�������', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'�������', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'�������� ���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'���������� �������', 'type'=>'s'),
		array('id'=>'size_bed', 'name'=>'�������� �����', 'type'=>'s'),
		array('id'=>'mechanism', 'name'=>'��������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'box', 'name'=>'�������� ����', 'type'=>'c'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'4'),
		array('id'=>'filler', 'name'=>'�����������', 'type'=>'do',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'����', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"���� ��"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'���� �� �����', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'� �������', 'type'=>'c'),
		array('id'=>'image', 'name'=>'����������', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'2', 'name'=>'������ ��� ��������', 'details'=>array(
		array('id'=>'section', 'name'=>'�������', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'�������', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'�������� ���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'���������� �������', 'type'=>'s'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'����', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"���� ��"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'���� �� �����', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'� �������', 'type'=>'c'),
		array('id'=>'image', 'name'=>'����������', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'3', 'name'=>'������ ��� �������', 'details'=>array(
		array('id'=>'section', 'name'=>'�������', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'�������', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'�������� ���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'���������� �������', 'type'=>'s'),
		array('id'=>'size_bed', 'name'=>'�������� �����', 'type'=>'s'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'����', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"���� ��"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'���� �� �����', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'� �������', 'type'=>'c'),
		array('id'=>'image', 'name'=>'����������', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'4', 'name'=>'������ ��� ��������', 'details'=>array(
		array('id'=>'section', 'name'=>'�������', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'�������', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'�������� ���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'���������� �������', 'type'=>'s'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'����', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"���� ��"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'���� �� �����', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'� �������', 'type'=>'c'),
		array('id'=>'image', 'name'=>'����������', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'5', 'name'=>'����� � ������', 'details'=>array(
		array('id'=>'section', 'name'=>'�������', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'�������', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'�������� ���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'���������� �������', 'type'=>'s'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'����', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"���� ��"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'���� �� �����', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'� �������', 'type'=>'c'),
		array('id'=>'image', 'name'=>'����������', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'6', 'name'=>'����� �����', 'details'=>array(
		array('id'=>'section', 'name'=>'�������', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'�������', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'�������� ���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'���������� �������', 'type'=>'s'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'����', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"���� ��"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'���� �� �����', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'� �������', 'type'=>'c'),
		array('id'=>'image', 'name'=>'����������', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'7', 'name'=>'�����������', 'details'=>array(
		array('id'=>'section', 'name'=>'�������', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'�������', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'�������� ���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'���������� �������', 'type'=>'s'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'����', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"���� ��"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'���� �� �����', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'� �������', 'type'=>'c'),
		array('id'=>'image', 'name'=>'����������', 'type'=>'i', 'limit'=>'3'),
	)),
	array('id'=>'8', 'name'=>'�������� ���������', 'details'=>array(
		array('id'=>'section', 'name'=>'�������', 'type'=>'dm',  'options'=>'1'),
		array('id'=>'article', 'name'=>'�������', 'type'=>'s', 'filter'=>true),
		array('id'=>'collection', 'name'=>'�������� ���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'2'),
		array('id'=>'size', 'name'=>'���������� �������', 'type'=>'s'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'price', 'name'=>'����', 'type'=>'d', 'need'=>true),
		array('id'=>'price_from', 'name'=>'"���� ��"', 'type'=>'c'),
		array('id'=>'price_action', 'name'=>'���� �� �����', 'type'=>'d'),
		array('id'=>'in_sight', 'name'=>'� �������', 'type'=>'c'),
		array('id'=>'image', 'name'=>'����������', 'type'=>'i', 'limit'=>'3'),
	)),
);

$_order_statuses=array(0=>'��������� � ������� �� ���������', 1=>'�������� ����������� ������', 2=>'� �������� ������������', 3=>'����� � ������', 999=>'������� ����������');

$_cms_goods_admin_list_page_length=20;
?>