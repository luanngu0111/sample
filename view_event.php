<!DOCTYPE php 
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

 <meta http-equiv="Content-Type" content="text/php; charset=utf-8" />
    <title>Listing calendar contents</title>
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

    <?php
	// load classes
	$user = 'luanngu.0111@gmail.com';
	$pass = 'nguyentriluanngu';
	
    $path = './ZendGData/library/';
	$oldpath = set_include_path($path);
    require_once 'Zend/Loader.php';

    Zend_Loader::loadClass('Zend_Gdata');
    Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
    Zend_Loader::loadClass('Zend_Gdata_Calendar');
    Zend_Loader::loadClass('Zend_Http_Client');
    
    $gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
	
    //$user = $_POST["username"];
	global $user;
	
    //$pass = $_POST["password"];
	global $pass;
	
    $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $gcal);
    $gcal = new Zend_Gdata_Calendar($client);
    
    $query = $gcal->newEventQuery();
    $query->setUser('default');
    $query->setVisibility('private');
    $query->setProjection('basic');
    $query->setOrderby('starttime');
    if(isset($_GET['q'])) {
      $query->setQuery($_GET['q']);      
    }
    
    try {
      $feed = $gcal->getCalendarEventFeed($query);
    } catch (Zend_Gdata_App_Exception $e) {
      echo "Error: " . $e->getResponse();
    }
    ?>
    <h1><?php echo $feed->title; ?></h1>
    <?php echo $feed->totalResults; ?> event(s) found.
    <p/>
    <ol>

    <?php        
    foreach ($feed as $event) {
      echo "<li>\n";
      echo "<h2>" . stripslashes($event->title) . "</h2>\n";
      echo stripslashes($event->summary) . " <br/>\n";
      $id = substr($event->id, strrpos($event->id, '/')+1);
      echo "<a href=edit_event.php?id=$id&user=$user&pass=$pass>edit</a> | ";
      echo "<a href=\"delete_event.php?id=$id&user=$user&pass=$pass\">delete</a> <br/>\n";
      echo "</li>\n";
    }
    echo "</ul>";
    ?>
    </ol>
    <p/>
    <?php
    echo "<a href=\"add_event.php?user=$user&pass=$pass\">Add a new event</a>" ?><p/>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
      Search for events containing:<br/>
      <input type="text" name="q" size="10"/><p/>
      <input type="submit" name="submit" value="Search"/>
    </form>
  
   
