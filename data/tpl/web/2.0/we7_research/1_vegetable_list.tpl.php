<?php defined('IN_IA') or exit('Access Denied');?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" href="<?php  echo $_W['siteroot']?>addons/juchuang_dinosaur/static/layui/css/layui.css" media="all">


<form class="form-inline" style="padding-bottom: 20px;" action="" method="post">
    <a class="btn btn-info  btn-danger" href="<?php  echo $this->createWebUrl('VegetableAction',['type'=>'add'])?>" >添加菜单</a>
    <div class="form-group">
        <label>菜单标题</label>
        <input type="text" name="search[nickname]" value="<?php  echo $nickname;?>" class="form-control" autocomplete="off">
    </div>
    <select name="classify" id="classify"  class="form-control" style="width: 150px">
        <option value="100">请选择类型</option>
        <option value="1">早餐菜单</option>
        <option value="2">午餐菜单</option>
        <option value="3">晚餐菜单</option>
    </select>
    <button type="submit" class="btn btn-primary">查询</button>
    <a class="btn btn-info" href="<?php  echo $this->createWebUrl('Vegetable')?>">显示全部</a>

    <!--<button class="btn btn-danger pull-right" name="derive" value="derive">导出全部数据</button>-->
    <?php  if(!empty($data)) { ?>
</form>
<table class="table table-bordered table-hover">
    <tr>
        <th class="text-center">id</th>
        <th class="text-center">菜单标题</th>
        <th class="text-center">菜单类型</th>
        <th class="text-center">菜单图片</th>
        <th class="text-center">菜单内容</th>
        <th class="text-center">添加时间</th>

        <th class="text-center">操作</th>
    </tr>
    <?php  if(is_array($data)) { foreach($data as $k => $v) { ?>
    <tr class="text-center">
        <td><?php  echo $v['id'];?></td>
        <td ><?php  echo $v['vegetable_title'];?></td>
        <td>
           <?php  echo $v['vegetable_type'];?>
        </td>
        <td>
            <?php  if(is_array($v['images'])) { foreach($v['images'] as $k1 => $v1) { ?>
            <img src="<?php  echo $v1['img_url'];?>" alt="" width="70">
            <?php  } } ?>
        </td>
        <td><?php  echo $v['content'];?></td>
        <td>
            <?php  echo $v['join_time'];?>
        </td>

        <td>
            <a href="<?php  echo $this->createWebUrl('VegetableAction',['id'=>$v['id'],'type'=>'compile'])?>" class="btn btn-primary">编辑</a>
            <a href="<?php  echo $this->createWebUrl('Vegetable',['id'=>$v['id'],'type'=>'delete'])?>" class="btn btn-primary btn-danger">删除</a>
        </td>
    </tr>
    <?php  } } ?>
</table>
<?php  if($nickname=="") { ?>
<?php  echo $pager;?>
<?php  } ?>
<?php  } else { ?>
<div class="help-block text-center">暂无数据</div>
<?php  } ?>
<div id="DetailTest" style="display: none">
    <div style="margin: 3% auto;text-align: center">
        <img   id="img" style="width: 400px;height: 300px;margin: 0 auto" src="" alt="">
    </div>
    <div>
        <span id="result2"></span>
    </div>
</div>
<script src="<?php  echo $_W['siteroot']?>addons/juchuang_dinosaur/static/layer2/layer.js"></script>
<script>
    function OnDetail(id)
    {
        console.log(id);

        console.log($('#lo_'+id).val());
        layer.open({
            type: 2,
            area: ['700px', '450px'],
            fixed: false, //不固定
            maxmin: true,
            content: 'https://blog.csdn.net/xuebaibaibai/article/details/81052570'
        });
    }

</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
