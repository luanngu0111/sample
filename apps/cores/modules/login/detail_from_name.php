<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lay thong tin tu Ten</title>
</head>

<body>
<?php
	$link = mysql_connect("localhost", "root", "root");
	mysql_select_db("epar", $link);
	if ($link == false)
		echo "Cannot connect to database";
?>
<?php
	mysql_query("set names 'utf8'");
	$sql = "select u.c_name, o.c_name, u.c_job_title  from epar.t_cores_user u, epar.t_cores_ou o where u.fk_ou = o.pk_ou and u.c_name = N'".$login_name."'";
	$result = mysql_query($sql, $link);
	$totalRows = mysql_num_rows($result);
?>	

<table width="657" border="1">
  <tr>
    <th width="253" scope="col"><strong>TÊN</strong></th>
    <th width="255" scope="col">CƠ QUAN HÀNH CHÍNH</th>
    <th width="127" scope="col">CHỨC DANH</th>
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
      	<?php
			for ($j=0; $j<sizeof($row); $j++)
			{
		?>
	    	<td><?=$row[$j]?></td>
    	    <?php
			}?>
      </tr>
     <?php
		}
	}else{
		?>
		<tr valign="top">
        <td >&nbsp;</td>
        <td > <b><font face="Arial" color="#FF0000">
  Oop! Name not found!</font></b></td>
        </tr>
    <?php 
	}?>
<?php
	mysql_close($link);
?>
</table>
</body>
</html>