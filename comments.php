<?php  
if(!isset($_SESSION)) { 
	session_start(); 
}
include('include/manage-db.php');
$bdd = getdb();

// Comment to display
$post_id = htmlspecialchars($_GET['post_id']);


// Get post information
$query_post = $bdd->prepare('SELECT * FROM posts WHERE id=?');
$query_post->execute(array($post_id));
$post = $query_post->fetch();


// Get post author information
$author_id = $post['author_id'];
$query_author = $bdd->prepare('SELECT * FROM users WHERE id=?');
$query_author->execute(array($author_id));
$author_data = $query_author->fetch();
$author = $author_data['username'];
$author_pfp_path = $author_data['pfp_path'];
if ($author_pfp_path == ""){
	$author_pfp_path = "images/default-pfp.jpg";
}

// Adding comment to DB
if(isset($_SESSION['username']) && isset($_POST) && isset($_POST['comment'])) { 
	$comment = htmlspecialchars($_POST['comment']);

	$username = htmlspecialchars($_SESSION["username"]);
	$query_id = $bdd->prepare('SELECT id FROM users WHERE username=?');
	$query_id->execute(array($username));
	$user_id = $query_id->fetchColumn();

	$query = $bdd->prepare('INSERT INTO posts_comments(post_id,author_id,content) VALUES (:post_id,:author_id,:content)');
	$query->execute(array(
		'post_id'=>$post_id ,
		'author_id'=>$user_id,
		'content'=>$comment));
}

// Query comments to display
$query_comments =  $bdd->prepare('SELECT * FROM posts_comments WHERE post_id=? ORDER BY post_date LIMIT 0,10');
$query_comments->execute(array($post_id)); 
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
		<div class='post-author'>
			<div class='post-pfp'>
				<?php 
				echo("<img class='roundpic' src='".$author_pfp_path."' title='".$author."'/>"); 
				?>
			</div>
			<div>
				<?php 
				echo("<h1>".$post['title']."</h1>");
				echo("<h3> <b>".date('Y-m-d, H:i:s',strtotime($post['post_date']))." :</b></h3>"); 
				echo('<p>'.$post['content'].'</p></br>');
				?>
			</div>
		</div>
	</section>

	<?php
	while ($comment = $query_comments->fetch()){
		$commenter_id = $comment['author_id'];

		// Gather commenter info
		$query_commenter = $bdd->prepare('SELECT * FROM users WHERE id=?');
		$query_commenter->execute(array($commenter_id));
		$commenter_data = $query_commenter->fetch();

		$commenter = $commenter_data['username'];
		$commenter_pfp_path = $commenter_data['pfp_path'];
		if ($commenter_pfp_path == ""){
			$commenter_pfp_path = "images/default-pfp.jpg";
		}

		?>
		<!-- Display comment -->
		<section>
			<div class='post-author'>
				<div class='post-pfp'>
					<?php echo("<img class='roundpic' src='".$commenter_pfp_path."' title='".$commenter."'/>"); ?>
				</div>
				<div>
					<?php
					echo("<h3> <b>".date('Y-m-d, H:i:s',strtotime($comment['post_date']))." :</b></h3>");
					echo('<p>'.$comment['content'].'</p></br>');
					?>
				</div>
			</div>
		</section>
		<?php
	}
	
	// Comment area
	if (!isset($_SESSION["username"])){
		?>
		<div class='home-message'><em> You must be connected to comment this post. </em></div>
		<?php
	}
	else {
		?>
		<section>
			<form method='post'>
				<fieldset class='postfieldset'>
					<textarea name ='comment' class='message' rows='500' cols='100' maxlength='5000' required/></textarea><br/>
					<input class='button' type='submit' value='Comment'/>
				</fieldset>
			</form>
		</section>
		<?php   
	}
	?>
</body>
</html>