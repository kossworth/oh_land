<?php
class admin_menu
{
	public $fld;
	public $SORT='sort desc';
	public $ECHO_ID='id';
	public $NAME='Меню сайта';
	public $NAME2='пункт меню';
    public $FIELD_UNDER = 'parent_id';
    
	function __construct()
	{
		$this->fld=array(
			new Field("name_1","Название раздела",1),
			new Field("link","Адрес страницы (к примеру /clinics/)",1),
			new Field("style_class_name","Класс CSS",1),
			new Field("hide","Скрыть",6,1,1),
			new Field("parent_id","Находитcя в разделе",9,0,0,'menu',-1),
			new Field("sort","SORT",4),
			new Field("crtdate","Date of creation",4)
		);
	}

	function addSpFields($row,$under)
	{
		return 1;
	}
};

?>
