<?php
/*
 * Очедерь для рассылки
 * */
 
class admin_unisender_log
{
	public $fld;
	public $SORT='id desc';
	public $ECHO_ID='id';
    public $ECHO_NAME='type';
    public $name = 'Лог отправки через Unisender';
    public $SEARCH_FIELD = 'unisender_log.send,unisender_log.answer';
	function __construct()
	{
		$this->fld=array(
		new Field("type","Тип",1),
		new Field("send","Данные отправки",7,1),
        new Field("answer","Ответ Unisender",7,1),
        new Field("created","Дата",13,1),
		);
	}
	
	function showed_send($row) {
		echo '<p class="txt"><strong>Данные отправки</strong></p>';
		echo '<pre>'.stripslashes(str_replace('\n', '<br/>', strip_tags($row['send']))).'</pre>';
	}
	function showed_answer($row) {
		echo '<p class="txt"><strong>Ответ Unisender</strong></p>';
		echo '<pre>'.$row['answer'].'</pre>';
	}
	function addSpFields($row,$under)
	{
		return 1;
	}
    
};

/*
 * Очедерь для рассылки SMS
 * */
 
class admin_sms_list
{
	public $fld;
	public $SORT='id desc';
	public $ECHO_ID='id';
    public $ECHO_NAME='to';
    public $name = 'Очередь SMS рассылки';
    
	function __construct()
	{
		$this->fld=array(
		new Field("from","От",1),
		new Field("to","Кому",1),
		new Field("sms_body","Текст",2, 1),
        new Field("type","Тип",0, 1),
		new Field("crtdate","Дата",4, 1)
		);
	}
	
	function show_crtdate($row)
	{
		return date('Y-m-d',$row['crtdate']);
	}
	
	function addSpFields($row,$under)
	{
		return 1;
	}
    
};
?>
