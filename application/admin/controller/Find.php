<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use think\Db;
class Find extends Common
{
    public function spe()
    {
        $admins=db('admin')->find(session('id'));
        $this->assign('admin',$admins);
        return view();
    }
    public function cla()
    {
        $admins=db('admin')->find(session('id'));
        $this->assign('admin',$admins);
        return view();
    }
    //生成系部对应的专业
   public function function_2()
    {
    	$Defence=new Denfence();//实例化当前的类【在本目录下的不用use引入 】
		//	安全防护开始
		$XB = $Defence->clean_xss($_POST['xb']); //防注入代码   
		//	安全防护结束
		$strSql="select specialtyName from think_specialty as sp ,think_department as de where de.departmentName='$XB' and sp.departmentName=de.departmentName;";//查询
		//原生sql查询
		$result = Db::query($strSql);
		if(!$result){
	   		$this->error("查询错误,请重新查询!","index/index");
		}
		$arr=array();//接收结果的数组
		//将二维数组转化为一位数组保存传递到前端去
		foreach($result as $row) {
			$arr[]=$row['specialtyName'];	
		}
		echo json_encode($arr,JSON_UNESCAPED_UNICODE);//中文不转为unicode
//		echo("1");
    }
    //生成专业对应的班级
   public function function_3()
    {
    	$Defence=new Denfence();//实例化当前的类【在本目录下的不用use引入 】
		//	安全防护开始
		$XB = $Defence->clean_xss($_POST['sc']); //防注入代码   
		//	安全防护结束
		$strSql="select className from think_class as cl,think_specialty as sp where sp.specialtyName ='$XB' and cl.specialtyName=sp.specialtyName";//查询
		//原生sql查询
		$result = Db::query($strSql);
		if(!$result){
	   		$this->error("查询错误,请重新查询!","index/index");
		}
		$arr=array();//接收结果的数组
		//将二维数组转化为一位数组保存传递到前端去
		foreach($result as $row) {
			$arr[]=$row['className'];	
		}
		echo json_encode($arr,JSON_UNESCAPED_UNICODE);//中文不转为unicode
    }
}
