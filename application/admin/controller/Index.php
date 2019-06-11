<?php
namespace app\admin\controller;
use app\admin\controller\Common;
class Index extends Common
{
    public function index()
    {
        $admins=db('admin')->find(session('id'));
        $this->assign('admin',$admins);
        return view();
    }
}
