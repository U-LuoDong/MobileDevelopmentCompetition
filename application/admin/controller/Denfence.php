<?PHP
/**
 * @param $string
 * @param $low 安全别级
 */
namespace app\admin\controller;
class Denfence
{
	public function clean_xss($string, $low = false)
	{
		if (! is_array ( $string ))//is_array() 函数用于检测变量是否是一个数组。
		{
			$string = trim ( $string );//清理空格  
			$string = strip_tags ( $string );//过滤html标签  【XSS的】 防XSS
			$string = htmlspecialchars($string,ENT_QUOTES);//将字符内容转化为html实体【ENT_QUOTES既转化单引号又转换双引号】   防XSS
			//注意的是php.ini是否开启了magic_quotes_gpc=ON，开启若使用addslashes会出现重复。所以使用的时候要先get_magic_quotes_gpc()检查
			 if(!get_magic_quotes_gpc())  
			 {  
			    $string = addslashes($string);  //防止SQL注入  Addslashes给这些 "’"、"""、"\","NULL" 添加斜杆"\’"、"\""、"\\","\NULL"
			 }
//			if (PHP_VERSION >= '4.3')  //防止SQL注入  mysql_ real _escape_string()会判断当前数据库连接字符集  没有连接数据库出错  会转义"\n"，"\r"【变种空格】
//			{  
//			$string  =  mysql_real_escape_string($string);  
//			}else  {  
//			$string  =  mysql_escape_string($string );  
//			}
			if ($low)
			{
				//安全级别高
//				$string = _str_replace($string);//把str_replace封装成函数时，重复使用会报错
				 $string = str_replace(" ","",$string);  
			     $string = str_replace("\n","",$string);  
			     $string = str_replace("\r","",$string);  
			     $string = str_replace("'","",$string);  
			     $string = str_replace('"',"",$string);  
			     $string = str_replace("or","",$string);  
			     $string = str_replace("and","",$string);  
			     $string = str_replace("#","",$string);  
			     $string = str_replace("\\","",$string);  
			     $string = str_replace("– ","",$string);  
			     $string = str_replace("null","",$string);  
			     $string = str_replace("%","",$string);  
			     //$string = str_replace("_","",$string);  
			     $string = str_replace(">","",$string);  
			     $string = str_replace("<","",$string);  
			     $string = str_replace("=","",$string);  
			     $string = str_replace("char","",$string);  
			     $string = str_replace("declare","",$string);  
			     $string = str_replace("select","",$string);  
			     $string = str_replace("create","",$string);  
			     $string = str_replace("delete","",$string);  
			     $string = str_replace("insert","",$string);  
			     $string = str_replace("execute","",$string);  
			     $string = str_replace("update","",$string);  
			     $string = str_replace("count","",$string);  
				 return $string;
			}
//			function _str_replace($string)  //方法里面不能再用方法
//			{
//			    $string = str_replace(" ","",$string);  
//			     $string = str_replace("\n","",$string);  
//			     $string = str_replace("\r","",$string);  
//			     $string = str_replace("'","",$string);  
//			     $string = str_replace('"',"",$string);  
//			     $string = str_replace("or","",$string);  
//			     $string = str_replace("and","",$string);  
//			     $string = str_replace("#","",$string);  
//			     $string = str_replace("\\","",$string);  
//			     $string = str_replace("– ","",$string);  
//			     $string = str_replace("null","",$string);  
//			     $string = str_replace("%","",$string);  
//			     //$string = str_replace("_","",$string);  
//			     $string = str_replace(">","",$string);  
//			     $string = str_replace("<","",$string);  
//			     $string = str_replace("=","",$string);  
//			     $string = str_replace("char","",$string);  
//			     $string = str_replace("declare","",$string);  
//			     $string = str_replace("select","",$string);  
//			     $string = str_replace("create","",$string);  
//			     $string = str_replace("delete","",$string);  
//			     $string = str_replace("insert","",$string);  
//			     $string = str_replace("execute","",$string);  
//			     $string = str_replace("update","",$string);  
//			     $string = str_replace("count","",$string);  
//			     return $string;  
//			}
			return $string;
		}
		//下面相当于else【因为前面if结束时有return true】 
		
		//数组的处理法1：写法也貌似有错
		$keys = array_keys ( $string );//返回包含数组中所有键名的一个新数组：
		var_dump($keys);
		foreach ( $keys as $key )
		{
			clean_xss ( $string [$key] );
		}
		//数组的处理法2，貌似能处理多维数组，能循环处理
	//	foreach($string as $key =>$val)  
	//  {  
	//      $string[$key]=no_inject($val);  
	//  }
	}
}
?>