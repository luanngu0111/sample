<!DOCTYPE html 
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
  <head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Adding calendar events</title>
    <style>
    body {
      font-family: Verdana;      
    }
    li {
      border-bottom: solid black 1px;      
      margin: 10px; 
      padding: 2px; 
      width: auto;
      padding-bottom: 20px;
    }
    h2 {
      color: red; 
      text-decoration: none;  
    }
    span.attr {
      font-weight: bolder;  
    }
    </style>    
  </head>
  <body>
    <h1>Thêm Sự kiện</h1>
    <?php if (!isset($_POST['submit'])) { ?>
    <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF'])."?user=$user&pass=$pass"; ?>">
      Tên sự kiện: <br/>
      <input name="title" type="text" size="15" /><p/>
      Ngày bắt đầu (dd/mm/yyyy): <br/>
      <input name="sdate_dd" type="text" size="2" />
      <input name="sdate_mm" type="text" size="2" />
      <input name="sdate_yy" type="text" size="4" /><p/>
      Giờ bắt đầu (hh:mm): <br/>
      <input name="sdate_hh" type="text" size="2" /> 
      <input name="sdate_ii" type="text" size="2" /><br/>
      Ngày kết thúc (dd/mm/yyyy): <br/>
      <input name="edate_dd" type="text" size="2" />
      <input name="edate_mm" type="text" size="2" />
      <input name="edate_yy" type="text" size="4" /><p/>
      Giờ kết thúc (hh:mm): <br/>
      <input name="edate_hh" type="text" size="2" /> 
      <input name="edate_ii" type="text" size="2" /><p/>
      Địa điểm: <br/>
      <input name="where" type="text" size="8"/>
      <br />
      Nội dung: <br />
      <textarea name="content" cols="8"></textarea> 
      <br />
      <input name="submit" type="submit" value="Save" />      
    </form>
    <?php
    } else {
      // load classes
	  $path = '.\\ZendGData\\library\\';
	  $oldpath = set_include_path($path);
	  require_once 'Zend/Loader.php';
      Zend_Loader::loadClass('Zend_Gdata');
      Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
      Zend_Loader::loadClass('Zend_Gdata_Calendar');
      Zend_Loader::loadClass('Zend_Http_Client');
      
      // connect to service
      $gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
      $user = "luanngu.0111@gmail.com";
      $pass = "nguyentriluanngu";
      $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $gcal);
      $gcal = new Zend_Gdata_Calendar($client);
      
      // validate input
      if (empty($_POST['title'])) {
        die('ERROR: Thiếu tên sự kiện');
      } 
      
      if (!checkdate($_POST['sdate_mm'], $_POST['sdate_dd'], $_POST['sdate_yy'])) {
        die('ERROR: Ngày/giờ bắt đầu không hợp lệ');        
      }
      
      if (!checkdate($_POST['edate_mm'], $_POST['edate_dd'], $_POST['edate_yy'])) {
        die('ERROR: Ngày/giờ kết thúc không hợp lệ');        
      }
      
      $title = htmlentities($_POST['title']);
      $start = date(DATE_ATOM, mktime($_POST['sdate_hh'], $_POST['sdate_ii'], 0, $_POST['sdate_mm'], $_POST['sdate_dd'], $_POST['sdate_yy']));
      $end = date(DATE_ATOM, mktime($_POST['edate_hh'], $_POST['edate_ii'], 0, $_POST['edate_mm'], $_POST['edate_dd'], $_POST['edate_yy']));
	  $diadiem = $_POST['where'];
	  $noidung = $_POST['content'];
      // construct event object
      // save to server      
      try {
        $event = $gcal->newEventEntry();        
        $event->title = $gcal->newTitle($title);        
		
        $when = $gcal->newWhen();
        $when->startTime = $start;
        $when->endTime = $end;
        $event->when = array($when);        
        
		$where = $gcal->newWhere($diadiem);
		$content = $gcal->newContent($noidung);
		$event->where = array($where);
		$event->content = $content;
	
		$gcal->insertEvent($event);  
      } catch (Zend_Gdata_App_Exception $e) {
        echo "Error: " . $e->getResponse();
      }
      echo 'Sự kiện đã thêm thành công!';  
	  echo "<script>window.location.href=\"view_event.php?username=$user&password=$pass\"</script>";
	  exit();    
    }
    ?>
  </body>
</html>     

