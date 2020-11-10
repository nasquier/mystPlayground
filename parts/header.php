<?php  
if(!isset($_SESSION)) { 
	session_start(); 
}  
?>
<header>
	<h1><a href='index.php'>my sick website</a></h1>
	<?php 
	if (isset($_SESSION["username"])){
		?>
		<a href="manageaccount.php">
			<img class='roundpic' src=<?php echo('"'.htmlspecialchars($_SESSION["user_pfp"]).'"');?> title='It&#039s you!'/> 
		</a>

		<?php	
	}
	?>
</header>