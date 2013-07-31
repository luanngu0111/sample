<?php

error_reporting(E_ALL);

define('AJAX_CHAT_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');

require(AJAX_CHAT_PATH.'lib/custom.php');

require(AJAX_CHAT_PATH.'lib/classes.php');

$ajaxChat = new CustomAJAXChat();
?>