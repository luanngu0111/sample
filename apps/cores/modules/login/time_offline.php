<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Time offline</title>
</head>

<body>
<table width="776" border="1">
  <tr>
    <th width="255" scope="col">Tên</th>
    <th width="198" scope="col">Tên đăng nhập</th>
    <th width="143" scope="col">Chức vụ</th>
    <th width="152" scope="col">Thời gian offline (Ngày)</th>
  </tr>
<?php
	$link = mysql_connect("localhost", "root", "root");
	mysql_select_db("epar", $link);
	if ($link == false)
		echo "Cannot connect to database";
?>
<?php
	mysql_query("set names 'utf8'");
	$sql = "select c_name, c_login_name, c_job_title, datediff(curdate(), c_last_login_date) as time_off from t_cores_user";
	$result = mysql_query($sql, $link);
	$totalRows = mysql_num_rows($result);
?>
<?php
	if ($totalRows > 0)
	{
		$i=0;
		while ($row = mysql_fetch_array($result))
		{
			$i++;
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
        <td><b>Oops! No record in database</b></td>
    <?php
	}
	?>
<?php
	mysql_close($link);
?>
</table>
</body>
</html>