<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>


<style>
    .price{
        position: relative;
        bottom: 30px;
        left: 200px;
        margin-bottom: 0;
    }
    .price_s{
        position: relative;
        top: 33px;

        margin-bottom: 0;
    }
</style>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            添加课程
        </h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" onsubmit="return OnSubimt()">
            <div class="form-group">
                <label for="iusse_title" class="col-sm-2 control-label">课程名称</label>
                <div class="col-sm-10">
                    <input type="text" name="goods[name]" value="<?php  echo $goods['name'];?>" class="form-control" id="iusse_title" placeholder="课程名称" >
                </div>
            </div>
            <div class="form-group">
                <label for="introduce" class="col-sm-2 control-label">课程简介</label>
                <div class="col-sm-10">
                    <input type="text" name="goods[brief]" value="<?php  echo $goods['brief'];?>" class="form-control" id="introduce" placeholder="课程简介" >
                </div>
            </div>
            <div class="form-group price_s">
                <label for="priceOld" class="col-sm-2 control-label">原价格</label>
                <div class="col-sm-10">
                    <input type="text" style="width: 80px" name="goods[old_price]" value="<?php  echo $goods['old_price'];?>" class="form-control" id="priceOld" placeholder="原价格" >
                </div>
            </div>

            <div class="form-group price">
                <label for="priceNew" class="col-sm-2 control-label">优惠价格</label>
                <div class="col-sm-10">
                    <input type="text" style="width: 80px" name="goods[new_price]" value="<?php  echo $goods['new_price'];?>" class="form-control" id="priceNew" placeholder="原价格" >
                </div>
            </div>

            <div class="form-group">
                <label for="lastname1" class="col-sm-2 control-label">所属主题</label>
                <div class="col-sm-10">
                    <select name="goods[classify_id]" id="lastname1">
                        <option value="0"  >请选择</option>
                        <?php  if(is_array($classifyArr)) { foreach($classifyArr as $k => $v) { ?>
                        <option value="<?php  echo $v['id'];?>"><?php  echo $v['name'];?></option>
                        <?php  } } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="img" class="col-sm-2 control-label">课程主图</label>
                <div class="col-sm-10">
                    <?php  echo tpl_form_field_image('image',$goods['img_url'], $default = '', $options = array())?>
                </div>
            </div>
            <div class="form-group">
                <label for="img" class="col-sm-2 control-label">课程轮播图 </label>
                <div class="col-sm-10" >
                    <?php  echo tpl_form_field_multi_image('imageAll');?>
                </div>
            </div>

            <div class="form-group">
                <label for="content" class="col-sm-2 control-label">课程详情内容</label>
                <div class="col-sm-10">
                    <?php  echo tpl_ueditor('goods[content]',$goods['content']);?>
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