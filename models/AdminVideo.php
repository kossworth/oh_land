<?php

// Video
class admin_video
{
	public $NAME = "Видео";
    public $NAME2 = "видео";
	public $fld;
	public $SORT = 'name_1 asc';
	public $ECHO_NAME = 'name_1';
	
	function __construct()
	{
		$this->fld=array(
			new Field("name_1", "Название",1),
			new Field("object", "Код видео",1),
			new Field("hide","Скрыть",6,1,1),
			new Field("crtdate", "Date of creation",4)
		);
	}

	function addSpFields($row,$under)
	{
		return 1;
	}
};