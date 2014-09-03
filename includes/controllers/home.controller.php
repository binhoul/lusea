<?php
/*
*/
class HomeController{
	public function handleRequest(){
		render("home",array(
			'title'		=>'欢迎查询炉石卡牌';
		));
		
	}
}