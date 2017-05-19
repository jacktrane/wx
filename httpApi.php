<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
header("Content-type: text/html; charset=utf-8"); 
if(empty($_GET['method'])){
	$err = array('code'=>'202', '参数错误');
	echo json_encode($err);
	die();
}

switch($_GET['method']){
	case 'map':
		if(empty($_GET['lat'])
		||empty($_GET['lng'])){
			$err = array('code'=>'202', '参数错误');
			echo json_encode($err);
			die();
		}
		$url = 	'http://apis.map.qq.com/ws/geocoder/v1/?location='.$_GET['lat'].','.$_GET['lng'].'&key=CLDBZ-MMDKG-7GGQ2-IRHQQ-YP7X3-3OB6X&get_poi=1';
		//初始化
		$ch = curl_init();
		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		//执行并获取HTML文档内容
		$output = curl_exec($ch);
		//释放curl句柄
		curl_close($ch);
		//打印获得的数据
		$outputArr = json_decode($output,true);
		
		$city = substr($outputArr['result']['address_component']['city'],0,-3);
		echo json_encode(array('city'=>$city));
		//print_r($outputArr);
		break;

		



}
