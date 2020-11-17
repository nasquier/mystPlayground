<?php  
if(!isset($_SESSION)) { 
	session_start(); 
}
include('include/manage-db.php');
$bdd = getdb();


// Add post to database
if(isset($_SESSION['username']) && isset($_POST) && isset($_POST['title']) && isset($_POST['content'])) { 

	$username = htmlspecialchars($_SESSION["username"]);
	$query = $bdd->prepare('SELECT id FROM users WHERE username=?');
	$query->execute(array($username));
	$user_id = $query->fetchColumn();

	$query = $bdd->prepare('INSERT INTO posts(title,content,author_id) VALUES (:title,:content,:author_id)');
	$query->execute(array(
		'title'=>$_POST['title'],
		'content'=>$_POST['content'],
		'author_id'=>$user_id));
} 

// Query posts to display
$query = $bdd->prepare('SELECT * FROM posts LIMIT 0,10');
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

	//  Post area
	if (!isset($_SESSION["username"])){
	?>
		<div class='home-message'><em> You must be connected to write a post. </em></div>
	<?php
	}
	else {
		?>
		<section>
			<form method='post' action='news.php'>
				<fieldset class='postfieldset'>
					<legend> Write a post </legend>
					<label> Title </label>
					<input type='text' name='title' maxlength='255' required>
					<textarea name ='content' class='message' rows='500' cols='100' maxlength='5000' required/></textarea><br/>
					<input class='button' type='submit' value='Post'/>
				</fieldset>
			</form>
		</section>
		<?php   
	}
	?>

	<?php
	while ($post = $query->fetch()){

		// Gather post author data
		$author_id = $post['author_id'];
		$query_author = $bdd->prepare('SELECT * FROM users WHERE id=?');
		$query_author->execute(array($author_id));
		$author_data = $query_author->fetch();

		$author = $author_data['username'];
		$author_pfp_path = $author_data['pfp_path'];
		if ($author_pfp_path == ""){
			$author_pfp_path = "images/default-pfp.jpg";
		}

		// Count comments on post and format comment text
		$query_ncomment = $bdd->prepare('SELECT count(*) FROM posts_comments WHERE post_id=?');
		$query_ncomment->execute(array($post['id']));
		$ncomment = $query_ncomment->fetchColumn();
		switch ($ncomment){
			case 0:
			$comment_text = "Comment";
			break;
			case 1:
			$comment_text = "Comment or read 1 comment";
			break;
			default:
			$comment_text = "Comment or read all ".$ncomment." comments";
			break;
		}
		?>

		<!-- Display comment -->
		<section>
		<div class='post-author'>
		<div class='post-pfp'>
			<?php echo("<img class='roundpic' src='".$author_pfp_path."' title='".$author."'/>"); ?>
		</div>
		<div>
		<?php
		echo("<h1>".$post['title']."</h1>");
		echo("<h3> <b>".date('Y-m-d, H:i:s',strtotime($post['post_date']))." :</b></h3>");
		echo('<p>'.$post['content'].'</p></br>');
		echo("<a href='comments.php?post_id=".$post['id']."'>".$comment_text."</a>");
		?>
		</div>
		</div>
		</section>
		<?php
	}
	?>
</body>
</html>