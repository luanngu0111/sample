<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
	$link = mysql_connect("localhost", "root", "root");
	mysql_select_db($database, $link);
	
	$user = $_POST['email'];
	$sql = "update information set kind=0 where username = '$user'";
	$result = mysql_query($sql, $link);
	if ($result)
	{
		$affectedRow = mysql_affected_rows();
		echo $user." đã đăng xuất";
	}
?>
</body>
</html>