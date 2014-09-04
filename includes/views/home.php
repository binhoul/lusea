<?php render('_header',array('title'=>$title))?>

    <form action='/index.php'>
    <?php echo __DIR__?>
      <div data-role="fieldcontain">
        <label for="cn_name"><h4>卡牌名称：</h4></label>
        <input type="text" name="cn_name" id="cn_name">
        <input type="submit" data-inline="true" value="查询">
      </div>
    </form>
<?php render('_footer')?>
