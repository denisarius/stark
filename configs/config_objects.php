<?php
$_cms_objects_table='objects';
$_cms_objects_details='objects_details';

$_cms_objects_image_sx=1024;
$_cms_objects_image_sy=800;

$_base_site_objects_images_path="$_base_site_root_path/data/objects";
$_base_site_objects_images_url="$_base_site_root_url/data/objects";

// ��������� ��������
// id		- ���������� id ���������
// name		- �������� ���������
// type		- ��� ������ ��������� (e(enum)|d(digital)|i(image)|c(checkbox)|s(string)|oo(once from objects)|do(once from dir)|dm(multiply from dir)|ff(features))
//			ff - <dir_id>[<id>,<price_change>]..[<>,<>]| 1[1,0][4,-80]|8[5,450][7,0]
//				- e (enun) ������������. Options �������� ������ � �������������� ���������� ����������� �������� '|'. ��������: ����|����|������|���������
//				- d (digital) �����. ���������� ����� � ��������� ������� ������
//				- i (image) �����������. ��� ���� ��������. ��� ����������� ����������� � /data/object. Limit ������ ������������ ���������� ����������� ��� ������ �������
//				- c (checkbox) �������. � �� ������������ �������� 0-������, 1-��������
//				- s (string) ������
//				- oo (once from objects). ��������� ������ �� ������. �������� - id �������. Options ������ ��� �������
//				- do (once from dir). ��������� ������ �� ����������. �������� - id �����������. Options ������ ��� �����������
//				- dm (multiply from dir). ������������� ������ �� ����������. �������� - id �����������. Options ������ ��� �����������. ����� ���� ��������� ����� ������� ��������� � ����� ����� ������ �������
//				- ff (features). �������������� ������� ��������� �� ������������. Options ������ ������ ������������ ��� ������ ����������. �������� - ������ � ����: <dir_id>[<id>,<price_change>]..[<>,<>]|<dir_id>[<id>,<price_change>]..[<>,<>] ... ��������: 1[1,0][4,-80]|8[5,450][7,0]. ��������: ���������� 1 - ������� 1 - ��������� ��������� '0', ������� 4 - ��������� ��������� '-80'; ���������� 8 - ������� 5 - ��������� ��������� '450', ������� 7 - ��������� ��������� '0'
//					���� ���� 'ff' ����� ���� ������ ���� !!!
//				- st (structured text). ����������������� �����. ������ �� ���� node ������� text_parts. ���������: sx-������ ������������ ����������� sy-������ ������������ �����������
//				- tb (table). �������. columns - ������ ������� ������� (����������� '|')
// options	- �������� ������ ��� ���� enum (����������� '|')
//			- ID ����������� ��� do � dm
//			- ������ ID ������������ ��� ff (����������� ',')
//			- ��� �������� ��� oo
// need		- ���� true �� ��� ������������ ��� ����� ��������
// limit	- ���������� ������ ��� �������� (�������� ���������� ����������� ��� ���� 'image')
// sx, sy	- ������ ����������� ��� ���� 'st'
// width	- ������ ���� �������������� ��������� ��� ���� 'tb'
// columns	- ������ �������� ������� (����������� '|'). ��� ���� 'tb'
$_cms_objects_types=array(
	array(
		'id'=>'1', 'name'=>'�������', 'menu_item_id'=>'3,4,5,6,7,8,9,10,11,12,13,14,15,20',
		'sx'=>400, 'sy'=>400,
		'details'=>array(
			array('id'=>'dish_type', 'name'=>'��������� �����', 'type'=>'do', 'options'=>1, 'need'=>true),
			array('id'=>'author', 'name'=>'�����', 'type'=>'s'),
			array('id'=>'time', 'name'=>'����� ������������� (�)', 'type'=>'d', 'need'=>true),
			array('id'=>'ingredients', 'name'=>'�����������', 'type'=>'tb', 'columns'=>'�������� �����������|���-��', 'width'=>600),
			array('id'=>'text', 'name'=>'����� �������', 'type'=>'st', 'sx'=>200, 'sy'=>200),
			array('id'=>'photo', 'name'=>'��������� ����', 'type'=>'st', 'sx'=>600, 'sy'=>400),
			array('id'=>'rating', 'name'=>'�������', 'type'=>'d', 'readonly'=>true, 'noshow'=>true),
			array('id'=>'rating_count', 'name'=>'���������� ������������', 'type'=>'d', 'readonly'=>true, 'noshow'=>true),
			array('id'=>'rating_ip', 'name'=>'IP ���������� �������������', 'type'=>'s', 'readonly'=>true, 'noshow'=>true),
			array('id'=>'is_interest', 'name'=>'����������', 'type'=>'c'),
		)),

	array(
		'id'=>'2', 'name'=>'������', 'menu_item_id'=>'16',
		'sx'=>400, 'sy'=>400,
		'details'=>array(
			array('id'=>'ingredients', 'name'=>'�����������', 'type'=>'tb', 'columns'=>'�������� �����������|���-��', 'width'=>600),
			array('id'=>'text', 'name'=>'�����', 'type'=>'st', 'sx'=>200, 'sy'=>200),
			array('id'=>'photo', 'name'=>'��������� ����', 'type'=>'st', 'sx'=>600, 'sy'=>400),
			array('id'=>'is_interest', 'name'=>'����������', 'type'=>'c'),
		)),

	array(
		'id'=>'3', 'name'=>'���������', 'menu_item_id'=>'17',
		'sx'=>400, 'sy'=>400,
		'details'=>array(
			array('id'=>'photo', 'name'=>'����', 'type'=>'st', 'sx'=>600, 'sy'=>400),
			array('id'=>'is_interest', 'name'=>'����������', 'type'=>'c'),
		)),
	array(
		'id'=>'4', 'name'=>'����������', 'menu_item_id'=>'21',
		'sx'=>400, 'sy'=>400,
		'details'=>array(
			array('id'=>'photo', 'name'=>'����', 'type'=>'st', 'sx'=>600, 'sy'=>400),
		)),
);
?>