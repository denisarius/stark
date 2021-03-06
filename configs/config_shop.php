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
$_shop_menu_id=3;

// ��������� �������
// id		- ���������� id ���������
// name		- �������� ���������
// type		- ��� ������ ��������� (e(enum)|d(digital)|i(image)|c(char))
// options	- �������� ������ ��� ���� enum (����������� '|')
// need		- ���� true �� ��� ������������ ��� ����� ��������
// limit	- ���������� ������ ��� �������� (�������� ���������� ����������� ��� ���� 'image')
$_cms_good_types=array(
	array('id'=>'1', 'name'=>'������ ����������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'8', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'2', 'name'=>'������ ���������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'9', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'3', 'name'=>'���', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'10', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'offset', 'name'=>'����� (��)', 'type'=>'d'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'4', 'name'=>'������� �����������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'5', 'name'=>'����������� ��� �����', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'11', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'6', 'name'=>'�������� ���������� �����������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'12', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'offset', 'name'=>'����� (��)', 'type'=>'d'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'7', 'name'=>'������������ ����������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'13', 'filter'=>true),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'built-in_height', 'name'=>'������ ������������ ����� (��)', 'type'=>'d'),
		array('id'=>'built-in_diametr', 'name'=>'������� �������� ��������� (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'voltage', 'name'=>'���������� (�)', 'type'=>'s'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'8', 'name'=>'����������� ��� ������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'14', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'voltage', 'name'=>'���������� (�)', 'type'=>'s'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'9', 'name'=>'��������� ��� ������ � ������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'offset', 'name'=>'����� (��)', 'type'=>'d'),
		array('id'=>'voltage', 'name'=>'���������� (�)', 'type'=>'s'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35|LED', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'10', 'name'=>'��������� �����������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'15', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'voltage', 'name'=>'���������� (�)', 'type'=>'s'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'11', 'name'=>'�������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'16', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'voltage', 'name'=>'���������� (�)', 'type'=>'s'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'12', 'name'=>'���������� �����', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'17', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'voltage', 'name'=>'���������� (�)', 'type'=>'s'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'13', 'name'=>'����������� ������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'14', 'name'=>'����������� �������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'18', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'offset', 'name'=>'����� (��)', 'type'=>'d'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),

	array('id'=>'15', 'name'=>'����� � ����-�������', 'details'=>array(
		array('id'=>'image', 'name'=>'�����������', 'type' => 'i', 'limit' => 1),
		array('id'=>'maker', 'name'=>'�������������', 'type'=>'do',  'options'=>'1', 'filter'=>true, 'need'=>true),
		array('id'=>'country', 'name'=>'������', 'type'=>'do', 'options'=>'2', 'filter'=>true),
		array('id'=>'type', 'name'=>'���������', 'type'=>'do', 'options'=>'19', 'filter'=>true),
		array('id'=>'height', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'width', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'length', 'name'=>'������ (��)', 'type'=>'d'),
		array('id'=>'diametr', 'name'=>'������� (��)', 'type'=>'d'),
		array('id'=>'offset', 'name'=>'����� (��)', 'type'=>'d'),
		array('id'=>'lamp_type', 'name'=>'��� �������� (��������)', 'type'=>'e',  'options'=>'E14|E27|G9|G4|R7S|GU10|GX5.3|GY6.35', 'need'=>true),
		array('id'=>'power', 'name'=>'�������� �����(��)', 'type'=>'d', 'need'=>true),
		array('id'=>'lamp_count', 'name'=>'���������� ����', 'type'=>'d', 'need'=>true),
		array('id'=>'area', 'name'=>'������� ��������� �2', 'type'=>'d'),
		array('id'=>'collection', 'name'=>'���������', 'type'=>'s', 'filter'=>true),
		array('id'=>'style', 'name'=>'����� �����������', 'type'=>'do',  'options'=>'3'),
		array('id'=>'placing', 'name'=>'������ ����������', 'type'=>'do',  'options'=>'4'),
		array('id'=>'material', 'name'=>'��������', 'type'=>'dm',  'options'=>'5'),
		array('id'=>'color', 'name'=>'����', 'type'=>'dm',  'options'=>'6'),
		array('id'=>'color_glass', 'name'=>'���� ������', 'type'=>'dm',  'options'=>'7'),
		array('id'=>'lamps_exists', 'name'=>'�������� � ���������', 'type'=>'c'),
		array('id'=>'price', 'name'=>'"����"', 'type'=>'d'),
	)),


);

$_order_statuses=array(0=>'��������� � ������� �� ���������', 1=>'�������� ����������� ������', 2=>'� �������� ������������', 3=>'����� � ������', 999=>'������� ����������');

$_cms_goods_admin_list_page_length=20;
?>