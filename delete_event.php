<!DOCTYPE html 
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
  <head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Deleting calendar events</title>
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
    <h1>Xóa sự kiện</h1>
    <?php
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
    //$user = "user@gmail.com";
   // $pass = "password";
    $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $gcal);
    $gcal = new Zend_Gdata_Calendar($client);
      
    // if event ID is present
    // get event object from feed
    // delete event  
    if (isset($_GET['id'])) {
      try {          
          $event = $gcal->getCalendarEventEntry('http://www.google.com/calendar/feeds/default/private/full/' . $_GET['id']);
          $event->delete();
      } catch (Zend_Gdata_App_Exception $e) {
          echo "Error: " . $e->getResponse();
      }        
      echo 'Sự kiện đã xóa thành công!';  
	  echo "<script>window.location.href=\"view_event.php?username=$user&password=$pass\"</script>";
	  exit();
    } else {
      echo 'Không có sự kiện nào';  
    }
    ?>
  </body>
</html>
