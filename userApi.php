<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
include_once('common/class.openapidb.php');
if(empty($_POST['nickName'])
||empty($_POST['gender'])
||empty($_POST['avatarUrl'])
||empty($_POST['city'])
||empty($_POST['province'])
||empty($_POST['openid'])){
	$err = array('code' => '202', 'content' => '缺少参数');
	echo json_encode($err);
	die();
}
$db = new WxDbConnection();
$sql = 'SELECT `id` FROM `wx_user` WHERE `openid`="'.$_POST['openid'].'"';
$res = $db -> query($sql);
$obj = new stdClass();
if(!$res||$res->num_rows == 0){
	$sql = 'INSERT INTO `wx_user`(`openid`,`user_name`, `user_img`, `city`, `province`, `gender`) VALUES("'.$_POST['openid'].'","'.$_POST['nickName'].'","'.$_POST['avatarUrl'].'","'.$_POST['city'].'","'.$_POST['province'].'",'.$_POST['gender'].') ';
	$rsInsert = $db -> query($sql);
	if(!$rsInsert){
		$db -> rollback();
		$db -> close();
		$err = array('code' => '203', 'content' => '数据库错误');
		echo json_encode($err);
		die();
	}
	$id = $db -> insert_id;
	$db -> commit();
	$db -> close();
	$obj -> id = $id;
	echo json_encode($obj);
}else{
	$arr = $res->fetch_array(MYSQLI_ASSOC);
	$obj->user_id = $arr['id'];
	$sql = 'SELECT `id`, `content`, `latitude`, `longitude` FROM `wx_user_publish` WHERE `user_id`='.$obj->user_id;
	$rsSelect = $db -> query($sql);
	if(!$rsSelect){
		$err = array('code' => '203', 'content' => '数据库错误');
		echo json_encode($err);
		die();
	}
	$pubList = array();
	while($listObj = $rsSelect->fetch_object()){
		if(empty($listObj->content)){
			$listObj->name = '暂不支持播放语音消息';
		}else{
			$listObj->name = $listObj->content;
		}
		unset($listObj->content);
		$pubList[] = $listObj;
		
	}
	$obj->list = $pubList;	
	echo json_encode($obj);
}
