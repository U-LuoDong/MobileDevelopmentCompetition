<?php
namespace app\admin\controller;
use app\admin\model\Admin as AdminModel;
use app\admin\controller\Common;
class Admin extends Common
{

    public function lst()
    {   
    	$auth=new Auth();//实例化当前的类【在本目录下的不用use引入 】
        $admin=new AdminModel();
        $adminres=$admin->getadmin();
        foreach ($adminres as $k => $v) {
            $_groupTitle=$auth->getGroups($v['id']);//getGroups返回的是一个二维数组
            $groupTitle=$_groupTitle[0]['title'];
            $v['groupTitle']=$groupTitle;
        }
        $admins=db('admin')->find(session('id'));
        $this->assign(array(
            'admin'=>$admins,
            'adminres'=>$adminres,
        ));
        return view();
	}

	public function add()
    {
        if(request()->isPost()){
            $data=input('post.');
            $validate = \think\Loader::validate('Admin');
            if(!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            $admin=new AdminModel();
            if($admin->addadmin($data)){
                $this->success('添加管理员成功！',url('lst'));
            }else{
                $this->error('添加管理员失败！');
            }
            return;
        }
        $authGroupRes=db('auth_group')->select();
        $admins=db('admin')->find(session('id'));
        $this->assign(array(
            'admin'=>$admins,
            'authGroupRes'=>$authGroupRes,
        ));
        return view();
	}

	public function edit($id)
    {
        $admins1=db('admin')->find($id);//最好用input('get.id')来获取
        if(request()->isPost()){
            $data=input('post.');
            $validate = \think\Loader::validate('Admin');
            if(!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            $admin=new AdminModel();
            $savenum=$admin->saveadmin($data,$admins1);
            if($savenum == '2'){
                $this->error('管理员用户名不得为空！');
            }
            if($savenum !== false){
                $this->success('修改成功！',url('lst'));
            }else{
                $this->error('修改失败！');
            }
            return;
        }
        
        if(!$admins1){
            $this->error('该管理员不存在');
        }
        $authGroupAccess=db('auth_group_access')->where(array('uid'=>$id))->find();
        $authGroupRes=db('auth_group')->select();
        $this->assign('authGroupRes',$authGroupRes);
        $admins=db('admin')->find(session('id'));//注意区分，一个是当前登录的用户，一个是要进行修改的用户
        $this->assign(array(
            'admin'=>$admins,
            'admin1'=>$admins1,
        ));
        $this->assign('groupId',$authGroupAccess['group_id']);
        return view();
	}

    public function del($id){
        $admin=new AdminModel();
        $delnum=$admin->deladmin($id);
        if($delnum == '1'){
            $this->success('删除管理员成功！',url('lst'));
        }else{
            $this->error('删除管理员失败！');
        }
    }

    public function logout(){
        session(null); 
        $this->success('退出系统成功！',url('admin/login/index'));
    }













}
