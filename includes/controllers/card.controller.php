<?php
	
class CardController{
	public function HandleRequest(){
		$result = Card::find(array('cn_name'=>$_GET['cn_name']));
		if(empty($result)){
			throw new Exception("没有该卡牌啊！");
		}
		
		//Fetch a random card:
		
		render('card',array(
			'title'		=> $result[0][0],
			'card'		=> $result[0],
			));


	}
}
