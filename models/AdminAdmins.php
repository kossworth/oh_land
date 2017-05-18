<?php
class admin_admins_menu
{
	public $fld;
	public $SORT='sort desc';
	public $ECHO_ID='id';
	public $NAME='Меню админов';
	public $NAME2='пункт меню';
	function __construct()
	{
		$this->fld=array(
		new Field("name_1","Название раздела",1),
		new Field("icon","Путь к иконке",1),
		new Field("url","Адрес страницы: about",1,1),
		new Field("target","Target",1,1),
		new Field("under","Находитcя в разделе",9,0,0,'admins_menu',-1),
		new Field("sort","SORT",4),
		new Field("crtdate","Date of creation",4)
		);
	}

	function addSpFields($row,$under)
	{
	return 1;
	}
};

class admin_admins_groups
{
    public $fld;
    public $SORT='name_1 asc';
    public $NAME="Группы пользователей";
	public $NAME2="группу";
   
    function __construct()
    {
        $this->fld=array(new Field("name_1","Название группы",1),
        //new Field("menu","HTML-код меню",2),
		new Field("deny_tables","Закрытые таблицы (через запятую)",1),
		new Field("under","Находится в группе",9,0,0,'admins_groups',-1),
        new Field("crtdate","Date of creation",4));
    }

    function addSpFields($row,$under)
    {
			if ($row['id']>0 && !isset($del)) {
			echo "<script language=\"JavaScript\" type=\"text/javascript\">
			$(document).ready(function() {
	/* This is basic - uses default settings */
	/* Using custom settings */
	$(\"a#inline\").fancybox({
		'hideOnContentClick': true
	});
	
	$(\"a.frame\").fancybox({
		   zoomSpeedIn: 0,
		   zoomSpeedOut:0,
		   frameWidth: 800,
		   frameHeight: 650
		 });
		 
});

		function ListRubs(under,addrub,delrub,rpage)
		{
		$.ajax({
		  type: \"GET\",
		  url: 'ajax/assoc.php',
		  data: 'under='+under+'&addrub='+addrub+'&delrub='+delrub+'&rpage='+rpage+'&jfname=ListRubs&id=".$row['id']."&tabler=admins_menu&tablei=&tablea=admins_menu_assoc&col_rec=group_id&col_under=menu_id&under=-1xr='+Math.random(),
		  success: function(answer) {
		  $('#rubs').html(answer);
		  }
		 });
		}
		
		</script>
		"
		
		;
		
echo '<br /><br />
<strong style="background-color: rgb(249, 249, 249); font-size:16px; font-weight: bold;">
<img src="img/folder.gif" border="0">Настройка меню</strong><br/><div id="rubs">
		<script language="javascript">ListRubs(0);</script>
	</div>
	';//onclick="popUp(\'topics\')"
	}
	
    return 1;
    }
};

class admin_admins
{
    public $fld;
    public $SORT='name_1 asc';
    public $name="Администраторы сайта";
	public $NAME2="пользователя";
   
    function __construct()
    {
        $this->fld=array(new Field("name_1","Логин",1),
        new Field("passwd","Хэш-код пароля",1),
        new Field("passwd_rec","Код восстановления пароля",3),
        new Field("email","E-mail",1),
		//new Field("deny_scripts","Закрытые скрипты (через запятую)",1),
		//new Field("deny_tables","Закрытые таблицы (через запятую)",1),
		new Field("under","Находится в группе",9,0,0,'admins_groups',-1),
        new Field("crtdate","Date of creation",4));
    }

    function addSpFields($row,$under)
    {
     global $TABLE_ADMINS,$HTPASS_ADDRR;
		echo "<script language=\"JavaScript\" type=\"text/javascript\">
		function gethash(inp)
		{
		if (inp!='')
		{
		word=document.getElementById(inp).value;
		}
		else word=0;

		$.ajax({
		  type: \"POST\",
		  url: 'ajax/get_hash_admin.php',
		  data: 'pass='+word,
		  success: function(answer) {
		  $('#passwd').val(answer);
		  }
		 });
		}</script>";
		 echo '<br/><br/><div id="md5" style="border: 1px #CCC;">
		 <strong>Введите новый пароль: </strong> <input name="cpass" type="text" id="cpass" value=""> <a href="javascript:gethash(\'cpass\')"><u>вставить Хэш в поле пароля</u></a>
		 </div>';
	return 1;
    }
};

class admin_admins_menu_assoc
{
    public $fld;
    public $SORT='group_id asc';
    public $name="меню";
	public $NAME2="меню";
   
    function __construct()
    {
        $this->fld=array(
        new Field("menu_id","id menu",1),
        new Field("group_id","id group",2),
		);
    }

    function addSpFields($row,$under)
    {
     
		return 1;
    }
};

?>
