<?php   
if(!isset($_SESSION)) { 
	session_start(); 
}
session_unset();
session_destroy();

unset($_COOKIE['username']);
unset($_COOKIE['pwd_hash']);
setcookie('username', '',null,null,null,false,true);
setcookie('pwd_hash', '',null,null,null,false,true);

header("Location: index.php?message_id=0");
exit();
?>
