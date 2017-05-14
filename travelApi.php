<?php
require 'spider/phpQuery.php';
require 'spider/QueryList.php';
use QL\QueryList;
if(empty($_GET['q'])){
	$err = array('code' => '202', 'content' => '缺少参数');
	echo json_encode($err);
}
switch($_GET['q']){
	case 'list':
		if(empty($_GET['city'])){
			$err = array('code' => '202', 'content' => '缺少参数');
			echo json_encode($err);		
		}
		$html = 'http://www.mafengwo.cn/search/s.php?t=article_gonglve&q='.$_GET['city'];
		$rules = array(
			"image"=>array('.att-list ul li .clearfix .flt1 a img','src'),
			"url" => array('.att-list ul li .clearfix .flt1 a', 'href'),
			"title" => array('.att-list ul li .clearfix .ct-text h3 a', 'text'),
			"content" => array('.att-list ul li .clearfix .ct-text .seg-desc','text')
		);		
		$data = QueryList::Query($html, $rules)->data;
		break;
	case 'content':
		if(empty($_GET['url'])){
			$err = array('code' => '202', 'content' => '缺少参数');
			echo json_encode($err);		
		}
		$html = $_GET['url'].'.html';
		$rules = array(
			"title" => array('.l-topic', 'html'),
			"content" => array('._j_content','html')
		);
		$dataBefore = QueryList::Query($html, $rules)->data;
		$data['title'] = htmlspecialchars($dataBefore[0]['title']);
		$contentBe = htmlspecialchars($dataBefore[0]['content']);
		$reg = '/src.*data.*R0lGODlhAQABAIAAAP.*wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw.{0,9}/';
		$content = preg_replace($reg,'',$contentBe);
		$data['content'] = preg_replace('/data\-src/', 'src', $content);
		//$data['content'] = $contentBe;
		
		//foreach($data[0] as $i=>$value){
		//	$data[$i] = htmlspecialchars($value);
		//	echo $data[$i];
		//}
		break;
}
//$data = $hj->getData(function($x){
//    return $x['url'];
//});
//print_r($data);
echo json_encode($data);
//echo $data;
