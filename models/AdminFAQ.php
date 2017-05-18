<?
/*
 *  Вопросы
 * */

class admin_faq_categories
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

class admin_faq
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
	public $NAME="Вопросы и ответы";
	public $NAME2="вопрос-ответ";
	
	function __construct()
	{
		$this->fld=array(
			new Field("name_1","Заголовок",1),
			new Field("name_2","Заголовок УКР",1),
            new Field("alias","Alias для URL на английском без пробелов", 1,1),
            new Field("text_1","Текст",2),
            new Field("text_2","Текст УКР",2),
            new Field("to_main","Вывести на главную",6,1,1),
//			new Field("under","Находится в разделе",9,0,0,'docs_categories',-1,'name_1'),
			new Field("sort","SORT",4),
			new Field("crtdate","Date of creation",4),
		);
        

	}

    function addSpFields($row,$under) {
        
    
        $q = "SELECT * FROM faq ORDER by id ASC";
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
                
              mysql_query("UPDATE faq 
                                SET alias = '" . $row['alias'] . "' WHERE id = ".$row['id']);
         }
                
        $url = '/faq/' . $row['alias'] . '/';
        
        $urlBase = '/faq/question/' . $row['id'] . '/';
        
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

?>
