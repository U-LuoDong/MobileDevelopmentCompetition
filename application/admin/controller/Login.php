<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Admin;
class Login extends Controller
{
	//登录页面开始
    public function index()
    {
    	if(request()->isPost()){//如果没有提交表单就跳转到登录页面 否则进行登录的验证
//			require '/StudentInformationManagementSystem/denfence.php';//引入安全保护类
			$Defence=new Denfence();//实例化当前的类【在本目录下的不用use引入 】
            //	安全防护开始
            $managerData=array();
			$managerData['name'] = $Defence->clean_xss(input('post.name')); //1.防注入代码   
			$managerData['password'] = $Defence->clean_xss(input('post.password'));
			//	安全防护结束
	   		$user = new Admin();
       		$result=$user->login($managerData);
       		if($result==1){
	   			$this->error("登陆账号不存在，请重新登录...");
       		}else if($result==3){
	   			$this->error("密码不正确，请重新登录...");
       		}else{
       			if(input('post.category')){//没有保持登录状态  那么每次需要重新登录
//					session('tel',$managerData['tel']);
        			$this->success('登录成功！',url('index/index'));
				}
       		}
            return;
        }
//  	return	$this->fetch();
		return  view();//用这个助手函数更加简洁  还不要用controller类
   }

    // 验证码检测
    public function check($code='')
    {
        if (!captcha_check($code)) {
            $this->error('验证码错误');
        } else {
            return true;
        }
    }

}
