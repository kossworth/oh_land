<?php
// Blog
class admin_blog_posts
{
	public $NAME = "Блог";
    public $NAME2 = "пост";
    public $fld;
	public $SORT = 'id desc';
	public $ECHO_ID = 'id';
	public $ECHO_NAME = 'name';

	public $RUBS_NO_UNDER = 1;
    
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
			new Field("name","Заголовок",1),
			new Field("date","Дата",13,1),
            new Field("preview","Краткий текст",7,1),
            new Field("text","Полный текст",2),
		);
	}
	
	function addSpFields($rowi,$under)
	{
		return 1;
	}
    
	
};

class admin_blog_comments
{
	public $fld;
    
	public $SORT = 'id ASC';
	
	public $IMG_SIZE = 100;
	public $IMG_RESIZE_TYPE = 1;
	public $IMG_BIG_SIZE = 250;
	public $IMG_BIG_VSIZE = 250;
	public $IMG_NUM = 0;
	public $ECHO_NAME = 'name';
    
    public $FIELD_UNDER = 'post_id';
	public $NAME = "Комментарии";
	public $NAME2 = "комментарий";
	public $RUBS_NO_UNDER = 1;
    
	function __construct()
	{
		$this->fld=array(
			new Field("name","Ник",1),
            new Field("date","Дата",13,1),
            new Field("text","Текст",7,1),
			new Field("post_id","Находится в посте",9,0,0,'blog_posts',NULL,'name'),
		);
        

	}

	function addSpFields($row,$under)
	{

		return 1;
	}
	

};
