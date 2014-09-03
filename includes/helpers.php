<?php

function render($template, $var = array()){
	
	//接受
	extract($vars);
	
	
	if(is_array($template)){
		
		foreach($template as $k){
			$cl = strtolower(get_class($k));
			$$cl = $k;
			
			include "views/_$cl.php";
		}
	
	}
	else {
		include "views/$template.php";
	}
}