<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<?php
	$link = mysql_connect("localhost", "root", "root");
	mysql_select_db("taikhoan", $link) or die 
	("Cannot connect to database");
	
	//Liet ke nhung ai dang online
	$sql = "select username from information where kind = 1";
	$result = mysql_query($sql, $link);
	$totalRows = mysql_num_rows($result);

	if ($totalRows > 0)
	{
		$i=0;
		while ($row = mysql_fetch_array($result))
		{
			$i++;
?>
			<td><?=$row["username"]?></td><br />
	<?php
		}
	}else{
	?>
    <td>Khong co ai online</td>
    <?php
	}?>
<?php
	mysql_close($link);
?>

<body>
<form id="form1" name="form1" method="post" action="logout.php">
  <input type="submit" name="id_logout" id="id_logout" value="Đăng Xuất" />
</form>
</body>
</html>