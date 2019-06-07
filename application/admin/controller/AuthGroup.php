<?php
namespace app\admin\controller;
use app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\controller\Common;
class AuthGroup extends Common
{
    public function lst(){
        $authGroupRes=AuthGroupModel::paginate(6);
        $admins=db('admin')->find(session('id'));
        $this->assign(array(
            'admin'=>$admins,
            'authGroupRes'=>$authGroupRes,
        ));
        return view();
    }

    public function add(){
        if(request()->isPost()){
            $data=input('post.');
       	//针对复选框的操作
           	//1、针对管理用户组的权限选择开始
            $_data=array();
            foreach ($data as $k => $v) {
                $_data[]=$k;//将页面提交的字段组合成一个一维数组
            }
//          if($data['rules'])//这种判断不行 会报错
            if(in_array('rules', $_data)){//如果rules字段存在就将其分割成字符串存入
                $data['rules']=implode(',', $data['rules']);
            }
            //针对管理用户组的权限选择结束
            
            //2、针对用户组启用状态status的选择 开始
            if(!in_array('status', $_data)){
                $data['status']=0;//如果status字段不存在则置0
            }
            //针对用户组启用状态status的选择 结束
            $add=db('auth_group')->insert($data);
            if($add){
                $this->success('添加用户组成功！',url('lst'));
            }else{
                $this->error('添加用户组失败！');
            }
            return;
        }
        $authRule=new \app\admin\model\AuthRule();
        $authRuleRes=$authRule->authRuleTree();
        $admins=db('admin')->find(session('id'));
        $this->assign(array(
            'admin'=>$admins,
            'authRuleRes'=>$authRuleRes,
        ));
        return view();
    }

    public function edit(){
        if(request()->isPost()){
            $data=input('post.');
            //针对复选框的操作
           	//1、针对管理用户组的权限选择开始
            $_data=array();
            foreach ($data as $k => $v) {
                $_data[]=$k;//将页面提交的字段组合成一个一维数组
            }
//          if($data['rules'])//这种判断不行 会报错
            if(in_array('rules', $_data)){//如果rules字段存在就将其分割成字符串存入
                $data['rules']=implode(',', $data['rules']);
            }
            //针对管理用户组的权限选择结束
            
            //2、针对用户组启用状态status的选择 开始
            if(!in_array('status', $_data)){
                $data['status']=0;//如果status字段不存在则置0
            }
            //针对用户组启用状态status的选择 结束
            $save=db('auth_group')->update($data);
            if($save!==false){
                $this->success('修改用户组成功！',url('lst'));
            }else{
                $this->error('修改用户组失败！');
            }
            return;
        }
        $authgroups=db('auth_group')->find(input('id'));
        $this->assign('authgroups',$authgroups);
        $authRule=new \app\admin\model\AuthRule();
        $authRuleRes=$authRule->authRuleTree();
        $admins=db('admin')->find(session('id'));
        $this->assign(array(
            'admin'=>$admins,
            'authRuleRes'=>$authRuleRes,
        ));
        return view();
    }

    public function del(){
        $del=db('auth_group')->delete(input('id'));
        if($del){
            $this->success('删除用户组成功！',url('lst'));
        }else{
            $this->error('删除用户组失败！');
        }
    }


    
    




   

	












}
