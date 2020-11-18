<?php   
if(!isset($_SESSION)) { 
	session_start(); 
}
session_destroy();

setcookie('username', '',null,null,false,true);
setcookie('pwd_hash', '',null,null,false,true);

header("Location: index.php?message_id=0");
exit();
?>
