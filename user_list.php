<?php
if (!isset($_SERVER['PHP_AUTH_USER'])) 
{
	header('WWW-Authenticate: Basic realm="Authetication Realm"');
	header('HTTP/1.0 401 Unauthorized');
	echo '0';
	exit;
} 
else 
{
	require 'connect.php';
	$uname = $_SERVER['PHP_AUTH_USER'];
	$pwd = md5($_SERVER['PHP_AUTH_PW']);
	$result = mysql_query("SELECT COUNT(*) as num_rows FROM user where user_id like '$uname' AND user_pwd like '$pwd'") or die(mysql_error());
	$row = mysql_fetch_array($result);	
	if($row['num_rows']==1)
	{
		$json = array();

		class User
		{
			public $id = "";
			public $name = "";
		}
	
		$user_result = mysql_query("SELECT * from user where user_id NOT like '$uname' ") or die(mysql_error());
		while($row = mysql_fetch_array($user_result))
		{
			$user = new User();
			$user->id = $row['user_id'];
			$user->name = $row['name'];
			array_push($json, $user);
		}
	
		echo (json_encode($json));  
	}
}
?>
