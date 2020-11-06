<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            添加广告
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" onsubmit="return OnSubimt()">
            <div class="form-group">
                <label for="lastname1" class="col-sm-2 control-label">选择添加类型</label>
                <div class="col-sm-10">
                    <select name="adv[adv_type]" id="lastname1">
                        <option value="0"  >请选择</option>
                        <option value="1" <?php echo $adv['vegetable_type']==1? "selected":'' ?> >首页轮播图</option>
                        <option value="2" <?php echo $adv['vegetable_type']==2? "selected":'' ?> >首页广告位</option>
                        <option value="3" <?php echo $adv['vegetable_type']==3? "selected":'' ?> >底部banner图</option>
                        <option value="4" <?php echo $adv['vegetable_type']==4? "selected":'' ?> >个人中心广告位</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label">广告名称</label>
                <div class="col-sm-10">
                    <input type="text" name="adv[name]" value="<?php  echo $adv['name'];?>" class="form-control" id="title" placeholder="广告名称" >
                </div>
            </div>
            <div class="form-group">
                <label for="location1" class="col-sm-2 control-label">广告跳转url</label>
                <div class="col-sm-10">
                    <input type="text" name="adv[url]" value="<?php  echo $adv['url'];?>" class="form-control" id="location1" placeholder="广告跳转url">
                </div>
            </div>
            <div class="form-group">
                <label for="location" class="col-sm-2 control-label">广告排序（倒序，从小到大）</label>
                <div class="col-sm-10">
                    <input type="text" name="adv[sort]" value="<?php  echo $adv['sort'];?>" class="form-control" id="location" placeholder="菜单内容">
                </div>
            </div>

            <div class="form-group">
                <label for="music_time" class="col-sm-2 control-label">广告图片</label>
                <div class="col-sm-10">
                    <?php  echo tpl_form_field_image('image',$adv['img_url'], $default = '', $options = array())?>
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

    $('#che').click(function () {
        console.log(1212112);
        console.log($(this).attr('checked'));
        if($(this).attr('checked')!='checked'){
            console.log(111111)
            $('#time').attr('display','none')
        }else{
            console.log(22222)
            $('#time').attr('display','block')
        }
    })


</script>







<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>