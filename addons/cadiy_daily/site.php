<?php
/**
 * cadiy_daily模块微站定义
 *
 * @author lujing520
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Cadiy_dailyModuleSite extends WeModuleSite {


    public function doWebAdvertising() {
        global $_W;
        global  $_GPC;

        if($_GPC['type']=='delete'){
            //删除数据
            pdo_delete('cadic_adv',['id'=>$_GPC['id']]);
        }
        if($_GPC['type'] == 'state'){
            pdo_update('cadic_adv',['state'=>$_GPC['show']],['id'=>$_GPC['id']]);
        }
        //查找分类


        $nickname = isset($_GPC["search"]["nickname"]) && $_GPC["search"]["nickname"] ? trim($_GPC["search"]["nickname"]) : "";
        $bool = false;
        if($_W['ispost']){
            $bool = true;
            $classify = $_GPC['classify'];
            if($classify!=100){
                $where = " WHERE  name LIKE  '%$nickname%' and adv_type = {$classify}  order by id desc " ;
            }else{
                $where = " WHERE name  LIKE  '%$nickname%' order by id desc " ;
            }
        }else{
            $total = pdo_fetchcolumn('SELECT COUNT(1) FROM '.tablename('cadic_adv'));
        }
        //获取页数
        $pindex = max(1, intval($_GPC['page']));
        //获取页行数
        $psize = 10;
        $sql1= "  limit " . ($pindex - 1) * $psize . ',' . $psize;
        $search_sql = "select * from ".tablename('cadic_adv').$where.$sql1;

        $sql = "select * from ".tablename('cadic_adv').' order by id desc'.$sql1;

        $data       = pdo_fetchall($bool?$search_sql:$sql);

        //分页
        $pager = pagination($total, $pindex, $psize);

        include $this->template('advertising');

    }

    /**
     * 定义广告的操作方法
     */

    public function doWebAdvertisingAction()
    {
        global $_W;
        global  $_GPC;
        $type = $_GPC['type'];
        if($_W['ispost']){
            if($type == 'add'){
                //添加操作
                $data = $_GPC['adv'];
                $data['img_url'] = $_GPC['image'];
                $data['from'] = 0;//上传本地
                $insert = pdo_insert('cadic_adv',$data);
                if(!$insert){
                    message("操作失败", $this->createWebUrl("Advertising"),'error ');
                }
                message("操作成功", $this->createWebUrl("Advertising"),'success ');
            }
            else{
                //编辑
                if(!empty($_GPC['image'])){
                    //删除图片
                }
                $data = $_GPC['adv'];
                $data['from'] = 0;//上传本地
                $data['img_url'] = $_GPC['image'];
                $update = pdo_update('cadic_adv',$data);
                if(!$update){
                    message("操作失败", $this->createWebUrl("Advertising"),'error ');
                }
                message("操作成功", $this->createWebUrl("Advertising"),'success ');

            }
        }



        include $this->template('advertising_action');
    }



    public function doWebAudit() {
        //这个操作被定义用来呈现 管理中心导航菜单
    }
    public function doWebCourse() {
        //这个操作被定义用来呈现 管理中心导航菜单
    }

    /**
     * 发布课程
     */
    public function doWebIssue() {
        global $_W;
        global  $_GPC;
        $classifyArr = pdo_getall('cadic_classify',['theme_type'=>1]);
        $type = $_GPC['type']?$_GPC['type']:'add';

        if($_W['ispost']){
            if($type == 'add'){
                //添加操作
                $data = $_GPC['goods'];
                $data['goods_img'] = $_GPC['image'];
                $data['from'] = 0;//上传本地
                $insert = pdo_insert('cadic_goods',$data);
                if(!$insert){
                    message("操作失败", $this->createWebUrl("Advertising"),'error ');
                    }
                $insertId = pdo_insertid();
                foreach ($_GPC['imageAll'] as $v){//批量上传轮播图
                    $imagesArr = [
                        'img_url' => $v,
                        'from' => 0,//本地
                        'path_id' => $insertId,
                    ];
                    $insertImage = pdo_insert('cadic_image',$imagesArr);
                    if(!$insertImage){
                        message("操作失败", $this->createWebUrl("Advertising"),'error ');
                        break;
                    }
                }
                message("操作成功", $this->createWebUrl("Advertising"),'success ');
            }
            else{
                //编辑
                if(!empty($_GPC['image'])){
                    //删除图片
                }
                if(!empty($_GPC['imageAll'])){
                    //删除轮播图
                }

                $data = $_GPC['goods'];
                $data['from'] = 0;//上传本地
                $data['goods_img'] = $_GPC['image'];
                $update = pdo_update('cadic_goods',$data);
                if(!$update){
                    message("操作失败", $this->createWebUrl("Advertising"),'error ');
                }
                $insertId = pdo_insertid();
                foreach ($_GPC['imageAll'] as $v) {//批量上传轮播图
                    $imagesArr = [
                        'img_url' => $v,
                        'from' => 0,//本地
                        'path_id' => $insertId,
                    ];
                    $insertImage = pdo_insert('cadic_image', $imagesArr);
                    if (!$insertImage) {
                        message("操作失败", $this->createWebUrl("Advertising"), 'error ');
                        break;
                    }
                }
                message("操作成功", $this->createWebUrl("Advertising"),'success ');

            }
        }

        include $this->template('issue');

    }

    /**
     * 我的课程
     */
    public function doWebMyCourse() {
        global $_W;
        global  $_GPC;

        if($_GPC['type']=='delete'){
            //删除数据
            pdo_delete('cadic_classify',['id'=>$_GPC['id']]);
        }
        if($_GPC['type'] == 'state'){
            pdo_update('cadic_classify',['state'=>$_GPC['show']],['id'=>$_GPC['id']]);
        }
        //查找分类


        $nickname = isset($_GPC["search"]["nickname"]) && $_GPC["search"]["nickname"] ? trim($_GPC["search"]["nickname"]) : "";
        $bool = false;
        if($_W['ispost']){
            $bool = true;
            $classify = $_GPC['classify'];
            if($classify!=100){
                $where = " WHERE  name LIKE  '%$nickname%' and theme_type = {$classify}  order by id desc " ;
            }else{
                $where = " WHERE name  LIKE  '%$nickname%' order by id desc " ;
            }
        }else{
            $total = pdo_fetchcolumn('SELECT COUNT(1) FROM '.tablename('cadic_classify'));
        }
        //获取页数
        $pindex = max(1, intval($_GPC['page']));
        //获取页行数
        $psize = 10;
        $sql1= "  limit " . ($pindex - 1) * $psize . ',' . $psize;
        $search_sql = "select * from ".tablename('cadic_classify').$where.$sql1;

        $sql = "select * from ".tablename('cadic_classify').' order by id desc'.$sql1;

        $data       = pdo_fetchall($bool?$search_sql:$sql);

        //分页
        $pager = pagination($total, $pindex, $psize);

        include $this->template('issue_list');
    }

    /**
     * 课程分类管理
     */
    public function doWebClassify() {
        global $_W;
        global  $_GPC;

        if($_GPC['type']=='delete'){
            //删除数据
            pdo_delete('cadic_classify',['id'=>$_GPC['id']]);
        }
        if($_GPC['type'] == 'state'){
            pdo_update('cadic_classify',['state'=>$_GPC['show']],['id'=>$_GPC['id']]);
        }
        //查找分类


        $nickname = isset($_GPC["search"]["nickname"]) && $_GPC["search"]["nickname"] ? trim($_GPC["search"]["nickname"]) : "";
        $bool = false;
        if($_W['ispost']){
            $bool = true;
            $classify = $_GPC['classify'];
            if($classify!=100){
                $where = " WHERE  name LIKE  '%$nickname%' and theme_type = {$classify}  order by id desc " ;
            }else{
                $where = " WHERE name  LIKE  '%$nickname%' order by id desc " ;
            }
        }else{
            $total = pdo_fetchcolumn('SELECT COUNT(1) FROM '.tablename('cadic_classify'));
        }
        //获取页数
        $pindex = max(1, intval($_GPC['page']));
        //获取页行数
        $psize = 10;
        $sql1= "  limit " . ($pindex - 1) * $psize . ',' . $psize;
        $search_sql = "select * from ".tablename('cadic_classify').$where.$sql1;

        $sql = "select * from ".tablename('cadic_classify').' order by id desc'.$sql1;

        $data       = pdo_fetchall($bool?$search_sql:$sql);

        //分页
        $pager = pagination($total, $pindex, $psize);

        include $this->template('classify');
    }

    /**
     * 分类操作
     */
    public function doWebClassifyAction()
    {
        global $_W;
        global  $_GPC;
        $type = $_GPC['type'];
        $data = $_GPC['theme'];
        if($_W['ispost']){
            if($type == 'add'){
                //添加操作

                $data['img_url'] = $_GPC['image'];
                $data['from'] = 0;//上传本地
                $insert = pdo_insert('cadic_classify',$data);
                if(!$insert){
                    message("操作失败", $this->createWebUrl("Advertising"),'error ');
                }
                message("操作成功", $this->createWebUrl("Advertising"),'success ');
            }
            else{
                //编辑
                if(!empty($_GPC['image'])){
                    //删除图片
                }
                $data['from'] = 0;//上传本地
                $data['img_url'] = $_GPC['image'];
                $update = pdo_update('cadic_classify',$data);
                if(!$update){
                    message("操作失败", $this->createWebUrl("Classify"),'error ');
                }
                message("操作成功", $this->createWebUrl("Classify"),'success ');

            }
        }

        include $this->template('classify_action');
    }


    public function doWebNurserySchool() {
        //这个操作被定义用来呈现 管理中心导航菜单
    }
    public function doWebOrder() {
        //这个操作被定义用来呈现 管理中心导航菜单
    }


}