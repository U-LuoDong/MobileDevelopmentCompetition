<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use think\Db;
class Num extends Common
{
    public function index()
    {
        $admins=db('admin')->find(session('id'));
        $this->assign('admin',$admins);
        return view();
    }
     //查找学生人数
   public function function_1()
    {
    	$Defence=new Denfence();//实例化当前的类【在本目录下的不用use引入 】
		//	安全防护开始
		$ZY = $Defence->clean_xss($_POST['zy']); //防注入代码   
		$BJ = $Defence->clean_xss($_POST['bj']); //防注入代码   
		//	安全防护结束
		if($BJ!='该项表示不对班级进行查询'&&$ZY!='该项表示不对专业进行查询'){
			$strSql="select count(sid) as num from think_student as st, think_class as cl where cl.specialtyName='$ZY' and cl.className='$BJ' 
			and st.classname=cl.className;";//查询
		}else if($BJ=='该项表示不对班级进行查询'&&$ZY!='该项表示不对专业进行查询'){
			$strSql="select count(sid) as num from think_student as st, think_class as cl where cl.specialtyName='$ZY'
			and st.classname=cl.className;";//查询
		//	$strSql="select count(sid) as num from student where specialty='$ZY'";//查询
		}else if($BJ=='该项表示不对班级进行查询'&&$ZY=='该项表示不对专业进行查询'){
			$strSql="select count(sid) as num from think_student";//查询
		}else{
			die("查询错误");
		}
		//原生sql查询
		$result = Db::query($strSql);
		if(!$result){
	   		$this->error("查询错误,请重新查询!","index/index");
		}
		$arr=array();//接收结果的数组
		//将二维数组转化为一位数组保存传递到前端去
		foreach($result as $row) {
			$arr[]=$row['num'];	
		}
		echo json_encode($arr,JSON_UNESCAPED_UNICODE);//中文不转为unicode
    }
    //根据专业返回班级
   public function function_1_Class()
    {
    	$Defence=new Denfence();//实例化当前的类【在本目录下的不用use引入 】
		//	安全防护开始
		$data = $Defence->clean_xss($_POST['data']); //防注入代码   
		//	安全防护结束
		$strSql="select className from 
		think_class as cl,think_specialty as sp
		where sp.specialtyName='$data' and cl.specialtyName=sp.specialtyName;";//查询
		//原生sql查询
		$result = Db::query($strSql);
		if(!$result){
	   		$this->error("查询错误,请重新查询!","search");
		}
		$arr=array();//接收结果的数组
		//将二维数组转化为一位数组保存传递到前端去
		foreach($result as $row) {
			$arr[]=$row['className'];	
		}
		echo json_encode($arr,JSON_UNESCAPED_UNICODE);//中文不转为unicode
    }
    
    
}
