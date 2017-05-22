<?php

// "7 причин" лендинг
class admin_landing_reasons
{
    public $TABLE           = "landing_reasons";
    public $NAME            = "7 причин";
    public $NAME2           = "причину";
    public $fld;
    public $SORT            = 'sort desc';
//    public $ECHO_ID         = 'id';
    public $ECHO_NAME       = 'name_1';
    public $RUBS_NO_UNDER   = 1;
        
    function __construct()
    {
        $this->fld[] = new Field("name_1", "Текст", 16);
        $this->fld[] = new Field("active", "Опубликована", 6, ['showInList' => 1, 'editInList' => 1]);
        $this->fld[] = new Field("sort", "sort", 4);
        $this->fld[] = new Field("crtdate", "Дата создания", 4);
    }
    
    function addSpFields($row,$under)
    {
       return;
    }

};