<?php defined('IN_IA') or exit('Access Denied');?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" href="<?php  echo $_W['siteroot']?>addons/juchuang_dinosaur/static/layui/css/layui.css" media="all">


<form class="form-inline" style="padding-bottom: 20px;" action="" method="post">
    <a class="btn btn-info  btn-danger" href="<?php  echo $this->createWebUrl('AdvertisingAction',['type'=>'add'])?>" >添加菜单</a>
    <div class="form-group">
        <label>菜单标题</label>
        <input type="text" name="search[nickname]" value="<?php  echo $nickname;?>" class="form-control" autocomplete="off">
    </div>
    <select name="classify" id="classify"  class="form-control" style="width: 150px">
        <option value="100">请选择类型</option>
        <option value="1">首页轮播图</option>
        <option value="2">首页广告位</option>
        <option value="3">底部banner图</option>
        <option value="4">个人中心广告位</option>
    </select>
    <button type="submit" class="btn btn-primary">查询</button>
    <a class="btn btn-info" href="<?php  echo $this->createWebUrl('Advertising')?>">显示全部</a>

    <!--<button class="btn btn-danger pull-right" name="derive" value="derive">导出全部数据</button>-->
    <?php  if(!empty($data)) { ?>
</form>
<table class="table table-bordered table-hover">
    <tr>

        <th class="text-center">id</th>
        <th class="text-center">广告名称</th>
        <th class="text-center">广告跳转url</th>
        <th class="text-center">广告类型</th>
        <th class="text-center">广告排序（倒序，从小到大）</th>
        <th class="text-center">广告图片</th>
        <th class="text-center">添加时间</th>
        <th class="text-center">是否显示</th>

        <th class="text-center">操作</th>
    </tr>
    <?php  if(is_array($data)) { foreach($data as $k => $v) { ?>
    <tr class="text-center">
        <td><?php  echo $v['id'];?></td>
        <td ><?php  echo $v['name'];?></td>
        <td ><?php  echo $v['url'];?></td>
        <td>

            <?php  if($v['adv_type']==1) { ?>
            <span style="color: red">首页轮播图</span>

            <?php  } else if($v['adv_type']==2) { ?>
            <span style="color: #0BB20C">首页广告位</span>
            <?php  } else if($v['adv_type']==3) { ?>
            <span style="color: blue">底部banner图</span>

            <?php  } else if($v['adv_type']==4) { ?>
            <span> 个人中心广告位</span>

            <?php  } ?>
        </td>
        <td><?php  echo $v['sort'];?></td>
        <td>
            <?php  if($v['from'] == 1) { ?>
            <img src="<?php  echo $v['img_url'];?>" alt="" width="70">
            <?php  } else { ?>
            <img src="<?php  echo $_W['attachurl_local']?>/<?php  echo $v['img_url'];?>" alt="" width="70">
            <?php  } ?>
        </td>

        <td>
            <?php  echo $v['add_time'];?>
        </td>

        <td><?php  if($v['state']==1) { ?>
            <a class="label label-success" href="<?php  echo $this->createWebUrl('Advertising',['show'=>0,'id'=>$v['id'],'type'=>'state'])?>" title="点击隐藏菜单">显示中</a>
            <?php  } else { ?>
            <a class="label label-danger" href="<?php  echo $this->createWebUrl('Advertising',['show'=>1,'id'=>$v['id'],'type'=>'state'])?>" title="点击显示菜单">隐藏</a>
            <?php  } ?>
        </td>

        <td>
            <a href="<?php  echo $this->createWebUrl('VegetableAction',['id'=>$v['id'],'type'=>'compile'])?>" class="btn btn-primary">编辑</a>
            <a href="<?php  echo $this->createWebUrl('Advertising',['id'=>$v['id'],'type'=>'delete'])?>" class="btn btn-primary btn-danger">删除</a>
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
