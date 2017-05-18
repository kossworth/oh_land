<?php
// News
class admin_news
{
	public $NAME = "Новости";
    public $NAME2 = "новость";
    public $fld;
	public $SORT = 'id desc';
	public $ECHO_ID = 'id';
	public $ECHO_NAME = 'name_1';
	//public $FIELD_UNDER = 'rub_id';
    
	public $IMG_SIZE = 200;
	public $IMG_RESIZE_TYPE = 1;
	public $IMG_BIG_SIZE = 450;
	public $IMG_BIG_VSIZE = 450;
	public $IMG_NUM = 1;
	public $IMG_FIELD = 'id';
    public $FOLDER_IMAGES = 2;
    
	function __construct()
	{
		$this->fld = array(
			new Field("name_1","Заголовок",1),
			new Field("name_2","Заголовок УКР",1),
            new Field("alias","Alias для URL на английском без пробелов", 1,1),
			new Field("not_unique","Текст неуникальный",6,1,1),
			new Field("date","Дата",13,1),
            new Field("preview_1","Краткий текст",7,1),
            new Field("preview_2","Краткий текст УКР",7,1),
            new Field("text_1","Полный текст",2),
            new Field("text_2","Полный текст УКР",2)
		);
	}
	
    function addSpFields($row,$under) {
        
        $q = 'DELETE FROM am_rewrite_mod
                WHERE system_url  LIKE "%/news/%"';
           //echo '!->'.$q;
        mysql_query($q);
           
       
        $q = "SELECT * FROM news ORDER by id ASC";
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
                
              mysql_query("UPDATE news 
                                SET alias = '" . $row['alias'] . "' WHERE id = ".$row['id']);
         }
                
        $url = '/news/' . $row['alias'] . '/';
        
        $urlBase = '/news/view/' . $row['id'] . '/';
        
        $q = 'INSERT INTO am_rewrite_mod
                SET rewrite = "' . $url . '", system_url = "' . $urlBase . '"
                ON DUPLICATE KEY UPDATE rewrite = "' . $url . '"';
       //echo '!->'.$q;
       mysql_query($q);
       
       $q = 'INSERT INTO am_rewrite_mod
                SET rewrite = "/ua' . $url . '", system_url = "/ua' . $urlBase . '"
                ON DUPLICATE KEY UPDATE rewrite = "/ua' . $url . '"';
       //echo '!->'.$q;
       mysql_query($q);
       echo mysql_error();
    }
    
    function afterAdd($row) {
        $this->afterEdit($row);
    }
    

	
};

