<?php
	$ip = $_SERVER['SERVER_ADDR'];
	$name = $_SERVER['SERVER_NAME'];
	if(!empty($name)){$srv = $name;}
	else{$srv = $ip;}
	header("location:/connexion.html");
	exit;
	
?>
