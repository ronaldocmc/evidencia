<?php 

function dumpanddie($var){
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
	die();
}

?>