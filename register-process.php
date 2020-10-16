<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8' />
	<link rel='stylesheet' href='styles/main-style.css' />
	<title>myst</title>
</head>
<body>
	<?php 
		include('header.php'); 
		include('main-menu.php');

		$file = 'users.csv';
		if (!file_exists($file)){
			$users_file = fopen($file, 'a+');
			fputcsv($users_file,array('username','email','password','picture'));
			fclose($users_file);
		}
		$users_file = fopen($file, 'a+');
		$fields = fgetcsv($users_file);

		$user_inputs = array_fill(0, count($fields), "");
		$user_inputs[array_search('username', $fields)] = $_POST['username'];
		$user_inputs[array_search('email', $fields)] = $_POST['email'];
		$user_inputs[array_search('password', $fields)] = $_POST['password'];
		echo '<p>Your username is '.$_POST['username'].', your email adress is '.$_POST['email'].'.</p>';

		if (isset($_FILES['profilepic']) AND $_FILES['profilepic']['error'] == 0){
			$picturepath = 'profile-pictures/'.$_POST['username'].'_'.basename($_FILES['profilepic']['name']);
			move_uploaded_file($_FILES['profilepic']['tmp_name'], $picturepath);
			$user_inputs[array_search('picture', $fields)]=realpath($picturepath);

			echo '<p> Here is your profile picture : </p>'
			?><img class='floating-object' src=<?php echo '"'.$picturepath.'"'; ?> title='It&#039s you!'/><?php
		}
		else{
			echo '<p> No valid profile picture detected. </p>';
		}

		fputcsv($users_file,$user_inputs);
		fclose($users_file);
	?>


</body>
</html>
