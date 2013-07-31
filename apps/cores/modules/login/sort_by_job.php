<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sort By Job</title>
</head>

<body>
<table width="785" border="1">
  <tr>
    <th width="295" scope="col">Tên</th>
    <th width="183" scope="col">Tên đăng nhập</th>
    <th width="143" scope="col">Trạng thái</th>
    <th width="136" scope="col">Chức vụ</th>
  </tr>
<?php
	$link = mysql_connect("localhost", "root", "root");
	mysql_select_db("epar", $link);
	if ($link == false)
		echo "Cannot connect to database";
?>
<?php
	mysql_query("set names 'utf8'", $link);
	$sql = "select c_name, c_login_name, c_status, c_job_title from t_cores_user order by c_job_title asc";
	$result = mysql_query($sql, $link);
	$totalRows = mysql_num_rows($result);
	if ($totalRows > 0)
	{
		$i++;
		while ($row = mysql_fetch_array($result))
		{
		?>

  		<tr>
	    <td><?=$row[0]?></td>
    	<td><?=$row[1]?></td>
	    <td><?=$row[2]?></td>
	    <td><?=$row[3]?></td>
	 	</tr>
     <?php
		}
	}else{
		?>
        <td><b>No reocord in Database</b></td>
     <?php
	}?>
<?php
	mysql_close($link);
?>
</table>
        
</body>
</html>