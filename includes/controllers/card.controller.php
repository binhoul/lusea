<?php
	
class CardController{
	public function HandleRequest(){
		$cat = Card::find(array('cn_name'=>$_GET['cn_name']));
		if(empty($cat)){
			throw new Exception("没有该卡牌啊！");
		}
		
		//Fetch a random card:
		$card = Card::find();
		
		render('card',array(
			'title'		=> 'Browsing '.$cat[0]->name,
			'card'		=> $card,
			));


	}
}