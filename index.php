<?php  
if(!isset($_SESSION)) { 
    session_start(); 
}  
if (isset($_SESSION["user_connected"])){
    $message = "Hi ".htmlspecialchars($_SESSION["user_connected"])." ! Nice to see you !"; 
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
    ?>
    <section>
        <?php 
        if (isset($message)){
            echo("<p>".$message."</p>");
        } else {
            echo('Hi !');
        }
        ?>
        <p> My name is Nicolas Asquier AKA myst. I am a wanabe developper who likes sports, music, beers, reading and gaming. I made this website "from scratch" as an attempt to learn more about informatics stuff. For the moment, what you see here have been done with no framework, in order for me to understand how things work.</p>
        <p> It started with an application of my HTML/CSS knowledge. Next, I have been using PHP to offer a personalized experience to the people who will sign up here. I stored the users' data using a Mariadb database, which I used both in shell mode and with phpMyAdmin. </p>
        <p> Everything is done in a virtual Debian which I access via my Windows host, so I can trained my network skills too !</p>
        <p> I will continue to add things here as my training progress. I'd like to code simple multiplayer games to make the users' inscription useful. Thank you for your time ! </p>
    </section>
</body>
</html>