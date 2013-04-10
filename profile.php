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
		class Profile
		{
		    public $id = "";
		    public $user_id = "";
		    public $name = "";
		    public $gender = "";
		    public $location = "";
		}

		class Post
		{
			public $from = "";
			public $msg = "";
		}
	
		$result = mysql_query("SELECT * FROM user where user_id like '$uname'") or die(mysql_error());
		$user = mysql_fetch_array($result);
		$profile = new Profile();
		$profile->id = $user['id'];
		$profile->user_id = $user['user_id'];
		$profile->name = $user['name'];
		$profile->gender = $user['gender'];
		$profile->location = $user['location'];
	
		array_push($json, $profile);

		$user_result = mysql_query("SELECT * from wall_post where to_id like '$uname' ") or die(mysql_error());
		while($row = mysql_fetch_array($user_result))
		{
			$post = new Post();
			$from_id = $row['from_id'];
			$from_user = mysql_fetch_array(	mysql_query("SELECT * FROM user where user_id like '$from_id'"));
			$post->from = $from_user['name'];
			$post->msg = $row['msg'];
			array_push($json, $post);
		}
	
	
	
		echo (json_encode($json));  
	}

}
?>
