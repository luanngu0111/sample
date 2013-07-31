<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
	$link = mysql_connect("localhost", "root", "root");
	mysql_select_db("epar", $link);
	if ($link == false)
		echo "cannot connect to database";
	$sql = "select c_is_admin from t_cores_user where c_login_name = '".$login_id."'";
	$result = mysql_query($sql, $link);
	$totalRows = mysql_num_rows($result);
	if ($totalRows > 0)
	{
		$row = mysql_fetch_array($result);
		if ($row[0] == 1)
		{
?>
		<td> Hi Admin </td>
	    <?php
		}else{
		?>
    	<td>Hi <?=$login_id?> </td>
	    <?php
		}
		mysql_close($link);
	}else{
	?>
    <td><b> Oops! login ID not found </b></td>
    <?php
	mysql_close($link);
	}
	?>
    
</body>
</html>