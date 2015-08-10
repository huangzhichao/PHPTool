<?php
class userCheck{
	private $host;            //服务器
    private $username;        //数据库用户名
    private $password;        //数据密码
    private $dbname;          //数据库名
    private $conn;


    public function __construct($host=DB_HOST,$username=DB_USER,$password=DB_PASSWORD,$dbname=DB_NAME)
    {

    	$this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        if(class_exists('DB')){
		    $db=new DB($this->host,$this->username,$this->password,$this->dbname);
		}else{
			require_once ("DB.class.php");
			$db=new DB($this->host,$this->username,$this->password,$this->dbname);
		}
    }

    public function check($username,$password,$view_state,$sql){
    	if(strcasecmp($_SESSION['view_state'], $view_state)){
			return "view_state error";
		}
		else if($_SESSION['auto_num']!=$_POST['yzm']){
			return "yzcode error";
		}
		else{
		    $row=$db->getObjListBySql($sql);
			if(!$row){
				return "password error";
			}else{
				return $row;
			}
		}
    }
}

?>