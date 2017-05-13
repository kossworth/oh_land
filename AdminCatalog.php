<?php

// Компании
class admin_company
{
    public $NAME = "Страховые компании";
    public $NAME2 = "компанию";
    public $fld;
    public $SORT = 'sort desc';
    public $ECHO_ID = 'id';
    public $ECHO_NAME = 'name_1';

    public $IMG_SIZE = 120;
    public $IMG_RESIZE_TYPE = 1;
    public $IMG_BIG_SIZE = 480;
    public $IMG_BIG_VSIZE = 200;
    public $IMG_NUM = 2;
    public $IMG_FIELD = 'id';
    public $IMG_TYPE = 'png';
    public $FOLDER_IMAGES = 2;
    
    public $RUBS_NO_UNDER = 1;
    
    function __construct() {
        $this->fld = array(
            new Field("name_1","Название РУС",1),
            new Field("name_2","Название УКР",1),
            new Field("alias","Alias для URL на английском без пробелов", 1,1),
            new Field("active","Вывод на главной",6,1,1),
            new Field("active_osago","ОСАГО",6,1,1),
            new Field("active_greencard","ЗК",6,1,1),
            new Field("description_1","Описание РУС",2),
            new Field("description_2","Описание УКР",2),
            new Field("ewa_id","EWA ID",0,1,1),
            new Field("rating","Рейтинг",0,1,1),
            new Field("background_file","Файл фона для шапки",11),
            new Field("sort","SORT",4),
        );
    }
	
    function addSpFields($row,$under) {
        
    
        $q = "SELECT * FROM company ORDER by id ASC";
        $res = mysql_query($q);
        $num = mysql_num_rows($res);

        for($i=0;$i<$num;$i++) {
            $row = mysql_fetch_assoc($res);
            //echo $row['name'] . '... ';
            $this->afterEdit($row);
        }
    
        return 1;
	}
    
    function afterEdit($row) {
        
		   
         if (empty($row['alias'])) {
                
              $row['alias'] = strtolower(Translit($row['name_1']));
                
              mysql_query("UPDATE company 
                                SET alias = '" . $row['alias'] . "' WHERE id = ".$row['id']);
         }
                
        $url = '/company/' . $row['alias'] . '/';
        
        $urlBase = '/company/view/' . $row['id'] . '/';
        
        $q = 'INSERT INTO am_rewrite_mod
                SET rewrite = "' . $url . '", system_url = "' . $urlBase . '"
                ON DUPLICATE KEY UPDATE rewrite = "' . $url . '"';
       //echo '!->'.$q;
       mysql_query($q);
       echo mysql_error();
    }
    
    function afterAdd($row) {
        $this->afterEdit($row);
    }
    
    
};

// Типы страховок 
class admin_product_type
{
	public $NAME = "Типы страховых продуктов";
    public $NAME2 = "тип";
    public $fld;
	public $SORT='name_1 asc';
	public $ECHO_ID='id';
	public $ECHO_NAME='name_1';
	
   
    public $FIELD_UNDER = 'parent_id'; 
        
	function __construct()
	{
		$this->fld[] = new Field("name_1","Название", 1);
		$this->fld[] = new Field("name_2","Название УКР", 1);
		$this->fld[] = new Field("description_1","Описание РУС",2);
		$this->fld[] = new Field("description_2","Описание УКР",2);
		$this->fld[] = new Field("parent_id","Тип продукта", 9, 1, 0, 'product_type', -1, 'name_1');

	}
	
    function addSpFields($row,$under)
	{
       return;
    }

};

// Страховые продукты
class admin_product
{
	public $NAME = "Страховые продукы";
    public $NAME2 = "продукт";
    public $fld;
	public $SORT='id desc';
	public $ECHO_ID='id';
	public $ECHO_NAME='name_1';
	
   
    public $FIELD_UNDER = 'type'; 
        
	function __construct()
	{
		$this->fld[] = new Field("name_1","Название", 1);
		$this->fld[] = new Field("name_2","Название УКР", 1);
		$this->fld[] = new Field("cis_id","CIS ID", 0);
		$this->fld[] = new Field("system_key","Алиас для ссылки", 1,1);
		$this->fld[] = new Field("type","Тип продукта", 9, 1, 0, 'product_type', -1, 'name_1');

	}
	
    function addSpFields($row,$under)
	{
       return;
    }

};

// Конкретные продукты
class admin_company_product
{
    public $NAME = "Продукы компаний";
    public $NAME2 = "продукт";
    public $fld;
    public $SORT='id desc';
    public $ECHO_ID='id';
    public $ECHO_NAME='name_1';
    public $RUBS_NO_UNDER = 1;
	
   
    public $FIELD_UNDER = 'company_id'; 
        
	function __construct()
	{
            $this->fld[] = new Field("name_1","Индивидуальное название", 1);
            $this->fld[] = new Field("name_2","Индивидуальное название УКР", 1);
            $this->fld[] = new Field("description_1","Описание РУС",2);
            $this->fld[] = new Field("description_2","Описание УКР",2);
            $this->fld[] = new Field("company_id","Компания", 9, 0, 0, 'company', NULL, 'name_1');
            $this->fld[] = new Field("product_id", "Тип продукта", 9, 1, 0, 'product', NULL, 'name_1');
            $this->fld[] = new Field("landing_top", "Топ продаж (лендинг)", 6);
            $this->fld[] = new Field("landing_recommend", "Рекомендуем (лендинг)", 6);
            $this->fld[] = new Field("landing_bonuses", "Бонусы предложения на лендинге", 16);
	}
	
    function addSpFields($row,$under)
    {
       return;
    }

};


// СЕО инструменты
class admin_am_seo_meta
{
	public $NAME = "Настройки СЕО";
    public $NAME2 = "запись";
    public $fld;
	public $SORT='system_url asc';
	public $ECHO_ID='id';
	public $ECHO_NAME='system_url';
	
   
        
	function __construct()
	{
		$this->fld[] = new Field("system_url","Системный УРЛ (/osago/index/kyiv/)", 1);
		$this->fld[] = new Field("lang","Язык (ru | ua)", 1,1);
		$this->fld[] = new Field("title","Тайтл",1,1);
		$this->fld[] = new Field("description","Дескрипшн",1);
		$this->fld[] = new Field("keywords","Ключевые слова",1);
		$this->fld[] = new Field("h1","H1",1);
		$this->fld[] = new Field("link_title","Текст ссылки города на странице продукта",1);
		$this->fld[] = new Field("text","SEO - текст", 2);

	}
	
    function addSpFields($row,$under)
	{
       return;
    }

};

// УРЛы городов
class admin_city_url
{
	public $NAME = "УРЛы городов";
    public $NAME2 = "город";
    public $fld;
	public $SORT='name asc';
	public $ECHO_ID='id';
	public $ECHO_NAME='name';
	
   
        
	function __construct()
	{
		$this->fld[] = new Field("name","Город", 1);
		$this->fld[] = new Field("url","Alias для URL на английском без пробелов", 1,1);
	}
	
    function addSpFields($row,$under)
	{
       return;
    }

};
