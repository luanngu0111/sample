<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php 

  if (isset($_FILES['ufile']['name'])){
     echo "Uploading: ".$_FILES['ufile']['name']."<br />"; 
  } else { 
     echo "You need to select a file. Please try again."; 
  } 
  
?> 
