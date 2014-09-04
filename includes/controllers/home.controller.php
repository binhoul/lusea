<?php
/*
*/
class HomeController{
    public function handleRequest(){
        render("home",array(
            'title'     =>'welcome to lushi'
        ));
        
    }
}
