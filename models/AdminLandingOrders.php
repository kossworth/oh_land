<?php

// Заказы с лендинга
class admin_landing_orders
{
    public $TABLE = "landing_orders";
    public $NAME = "Заказы с лендинга";
    public $NAME2 = "заказ";
    public $fld;
    public $SORT='id desc';
    public $ECHO_ID='id';
    public $ECHO_NAME='name';
    public $RUBS_NO_UNDER = 1;
	
//    public $FIELD_UNDER = 'company_id'; 
        
    function __construct()
    {
        $this->fld[] = new Field("domain", "Домен, на котором оформлялся заказ", 1);
        $this->fld[] = new Field("type", "Тип заказа", 1);
        $this->fld[] = new Field("phone", "Телефон клиента", 1);
        $this->fld[] = new Field("name", "Имя клиента", 1);
        $this->fld[] = new Field("email", "E-mail клиента", 1);
        $this->fld[] = new Field("search", "Данные из формы поиска", 16);
        $this->fld[] = new Field("offer", "Выбранный тариф", 16);
//        $this->fld[] = new Field("auth", "Данные из формы авторизации", 16);
        $this->fld[] = new Field("info", "Данные из формы информации о страхователе и ТС", 16);
        $this->fld[] = new Field("delivery", "Данные из формы доставки", 16);
//        $this->fld[] = new Field("contract", "Данные для формирования договора в EWA", 16);
        $this->fld[] = new Field("result", "Результат создания договора (ответ от EWA)", 16);
        $this->fld[] = new Field("payment", "Тип оплаты", 1);
        $this->fld[] = new Field("comment", "Комментарий", 16);
        $this->fld[] = new Field("done", "Оформление заказа завершено", 6);
        $this->fld[] = new Field("files", "Названия файлов", 16);
//        $this->fld[] = new Field("last_stage", "Последний активный шаг", 1);
        $this->fld[] = new Field("last_active", "Время последней активности по заказу", 13);
    }
	
    function addSpFields($row,$under)
    {
       return;
    }

};
