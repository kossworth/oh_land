<?php

// Отзывы с лендинга
class admin_landing_feedbacks
{
    public $TABLE           = "landing_feedbacks";
    public $NAME            = "Отзывы с лендинга";
    public $NAME2           = "отзыв";
    public $fld;
    public $SORT            = 'id desc';
    public $ECHO_ID         = 'id';
    public $ECHO_NAME       = 'username';
    public $RUBS_NO_UNDER   = 1;
        
    function __construct()
    {
        $this->fld[] = new Field("username", "Имя пользователя", 1);
        $this->fld[] = new Field("userphone", "Номер телефона пользователя", 1);
        $this->fld[] = new Field("published", "Опубликован", 6, ['showInList' => 1, 'editInList' => 1]);
        $this->fld[] = new Field("processed", "Обработан", 6, ['showInList' => 1, 'editInList' => 1]);
        $this->fld[] = new Field("facebook_link", "Ссылка на отзыв из Facebook", 1);
        $this->fld[] = new Field("rating", "Рейтинг", 1);
        $this->fld[] = new Field("text", "Текст отзыва", 16);
        $this->fld[] = new Field("crtdate", "Дата создания", 4);
        
        $this->fld[] = new Field("level_companies", "Выбор компаний", 1);
        $this->fld[] = new Field("level_service", "Уровень обслуживания", 1);
        $this->fld[] = new Field("level_price", "Уровень цен", 1);
        
//        $this->fld[] = new Field("rating", "Рейтинг", 10,
//                ['showInList' => 0,
//                    'values' => [
//                        '1',
//                        '2',
//                        '3',
//                        '4',
//                        '5',
//                    ]
//                ]
//            );
//        $this->fld[] = new Field("level_service", "Уровень обслуживания", 10,
//                ['showInList' => 0,
//                    'values' => [
//                        '1',
//                        '2',
//                        '3',
//                        '4',
//                        '5',
//                    ]
//                ]
//            );
//        $this->fld[] = new Field("level_price", "Уровень цен", 10,
//                ['showInList' => 0,
//                    'values' => [
//                        '1',
//                        '2',
//                        '3',
//                        '4',
//                        '5',
//                    ]
//                ]
//            );
    }
	
    
    
    function addSpFields($row,$under)
    {
       return;
    }

};

// Обратные звонки с лендинга
class admin_landing_callbacks
{
    public $TABLE           = "landing_callbacks";
    public $NAME            = "Заказы на обратный звонок с лендинга";
    public $NAME2           = "заказ";
    public $fld;
    public $SORT            = 'id desc';
    public $ECHO_ID         = 'id';
    public $ECHO_NAME       = 'phone';
    public $RUBS_NO_UNDER   = 1;
        
    function __construct()
    {
        $this->fld[] = new Field("phone", "Номер телефона пользователя", 1);
        $this->fld[] = new Field("processed", "Обработан", 6, ['showInList' => 1, 'editInList' => 1]);
        $this->fld[] = new Field("crtdate", "Дата создания", 4);
    }
    
    function addSpFields($row,$under)
    {
       return;
    }

};