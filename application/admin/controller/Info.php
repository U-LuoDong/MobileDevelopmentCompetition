<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use think\Db;
use app\admin\model\Student as StudentModel;
class Info extends Common
{
    public function lst(){
    	$flag = input('post.flag');
    	//判断来自那个页面
    	if(!$flag){
    		$studentres=Db::field('num,de.departmentName,grade,sp.specialtyName,cl.className,sid,sname,sex,level')
			->table(['think_student'=>'st','think_class'=>'cl','think_specialty'=>'sp','think_department'=>'de'])
			->where("st.classname=cl.className and cl.specialtyName=sp.specialtyName and sp.departmentName=de.departmentName")
	        ->paginate(6);
        	$this->assign('mark','1');//标识，判断是否显示分页
    	}else{
    		$id = input('post.spx_result');
    		$studentres=Db::field('num,de.departmentName,grade,sp.specialtyName,cl.className,sid,sname,sex,level')
			->table(['think_student'=>'st','think_class'=>'cl','think_specialty'=>'sp','think_department'=>'de'])
			//解析变量时使用.进行连接
			->where('st.classname=cl.className and cl.specialtyName=sp.specialtyName and sp.departmentName=de.departmentName
			and (st.id='.$id.')')
			->select();
			dump($studentres);die;
        	$this->assign('mark','0');//标识，判断是否显示分页
    	}
        $this->assign('studentres',$studentres);
        return view();
    }
    public function add(){
        if(request()->isPost()){//如果没有提交表单就跳转到添加学生页面 
			$Defence=new Denfence();//实例化当前的类【在本目录下的不用use引入 】
			$studentData=array();
			$studentData['Num'] = $Defence->clean_xss($_POST['Num']); //防注入代码   
			$studentData['Gr'] = $Defence->clean_xss($_POST['Gr']); 
			$studentData['Cl'] = $Defence->clean_xss($_POST['Cl']); 
			$studentData['Sid'] = $Defence->clean_xss($_POST['Sid']);
			$studentData['Name'] = $Defence->clean_xss($_POST['Name']);   
			$studentData['Sex'] = $Defence->clean_xss($_POST['Sex']);
			$studentData['Level'] = $Defence->clean_xss($_POST['Level']);
			$user=new StudentModel();
			if($user->addStudent($studentData)){
				$this->success('恭喜您添加学生信息成功!','lst');
		 	}else{
		   	$this->error("输入有误!请重新输入...","add");
		 	} 
	    	return ;
	    }
        return view();
    }

    public function edit(){
		$Defence=new Denfence();//实例化当前的类【在本目录下的不用use引入 】
    	//根据网页的来源显示来显示不同的内容【在权限系统中换成了用隐藏的表单搞定了】
		$url = $_SERVER['HTTP_REFERER'];//获取上个页面的url
		//如果不是查看页面来的 而且提交了表单
		//strcmp函数相同的返回0
        if(request()->isPost()&&strcmp($url,'http://10.118.35.182/tp5/public/admin/info/find.html')){
			//	安全防护开始
			$Num = $Defence->clean_xss($_POST['Num']); //防注入代码   
			$Gr = $Defence->clean_xss($_POST['Gr']); 
			$Cl = $Defence->clean_xss($_POST['Cl']); 
			$Sid = $Defence->clean_xss($_POST['Sid']);
			$Name = $Defence->clean_xss($_POST['Name']);   
			$Sex = $Defence->clean_xss($_POST['Sex']);
			$Level = $Defence->clean_xss($_POST['Level']); 
			//	安全防护结束
			
			$user = new StudentModel(); 
		  	if($user->save(['num'=>$Num,'grade'=>$Gr,'className'=>$Cl,'sname'=>$Name,'sex'=>$Sex,'level'=>$Level],['sid'=>$Sid])){
				$this->success('恭喜您修改学生信息成功!','lst');
			}else{
		   		$this->error("输入有误!请重新输入...","modify");
			}
	    	return ;
        }
        $Sid = $Defence->clean_xss(input('Sid')); //防注入代码
        //如果这个学生被软删除了 那么查找出来的值为null
        $studentres=StudentModel::where('sid',$Sid)->find();
        $this->assign('studentres',$studentres);
        return view();
    }

    public function del(){
		$Defence=new Denfence();//实例化当前的类【在本目录下的不用use引入 】
		//	安全防护开始
		$Sid = $Defence->clean_xss(input('Sid')); //防注入代码   
		//	安全防护结束
		if(StudentModel::destroy($Sid)){
			$this->success('删除学生信息成功!',url('lst')); 
		}else{
            $this->error('删除学生信息项失败!');
		}
    }
    
     //删除学生开始
   public function shanchu()
   {
    if(request()->isPost()){//如果没有提交表单就跳转到删除学生页面 
		$Defence=new Denfence();//实例化当前的类【在本目录下的不用use引入 】
		//	安全防护开始
		$Sid = $Defence->clean_xss($_POST['Sid']); //防注入代码   
		//	安全防护结束
		if(StudentModel::destroy($Sid)){
			$this->success('删除学生信息成功!',url('lst')); 
		}else{
	   		$this->error("该学生不存在!请重新输入...");
		}
		return ;
	}
    return	$this->fetch();
  }
   //删除学生结束
    
  //查看学生开始
   public function find()
   {
    if(request()->isPost()){//如果没有提交表单就跳转到删除学生页面 
		$Defence=new Denfence();//实例化当前的类【在本目录下的不用use引入 】
		//	安全防护开始
		$Sid = $Defence->clean_xss($_POST['Sid']); //防注入代码   
		//	安全防护结束
		$strSql=Db::field('num,de.departmentName,grade,sp.specialtyName,cl.className,sid,sname,sex,level')
		->table(['think_student'=>'st','think_class'=>'cl','think_specialty'=>'sp','think_department'=>'de'])
		->where("sid ='$Sid' and st.classname=cl.className and cl.specialtyName=sp.specialtyName and sp.departmentName=de.departmentName")
		->select();
		if(!$strSql){
	   		$this->error("查询错误,请重新查询!","search");
		}
		$arr=array();//接收结果的数组
		//将二维数组转化为一位数组保存传递到前端去
		foreach($strSql as $row) {
			$arr[]=$row['num'];
			$arr[]=$row['departmentName'];
			$arr[]=$row['grade'];
			$arr[]=$row['specialtyName'];	
			$arr[]=$row['className'];
			$arr[]=$row['sid'];
			$arr[]=$row['sname'];
			$arr[]=$row['sex'];
			$arr[]=$row['level'];
		}
		echo json_encode($arr,JSON_UNESCAPED_UNICODE);//中文不转为unicode
	    return ;
	 }
    return	$this->fetch();
   }
}
