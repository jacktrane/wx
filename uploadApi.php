<?php

if(empty($_FILES['filePath'])){
	$err = array('code' => '202', 'content' => '参数不足');
	echo json_encode($err);
	die();
}
$file = $_FILES['filePath'];
$fileSize = 3145728;
$path = 'uploads/';
//if(!file_exists($path)){
//	mkdir($path);
//}
$err = $file['error'];
if($err > 0){
	$err = array('code' => '203', 'content' => '文件上传失败');
	echo json_encode($err);
	die();
}
$pre = pathinfo($file['name'], PATHINFO_EXTENSION);
if($file['size']>$fileSize){
	$err = array('code' => '203', 'content' => '文件上传失败');
	echo json_encode($err);
	die();
}
$fileName = date('YmdHis',time()).mt_rand(1000,9999).'.'.$pre;
if(is_uploaded_file($file['tmp_name'])){
	$res = move_uploaded_file($file['tmp_name'], $path.$fileName);
	$result = array('file_path' => $path.$fileName);
	//$obj = new stdClass();
	//$obj->file_path = $path.$fileName;
	echo json_encode($result);
}else{
	$err = array('code' => '203', 'content' => '文件上传失败');
	echo json_encode($err);
	die();
}
