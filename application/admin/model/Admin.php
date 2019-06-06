<?php
namespace app\admin\model;
use think\Model;
class Admin extends Model
{

    protected static function init()
    {
        //没有对上传头像的大小等进行相关的设置
        //当添加save的时候触发
        //$txx是控制器中save中的参数 下面的同理
        Admin::event('before_insert', function ($txx) {
//            dump($txx);die;
            if ($_FILES['tx']['tmp_name']) {//输出文件名，判断是否进行了上传
                $file = request()->file('tx');
                $info = $file->move(ROOT_PATH . 'public' . DS . 'HeadPortrait');//本机上打印出来的ROOT_PATH路径 D:\PHPWAMP_IN3_1\wwwroot\tp5_2
                if ($info) {
                    $tx = DS . 'tp5'. DS . 'public' . DS . 'HeadPortrait' . DS . $info->getSaveName();
                    $txx['HeadPortrait'] = $tx;//保存到数据库中的相应字段中
                }
            }
        });

        //当更新update的时候触发
        Admin::event('before_update', function ($txx) {
            if ($_FILES['tx']['tmp_name']) {//输出文件名，判断是否进行了上传【如果edit中没有上传缩略图 是不会进行下面的步骤的】
                //更新时判断原来有没有缩略图，有的话就先进行删除 --开始
                $admin = Admin::find($txx->id);
                //$_SERVER['DOCUMENT_ROOT']为根目录D:/PHPWAMP_IN3_1/wwwroot/tp5_2/public
                $txpath = $_SERVER['DOCUMENT_ROOT'] . $admin['HeadPortrait'];
//                echo $txpath;die;
                if ($txpath!=$_SERVER['DOCUMENT_ROOT']){//如果用户没有头像，那么$txpath=$_SERVER['DOCUMENT_ROOT']
                    if (file_exists($txpath)) {//file_exists用这个判断文件是否存在比直接用$txpath好 file_exists貌似不支持www.127.0[域名形式]这种形式的判断
                        unlink($txpath);//删除原先的缩略图   http不允许unliking【断开连接】 unlink貌似不能对127.0.0.1[域名形式]这种路径进行删除，和file_exists类似
                    }
                }
                //更新时判断原来有没有缩略图，有的话就先进行删除  --结束

                //下面和添加的代码一样
                $file = request()->file('tx');
                $info = $file->move(ROOT_PATH . 'public' . DS . 'HeadPortrait');//移动文件的位置，不能采用下面的那种
//              $info = $file->move('http:' . DS . '' . DS . '127.0.0.1' . DS . 'tp5_2' . DS . 'public' . DS . 'HeadPortrait');
                if ($info) {
                    $tx = DS . 'tp5'. DS . 'public' . DS . 'HeadPortrait' . DS . $info->getSaveName();
                    $txx['HeadPortrait'] = $tx;
                }

            }
        });

        //当删除delete的时候触发  删除对应服务器上的图片
        Admin::event('before_delete', function ($txx) {

            $admin = Admin::find($txx->id);
            $txpath = $_SERVER['DOCUMENT_ROOT'] . $admin['HeadPortrait'];
            if (file_exists($txpath)) {
                @unlink($txpath);
            }
        });

    }

   public function addadmin($data){
    if(empty($data) || !is_array($data)){
        return false;
    }
    if($data['password']){
        $data['password']=md5($data['password']);
    }
    $adminData=array();
    $adminData['name']=$data['name'];
    $adminData['password']=$data['password'];
    if($this->save($adminData)){
        $groupAccess['uid']=$this->id;
        $groupAccess['group_id']=$data['group_id'];
        db('auth_group_access')->insert($groupAccess);//添加管理员的时候还要添加group_access的id和groupid
        return true;
    }else{
        return false;
    }

   }

   public function getadmin(){
    return $this::paginate(5,false,[
        'type'=>'boot',
        'var_page' => 'page',
        ]);
   }

   public function saveadmin($data,$admins){
        if(!$data['name']){
            return 2;//管理员用户名为空
        }
        if(!$data['password']){
            $data['password']=$admins['password'];
        }else{
            $data['password']=md5($data['password']);
        }
        db('auth_group_access')->where(array('uid'=>$data['id']))->update(['group_id'=>$data['group_id']]);
       unset($data['group_id']);//删除group_id字段
       return $this::update($data);
    }

    public function deladmin($id){
        if($this::destroy($id)){
            return 1;
        }else{
            return 2;
        }
    }

    public function login($data){
        $admin=Admin::getByName($data['name']);
        if($admin){
            if($admin['password']==md5($data['password'])){
                session('id', $admin['id']);
                session('name', $admin['name']);
                return 2; //登录密码正确的情况
            }else{
                return 3; //登录密码错误
            }
        }else{
            return 1; //用户不存在的情况
        }
    }






}
