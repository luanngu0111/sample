<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Trang thai nguoi dung</title>
</head>

<body><table width="200" border="1">
  <tr>
    <th scope="col">Tên đăng nhập</th>
    <th scope="col">Trạng thái</th>
  </tr>
<?php
	$link = mysql_connect("localhost", "root", "root");
	mysql_select_db("epar", $link);
?>
<?php
	if ($link == false)
		echo "Cannot connect to database";
	$sql = "select c_login_name,c_status from t_cores_user where c_status = 1";
	$result = mysql_query($sql, $link);
	$totalRows = mysql_num_rows($result);
?>
<?php
	if ($totalRows > 0)
	{
		$i = 0;
		while ($row = mysql_fetch_array($result))
		{
			$i++;
			
		?>
      	<tr>
        <td><?=$row[0]?></td>
        <td>Available</td>
      	</tr>
    <?php
		}
	}else{
	?>
    <tr valign="top">
        <td >&nbsp;</td>
        <td > <b><font face="Arial" color="#FF0000">
  Oop! No user available!</font></b></td>
    </tr>
    <?php
	}?>
</table>

</body>
</html>