<?php  
if(!isset($_SESSION)) { 
	session_start(); 
}  
?>
<header>

	<?php 
	if (isset($_SESSION["username"])){
		?>
		<a href="index.php">
			<img class='profile-pic' src=<?php echo('"'.$_SESSION["picture"].'"');?> title='It&#039s you!'/> 
		</a>

		<?php	
	}
	?>

	<h1>myst dope website</h1>
	<p>Date and time are <?php echo date('d/m/Y, h:i:s'); ?>. You're welcome.</p>
</header>