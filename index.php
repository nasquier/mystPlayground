<?php  
include('include/manage-db.php');
include('parts/user-autoconnect.php');
if (isset($_GET) && isset($_GET['message_id']) && is_numeric($_GET['message_id'])){
    switch ((int)htmlspecialchars($_GET['message_id'])){
        case 0:
        $message = "Logged out.";
        break;
        case 1:
        $message = "Account updated.";
        break;
        case 2:
        $message = "Account deleted.";
        break;
    }
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
        echo("<div class='home-message'><em>".$message.'</em></div>');
    }
    ?>
    <section>
            <?php 
            if (isset($_SESSION["username"])){
                echo("<p> Hey ".htmlspecialchars($_SESSION["username"])." ! Nice to see you !</p>");
            } else {
                echo('<p> Hey ! </p>');
            }
            ?>
            <p> My name is Nicolas Asquier AKA myst. I am a wannabe developper who likes sports, music, beers, reading and gaming. I made this website "from scratch" as an attempt to learn more about informatics. For the moment, what you see here have been done with no framework, in order for me to understand how things work.</p>
            <p> It started with an application of my HTML/CSS knowledge. Next, I have been using PHP to offer a personalized experience to the people who will sign up here. I stored the users' data using a MariaDB database, which I used both in shell mode and with phpMyAdmin. </p>
            <p> Everything is done in a virtual Debian which I access via my Windows host, so I can trained my network skills too !</p>
            <p> I will continue to add things here as my training progress. I would like to code simple multiplayer games to make the users' inscription useful.</p>
            <p> Thank you for your time ! </p>
    </section>
</body>
</html>