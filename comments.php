<?php  
include('include/manage-db.php');
include('parts/user-autoconnect.php');
$bdd = getdb();

// Post to display
$post_id = htmlspecialchars($_GET['post_id']);


// Get post information
$query_post = $bdd->prepare('
	SELECT p.*, u.username, u.pfp_path 
	FROM posts AS p 
	INNER JOIN users AS u
	ON p.author_id=u.id
	WHERE p.id=?');
$query_post->execute(array($post_id));
$post = $query_post->fetch();

$post_title = $post['title'];
$post_content = $post['content'];
$post_date = $post['post_date'];
$author = $post['username'];
$author_pfp_path = $post['pfp_path'];
if ($author_pfp_path == ""){
	$author_pfp_path = "images/default-pfp.jpg";
}

// Adding comment to DB
if(isset($_SESSION['username']) && isset($_POST) && isset($_POST['comment'])) { 
	$comment = htmlspecialchars($_POST['comment']);
	$user_id = htmlspecialchars($_SESSION["user_id"]);

	$query = $bdd->prepare('INSERT INTO posts_comments(post_id,author_id,content) VALUES (:post_id,:author_id,:content)');
	$query->execute(array(
		'post_id'=>$post_id ,
		'author_id'=>$user_id,
		'content'=>$comment));
}

// Query comments to display
$query_comments =  $bdd->prepare('
	SELECT c.content, c.post_date, u.username, u.pfp_path 
	FROM posts_comments AS c 
	INNER JOIN users AS u ON c.author_id=u.id 
	WHERE c.post_id=? 
	ORDER BY c.post_date 
	LIMIT 0,10
	');
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
				echo("<h1>".$post_title."</h1>");
				echo("<h3> <b>".date('Y-m-d, H:i:s',strtotime($post_date))." :</b></h3>"); 
				echo('<p>'.$post_content.'</p></br>');
				?>
			</div>
		</div>
	</section>

	<?php
	while ($comment = $query_comments->fetch()){
		// Gather data
		$commenter = $comment['username'];
		$comment_date = $comment['post_date'];
		$comment_content = $comment['content'];
		$commenter_pfp_path = $comment['pfp_path'];
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
					echo("<h3> <b>".date('Y-m-d, H:i:s',strtotime($comment_date))." :</b></h3>");
					echo('<p>'.$comment_content.'</p></br>');
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