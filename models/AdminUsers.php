<?php

// Клиенты
class admin_users 
{
	public $NAME = "Клиенты";
    public $NAME2 = "клиента";
    public $fld;
	public $dynamicFields;
	public $SORT = 'id desc';
	public $ECHO_ID = 'id';
	public $ECHO_NAME = 'name';
    public $SEARCH_FIELD = 'name,email,phone';
	
	function __construct() {
		$this->fld = array(
			new Field("name","Имя",1),
			new Field("orders", "Заказы", 5,1),
			new Field("email","Email",1,1),
			new Field("login","Логин",1),
			new Field("country_id","Страна",9,1,0,'cities',-1,'name_1',array('LEVELS'=>1)),
			new Field("city","Город",1),
			new Field("addr","Адрес",1),
			new Field("phone","Телефон",1,1),
			new Field("red_date","Дата регистрации",13),
			new Field("birth","Дата рождения",13),
			new Field("sex","Пол (1 - мужской, 0 - женский)",1),
			new Field("crtdate","Date of creation",4)
		);
	}
	
	function show_orders($row) {
		$res = "";
		
		if (!empty($row['id'])) {
			$q = "SELECT id FROM feedback WHERE user_id = $row[id]";
			$res1 = pdoFetchAll($q);
			if(!empty($res1)) {
				foreach ($res1 as $row1) {
					$res .= '<a href="catalog.php?tabler=feedback&tablei=feedback_files&id=' . $row1['id'] . '">' . $row1['id'] . '</a><br />';	
				}
			}
		}
        
		return $res;
	}
	
    function addSpFields($row,$under)
    {
    	if (!empty($row['id']) && !isset($del)) {
	    	$q = "	SELECT 	a.name_1 AS agency_name,
	    					ua.favourite AS favourite,
	    					ua.orders AS orders 
	    			FROM users_agencies AS ua
	    			INNER JOIN agencies AS a ON ua.agency_id = a.id
	    			WHERE ua.user_id = '" . $row['id'] . "' 
	    			ORDER BY a.name_1 ASC 
	    		 ";
	        $res = pdoFetchAll($q);
	        
			if(!empty($res)) {
				echo   '<strong style="font-size:16px; font-weight: bold;">Связь с агентствами</strong>
						<br/>
						<table style="width:500px;">
							<tr>
								<th style="width:300px;">Агентство</th>
								<th style="width:100px;">Избранное</th>
								<th style="width:100px;">Кол-во заказов</th>
							</tr>
				';
		        foreach($res AS $rowa) {
		            echo '	<tr>
		            			<td>
		            				' . $rowa['agency_name'] . '
		            			</td>
		            			<td>
		            				' . $rowa['favourite'] . '
		            			</td>
		            			<td>
		            				' . $rowa['orders'] . '
		            			</td>
		            		</tr>';
		        }
				echo   '</table>
						<br/><br/>
				';
			}
			
			$q = "	SELECT 	c.name_1 AS clinic_name,
	    					uc.favourite AS favourite,
	    					uc.orders AS orders 
	    			FROM users_clinics AS uc
	    			INNER JOIN clinics AS c ON uc.clinic_id = c.id
	    			WHERE uc.user_id = '" . $row['id'] . "' 
	    			ORDER BY c.name_1 ASC 
	    		 ";
	        $res = pdoFetchAll($q);
	        
			if(!empty($res)) {
				echo   '<strong style="font-size:16px; font-weight: bold;">Связь с клиниками</strong>
						<br/>
						<table style="width:500px;">
							<tr>
								<th style="width:300px;">Клиника</th>
								<th style="width:100px;">Избранное</th>
								<th style="width:100px;">Кол-во заказов</th>
							</tr>
				';
		        foreach($res AS $rowc) {
		            echo '	<tr>
		            			<td>
		            				' . $rowc['clinic_name'] . '
		            			</td>
		            			<td>
		            				' . $rowc['favourite'] . '
		            			</td>
		            			<td>
		            				' . $rowc['orders'] . '
		            			</td>
		            		</tr>';
		        }
				echo   '</table>
						<br/>
				';
			}
		}
		
    	return 1;
    }
};

// Файлы, привязанные к клиентам 
class admin_users_files
{
	public $NAME = "Файлы";
    public $NAME2 = "файл";
    public $fld;
	public $SORT = 'id desc';
	public $ECHO_ID = 'id';
	public $ECHO_NAME = 'filename';
	
	public $IMG_NUM = 0;
	public $IMG_FIELD = 'id';
    public $FOLDER_IMAGES = 1;
    
    public $FIELD_UNDER = 'parent_id'; 
        
	function __construct() {
		$this->fld = array(
			new Field("filename","Название файла",1),
			new Field("format","Файл",4),
			new Field("parent_id","ID клиента",3),
			new Field("crtdate","Date of creation",4)
		);
		
	}
	
    function addSpFields($row,$under) {	
	    return 1;
    }
	
};
