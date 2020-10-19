<?php 

$file = 'users.csv';
if (!file_exists($file)){
	$users_file = fopen($file, 'a+');
	fputcsv($users_file,array('username','email','password','picture'));
	fclose($users_file);
}
$users_file = fopen($file, 'a+');
$fields = fgetcsv($users_file);

$user_inputs = array_fill(0, count($fields), "");
$user_inputs[array_search('username', $fields)] = htmlspecialchars($_POST['username']);
$user_inputs[array_search('email', $fields)] = htmlspecialchars($_POST['email']);
$user_inputs[array_search('password', $fields)] = htmlspecialchars($_POST['password']);

if (isset($_FILES['profilepic']) AND $_FILES['profilepic']['error'] == 0){
	$picturepath = 'profile-pictures/'.$_POST['username'].'_'.basename($_FILES['profilepic']['name']);
	move_uploaded_file($_FILES['profilepic']['tmp_name'], $picturepath);
	$user_inputs[array_search('picture', $fields)]=$picturepath;
}

fputcsv($users_file,$user_inputs);
fclose($users_file);
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
	echo '<p>Successfully registered as '.htmlspecialchars($_POST['username']).'.</p>';
	?>
	<p> <a href="login.php"><input type="button" value="Sign in here"/></a> </p>


</body>
</html>
