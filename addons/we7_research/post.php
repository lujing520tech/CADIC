<?php
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
//        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }
class post{
    function testAction($url,$post_data)
    {

        $o = "";
        foreach ($post_data as $k => $v) {
            $o .= "$k=" . urlencode($v) . "&";
        }
        $post_data = substr($o, 0, -1);
        $res = request_post($url, $post_data);
//        print_r($res);
        return $res;
    }
}
//     json_decode($res,true);
//    print_r($hd);
//
//$str= testAction();
//echo $str;
//function text123(){
//  return 324;
//}
//echo 123;
//echo text123();

