<?php  
if(!isset($_SESSION)) { 
    session_start(); 
}  
if (isset($_SESSION["user_connected"])){
    $message = "Nice to see you, ".htmlspecialchars($_SESSION["user_connected"])."."; 
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <link rel='stylesheet' href='styles/main-style.css' />
    <title>myst</title>
</head>
<body>
	<?php 
    include('parts/header.php'); 
    include('parts/main-menu.php');
    if (isset($message)){
        echo("<p>".$message."</p>");
    }
    ?>
</body>
</html>