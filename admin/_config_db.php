<?php
$_admin_db_structure=array();

	// таблица для работы с константами
	// id 		- уникальный id константы
	// name 	- имя константы
	// value	- значение константы
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

	// таблица меню сайта
	// id	- уникальный id меню
	// name	- название меню
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

	// таблица пунктов меню
	// id		- уникальный id пункта меню
	// name		- текст (название) пункта меню
	// url		- URL связанный с этим пунктом меню
	// parent	- родительский пункт меню
	// sort		- порядок сортировки пунктов меню
	// menu		- id меню к которому относится данный пункт
	// visible	- признак отображения (0 - не отображается)
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

	// таблица информации о галереях
	// id			- уникальный id блока данных (описание галереи не формируется автоматически и может отсутствовать)
	// language		- ID языка данных
	// menu_item	- пункт меню с которым связанна галерея
	// link_type	- тип ссылки задаваемой menu_item (0-меню, 1-объект)
	// name			- название галереи
	// note			- описание галереи
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

	// таблица изображений для фотогалерей
	// id			- уникальный id изобажения
	// menu_item	- пункт меню с которым связанна галерея
	// link_type	- тип ссылки задаваемой menu_item (0-меню, 1-объект)
	// file			- имя файла изображения
	// title		- название изображения
	// comment		- описание изображения
	// sort			- порядок сортировки изображений в галерее
	// visible		- признак отображения (0 - не отображается)
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

	// таблица новостей
	// id			- уникальный id новости
	// language		- ID языка новости
	// date			- дата публикации новости
	// content		- содержание новости
	// image		- имя файла изображения для новости
	// linked		- id связанного объекта (+n - текст; -n - пункт меню)
	// object_id	- id of linked object
	// visible		- признак отображения (0 - не отображается)
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

	// таблица текстов
	// id			- уникальный id текста
	// signature	- уникальная сигнатура текста
	// menu_item	- пункт меню с которым связан текст
	// date			- дата редактирования текста
	// title		- заголовок текста
	// keywords		- ключевые слова
	// descr		- текст для метаттэга description
	// content		- текст (HTML код)
	// visible		- признак видимости (0 - не отображается)
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

	// таблица структурированных текстов
	// id			- уникальный id части текста
	// type			- тип связи текста (0-меню; 1-свойство объекта)
	// node			- ID объекта (меню, свойства объекта) с которым связан текст
	// date			- дата редактирования текста
	// title		- заголовок части
	// image		- имя файла картинки
	// content		- текст (HTML код)
	// sort			- порядок сортировки частей в тексте
	// visible		- признак видимости (0 - не отображается)
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

	// таблица документов
	// id			- уникальный id документа
	// name			- название документа
	// note 		- комментарии к документу
	// file			- имя файла с документом в файловой системе
	// real_file	- оригинальное имя файла (для скачивания)
	// visible		- признак видимости (0 - не отображается)
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

	// Таблица сведений о коллективе
	// id			- уникальный id человека
	// department	- id отдела (пока не используется)
	// position		- должность
	// note			- комментарии
	// image		- имя файла изображения
	// visible		- признак видимости (0 - не отображается)
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

	// Таблица заказов в магазине
	// id			- уникальный id заказа
	// public_id	- уникальный символьный идентификатор заказа
	// date			- дата заказа
	// user			- id клиента сделавшего заказ
	// address		- id адреса клиента
	// status		- статус заказа (код из массива статусов $_order_statuses)
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

	// Таблица товаров в заказах
	// id		- уникальный id заказанной позиции
	// order	- id заказа (таблица shop_orders)
	// good		- id товара (таблица shop_tree_nodes)
	// qty		- количество товара в заказе
	// price	- цена по которой товар был заказан
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

	// Таблица товаров
	// id		- уникальный id товара
	// menu		- id меню к которому относится товар
	// parent	- id пункта меню к которому относится товар
	// code		- алфавитно-цифровой артикул товара
	// name		- название товара
	// note		- описание товара
	// sort		- порядок сортировки товаров
	// date		- дата последнего изменения данных товара
	// visible	- признак видимости (0 - не отображается)
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

	// Таблица параметров товаров
	// id		- уникальный id параметра
	// node		- id товара
	// typeId	- тип параметра (id из массива описания параметров товаров $_cms_good_types)
	// type		- тип параметра (e(enum)|d(digital)|i(image)|c(char))
	// value	- значение параметра
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

	// Таблица описания справочников
	// id		- уникальный id справочника
	// name		- название справочника
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

	// Таблица данных справочников
	// id		- уникальный id данного
	// dir		- id справочника
	// content	- значение данного
	// linked	- id связанного пункта меню)
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

	// Таблица зарегистрированных клиентов
	// id			- уникальный id клиента
	// language		- ID языка пользователя
	// email		- email клиента
	// password		- парль клиента
	// surname		- фамилия
	// name			- имя
	// fathername	- отчество
	// phone		- телефон
	// status		- статус клиента (0-не подтвержден email)
	// activation	- код активации аккаунта
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

	// Таблица адресов клиентов
	// id			- уникальный id адреса
	// user_id		- ID пользователя
	// name			- Ф.И.О. получателя
	// organization	- организация
	// post_index	- почтовый индекс
	// country		- страна
	// state		- область / штат
	// city			- город
	// street		- улица
	// house		- дом
	// building		- корпус
	// flat			- квартира
	// note			- примечание
	// visible		- признак видимости (0 - не отображается)
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

	// Таблица сообщений пользователей
	// id	  	- уникальный id сообщения
	// user_id	- id пользователя
	// date	  	- дата добавления сообщения
	// name	  	- имя пользователя
	// email  	- email пользователя
	// subject	- тема сообщения
	// message	- текст сообщения
	// answer 	- ответ администрации сайта
	// status 	- статус сообщения
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

	// Таблица объектов
	// id			- уникальный id объекта
	// menu_item	- id пункта меню к которому относится объект
	// name			- название объекта
	// note			- описание объекта
	// sort			- порядок сортировки объектов
	// gallery		- ID связанной фотогаллереи
	// date			- дата последнего изменения данных объекта
	// visible		- признак видимости (0 - не отображается)
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

	// Таблица параметров объектовов
	// id		- уникальный id параметра
	// node		- id объекта
	// typeId	- тип параметра (id из массива описания параметров товаров $_cms_objects_types)
	// type		- тип параметра (e(enum)|d(digital)|i(image)|c(char))
	// value	- значение параметра
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

	// таблица акций
	// id		 			- уникальный id акции
	// language				- ID языка акции
	// date_publish_start	- дата начала отображения акции
	// date_publish_stop	- дата окончания отображения акции
	// date_start 			- дата начала действия акции
	// date_stop  			- дата окончания действия акции
	// content	  			- описание акции
	// image	  			- имя файла изображения для акции
	// linked	  			- id связанного объекта (+n - текст; -n - пункт меню)
	// visible	  			- признак отображения (0 - не отображается)
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

	// таблица изображений для баннеров
	// id			- уникальный id баннера
	// language		- ID языка баннера
	// menu_item	- ID раздела к которому привязан баннер
	// type			- ID типа баннера (=>$_cms_banners_description);
	// text			- текст
	// file			- имя файла изображения
	// url			- URL ссылки с баннера
	// link			- ID раздела на который ссылается баннер
	// sort			- порядок сортировки изображений
	// visible		- признак отображения (0 - не отображается)
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

	// Таблица записей стены
	// id	  		- уникальный id строки данных
	// menu_item	- id пункта меню к которому относится запись
	// date			- дата публикации
	// title		- заголовок
	// image		- картинка
	// content		- содержимое записи
	// video		- URL видеофрагмента вставленного в запись
	// attachment	- прикрепленный документ
	// url			- ссылка
	// visible		- флаг отображения на сайте
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

	// Таблица загруженных на сервер и прицепленных к чему-либо файлов
	// id	  		- уникальный id
	// name			- название документа
	// document		- оригинальное имя файла
	// real_path	- путь к файлу ли папке в файловой системе от корня диска
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

	// Сервисная таблица соответствий виртуальных и реальных имен файлов и папок
	// id	  		- уникальный id строки данных
	// real_name	- имя файла или папки в файловой системе
	// real_path	- путь к файлу ли папке в файловой системе от корня диска
	// virtual_name	- виртуальное (отображаемое) имя файла или папки. Должно быть уникальным
	// virtual_path	- виртуальный путь к файлу или папке относительно какого-то места (определяется видом файла)
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

	// Сервисная таблица подсказок при вводе данных
	// id	  		- уникальный id строки данных
	// text			- текст подсказки
	// dir			- ID блока подсказок
	array_push($_admin_db_structure,
		array('name'=>'dir_prompt',
			'fields'=>array(
				array('name'=>'id', 		'type'=>'int(11)',		'primary'=>true),
				array('name'=>'text', 		'type'=>'varchar(255)',	'primary'=>false, 'null'=>true),
				array('name'=>'dir',   		'type'=>'int(11)', 		'primary'=>false, 'default'=>'0'),
			),
		)
	);

	// Сервисная таблица данных фалов
	// id	  		- уникальный id строки данных
	// path			- полный путь к файлу
	// size			- размер файла
	// timestamp	- дата и время изменения файла
	// md5			- контрольная сумма файла
	// content		- садержимое файла
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

	// Служебная таблица временных загруженных файлов
	// id	  	- уникальный id записи о файле
	// file		- имя файла
	// created	- дата загрузки файла
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
	// Служебная таблица временных данных
	// id	  	- уникальный id записи о файле
	// type		- тип данных
	//				1 - заголовок фрагмента текста
	//				2 - изображение фрагмента текста
	//				3 - текст фрагмента текста
	// node		- ID объекта к которому относятся данные
	// tag		- тэг данных
	// data		- данные
	// created	- дата создания объекта данных
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