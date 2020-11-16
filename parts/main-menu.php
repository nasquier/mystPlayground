<?php  
if(!isset($_SESSION)) { 
	session_start(); 
}  
?>
<nav class='main-menu'>
	<label><a href='../index.php'> Home </a></label>
	<label><a href='../news.php'> News </a></label>
	<label><a href='../aboutme.php'> About me </a></label>
	<label><a href='../index.php'> Play a game </a></label>
	<label><a href='../info.php'> PHP info </a></label>
	<label><a href='../guestbook.php'> Guestbook </a></label>

	<?php 
	if (isset($_SESSION["username"])){
		?><label><a href='../logout.php'> Log out </a></label><?php	
	}
	else{
		?><label><a href='../login.php'> Sign in </a></label><?php	
	}
	?>
</nav>