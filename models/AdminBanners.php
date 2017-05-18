<?php
/*
 * Баннера
 * */
 
class admin_banners_places
{
	var $fld;
	var $SORT='name asc';
	var $ECHO_ID='id';
    var $ECHO_NAME = 'name';
    var $FIELD_UNDER = 'place_id';
    
	function __construct()
	{
		$this->fld = array(
			new Field("name", "Название", 1),
			new Field("banners_limit", "Лимит баннеров", 0,1),
			new Field("place_id", "Находится в",9,0,0,'banners_places',NULL,'name'),
			new Field("system_key", "Системный ключ", 1)
		);
	}

	function addSpFields($row,$under)
	{
	return 1;
	}
};

class admin_banners
{
	var $fld;
	var $SORT='sort DESC';
	var $ECHO_NAME='name_1';
	
	var $NAME = 'Баннера';
	var $NAME2 = 'баннер';

	var $IMG_NUM = 0;
	
	function __construct()
	{
		$this->fld = array(
			new Field("name_1","Заголовок РУС",1),
			new Field("name_2","Заголовок УКР",1),
			new Field("active", "Показывать?", 6, 1, 1),
            new Field("txt_1", "Текст РУС", 2),
			new Field("txt_2", "Текст УКР", 2),
			new Field("url","Ссылка с http://", 1),
			new Field("file_1","Файл РУС",11),
			new Field("file_2","Файл УКР",11),
			new Field("place_id","Площадка",9,0,0,'banners_places',NULL,'name'),
			new Field("sort","SORT",4)
		);
	}

	function addSpFields($row,$under)
	{
		return 1;
	}
};
