<?php  
if(!isset($_SESSION)) { 
    session_start(); 
}
include('include/manage-db.php');
$bdd = getdb();

// Add entry to guest book
if(isset($_SESSION['username']) && isset($_POST) && isset($_POST['message'])) { 
    $username = htmlspecialchars($_SESSION["username"]);
    $query = $bdd->prepare('SELECT id FROM users WHERE username=?');
    $query->execute(array($username));
    $user_id = $query->fetchColumn();
    $query = $bdd->prepare('INSERT INTO guestbook(message,author_id) VALUES (:message,:author_id)');
    $query->execute(array(
        'message'=>$_POST['message'],
        'author_id'=>$user_id));
} 

// Query guestbook entries
$query = $bdd->prepare('SELECT * FROM guestbook LIMIT 0,10');
$query->execute();
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

    // Guestbook post area
    if (!isset($_SESSION["username"])){
        ?>
        <div class='home-message'><em> You must be connected to write in the guestbook. </em></div>
        <?php
    }
    else {
        ?>
        <section>
            <form method='post' action='guestbook.php'>
                <fieldset class='postfieldset'>
                    <textarea name='message' class='message' rows='100' cols='100' maxlength='500' required/></textarea><br/>
                    <input class='button' type='submit' value='Send'/>
                </fieldset>
            </form>
        </section>
        <?php   
    }
    ?>

    <section>
        <?php
        while ($guestbook_entry = $query->fetch()){
            if (!isset($author_id) || $guestbook_entry['author_id']!=$author_id){
                if (isset($author_id)){
                    ?> 
                    </div> 
                    </div> 
                    <?php
                }

                // Gather author data
                $author_id = $guestbook_entry['author_id'];

                $query_author = $bdd->prepare('SELECT * FROM users WHERE id=?');
                $query_author->execute(array($author_id));
                $userlist = $query_author->fetch();

                $author = $userlist['username'];
                $author_pfp_path = $userlist['pfp_path'];
                if ($author_pfp_path == ""){
                    $author_pfp_path = "images/default-pfp.jpg";
                }
                ?>

                <!-- Display author pfp -->
                <div class='post-author'>
                <div class='post-pfp'>
                <?php echo("<img class='roundpic' src='".$author_pfp_path."' title='".$author."'/>"); ?>
                </div>
                <div>
                <?php
            }

            // Display all posts from author until it changes
            echo("<p> <b>".date('Y-m-d, H:i:s',strtotime($guestbook_entry['post_date']))." :</b><br/>");
            echo($guestbook_entry['message'].'</p>');
        }
        ?>
        </div>
        </div>
    </section>
</body>
</html>