<?php
class admin_partners
{
	public $fld;
    
	public $SORT = 'id ASC';
	
	public $IMG_NUM = 0;
	public $ECHO_NAME = 'name_1';
    
	public $NAME="Партнерские витрины";
	public $NAME2="подсайт";
	
	function __construct()
	{
		$this->fld=array(
			new Field("name_1","Название",1),
			new Field("host","Хост",1,1),
			new Field("phone","Телефон",1,1),
            //new Field("admin_email","Адрес с которого отправляется почта",1,1),
            new Field("task_email","Адреса для получения уведомлений о заказах (через запятую)",1,1),
			new Field("crtdate","Date of creation",4),
		);
        

	}

	function addSpFields($row,$under)
	{

		return 1;
	}
	

};

