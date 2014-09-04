<?php
include('./simple_html_dom/simple_html_dom.php');
//include('/saestorage.class.php');
//header("content-type:text/html; charset=utf-8");
$crawl = new CardsParser();
$conn = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
//$conn = mysql_connect('127.0.0.1:3306','root','fengmao');
mysql_select_db("app_roomheart", $conn);
mysql_query("set names 'utf8'");

$first_page_url = "http://db.duowan.com/lushi/card/list/eyJwIjoxLCJzb3J0IjoiaWQuZGVzYyJ9.html";
$page_links = $crawl->get_page_links($first_page_url);

foreach($page_links as $page_link)
{
    $card_page_links = $crawl->get_card_page_links($page_link);
    foreach($card_page_links as $card_page_link)
    {
        sleep(0.5);
        $card_properities = $crawl->get_card_properities($card_page_link);
        $crawl->pic_to_storage($card_properities[1],$card_properities[10]);
        
        $check_sql = "select count(1) from cards where en_name='".$card_properities[1]."'";
        $result = mysql_query($check_sql);
        $hitcount = mysql_fetch_row($result);
        if($hitcount[0] > 0)
        {
            $method = "update";
        }elseif($hitcount[0] == 0)
        {
            $method = "insert";
        }
        $sql = $crawl->convert_array_to_sql($card_properities, $method);
        mysql_query($sql) or die("插入错误：". mysql_error()."\n");
        //传入图片名称和url

    }
}




class CardsParser
{

    function get_card_properities($card_url)
    {
        $arr_of_raw = array();
        $arr_of_properities = array();
        $count = 0;
        $html = file_get_html($card_url);
        foreach($html->find('tbody tr td') as $element)
        {
            $arr_of_raw[$count++] = $element->plaintext;
        }
        array_shift($arr_of_raw);
        $keys = array_keys($arr_of_raw);
        foreach($keys as $key)
        {
            if($key % 2 == 1)
                $arr_of_properities[$key/2] = $arr_of_raw[$key];
        }
        $keys = array_keys($arr_of_properities);
        foreach($keys as $key)
        {
            $arr_of_properities[$key] = str_replace(" ","",$arr_of_properities[$key]);
            $arr_of_properities[$key] = addslashes($arr_of_properities[$key]);
        }
        //获得图片信息
        $img_url = $html->find('img',0)->src;
        $img_url = urlencode($img_url);
        //$img_file = file_get_html($img_url);
        //array_push($arr_of_properities, $img_file);
        array_push($arr_of_properities, $img_url);
        return $arr_of_properities;
    }

    function get_card_page_links($page_url)
    {
        $html = file_get_html($page_url);
        $arr_of_link = array();
        $count = 0;
        //获取链接url
        foreach($html->find('tbody tr td[class="name"] a') as $element)
        {
            $arr_of_link[$count++] = $element->href;

        }
        return $arr_of_link;
        /*
        //根据获取的url逐个抓取页面进行分析
        foreach($arr_of_link as $element)
        {
            
            $card_properities = $this->get_card_properities($element);
            $this->write_cardpros_to_db($card_properities);
        }

        //获取下一页
        $current_page_link = $page_link;
        $next_page_link = $html->find('div[class="mod-page center"] a[title="下一页",rel="next"]',0)->href;
        if($current_page_link != $next_page_link)
        {
            $this->get_page_link($next_page_link);
        }
        */
    }

    //获取所有页面链接

    function get_page_links($first_page_url)
    {
        $array_of_page_links = array();
        $html = file_get_html($first_page_url);
        $current_page_link = $first_page_url;
        $next_page_link = $html->find('div[class="mod-page center"] a[rel="next"]',0)->href;
        //array_push($array_of_page_links,$current_page_link);
        while(!empty($next_page_link) && $current_page_link != $next_page_link)
        {
            array_push($array_of_page_links,$current_page_link);
            $html = file_get_html($next_page_link);
            $current_page_link = $next_page_link;
            $next_page_link = $html->find('div[class="mod-page center"] a[rel="next"]',0)->href;
        }
        array_push($array_of_page_links,$current_page_link);
        return $array_of_page_links;

    }

    //将写入数据库
    function convert_array_to_sql($card_properities,$method)
    {
        //$conn = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
        //mysql_select_db("app_hearthstone", $conn);
        if($method == "insert")
        {
        $sql = "INSERT INTO cards (cn_name,en_name,occupation,rarity,type,cost,hp,atk,skill,description,url) VALUES(
'".$card_properities[0]."',
'".$card_properities[1]."',
'".$card_properities[2]."',
'".$card_properities[3]."',
'".$card_properities[4]."',
'".$card_properities[5]."',
'".$card_properities[6]."',
'".$card_properities[7]."',
'".$card_properities[8]."',
'".$card_properities[9]."',
'".$card_properities[10]."')";
        }elseif($method == "update")
        {
            $sql = "UPDATE cards SET 
            cn_name='".$card_properities[0]."',
            occupation='".$card_properities[2]."',
            rarity='".$card_properities[3]."',
            type='".$card_properities[4]."',
            cost='".$card_properities[5]."',
            hp='".$card_properities[6]."',
            atk='".$card_properities[7]."',
            skill='".$card_properities[8]."',
            description='".$card_properities[9]."',
            url='".$card_properities[10]."' 
            where en_name='".$card_properities[1]."'";
            
        }
        return $sql;

    }

    function pic_to_storage($filename, $fileurl)
    {
        $f = new SaeFetchurl();
        $fileurl = str_replace(" ","%20",urldecode($fileurl));
        $img_data = $f->fetch($fileurl);
        //$img_data = file_get_contents($fileurl);
        $destFileName = "saestor://lushipic/".$filename.".png";
        file_put_contents($destFileName, $img_data);

        //var_dump($fileurl);
        //$img = new SaeImage();
        //$img->setData( $img_data );
        //$img->resize(200); // 等比缩放到200宽
        //$img->flipH(); // 水平翻转
        //$img->flipV(); // 垂直翻转
        //$new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
        //$img_data = file_get_contents($fileurl);
        //$storage = new SaeStorage();
        //$domain = 'lushipic';
        //$attr = array('encoding'=>'gzip');
        //$destFileName = $filename.".png";
        //$result = $storage->write($domain,$destFileName, $img_data);
        //if($result)
        //{
        //    echo "保存成功！！"."\n";
        //}else{
        //    echo "保存失败！！"."\n";
        //}

    }



}



?>

