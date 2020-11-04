<?php  
if(!isset($_SESSION)) { 
	session_start(); 
}  
?>
<nav class='main-menu'>
	<label class='main-menu-item'><a href='../index.php'> Home </a></label>
	<label class='main-menu-item'><a href='../aboutme.php'> About me </a></label>
	<label class='main-menu-item'><a href='../index.php'> Play a game </a></label>
	<label class='main-menu-item'><a href='../info.php'> PHP info </a></label>

	<?php 
	if (isset($_SESSION["user_connected"])){
		?><label class='main-menu-item'><a href='../logout.php'> Log out </a></label><?php	
	}
	else{
		?><label class='main-menu-item'><a href='../login.php'> Sign in </a></label><?php	
	}
	?>
</nav>