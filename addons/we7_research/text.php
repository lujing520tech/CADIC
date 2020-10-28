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
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);

        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }

    function testAction()
    {
        $url = 'http://172.19.183.123:8001/hello/';
        $post_data['data'] = 'data="1:用饮食帮助睡眠（例如：喝牛奶等）,1:非常满意（非常适合睡眠）,1:很快能入睡（30分钟以内）,1:非常好（7小时以上）,1:基本不醒,1:非常满意,1:不做梦,1:精神百倍，感觉很好1:睡眠类药物,images/2/2019/04/7.jpg';
//        $post_data['answer'] = '4|不太满意（环境不利于睡眠）2|需要一些时间才能入睡（1小时左右）3|不觉得疲惫，感觉尚可3|用养生方式帮助睡眠（例如：泡脚等）5|没有服药';
        $o = "";
        foreach ($post_data as $k => $v) {
            $o .= "$k=" . urlencode($v) . "&";
        }
        $post_data = substr($o, 0, -1);
        $res = request_post($url, $post_data);
//        print_r($res);
        return $res;
    }

        $arr = testAction();
        print_r($arr);
//      function text123(){
//      return 324;
//}
//
//      echo text123();


