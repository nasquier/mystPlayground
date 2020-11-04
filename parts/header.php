<?php  
if(!isset($_SESSION)) { 
	session_start(); 
}  
?>
<header>
	<h1><a href='index.php'>my sick website</a></h1>
	<?php 
	if (isset($_SESSION["user_connected"])){
		?>
		<a href="index.php">
			<img class='profile-pic' src=<?php echo('"'.htmlspecialchars($_SESSION["user_picture"]).'"');?> title='It&#039s you!'/> 
		</a>

		<?php	
	}
	?>
</header>