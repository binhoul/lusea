<?php
	
class CardController{
	public function HandleRequest(){
		$result = Card::find(array('cn_name'=>$_GET['cn_name']));
		if(empty($result)){
			throw new Exception("没有该卡牌啊！");
		}
		
		//Fetch a random card:
		
		render('card',array(
			'title'		=> '卡牌查询',
			'card'		=> $result[0],
			));


	}
}
