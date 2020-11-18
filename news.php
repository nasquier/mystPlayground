<?php  
if(!isset($_SESSION)) { 
	session_start(); 
}
include('include/manage-db.php');
$bdd = getdb();


// Add post to database
if(isset($_SESSION['username']) && isset($_POST) && isset($_POST['title']) && isset($_POST['content'])) { 

	$username = htmlspecialchars($_SESSION["username"]);
	$user_id = htmlspecialchars($_SESSION["user_id"]);

	$query = $bdd->prepare('INSERT INTO posts(title,content,author_id) VALUES (:title,:content,:author_id)');
	$query->execute(array(
		'title'=>$_POST['title'],
		'content'=>$_POST['content'],
		'author_id'=>$user_id));
} 

// Query posts to display
$query = $bdd->prepare('
	SELECT p.*, u.username, u.pfp_path, c.ncomment 
	FROM posts AS p 
	INNER JOIN users AS u ON p.author_id=u.id 
	LEFT JOIN ( 
    	SELECT post_id,count(*) AS ncomment 
    	FROM posts_comments 
    	GROUP BY post_id
	) AS c ON p.id = c.post_id
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
		// Gather data
		$post_id = $post['id'];
		$author = $post['username'];
		$post_date = $post['post_date'];
		$author_pfp_path = $post['pfp_path'];
		if ($author_pfp_path == ""){
			$author_pfp_path = "images/default-pfp.jpg";
		}

		// Format comment text
		$ncomment = $post['ncomment'];
		switch ($post['ncomment']){
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
		echo("<h3> <b>".date('Y-m-d, H:i:s',strtotime($post_date))." :</b></h3>");
		echo('<p>'.$post['content'].'</p></br>');
		echo("<a href='comments.php?post_id=".$post_id."'>".$comment_text."</a>");
		?>
		</div>
		</div>
		</section>
		<?php
	}
	?>
</body>
</html>