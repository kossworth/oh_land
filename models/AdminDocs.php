<?
/*
 *  Текстовые страницы
 * */

class admin_docs_categories
{
	public $fld;
	public $SORT = 'name_1 asc';
	public $ECHO_NAME = 'name_1';
    public $FIELD_UNDER = 'under';
    
	function __construct()
	{
		$this->fld=array(
			new Field("name_1", "Название",1),
			new Field("under","Находится в разделе",9,0,0,'docs_categories',-1,'name_1'),
		);
	}

	function addSpFields($row,$under)
	{
		return 1;
	}
};

class admin_docs
{
	public $fld;
    
	public $SORT = 'sort DESC';
	
	public $IMG_SIZE = 100;
	public $IMG_RESIZE_TYPE = 1;
	public $IMG_BIG_SIZE = 250;
	public $IMG_BIG_VSIZE = 250;
	public $IMG_NUM = 0;
	public $ECHO_NAME = 'name_1';
    
    public $FIELD_UNDER = 'under';
	public $NAME="Текстовые страницы";
	public $NAME2="текстовую страницу";
	
	function __construct()
	{
		$this->fld=array(
			new Field("name_1","Заголовок",1),
			new Field("name_2","Заголовок УКР",1),
            new Field("text_1","Текст",2),
            new Field("text_2","Текст УКР",2),
            new Field("partner_id","Витрина",9,1,0,'partners',NULL,'name_1'),
            new Field("system_key","Системный тэг",1),
			
			new Field("under","Находится в разделе",9,0,0,'docs_categories',-1,'name_1'),
			new Field("sort","SORT",4),
			new Field("crtdate","Date of creation",4),
		);
        

	}

	function addSpFields($row,$under)
	{

		return 1;
	}
	

};

?>
