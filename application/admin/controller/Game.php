<?php
namespace app\admin\controller;
use think\Cache as Ca;
use app\admin\controller\Common;
class Game extends Common
{
	/**
	 * 贪吃蛇
	 */
    public function retroSnaker()
    {   
        return view();
	}
	/**
	 * 幸运儿
	 */
	public function luckyDog()
	{
		return view();
	}
	/**
	 * 判断是否存在过期redis
	 */
	public function toChenkTime()
	{
		if(Ca::store('redis')->has("time")){
			echo("1");
		}else{
			echo("0");
		}
	}
	/**
	 * 点击一次 string减一  取完以后对redis进行清空
	 */ 
	public function decrRedis()
	{
		//游戏结束之后再点击按钮 则直接返回
		if(!Ca::store('redis')->has("res")){
			echo("-1");die;
		}else if(!Ca::store('redis')->has("time")){
			echo("-2");die;
		}
		$res = Ca::store('redis')->dec("res");
		if($res=="0"){
			echo("1");
			Ca::store('redis')->clear();
		}else{
			echo("0");
		}
	}
	
	public function test()
	{
		Ca::store('redis')->set("time", "time",10);
		Ca::store('redis')->set("res", 1);
		$res = Ca::store('redis')->dec("res");
		if($res=="0"){
			echo("取到最后一个了</br>");
			Ca::store('redis')->clear();
		}else{
			echo("0</br>");
		}
		if(!Ca::store('redis')->has("time")){
			echo("没有这个");die;
		}
	}
	/**
	 * 设置2个redis
	 */
	public function setRedis()
	{
		//避免出现2个页面都点击了开始游戏
		if(!Ca::store('redis')->has("time")){
			Ca::store('redis')->set("time", "time",5);
			Ca::store('redis')->set("res", 20);
		}
	}
}
