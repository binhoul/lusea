<?php render('_header',array('title'=>$title))?>
<?php echo "<img src='/pic/".$card[1].".png' />"?>
<ul data-role="listview">
  <li><span>名称：</span><?php echo $card[0]?></li>
  <li><span>英文：</span><?php echo $card[1]?></li>
  <li><span>职业：</span><?php echo $card[2]?></li>
  <li><span>稀有：</span><?php echo $card[3]?></li>
  <li><span>类型：</span><?php echo $card[4]?></li>
  <li><span>法力：</span><?php echo $card[5]?></li>
  <li><span>生命：</span><?php echo $card[6]?></li>
  <li><span>攻击：</span><?php echo $card[7]?></li>
  <li><span>技能：</span><?php echo $card[8]?></li>
  <li><span>描述：</span><?php echo $card[9]?></li>
</ul>


<?php render('_footer')?>
