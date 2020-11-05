<?php
/**
 * western_app模块APP接口定义
 *
 * @author lujing520
 * @url
 */

defined('IN_IA') or exit('Access Denied');

class Western_appModulePhoneapp extends WeModulePhoneapp {
	public function doPageTest(){
		global $_GPC, $_W;
		$errno = 0;
		$message = '返回消息';
		$data = array();
		return $this->result($errno, $message, $data);
	}

    /**
     * 机器人接口(音乐)
     */
    public function doPageMusic()
    {
        global $_W, $_GPC;
        $name = '你好,我是你爸爸';
        $post = '你好';
        $result = strstr($name,$post);
        echo $result;
        die;
        $sji = pdo_getall('tianma_music');

        $musicName = $_GPC['musicName'];

        $sql = "select * from ".tablename('tianma_music_classify');
        $classifyArr = pdo_fetch($sql);

        if( !empty($classifyArr)){
            $where = " where music_classify = ".$classifyArr['id'];
        }else{
            foreach ($sji as $v){
                $bool = strstr($musicName,$v['music_name']);
                if($bool){
                    $where = " where music_name like '%{$bool}%'";
                    break;
                }else{
                    //查作者
                    $bool_author = strstr($musicName,$v['author']);
                    if($bool_author){
                        $musicArr = pdo_getall('tianma_music',['author'=>$bool_author]);

                        load()->func('logging');
//记录文本日志
                        logging_run(json_encode($musicArr));
                        $arr = [];
                        foreach ($musicArr as $k=>$v1){
                            $arr[$k]['id'] = $v1['id'];
                            $arr[$k]['music_name'] = $v1['music_name'];
                            $arr[$k]['music_url'] = trim($v1['music_url']);
                            $arr[$k]['music_classify'] = $v1['music_classify'];
                            $arr[$k]['time'] = $v1['time'];
                            $arr[$k]['music_time'] = $v1['music_time'];
                            $arr[$k]['music_image'] = $v1['music_image'];
                            $arr[$k]['author'] = $v1['author'];
                            $arr[$k]['tishi'] = $v1['tishi'];
                        }
                        return $this->result(0,'请求成功',$arr);
                        break;
                    }else{
                        $where = " where music_name like '%{$musicName}%'";
                    }
                }
            }
        }
        $sql2 = "select * from ".tablename('tianma_music').$where;
        $musicArr = pdo_fetchall($sql2);

        if(empty($musicArr)){
            $rand_num =  rand(1,count($sji));
            $sji[$rand_num]['tishi'] = "未查找到，为您推荐". $sji[$rand_num]['music_name'];
            $musicArr[0] = $sji[$rand_num];
        }

        load()->func('logging');
//记录文本日志
        logging_run(json_encode($musicArr));

//记录数组数据
        $arr = [];
        foreach ($musicArr as $k=>$v){
            $arr[$k]['id'] = $v['id'];
            $arr[$k]['music_name'] = $v['music_name'];
            $arr[$k]['music_url'] = trim($v['music_url']);
            $arr[$k]['music_classify'] = $v['music_classify'];
            $arr[$k]['time'] = $v['time'];
            $arr[$k]['music_time'] = $v['music_time'];
            $arr[$k]['music_image'] = $v['music_image'];
            $arr[$k]['author'] = $v['author'];
            $arr[$k]['tishi'] = $v['tishi'];
        }
//        logging_run(array('username' => '米粥', 'age' => '18'));

        return $this->result(0,'请求成功',$arr);
    }

	
	
}