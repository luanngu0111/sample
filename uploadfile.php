<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<h3>Upload a file</h3> 

<p>You can add files to the system for review by an
    administrator. Click <b>Browse</b> to select the file you'd like to
    upload, and then click <b>Upload</b>.</p> 

<form action="uploadfile_action.php" method="POST" 
  enctype="multipart/form-data">
  <input type="file" name="ufile" \> 
  <input type="submit" value="Upload" \> 
</form> 
</body>
</html>