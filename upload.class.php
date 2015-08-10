<?php
class upload{
	private $file_size;
	private $file_name;	//为获取后缀
	private $file_type;	//为了检验文件格式
	private $file_error;
	private $file_tmp_name;
	private $file_address;
	public function __construct($name,$size,$type,$error,$tmp,$address){
		//取后缀
	   $end=strrpos($name,'.');
	 	   $name=substr(strtolower($name),$end+1);//已经为后缀名;
	   $name=time().'.'.$name;//格式已经如 ：  21412412312.jpg
	   $this->file_address=$address;
	   $this->file_name=$name; 
	   $this->file_size=$size;
	   $this->file_type=$type;
	   $this->file_error=$error;
	   $this->file_tmp=$tmp;
	}
	public function upload_file(){
		$addr=$this->file_address;
		$name=$this->file_name;
		$size=$this->file_size;
		$type=$this->file_type;
		$error=$this->file_error;
		$tmp=$this->file_tmp;

		if ((($type == "image/gif")
		|| ($type== "image/jpeg")
		|| ($type == "image/pjpeg")
		||($type =="image/png") 
		|| ($type =="image/bmp") 
		|| ($type=="image/x-icon"))
		&& ($size < 2000000)){ //大小限制为2000k
  			if ($error > 0){
				return false;// echo "文件出错: " .$error . "<br />";
		    }else{
	      		if(move_uploaded_file($tmp,$addr . $name)) //将文件以 $name 为文件名 保存在该目录下， 
	      		{
					return $name;//echo "转移成功";
				}else{
					return false;//echo "转移失败";
			  	}
	    	}
  		}
		else{
  			return false;//echo "文件不合法";
  		}
	}
}
	//;固定格式，最后一个为保存地址；
	//$a=new upload($_FILES["file"]["name"],$_FILES["file"]["size"],$_FILES["file"]["type"],$_FILES["file"]["error"],$_FILES["file"]["tmp_name"],"images/");
	//echo $url=$a->upload_file();  //调用方法，如果不成功，返回false 否则返回url，例如：F:/123/1398915610.jpg;

?>