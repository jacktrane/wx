<?php
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
include_once('common/class.openapidb.php');
if(empty($_POST['method'])){
	$err = array('code' => '202', 'content' => '参数错误1');
	echo json_encode($err);
	die();
}
switch($_POST['method']){
	case 'interation':
		if(empty($_POST['city'])){
			$err = array('code' => '202', 'content' => '参数错误2');
			echo json_encode($err);
			die();
		}
		$city = $_POST['city'];
		$db = new WxDbConnection();
		$pubSql = 'SELECT publish.*, user.user_name, user.user_img FROM wx_user_publish AS publish LEFT JOIN wx_user AS user ON user.id=publish.user_id WHERE publish.city="'.$city.'" ORDER BY publish.id DESC';
		$res = $db->query($pubSql);
		if(!$res){
			$db -> close();
			$err = array('code' => '202', 'content' => '参数错误3');
			echo json_encode($err);
			die();	
		}
		$retObj = array();
		while($rowObj = $res->fetch_object()){
			$comSql = 'SELECT comment.*,user.user_name FROM wx_publish_comment AS comment LEFT JOIN wx_user AS user ON user.id=comment.user_id WHERE pub_id='.$rowObj->id;
			$resCom = $db->query($comSql);
			if(!$resCom){
				$db -> close();
				$err = array('code' => '202', 'content' => '参数错误4');
				echo json_encode($err);
				die();	
			}		
			$rowObj->comlist = array();
			while($comObj = $resCom->fetch_object()){
				$rowObj->comlist[] = $comObj;
				
			}
			$retObj[] = $rowObj;
		}

		echo json_encode($retObj);
	break;
	case 'user':
		if(empty($_POST['id'])){
			$err = array('code' => '202', 'content' => '参数错误');
			echo json_encode($err);
			die();
		}
		$userId = $_POST['id'];
		$db = new WxDbConnection();
		$pubSql = 'SELECT * FROM wx_user_publish WHERE user_id='.$userId.' ORDER BY id DESC';
		$res = $db->query($pubSql);
		if(!$res){
			$db -> close();
			$err = array('code' => '202', 'content' => '参数错误');
			echo json_encode($err);
			die();	
		}
		$retObj = array();
		while($rowObj = $res->fetch_object()){
			$comSql = 'SELECT comment.*,user.user_name FROM wx_publish_comment AS comment LEFT JOIN wx_user AS user ON user.id=comment.user_id WHERE pub_id='.$rowObj->id;
			$resCom = $db->query($comSql);
			if(!$resCom){
				$db -> close();
				$err = array('code' => '202', 'content' => '参数错误');
				echo json_encode($err);
				die();	
			}		
			$rowObj->comlist = array();
			while($comObj = $resCom->fetch_object()){
				$rowObj->comlist[] = $comObj;
				
			}
			$retObj[] = $rowObj;
		}

		echo json_encode($retObj);
		break;
	case 'comment':
		if(empty($_POST['user_id'])
		||empty($_POST['pub_id'])
		||empty($_POST['content'])){
			$err = array('code' => '202', 'content' => '参数错误');
			echo json_encode($err);
			die();
		}
		$db = new WxDbConnection();
		$insSql = 'INSERT INTO wx_publish_comment(`pub_id`, `user_id`, `content`) VALUES('.$_POST['pub_id'].','.$_POST['user_id'].',"'.$_POST['content'].'")';
		$res = $db->query($insSql);
		if(!$res){
			$db -> close();
			$err = array('code' => '202', 'content' => '参数错误');
			echo json_encode($err);
			die();	
		}
		$result = array('code' => '200');
		echo json_encode($result);
		break;


}



