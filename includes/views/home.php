<?php render('_header',array('title'=>$title))?>

<div data-role="page">
  <div data-role="content">
    <form action='index.php'>
      <div data-role="fieldcontain">
        <label for="cn_name">卡牌名称：</label>
        <input type="text" name="cn_name" id="cn_name">
        <input type="submit" data-inline="true" value="查询">
      </div>
    </form>
  </div>
</div>
<?php render('_footer')?>
