<?
/*
 *  Настройки
 * */

class admin_settings
{
	public $fld;
    
	public $SORT = 'name ASC';
	
	public $IMG_SIZE = 100;
	public $IMG_RESIZE_TYPE = 1;
	public $IMG_BIG_SIZE = 250;
	public $IMG_BIG_VSIZE = 250;
	public $IMG_NUM = 0;
	public $ECHO_NAME = 'name';
    
	public $NAME="Настройки";
	public $NAME2="настройку";
	
	function __construct()
	{
		$this->fld=array(
			new Field("name","Название",1),
            new Field("value_1","Значение РУС",1,1),
            new Field("value_2","Значение УКР",1,1),
            new Field("system_key","Системный тэг",1,1),
		);
        

	}

	function addSpFields($row,$under)
	{

		return 1;
	}
	

};

?>
