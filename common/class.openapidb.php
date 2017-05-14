<?php

class WxDbConnection extends mysqli {
	public function __construct(
		$host = '127.0.0.1',
		$username = 'root',
		$password = 'jacktrane',
		$dbname = 'wTravel'
	){
		parent::init();
		if(!parent::options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)){
			die('Setting options failed');
		}
		if(!parent::real_connect($host, $username, $password, $dbname)){
			die('Connect to db failed');
		}
		parent::query('set names utf8');
	}

}
