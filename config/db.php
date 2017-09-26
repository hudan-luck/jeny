<?php
return 	[
	'class' => '\sf\db\Connection',
	'dsn' => 'mysql:dbname=test;host=127.0.0.1',
	'username' => 'root',
	'password' => '',	
	'option' => [
		PDO::ATTR_STRINGIFY_FETCHES => false, //提取数据时，使用字符串(true),使用原数据类型(false)
		PDO::ATTR_EMULATE_PREPARES => false, //强制pdo模拟预处理语句(true)，还是本地预处理(false)
		]
	];
?>
