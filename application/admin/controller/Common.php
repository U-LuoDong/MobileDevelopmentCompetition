<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
class Common extends Controller
{
    public function _initialize(){
        if(!session('id') || !session('name')){
           $this->error('您尚未登录系统',url('login/index')); 
        }
        $auth=new Auth();
        $request=Request::instance();
        $con=$request->controller();//获取当前控制器名称
        $action=$request->action();//获取当前控制器方法
        $name=$con.'/'.$action;
        //可以访问首页、管理员列表、退出登录和修改密码
        $notCheck=array('Index/index','Admin/lst','Admin/logout','Admin/edit');//不论那个管理员都可以访问的页面
         if(session('id')!=1){//针对超级管理员 如果id为1就不需要验证
         	if(!in_array($name, $notCheck)){
         		if(!$auth->check($name,session('id'))){
		    	 $this->error('没有权限',url('index/index')); 
		    	 }
         	}
         }
        
    }
    /**
     * 利用sphinx全文索引，返回学生的学号
     */
    public function sphinx(){
    	$info = input('post.info');
    	
    }
	

}
