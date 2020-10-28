<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            添加菜单
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" onsubmit="return OnSubimt()">
            <div class="form-group">
                <label for="lastname1" class="col-sm-2 control-label">选择添加类型</label>
                <div class="col-sm-10">
                    <select name="vegetable[vegetable_type]" id="lastname1">
                        <option value="0"  >请选择</option>
                        <option value="1" <?php echo $vegetable['vegetable_type']!=1? "selected":'' ?> >早餐菜单</option>
                        <option value="2" <?php echo $vegetable['vegetable_type']!=2? "selected":'' ?> >午餐菜单</option>
                        <option value="3" <?php echo $vegetable['vegetable_type']!=3? "selected":'' ?> >晚餐菜单</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label">菜单标题</label>
                <div class="col-sm-10">
                    <input type="text" name="vegetable[vegetable_title]" value="<?php  echo $vegetable['vegetable_title'];?>" class="form-control" id="title" placeholder="请输音乐名称" >
                </div>
            </div>
            <div class="form-group">
                <label for="location" class="col-sm-2 control-label">菜单内容</label>
                <div class="col-sm-10">
                    <input type="text" name="vegetable[content]" value="<?php  echo $vegetable['content'];?>" class="form-control" id="location" placeholder="请输音乐链接">
                </div>
            </div>

            <div class="form-group">
                <label for="music_time" class="col-sm-2 control-label">菜单图片</label>
                <div class="col-sm-10">
                    <?php  echo tpl_form_field_multi_image('image');?>
                </div>
                </div>
            </div>


            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="hidden" name="type" value="<?php  echo $type;?>">
                    <input type="hidden" name="id" value="<?php  echo $id;?>">
                    <button type="submit" class="btn btn-primary"
                    > 提交
                    </button>
                    <button type="button"  onclick="OnCancel()"  class="btn btn-primary"
                            data-toggle="button"> 取消
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
<script>
    $(function () {
        // $('.img-responsive').attr('src',<?php  echo $share_data['image'];?>);
    });




</script>







<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>