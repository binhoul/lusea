<?php

require_once("includes/main.php");
header("Content-Type: text/html; charset=utf-8");
try {
	if($_GET["cn_name"]){
		$c = new CardController();
	}
	else if(empty($_GET)){
		$c = new HomeController();
	}
	else throw new Exception("Wrong page!");
	
	$c->handleRequest();
}
catch(Exception $e){
	//Display the error page
	render('error',array('message'=>$e->getMessage()));

}
?>
