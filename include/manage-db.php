<?php
function getdb(){
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=mystPlayground;charset=utf8','root', '');
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