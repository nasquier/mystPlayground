<?php
function getdb(){
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=mystPlayground;charset=utf8','guest', 'welcome2MYSTplayground!');
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	ini_set('display_errors','on');
	error_reporting(E_ALL);
	return $bdd;
}
?>