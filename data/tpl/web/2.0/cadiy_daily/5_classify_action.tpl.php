<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            添加icon主题
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" onsubmit="return OnSubimt()">
            <div class="form-group">
                <label for="lastname1" class="col-sm-2 control-label">选择类型</label>
                <div class="col-sm-10">
                    <select name="theme[theme_type]" id="lastname1">
                        <option value="1" <?php echo $theme['theme_type']==1? "selected":'' ?>  >做为课程分类</option>
                        <option value="2" <?php echo $theme['theme_type']==1? "selected":'' ?> >做为信息页面</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label">主题名称</label>
                <div class="col-sm-10">
                    <input type="text" name="theme[name]" value="<?php  echo $theme['name'];?>" class="form-control" id="title" placeholder="主题名称" >
                </div>
            </div>
            <div class="form-group">
                <label for="location" class="col-sm-2 control-label">主题排序（倒序）</label>
                <div class="col-sm-10">
                    <input type="text" style="width: 300px" name="theme[sort]" value="<?php  echo $theme['sort'];?>" class="form-control" id="location" placeholder="主题排序（倒序）">
                </div>
            </div>

            <div class="form-group">
                <label for="music_time" class="col-sm-2 control-label">主题icon图片</label>
                <div class="col-sm-10">
                    <?php  echo tpl_form_field_image('image',$theme['img_url'], $default = '', $options = array())?>
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