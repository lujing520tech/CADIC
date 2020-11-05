<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/4
 * Time: 13:05
 */
defined('IN_IA') or exit('Access Denied');
class Music extends WeModulePhoneapp
{
    public function getMusic($musicName)
    {
        global $_GPC, $_W;
        $sql = "select * from ".tablename('tianma_music_classify')." where classify_name  LIKE '%{$musicName}%'";
        $classifyArr = pdo_fetch($sql);
        return $classifyArr;

    }
}