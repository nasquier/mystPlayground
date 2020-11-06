<?php   
if(!isset($_SESSION)) { 
	session_start(); 
}
session_destroy();
header("Location: index.php?message_id=0");
exit();
?>
