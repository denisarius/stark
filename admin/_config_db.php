<?php
$_admin_db_structure=array();

	// ������� ��� ������ � �����������
	// id 		- ���������� id ���������
	// name 	- ��� ���������
	// value	- �������� ���������
	if (isset($_cms_constants_table) && $_cms_constants_table!='')
	array_push($_admin_db_structure,
		array('name'=>$_cms_constants_table,
			'fields'=>array(
				array('name'=>'id', 		'type'=>'int(11)', 		'primary'=>true),
				array('name'=>'name', 		'type'=>'varchar(254)', 'primary'=>false, 'null'=>true),
				array('name'=>'value', 		'type'=>'text', 		'primary'=>false, 'null'=>true),
			),
			'indexes'=>array(
				array('name'=>'name',		'index'=>'name'),
			)
		)
	);

	// ������� ���� �����
	// id	- ���������� id ����
	// name	- �������� ����
	if (isset($_cms_menus_table) && $_cms_menus_table!='')
	array_push($_admin_db_structure,
		array('name'=>$_cms_menus_table,
			'fields'=>array(
				array('name'=>'id',    		'type'=>'int(11)', 		'primary'=>true),
				array('name'=>'name', 		'type'=>'varchar(254)', 'primary'=>false, 'null'=>true),
			),
			'indexes'=>array(
			)
		)
	);

	// ������� ������� ����
	// id		- ���������� id ������ ����
	// name		- ����� (��������) ������ ����
	// url		- URL ��������� � ���� ������� ����
	// parent	- ������������ ����� ����
	// sort		- ������� ���������� ������� ����
	// menu		- id ���� � �������� ��������� ������ �����
	// visible	- ������� ����������� (0 - �� ������������)
	if (isset($_cms_menus_items_table) && $_cms_menus_items_table!='')
	array_push($_admin_db_structure,
		array('name'=>$_cms_menus_items_table,
			'fields'=>array(
				array('name'=>'id', 		'type'=>'int(11)', 		'primary'=>true),
				array('name'=>'name', 		'type'=>'varchar(254)', 'primary'=>false, 'null'=>true),
				array('name'=>'url', 		'type'=>'text', 		'primary'=>false, 'null'=>true),
				array('name'=>'parent', 	'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				array('name'=>'sort', 		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				array('name'=>'menu', 		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				array('name'=>'visible',	'type'=>'int(1)', 		'primary'=>false, 'default'=>'0'),
				array('name'=>'tag', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
			),
			'indexes'=>array(
			)
		)
	);

	// ������� ���������� � ��������
	// id			- ���������� id ����� ������ (�������� ������� �� ����������� ������������� � ����� �������������)
	// language		- ID ����� ������
	// menu_item	- ����� ���� � ������� �������� �������
	// link_type	- ��� ������ ���������� menu_item (0-����, 1-������)
	// name			- �������� �������
	// note			- �������� �������
	if (isset($_cms_gallery_data_table) && $_cms_gallery_data_table!='')
	array_push($_admin_db_structure,
		array('name'=>$_cms_gallery_data_table,
			'fields'=>array(
				array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
				array('name'=>'language',	'type'=>'varchar(5)',	'primary'=>false, 'null'=>true),
				array('name'=>'menu_item', 	'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
				array('name'=>'link_type', 	'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
				array('name'=>'title', 		'type'=>'text',			'primary'=>false, 'null'=>true),
				array('name'=>'note',	 	'type'=>'text',			'primary'=>false, 'null'=>true),
			),
			'indexes'=>array(
				array('name'=>'ml',			'index'=>'menu_item, link_type'),
			)
		)
	);

	// ������� ����������� ��� �����������
	// id			- ���������� id ����������
	// menu_item	- ����� ���� � ������� �������� �������
	// link_type	- ��� ������ ���������� menu_item (0-����, 1-������)
	// file			- ��� ����� �����������
	// title		- �������� �����������
	// comment		- �������� �����������
	// sort			- ������� ���������� ����������� � �������
	// visible		- ������� ����������� (0 - �� ������������)
	if (isset($_cms_gallery_table) && $_cms_gallery_table!='')
	array_push($_admin_db_structure,
		array('name'=>$_cms_gallery_table,
			'fields'=>array(
				array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
				array('name'=>'menu_item', 	'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
				array('name'=>'link_type', 	'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
				array('name'=>'file',  		'type'=>'varchar(21)',	'primary'=>false, 'null'=>true),
				array('name'=>'title',	 	'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
				array('name'=>'comment', 	'type'=>'text',			'primary'=>false, 'null'=>true),
				array('name'=>'sort',  		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
				array('name'=>'visible',	'type'=>'int(1)', 		'primary'=>false, 'default'=>'0'),
			),
			'indexes'=>array(
			)
		)
	);

	// ������� ��������
	// id			- ���������� id �������
	// language		- ID ����� �������
	// date			- ���� ���������� �������
	// content		- ���������� �������
	// image		- ��� ����� ����������� ��� �������
	// linked		- id ���������� ������� (+n - �����; -n - ����� ����)
	// object_id	- id of linked object
	// visible		- ������� ����������� (0 - �� ������������)
	if (isset($_cms_news_table) && $_cms_news_table!='')
	array_push($_admin_db_structure,
		array('name'=>$_cms_news_table,
			'fields'=>array(
				array('name'=>'id', 		'type'=>'int(11)', 		'primary'=>true),
				array('name'=>'language',	'type'=>'varchar(5)',	'primary'=>false, 'null'=>true),
				array('name'=>'date', 		'type'=>'date', 		'primary'=>false),
				array('name'=>'title', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
				array('name'=>'note', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
				array('name'=>'content', 	'type'=>'text', 		'primary'=>false, 'null'=>true),
				array('name'=>'image', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
				array('name'=>'linked',		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				array('name'=>'object_id',	'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				array('name'=>'tag',		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				array('name'=>'visible',	'type'=>'int(1)', 		'primary'=>false, 'default'=>'0'),
			),
			'indexes'=>array(
			)
		)
	);

	// ������� �������
	// id			- ���������� id ������
	// signature	- ���������� ��������� ������
	// menu_item	- ����� ���� � ������� ������ �����
	// date			- ���� �������������� ������
	// title		- ��������� ������
	// keywords		- �������� �����
	// descr		- ����� ��� ��������� description
	// content		- ����� (HTML ���)
	// visible		- ������� ��������� (0 - �� ������������)
	if (isset($_cms_texts_table) && $_cms_texts_table!='')
	array_push($_admin_db_structure,
		array('name'=>$_cms_texts_table,
			'fields'=>array(
				array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
				array('name'=>'signature', 	'type'=>'varchar(10)',	'primary'=>false, 'null'=>true),
				array('name'=>'menu_item',  'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				array('name'=>'date',	 	'type'=>'date',			'primary'=>false),
				array('name'=>'title', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
				array('name'=>'keywords',  	'type'=>'text',	 		'primary'=>false, 'null'=>true),
				array('name'=>'descr',		'type'=>'text',	 		'primary'=>false, 'null'=>true),
				array('name'=>'content',  	'type'=>'longtext',		'primary'=>false, 'null'=>true),
				array('name'=>'visible',  	'type'=>'int(1)',  		'primary'=>false, 'default'=>'0'),
			),
			'indexes'=>array(
			)
		)
	);

	// ������� ����������������� �������
	// id			- ���������� id ����� ������
	// type			- ��� ����� ������ (0-����; 1-�������� �������)
	// node			- ID ������� (����, �������� �������) � ������� ������ �����
	// date			- ���� �������������� ������
	// title		- ��������� �����
	// image		- ��� ����� ��������
	// content		- ����� (HTML ���)
	// sort			- ������� ���������� ������ � ������
	// visible		- ������� ��������� (0 - �� ������������)
	if (isset($_cms_text_parts) && $_cms_text_parts!='')
	array_push($_admin_db_structure,
		array('name'=>$_cms_text_parts,
			'fields'=>array(
				array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
				array('name'=>'type', 		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
				array('name'=>'node',		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				array('name'=>'date',	 	'type'=>'date',			'primary'=>false),
				array('name'=>'title', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
				array('name'=>'image', 		'type'=>'varchar(50)',	'primary'=>false, 'null'=>true),
				array('name'=>'content',  	'type'=>'longtext',		'primary'=>false, 'null'=>true),
				array('name'=>'sort',		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				array('name'=>'visible',  	'type'=>'int(1)',  		'primary'=>false, 'default'=>'0'),
			),
			'indexes'=>array(
				array('name'=>'tnvs',		'index'=>'type, node, visible, sort'),
			)
		)
	);

	// ������� ����������
	// id			- ���������� id ���������
	// name			- �������� ���������
	// note 		- ����������� � ���������
	// file			- ��� ����� � ���������� � �������� �������
	// real_file	- ������������ ��� ����� (��� ����������)
	// visible		- ������� ��������� (0 - �� ������������)
	if (isset($_cms_documents_table) && $_cms_documents_table!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_documents_table,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'name', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'note',  		'type'=>'text', 		'primary'=>false, 'null'=>true),
					array('name'=>'file',	 	'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'real_file',	'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'visible',  	'type'=>'int(1)',  		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
				)
			)
		);

	// ������� �������� � ����������
	// id			- ���������� id ��������
	// department	- id ������ (���� �� ������������)
	// position		- ���������
	// note			- �����������
	// image		- ��� ����� �����������
	// visible		- ������� ��������� (0 - �� ������������)
	if (isset($_cms_staff_table) && $_cms_staff_table!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_staff_table,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'menu_item',	'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'department', 'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'name',  		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'position', 	'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'note',  		'type'=>'text',			'primary'=>false, 'null'=>true),
					array('name'=>'image', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'visible',  	'type'=>'int(1)',  		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
					array('name'=>'di',		'index'=>'department, id'),
				)
			)
	);

	// ������� ������� � ��������
	// id			- ���������� id ������
	// public_id	- ���������� ���������� ������������� ������
	// date			- ���� ������
	// user			- id ������� ���������� �����
	// address		- id ������ �������
	// status		- ������ ������ (��� �� ������� �������� $_order_statuses)
	if (isset($_cms_shop_orders) && $_cms_shop_orders!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_shop_orders,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'public_id', 	'type'=>'char(10)',		'primary'=>false, 'null'=>true),
					array('name'=>'date',  		'type'=>'date',			'primary'=>false),
					array('name'=>'user',	 	'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
					array('name'=>'address', 	'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
					array('name'=>'status', 	'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
				)
			)
	);

	// ������� ������� � �������
	// id		- ���������� id ���������� �������
	// order	- id ������ (������� shop_orders)
	// good		- id ������ (������� shop_tree_nodes)
	// qty		- ���������� ������ � ������
	// price	- ���� �� ������� ����� ��� �������
	if (isset($_cms_shop_orders_goods) && $_cms_shop_orders_goods!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_shop_orders_goods,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'order', 		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'good',  		'type'=>'int(11)',	 	'primary'=>false, 'default'=>'0'),
					array('name'=>'qty',	 	'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
					array('name'=>'price', 		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
				)
			)
	);

	// ������� �������
	// id		- ���������� id ������
	// menu		- id ���� � �������� ��������� �����
	// parent	- id ������ ���� � �������� ��������� �����
	// code		- ���������-�������� ������� ������
	// name		- �������� ������
	// note		- �������� ������
	// sort		- ������� ���������� �������
	// date		- ���� ���������� ��������� ������ ������
	// visible	- ������� ��������� (0 - �� ������������)
	if (isset($_cms_tree_node_table) && $_cms_tree_node_table!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_tree_node_table,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'menu',  		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'type', 		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
					array('name'=>'parent',  	'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
					array('name'=>'code',	 	'type'=>'varchar(10)',	'primary'=>false, 'null'=>true),
					array('name'=>'name', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'note',  		'type'=>'text',			'primary'=>false, 'null'=>true),
					array('name'=>'sort',  		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'date',  		'type'=>'date',			'primary'=>false),
					array('name'=>'visible',  	'type'=>'int(1)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'cnt_view', 	'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'cnt_order',  'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
				)
			)
	);

	// ������� ���������� �������
	// id		- ���������� id ���������
	// node		- id ������
	// typeId	- ��� ��������� (id �� ������� �������� ���������� ������� $_cms_good_types)
	// type		- ��� ��������� (e(enum)|d(digital)|i(image)|c(char))
	// value	- �������� ���������
	if (isset($_cms_tree_node_details) && $_cms_tree_node_details!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_tree_node_details,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'node',  		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'typeId',	 	'type'=>'varchar(50)',	'primary'=>false, 'null'=>true),
					array('name'=>'type', 		'type'=>'varchar(5)', 	'primary'=>false, 'null'=>true),
					array('name'=>'value',     	'type'=>'text',			'primary'=>false, 'null'=>true),
				),
				'indexes'=>array(
					array('name'=>'tv',		'index'=>'typeId, value(950)'),
				)
			)
	);

	// ������� �������� ������������
	// id		- ���������� id �����������
	// name		- �������� �����������
	if (isset($_cms_directories) && $_cms_directories!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_directories,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'name', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
				),
				'indexes'=>array(
				)
			)
	);

	// ������� ������ ������������
	// id		- ���������� id �������
	// dir		- id �����������
	// content	- �������� �������
	// linked	- id ���������� ������ ����)
	if (isset($_cms_directories_data) && $_cms_directories_data!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_directories_data,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'dir', 		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'content',  	'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'linked',		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
					array('name'=>'dc',			'index'=>'dir, content'),
				)
			)
	);

	// ������� ������������������ ��������
	// id			- ���������� id �������
	// language		- ID ����� ������������
	// email		- email �������
	// password		- ����� �������
	// surname		- �������
	// name			- ���
	// fathername	- ��������
	// phone		- �������
	// status		- ������ ������� (0-�� ����������� email)
	// activation	- ��� ��������� ��������
	if (isset($_cms_users_table) && $_cms_users_table!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_users_table,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'language',	'type'=>'varchar(5)',	'primary'=>false, 'null'=>true),
					array('name'=>'email',     	'type'=>'varchar(50)', 	'primary'=>false, 'null'=>true),
					array('name'=>'password',  	'type'=>'varchar(30)', 	'primary'=>false, 'null'=>true),
					array('name'=>'surname',   	'type'=>'varchar(30)',	'primary'=>false, 'null'=>true),
					array('name'=>'name', 		'type'=>'varchar(30)',	'primary'=>false, 'null'=>true),
					array('name'=>'fathername',	'type'=>'varchar(30)', 	'primary'=>false, 'null'=>true),
					array('name'=>'phone',  	'type'=>'varchar(20)', 	'primary'=>false, 'null'=>true),
					array('name'=>'address',  	'type'=>'text',   		'primary'=>false, 'null'=>true),
					array('name'=>'status',  	'type'=>'int(1)',	   	'primary'=>false, 'default'=>'0'),
					array('name'=>'activation', 'type'=>'varchar(32)', 	'primary'=>false, 'null'=>true),
				),
				'indexes'=>array(
				)
			)
	);

	// ������� ������� ��������
	// id			- ���������� id ������
	// user_id		- ID ������������
	// name			- �.�.�. ����������
	// organization	- �����������
	// post_index	- �������� ������
	// country		- ������
	// state		- ������� / ����
	// city			- �����
	// street		- �����
	// house		- ���
	// building		- ������
	// flat			- ��������
	// note			- ����������
	// visible		- ������� ��������� (0 - �� ������������)
	if (isset($_cms_users_addresses_table) && $_cms_users_addresses_table!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_users_addresses_table,
				'fields'=>array(
					array('name'=>'id', 			'type'=>'int(11)',		'primary'=>true),
					array('name'=>'user_id',		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'name',			'type'=>'varchar(250)',	'primary'=>false, 'null'=>true),
					array('name'=>'organization',	'type'=>'varchar(250)',	'primary'=>false, 'null'=>true),
					array('name'=>'post_index',  	'type'=>'varchar(20)', 	'primary'=>false, 'null'=>true),
					array('name'=>'country',	   	'type'=>'varchar(30)',	'primary'=>false, 'null'=>true),
					array('name'=>'state',	 		'type'=>'varchar(50)',	'primary'=>false, 'null'=>true),
					array('name'=>'city',			'type'=>'varchar(100)', 'primary'=>false, 'null'=>true),
					array('name'=>'street',			'type'=>'varchar(200)', 'primary'=>false, 'null'=>true),
					array('name'=>'house',			'type'=>'varchar(20)',  'primary'=>false, 'null'=>true),
					array('name'=>'building',		'type'=>'varchar(20)',  'primary'=>false, 'null'=>true),
					array('name'=>'flat',			'type'=>'varchar(20)',  'primary'=>false, 'null'=>true),
					array('name'=>'note',			'type'=>'text',			'primary'=>false, 'null'=>true),
					array('name'=>'visible',		'type'=>'int(1)',		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
				)
			)
	);

	// ������� ��������� �������������
	// id	  	- ���������� id ���������
	// user_id	- id ������������
	// date	  	- ���� ���������� ���������
	// name	  	- ��� ������������
	// email  	- email ������������
	// subject	- ���� ���������
	// message	- ����� ���������
	// answer 	- ����� ������������� �����
	// status 	- ������ ���������
	if (isset($_cms_messages_table) && $_cms_messages_table!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_messages_table,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'user_id',	'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
					array('name'=>'date',		'type'=>'date', 		'primary'=>false),
					array('name'=>'name',   	'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'email', 		'type'=>'varchar(50)',	'primary'=>false, 'null'=>true),
					array('name'=>'subject',	'type'=>'varchar(254)', 'primary'=>false, 'null'=>true),
					array('name'=>'message',  	'type'=>'text', 		'primary'=>false, 'null'=>true),
					array('name'=>'answer',  	'type'=>'text',	   		'primary'=>false, 'null'=>true),
					array('name'=>'status', 	'type'=>'int(1)', 		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
				)
			)
	);

	// ������� ��������
	// id			- ���������� id �������
	// menu_item	- id ������ ���� � �������� ��������� ������
	// name			- �������� �������
	// note			- �������� �������
	// sort			- ������� ���������� ��������
	// gallery		- ID ��������� ������������
	// date			- ���� ���������� ��������� ������ �������
	// visible		- ������� ��������� (0 - �� ������������)
	if (isset($_cms_objects_table) && $_cms_objects_table!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_objects_table,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'menu_item',	'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'type', 		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
					array('name'=>'name', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'note',  		'type'=>'text',			'primary'=>false, 'null'=>true),
					array('name'=>'image', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'gallery',	'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'sort',  		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'date',  		'type'=>'date',			'primary'=>false, 'null'=>true),
					array('name'=>'visible',  	'type'=>'int(1)',		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
					array('name'=>'ft_name',			'index'=>'name, note(512)',	'fulltext'=>true),
				)
			)
	);

	// ������� ���������� ����������
	// id		- ���������� id ���������
	// node		- id �������
	// typeId	- ��� ��������� (id �� ������� �������� ���������� ������� $_cms_objects_types)
	// type		- ��� ��������� (e(enum)|d(digital)|i(image)|c(char))
	// value	- �������� ���������
	if (isset($_cms_objects_details) && $_cms_objects_details!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_objects_details,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'node',  		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'typeId',	 	'type'=>'varchar(50)',	'primary'=>false, 'null'=>true),
					array('name'=>'type', 		'type'=>'varchar(10)', 	'primary'=>false, 'null'=>true),
					array('name'=>'value',     	'type'=>'text',			'primary'=>false, 'null'=>true),
				),
				'indexes'=>array(
				)
			)
	);

	// ������� �����
	// id		 			- ���������� id �����
	// language				- ID ����� �����
	// date_publish_start	- ���� ������ ����������� �����
	// date_publish_stop	- ���� ��������� ����������� �����
	// date_start 			- ���� ������ �������� �����
	// date_stop  			- ���� ��������� �������� �����
	// content	  			- �������� �����
	// image	  			- ��� ����� ����������� ��� �����
	// linked	  			- id ���������� ������� (+n - �����; -n - ����� ����)
	// visible	  			- ������� ����������� (0 - �� ������������)
	if (isset($_cms_actions_table) && $_cms_actions_table!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_actions_table,
				'fields'=>array(
					array('name'=>'id', 				'type'=>'int(11)', 		'primary'=>true),
					array('name'=>'language', 			'type'=>'varchar(5)',	'primary'=>false, 'null'=>true),
					array('name'=>'date_start',			'type'=>'date',			'primary'=>false),
					array('name'=>'date_stop', 			'type'=>'date',			'primary'=>false),
					array('name'=>'date_publish_start',	'type'=>'date',			'primary'=>false),
					array('name'=>'date_publish_stop',	'type'=>'date',			'primary'=>false),
					array('name'=>'title', 	   			'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'content',   			'type'=>'text', 		'primary'=>false, 'null'=>true),
					array('name'=>'image', 	   			'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
					array('name'=>'linked',	   			'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
					array('name'=>'visible',   			'type'=>'int(1)', 		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
				)
			)
	);

	// ������� ����������� ��� ��������
	// id			- ���������� id �������
	// language		- ID ����� �������
	// menu_item	- ID ������� � �������� �������� ������
	// type			- ID ���� ������� (=>$_cms_banners_description);
	// text			- �����
	// file			- ��� ����� �����������
	// url			- URL ������ � �������
	// link			- ID ������� �� ������� ��������� ������
	// sort			- ������� ���������� �����������
	// visible		- ������� ����������� (0 - �� ������������)
	if (isset($_cms_banners_table) && $_cms_banners_table!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_banners_table,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'language',	'type'=>'varchar(5)',	'primary'=>false, 'null'=>true),
					array('name'=>'menu_item',	'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'type',		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'file',  		'type'=>'varchar(21)',	'primary'=>false, 'null'=>true),
					array('name'=>'text', 	   	'type'=>'text',			'primary'=>false, 'null'=>true),
					array('name'=>'url', 	   	'type'=>'text',			'primary'=>false, 'null'=>true),
					array('name'=>'link',		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'sort',  		'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'visible',	'type'=>'int(1)', 		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
				)
			)
	);

	// ������� ������� �����
	// id	  		- ���������� id ������ ������
	// menu_item	- id ������ ���� � �������� ��������� ������
	// date			- ���� ����������
	// title		- ���������
	// image		- ��������
	// content		- ���������� ������
	// video		- URL �������������� ������������ � ������
	// attachment	- ������������� ��������
	// url			- ������
	// visible		- ���� ����������� �� �����
	if (isset($_cms_walls_table) && $_cms_walls_table!='')
		array_push($_admin_db_structure,
			array('name'=>$_cms_walls_table,
				'fields'=>array(
					array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
					array('name'=>'menu_item',	'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
					array('name'=>'date',		'type'=>'datetime',		'primary'=>false),
					array('name'=>'title',		'type'=>'varchar(255)',	'primary'=>false, 'null'=>true),
					array('name'=>'image',		'type'=>'varchar(255)', 'primary'=>false, 'null'=>true),
					array('name'=>'content',	'type'=>'text', 		'primary'=>false, 'null'=>true),
					array('name'=>'video',		'type'=>'text', 		'primary'=>false, 'null'=>true),
					array('name'=>'attachment',	'type'=>'int(10)',		'primary'=>false, 'null'=>true),
					array('name'=>'url',		'type'=>'text', 		'primary'=>false, 'null'=>true),
					array('name'=>'visible',	'type'=>'int(1)', 		'primary'=>false, 'default'=>'0'),
				),
				'indexes'=>array(
					array('name'=>'date',		'index'=>'date'),
				)
			)
	);

	// ������� ����������� �� ������ � ������������ � ����-���� ������
	// id	  		- ���������� id
	// name			- �������� ���������
	// document		- ������������ ��� �����
	// real_path	- ���� � ����� �� ����� � �������� ������� �� ����� �����
	array_push($_admin_db_structure,
		array('name'=>'_attachments',
			'fields'=>array(
				array('name'=>'id', 			'type'=>'int(11)',		'primary'=>true),
				array('name'=>'name',			'type'=>'varchar(255)',	'primary'=>false, 'null'=>true),
				array('name'=>'file',			'type'=>'varchar(255)',	'primary'=>false, 'null'=>true),
				array('name'=>'real_file',		'type'=>'varchar(255)',	'primary'=>false, 'null'=>true),
			),
			'indexes'=>array(
			)
		)
	);

	// ��������� ������� ������������ ����������� � �������� ���� ������ � �����
	// id	  		- ���������� id ������ ������
	// real_name	- ��� ����� ��� ����� � �������� �������
	// real_path	- ���� � ����� �� ����� � �������� ������� �� ����� �����
	// virtual_name	- ����������� (������������) ��� ����� ��� �����. ������ ���� ����������
	// virtual_path	- ����������� ���� � ����� ��� ����� ������������ ������-�� ����� (������������ ����� �����)
	array_push($_admin_db_structure,
		array('name'=>'_vfs',
			'fields'=>array(
				array('name'=>'id', 			'type'=>'int(11)',		'primary'=>true),
				array('name'=>'real_name',		'type'=>'varchar(64)',	'primary'=>false, 'null'=>true),
				array('name'=>'real_path',		'type'=>'text',			'primary'=>false, 'null'=>true),
				array('name'=>'virtual_name',	'type'=>'varchar(64)',	'primary'=>false, 'null'=>true),
				array('name'=>'virtual_path',	'type'=>'text', 		'primary'=>false, 'null'=>true),
			),
			'indexes'=>array(
				array('name'=>'virtual_name',	'index'=>'virtual_name'),
			)
		)
	);

	// ��������� ������� ��������� ��� ����� ������
	// id	  		- ���������� id ������ ������
	// text			- ����� ���������
	// dir			- ID ����� ���������
	array_push($_admin_db_structure,
		array('name'=>'dir_prompt',
			'fields'=>array(
				array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
				array('name'=>'text', 		'type'=>'varchar(255)',	'primary'=>false, 'null'=>true),
				array('name'=>'dir',   		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
			),
		)
	);

	// ��������� ������� ������ �����
	// id	  		- ���������� id ������ ������
	// path			- ������ ���� � �����
	// size			- ������ �����
	// timestamp	- ���� � ����� ��������� �����
	// md5			- ����������� ����� �����
	// content		- ���������� �����
	array_push($_admin_db_structure,
		array('name'=>'_files_data',
			'fields'=>array(
				array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
				array('name'=>'path',  		'type'=>'text',			'primary'=>false, 'null'=>true),
				array('name'=>'size',  		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
				array('name'=>'timestamp',	'type'=>'int(11)',		'primary'=>false, 'default'=>'0'),
				array('name'=>'md5', 		'type'=>'varchar(32)',	'primary'=>false, 'null'=>true),
				array('name'=>'content',  	'type'=>'mediumblob',	'primary'=>false, 'null'=>true),
			),
		)
	);

	// ��������� ������� ��������� ����������� ������
	// id	  	- ���������� id ������ � �����
	// file		- ��� �����
	// created	- ���� �������� �����
	array_push($_admin_db_structure,
		array('name'=>'_temp_files',
			'fields'=>array(
				array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
				array('name'=>'file', 		'type'=>'varchar(254)',	'primary'=>false, 'null'=>true),
				array('name'=>'created',  	'type'=>'date',			'primary'=>false),
			),
		)
	);

/*
	// ��������� ������� ��������� ������
	// id	  	- ���������� id ������ � �����
	// type		- ��� ������
	//				1 - ��������� ��������� ������
	//				2 - ����������� ��������� ������
	//				3 - ����� ��������� ������
	// node		- ID ������� � �������� ��������� ������
	// tag		- ��� ������
	// data		- ������
	// created	- ���� �������� ������� ������
	array('name'=>'_temp_files',
		'fields'=>array(
			array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
			array('name'=>'type', 		'type'=>'int(11)',		'primary'=>true),
			array('name'=>'file', 		'type'=>'varchar(254)',	'primary'=>false),
			array('name'=>'created',  	'type'=>'date',			'primary'=>false),
		),
	),
*/

?>