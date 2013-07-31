<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lay thong tin tu ID dang nhap</title>

<style type="text/css">
body,td,th {
	color: #000;
}
</style>
</head>
<?php
	
	$link = mysql_connect("localhost", "root", "root");
	mysql_select_db("epar", $link);
?>
<?php
	//require("main.php");
	
	if ($link == false)
		echo "Cannot connect to database";
	mysql_query("set names'utf8'", $link);
	$sql = "select * from t_cores_user where c_login_name = '".$login_id."'";
	$result = mysql_query($sql, $link);
	$totalRows = 0;
	$totalRows = mysql_num_rows($result);
	?>
<table width="647" height="31" border="1">
  <tr>
    <th bgcolor="#0099FF" width="143" scope="col">TÊN</th>
    <th bgcolor="#0099FF" width="173" scope="col">ID ĐĂNG NHẬP</th>
    <th bgcolor="#0099FF" width="53" scope="col">CHỨC VỤ</th>
</tr>
	<?php
	if ($totalRows > 0)
	{
		$i=0;
		
		while ($row = mysql_fetch_array($result))
		{
			$i++;
		?>
<tr>
  <th scope="col" align="left"><?=$row[2]?></th>
  <th scope="col"><?=$row[3]?></th>
  <th scope="col"><?=$row[10]?></th>
</tr>
	<?php
		}
	}else{
		?>
<tr valign="top">
        <td >&nbsp;</td>
        <td > <b><font face="Arial" color="#FF0000">
  Oop! ID not found!</font></b></td>
        </tr>
        <?php
	}
	?>

<?php
	mysql_close($link);

?>

<body>


  
</table>
</body>
</html>