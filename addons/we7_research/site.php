<?php

/**
 * 预约与调查模块微站定义
 * http://wx.iik-tech.com/app/./index.php?i=2&c=entry&eid=47
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&id=4&do=research&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=myresearch&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&op=detail&do=myresearch&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=myresearch&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=myremark&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=myp6_detail&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=mytime_axis&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=myPersonal_Data&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=mylist_question&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=mynewshetai&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=myyiyu&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=mywechat&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=mysleep&m=we7_research
 * http://wx.iik-tech.com/app/index.php?i=2&c=entry&do=mysleepresult&m=we7_research
 * @author WeEngine Team
 * @url http://bbs.we7.cc
 */
defined('IN_IA') or exit('Access Denied');



class We7_researchModuleSite extends WeModuleSite {

    public function getHomeTiles() {
        global $_W;
        $urls = array();
        $list = pdo_fetchall("SELECT title, reid FROM " . tablename('research') . " WHERE weid = '{$_W['uniacid']}'");
        if (!empty($list)) {
            foreach ($list as $row) {
                $urls[] = array('title' => $row['title'], 'url' => $_W['siteroot']."app/".$this->createMobileUrl('research', array('id' => $row['reid'])));
            }
        }
        return $urls;
    }
    //菜单管理
    public function doWebVegetable()
    {

        global $_W;
        global  $_GPC;
        $url = $this->createMobileUrl('Brief');

        if($_GPC['type']=='delete'){
            //删除数据
            pdo_delete('shch_vegetable',['id'=>$_GPC['id']]);
        }
        //查找分类


        $nickname = isset($_GPC["search"]["nickname"]) && $_GPC["search"]["nickname"] ? trim($_GPC["search"]["nickname"]) : "";
        $bool = false;
        if($_W['ispost']){
            $bool = true;
            $classify = $_GPC['classify'];
            if($classify!=100){
                $where = " WHERE  vegetable_title LIKE  '%$nickname%' and vegetable_type = {$classify} " ;
            }else{
                $where = " WHERE vegetable_title  LIKE  '%$nickname%'" ;
            }
        }else{
            $total = pdo_fetchcolumn('SELECT COUNT(1) FROM '.tablename('shch_vegetable'));
        }
        //获取页数
        $pindex = max(1, intval($_GPC['page']));
        //获取页行数
        $psize = 10;
        $sql1= "  limit " . ($pindex - 1) * $psize . ',' . $psize;
        $search_sql = "select * from ".tablename('shch_vegetable').$where.$sql1;

        $sql = "select * from ".tablename('shch_vegetable').$sql1;
        $data       = pdo_fetchall($bool?$search_sql:$sql);
        foreach ($data as $k=>$v){
            $img_url = pdo_getall('shch_images',['img_pgth_id'=>$v['id']]);
            $data[$k]['images'] = $img_url;
        }
        //分页
        $pager = pagination($total, $pindex, $psize);

        include $this->template('vegetable_list');
    }
    public function doWebVegetableAction()
    {
        global $_W;
        global $_GPC;

        $id = $_GPC['id'];
        $type = $_GPC['type'];
        $data = $_GPC['vegetable'];
        $vegetable = pdo_get('shch_vegetable',['id'=>$id]);

        if($_W['ispost']){
            if($type=='add'){
                //添加

                $data['add_time'] =time();
                $insert = pdo_insert('shch_vegetable',$data);
                if(!empty($insert)){
                    $vid = pdo_insertid();
                    foreach ($_GPC['image'] as $v){
                       pdo_insert('shch_images',['img_url'=>tomedia($v),'img_type'=>1,'img_pgth_id'=>$vid]);
                    }
                }
            }else{
                //编辑
                if(!empty($_GPC['image'])){
                    //删除图片

                    //删除数据库
                       pdo_delete('shch_images',['img_pgth_id'=>$id]);
                }
                $updata = pdo_update('shch_vegetable',$data);
                if(!empty($updata)){
                    foreach ($_GPC['image'] as $v){
                       pdo_insert('shch_images',['img_url'=>tomedia($v),'img_type'=>1,'img_pgth_id'=>$id]);
                    }
                }
            }
        }

        include $this->template('vegetable_action');
    }







    //前台

    /**
     * 简介
     */
    public function doMobileBrief()
    {
        include $this->template('brief');
    }
    public function doMobileCaiDan()
    {
        include $this->template('caidan');
    }


    public function doMobileMyyiyu(){
        global $_W, $_GPC;
        $id = ($_W['openid']);
        $a= pdo_fetch("SELECT * FROM ".tablename('_rows') . " WHERE openid = '$id' and status ='1' order by rerid desc ");
        $b=json_decode($a['result'],true);
        print_r( $b);

        include $this->template('yiyu');
    }
    public function doMobileMywechat(){
        global $_W, $_GPC;

        include $this->template('wechat');
    }
}

