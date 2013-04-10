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
		$to = $_GET['uname'];
		$post = $_GET['post'];
		$user_result = mysql_query("INSERT INTO wall_post(to_id, from_id,msg) VALUES('$to', '$uname', '$post')") or die(mysql_error());
		echo 1;
	}
}
?>
