<?php
class post_{
    function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }

    function testAction()
    {
        $url = 'http://172.19.183.123:8000/hello/';
//    session_start();
//    for($i=0;$i<3;$i++)
//    {
//        echo $_SESSION['temp'][$i];
//    }
//    return;
        $post_data['img'] = 'images/2/2019/05/B2866EZ57IJyHcoV7812Hhoe21VtJs.jpg';
        $o = "";
        foreach ($post_data as $k => $v) {
            $o .= "$k=" . urlencode($v) . "&";
        }
        $post_data = substr($o, 0, -1);
        $res = request_post($url, $post_data);
        print_r($res);

    }
}

$str= testAction();
//echo $str;
//function text123(){
//  return 324;
//}
//echo 123;
//echo text123();


//将收到的数据插入数据库   表ims_research_rows 字段result 根据oppenid
//$res = $pdo->exec("insert into xc_research_data(name) values('测试添加111')");
//if($res){
//    echo '11添加成功数据ID为：'.$pdo->lastinsertid().'<br/>';
//}
//$res = $pdo->query("insert into xc_company(name) values('小川编程添加222')");
//if($res){
//    echo '22添加成功数据ID为：'.$pdo->lastinsertid().'<br/>';
//}
