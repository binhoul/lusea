<?php

function render($template, $vars = array()){
	
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

function formatTitle($title = ''){
    if($title){
        $title.= ' | ';
    }

    $title .= $GLOBALS['defaultTitle'];

    return $title;
}

?>
