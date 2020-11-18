<?php  
include('include/user-autoconnect.php');
include('include/manage-db.php');
$bdd = getdb();

// Add entry to guest book
if(isset($_SESSION['username']) && isset($_POST) && isset($_POST['message'])) { 
    $username = htmlspecialchars($_SESSION["username"]);
    $user_id = htmlspecialchars($_SESSION["user_id"]);
    $query = $bdd->prepare('INSERT INTO guestbook(message,author_id) VALUES (:message,:author_id)');
    $query->execute(array(
        'message'=>$_POST['message'],
        'author_id'=>$user_id));
} 

// Query guestbook entries
$query = $bdd->prepare('SELECT * FROM guestbook LIMIT 0,10');
$query->execute();

$query = $bdd->prepare('
    SELECT g.*, u.username, u.pfp_path
    FROM guestbook AS g 
    INNER JOIN users AS u ON g.author_id=u.id 
    ORDER BY g.post_date
    LIMIT 10
    ');
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
            if (!isset($author) || $guestbook_entry['username']!=$author){
                // This is to or
                if (isset($author)){
                    ?> 
                    </div> 
                    </div> 
                    <?php
                }

                // Gather data
                $author = $guestbook_entry['username'];
                $author_pfp_path = $guestbook_entry['pfp_path'];
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
            $message = $guestbook_entry['message'];
            $post_date = $guestbook_entry['post_date'];
            echo("<p> <b>".date('Y-m-d, H:i:s',strtotime($post_date))." :</b><br/>
                ".$message.'</p>');
        }
        ?>
        </div>
        </div>
    </section>
</body>
</html>