<?php
namespace app\admin\model;
use think\Model;
use traits\model\SoftDelete;
class Student extends Model//这里的模型类名要和数据库中的表名对应
{
//软删除
	use SoftDelete;
	protected static $deleteTime='delete_time';//设置软删除的字段
	//增加管理员
	public function addStudent($data){
	    if(empty($data) || !is_array($data)){
	        return false;
	    }
	    $this->num=$data['Num'];
		$this->grade=$data['Gr'];
		$this->className=$data['Cl'];
		$this->sid=$data['Sid'];
		$this->sname=$data['Name'];
		$this->sex=$data['Sex'];
		$this->level=$data['Level'];
	    if($this->save()){
	        return true;
	    }else{
	        return false;
	    }
   }
   
	
}
?>