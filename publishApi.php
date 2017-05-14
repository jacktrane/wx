<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
include_once('common/class.openapidb.php');
if(empty($_POST['method'])
||empty($_POST['id'])
||empty($_POST['longitude'])
||empty($_POST['latitude'])
||empty($_POST['city'])
){
	$err = array('code' => '202', 'content' => '参数错误');
	echo json_encode($err);
	die();
}
$userId = $_POST['id'];
$longitude = $_POST['longitude'];
$latitude = $_POST['latitude'];
$city = $_POST['city'];
$db = new WxDbConnection();
switch($_POST['method']){
	case 'character':
		if(empty($_POST['content'])){
			$err = array('code' => '202', 'content' => '参数错误');
			echo json_encode($err);
			die();
		}
		$content = $_POST['content'];
		//$imgArr = json_decode($_POST['img_url'], true);
		//$imgUrl = $imgArr['file_path'];
		$sql = 'INSERT INTO `wx_user_publish`(`content`, `latitude`, `longitude`, `city`, `user_id`) VALUES ("'.$content.'",'.$latitude.','.$longitude.',"'.$city.'",'.$userId.')';
		$res = $db -> query($sql);
		if(!$res){
			$db->rollback();
			$db->close();
			$err = array('code' => '203', 'content' => '数据库错误');
			echo json_encode($err);
			die();
		}
		$db->commit();
		$db->close();
		$result = array('code' => '200');
		echo json_encode($result);
		break;
	case 'voice':
		if(empty($_POST['sound_url'])){
			$err = array('code' => '202', 'content' => '参数错误');
			echo json_encode($err);
			die();
		}
		//$content = $_POST['content'];
		$urlArr = json_decode($_POST['sound_url'], true);
		$soundUrl = $urlArr['file_path'];
		$sql = 'INSERT INTO `wx_user_publish`(`sound_url`, `latitude`, `longitude`, `city`, `user_id`) VALUES ("'.$soundUrl.'",'.$latitude.','.$longitude.',"'.$city.'",'.$userId.')';
		$res = $db -> query($sql);
		if(!$res){
			$db->rollback();
			$db->close();
			$err = array('code' => '203', 'content' => '数据库错误');
			echo json_encode($err);
			die();
		}
		$db->commit();
		$db->close();
		$result = array('code' => '200');
		echo json_encode($result);
		break;
	case 'picture':
		if(empty($_POST['content'])
		||empty($_POST['img_url'])){
			$err = array('code' => '202', 'content' => '参数错误');
			echo json_encode($err);
			die();
		}
		$content = $_POST['content'];
		$imgArr = json_decode($_POST['img_url'], true);
		$imgUrl = $imgArr['file_path'];
		$sql = 'INSERT INTO `wx_user_publish`(`content`, `img_url`, `latitude`, `longitude`, `city`, `user_id`) VALUES ("'.$content.'","'.$imgUrl.'",'.$latitude.','.$longitude.',"'.$city.'",'.$userId.')';
		$res = $db -> query($sql);
		if(!$res){
			$db->rollback();
			$db->close();
			$err = array('code' => '203', 'content' => '数据库错误');
			echo json_encode($err);
			die();
		}
		$db->commit();
		$db->close();
		$result = array('code' => '200');
		echo json_encode($result);
		break;
	default:
		$err = array('code' => '202', 'content' => '参数错误');
		echo json_encode($err);
		die();
		break;

}
