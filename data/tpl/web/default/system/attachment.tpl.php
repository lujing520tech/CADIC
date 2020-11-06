<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="we7-page-tab">
	<li <?php  if($do == 'global') { ?>class="active"<?php  } ?>><a href="<?php  echo url('system/attachment/global')?>">全局设置</a></li>
	<li <?php  if($do == 'remote') { ?>class="active"<?php  } ?>><a href="<?php  echo url('system/attachment/remote')?>">远程附件</a></li>
</ul>
<?php  if($do == 'global') { ?>
<div class="alert we7-page-alert">
	<p><i class="wi wi-info-sign"></i>全局设置只对上传到服务器的文件有效</p>
</div>
<div class="clearfix" id="js-global-attachment" ng-controller="globalAttachmentCtrl" ng-cloak>
	<?php  if(!empty($upload_max_filesize) && !empty($post_max_size)) { ?>
	<div class="row">
		<label class="col-sm-2 control-label">PHP 环境说明</label>
		<div class="col-sm-10">
			<div class="form-control-static">1. 当前 PHP 环境允许最大单个上传文件大小为: <?php  echo $upload_max_filesize;?></div>
			<div class="form-control-static">2. 当前 PHP 环境允许最大 POST 表单大小为: <?php  echo $post_max_size;?></div>
			<div class="form-control-static">3. 源码来自猫咪科技http://kevin.maomikeji.top</div>
		</div>
	</div>
	<?php  } ?>
	<div class="form-files-box">
		<div class="form-files we7-margin-bottom">
			<div class="form-file header">本地附件空间设置</div>
			<div class="form-file">
				<div class="form-label">空间容量</div>
				<div class="form-value" ng-if="upload.attachment_limit">{{upload.attachment_limit}}M</div>
				<div class="form-value" ng-if="!upload.attachment_limit">容量单位为M, 设置为 0 时不限制空间</div>
				<div class="form-edit">
					<we7-modal-form type="'text'" label="'空间容量'" value="upload.attachment_limit" on-confirm="saveSetting(formValue, 'attachment_limit')" help="'请输入单位为M的容量值, 设置为 0 时不限制空间'"></we7-modal-form>
				</div>
			</div>
		</div>

		<div class="form-files we7-margin-bottom">
			<div class="form-file header">附件缩略设置</div>
			<div class="form-file">
				<div class="form-label">缩略设置</div>
				<div class="form-value">是否启用缩略</div>
				<div class="form-edit">
					<div ng-class="!upload.image.thumb ? 'switch' : 'switch switchOn'"  ng-click="saveSetting(upload.image.thumb ? 0 : 1, 'image_thumb')"></div>
				</div>
			</div>
			<div class="form-file" ng-if="upload.image.thumb">
				<div class="form-label">缩略图最大宽度</div>
				<div class="form-value" ng-if="upload.image.width">{{upload.image.width}}px</div>
				<div class="form-value" ng-if="!upload.image.width">缩略后图片的最大宽度</div>
				<div class="form-edit">
					<we7-modal-form
							type="'text'" label="'缩略图最大宽度'" value="upload.image.width"
							on-confirm="saveSetting(formValue, 'image_width')"
							help="'请输入单位px的图片宽度'">
					</we7-modal-form>
				</div>
			</div>
		</div>

		<div class="form-files we7-margin-bottom">
			<div class="form-file header">图片附件设置</div>
			<div class="form-file">
				<div class="form-label">支持文件后缀</div>
				<div class="form-value" ng-if="upload.image.extentions" ng-bind="upload.image.extentions"></div>
				<div class="form-value" ng-if="!upload.image.extentions">填写图片后缀名称, 如: jpg, 换行输入, 一行一个后缀 (如果为空，则采用系统默认设置).</div>
				<div class="form-edit">
					<we7-modal-form
							type="'textarea'" label="'支持文件后缀'" value="upload.image.extentions"
							on-confirm="saveSetting(formValue, 'image_extentions')"
							help="'填写图片后缀名称, 如: jpg, 换行输入, 一行一个后缀 (如果为空，则采用系统默认设置).'">
					</we7-modal-form>
				</div>
			</div>
			<div class="form-file">
				<div class="form-label">支持文件大小</div>
				<div class="form-value" ng-if="upload.image.limit">{{upload.image.limit}}KB</div>
				<div class="form-edit">
					<we7-modal-form
							type="'text'" label="'支持文件大小'" value="upload.image.limit"
							on-confirm="saveSetting(formValue, 'image_limit')"
							help="'请输入单位为kb的值'">
					</we7-modal-form>
				</div>
			</div>
			<div class="form-file">
				<div class="form-label">图片压缩</div>
				<div class="form-value" ng-if="upload.image.zip_percentage">{{upload.image.zip_percentage}}%</div>
				<div class="form-value" ng-if="!upload.image.zip_percentage">100不压缩 值越大越清晰</div>
				<div class="form-edit">
					<we7-modal-form
							type="'text'" label="'压缩比率'" value="upload.image.zip_percentage"
							on-confirm="saveSetting(formValue, 'image_zip_percentage')"
							help="'请输入1到100的整数, 100为不压缩, 值越大越清晰'">
					</we7-modal-form>
				</div>
			</div>
		</div>

		<div class="form-files we7-margin-bottom">
			<div class="form-file header">音频视频附件设置</div>
			<div class="form-file">
				<div class="form-label">支持文件后缀</div>
				<div class="form-value" ng-if="upload.audio.extentions" ng-bind="upload.audio.extentions"></div>
				<div class="form-value" ng-if="!upload.audio.extentions">填写音频视频后缀名称, 如: mp3, 换行输入, 一行一个后缀 (如果为空，则采用系统默认设置).</div>
				<div class="form-edit">
					<we7-modal-form
							type="'textarea'" label="'支持文件后缀'" value="upload.audio.extentions"
							on-confirm="saveSetting(formValue, 'audio_extentions')"
							help="'填写音频视频后缀名称, 如: mp3, 换行输入, 一行一个后缀 (如果为空，则采用系统默认设置).'">
					</we7-modal-form>
				</div>
			</div>
			<div class="form-file">
				<div class="form-label">支持文件大小</div>
				<div class="form-value" ng-if="upload.audio.limit">{{upload.audio.limit}}KB</div>
				<div class="form-edit">
					<we7-modal-form
							type="'text'" label="'支持文件大小'" value="upload.audio.limit"
							on-confirm="saveSetting(formValue, 'audio_limit')"
							help="'请输入单位为kb的值'">
					</we7-modal-form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	angular.module('systemApp').value('config', {
		'upload' : <?php  echo json_encode($upload)?>,
		'token' : "<?php  echo $_W['token'];?>",
		'saveSettingUrl' : "<?php  echo url('system/attachment/global')?>",
	});
	angular.bootstrap($('#js-global-attachment'), ['systemApp']);
</script>

<?php  } else if($do == 'remote') { ?>
<form action="" method="post" class="we7-form form" id="form">
	<div class="form-group">
		<div class="col-sm-12">
			<input type="radio" name="type" id="type-0" value="0" onclick="$('.remote-qiniu').hide();$('.remote-alioss').hide();$('.remote-ftp').hide();$('.remote-close').show();$('.remote-cos').hide();" <?php  if(empty($remote['type']) || $remote['type'] == '0') { ?> checked="checked" <?php  } ?>>
			<label class="radio-inline" for="type-0">
				 关闭
			</label>
			<input type="radio" name="type" id="type-1" value="1" onclick="$('.remote-qiniu').hide();$('.remote-ftp').show();$('.remote-alioss').hide();$('.remote-close').hide();$('.remote-cos').hide();" <?php  if(!empty($remote['type']) && $remote['type'] == '1') { ?> checked="checked" <?php  } ?>>
			<label class="radio-inline" for="type-1">
				 FTP服务器
			</label>
			<input type="radio" name="type" id="type-2" value="2" onclick="$('.remote-qiniu').hide();$('.remote-alioss').show();$('.remote-ftp').hide();$('.remote-close').hide();$('.remote-cos').hide();" <?php  if(!empty($remote['type']) && $remote['type'] == '2') { ?> checked="checked" <?php  } ?>>
			<label class="radio-inline" for="type-2">
				 阿里云OSS <span class="label label-success">推荐，快速稳定</span>
			</label>
			<input type="radio" name="type" id="type-3" value="3" onclick="$('.remote-qiniu').show();$('.remote-alioss').hide();$('.remote-ftp').hide();$('.remote-close').hide();$('.remote-cos').hide();" <?php  if(!empty($remote['type']) && $remote['type'] == '3') { ?> checked="checked" <?php  } ?>>
			<label class="radio-inline" for="type-3">
				 七牛云存储 <span class="label label-success">推荐，快速稳定</span>
			</label>
			<input type="radio" name="type" id="type-4" value="4" onclick="$('.remote-qiniu').hide();$('.remote-alioss').hide();$('.remote-ftp').hide();$('.remote-close').hide();$('.remote-cos').show();" <?php  if(!empty($remote['type']) && $remote['type'] == '4') { ?> checked="checked" <?php  } ?>>
			<label class="radio-inline" for="type-4">
				 腾讯云存储 <span class="label label-success">推荐，快速稳定</span>
			</label>
			<span class="help-block"></span>
		</div>
	</div>
	<div class="remote-ftp" <?php  if(empty($remote['type']) || $remote['type'] != '1') { ?> style="display:none;" <?php  } ?>>
		<div class="form-group">
			<label class="col-sm-2 control-label">启用SSL连接</label>
			<div class="col-sm-9">
				<input type="radio" id="ftp[ssl]-1" name="ftp[ssl]" value="1" <?php  if(!empty($remote['ftp']['ssl'])) { ?> checked="checked" <?php  } ?>>
				<label class="radio-inline" for="ftp[ssl]-1">
					 是
				</label>
				<input type="radio" id="ftp[ssl]-0" name="ftp[ssl]" value="0" <?php  if(empty($remote['ftp']['ssl'])) { ?> checked="checked" <?php  } ?>>
				<label class="radio-inline" for="ftp[ssl]-0">
					否
				</label>
				<span class="help-block">注意：选择是后，FTP 服务器必须开启了 SSL，一般情况选择否即可</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">FTP服务器地址</label>
			<div class="col-sm-9">
				<input type="text" name="ftp[host]" class="form-control" value="<?php  echo $remote['ftp']['host'];?>" />
				<span class="help-block">可以是 FTP 服务器的 IP 地址或域名</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">FTP服务器端口</label>
			<div class="col-sm-9">
				<input type="text" name="ftp[port]" class="form-control" value="<?php  if(empty($remote['ftp']['port'])) { ?>21<?php  } else { ?><?php  echo $remote['ftp']['port'];?><?php  } ?>" />
				<span class="help-block">默认为21</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">FTP帐号</label>
			<div class="col-sm-9">
				<input type="text" name="ftp[username]" class="form-control" value="<?php  echo $remote['ftp']['username'];?>" />
				<span class="help-block">该帐号必须具有以下权限：读取文件、写入文件、删除文件、创建目录、子目录继承</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">FTP密码</label>
			<div class="col-sm-9">
				<input type="text" name="ftp[password]" class="form-control encrypt" value="<?php  echo $remote['ftp']['password'];?>" />
				<span class="help-block">基于安全考虑将只显示 FTP 密码的第一位和最后一位，中间显示八个 * 号</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">被动模式(pasv)连接：</label>
			<div class="col-sm-9">
				<label class="radio-inline">
					<input type="radio" name="ftp[pasv]" value="1" <?php  if(!empty($remote['ftp']['pasv'])) { ?> checked="checked" <?php  } ?>> 是
				</label>
				<label class="radio-inline">
					<input type="radio" name="ftp[pasv]" value="0" <?php  if(empty($remote['ftp']['pasv'])) { ?> checked="checked" <?php  } ?>> 否
				</label>
				<span class="help-block">一般情况下非被动模式即可，如果存在上传失败问题，可尝试打开此设置</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">远程附件目录</label>
			<div class="col-sm-9">
				<input type="text" name="ftp[dir]" class="form-control" value="<?php  echo $remote['ftp']['dir'];?>" />
				<span class="help-block">远程附件目录的绝对路径或相对于 FTP 主目录的相对路径，结尾不要加斜杠 "/" , 例如：/attachment</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">远程访问URL</label>
			<div class="col-sm-9">
				<input type="text" name="ftp[url]" class="form-control" value="<?php  echo $remote['ftp']['url'];?>" />
				<span class="help-block">支持 HTTP 和 FTP 协议，结尾不要加斜杠 "/" ; 例如：http://mydomin.com/attachment 如果使用 FTP 协议，FTP 服务器必须支持 PASV 模式，为了安全起见，
				使用 FTP 连接的帐号不要设置可写权限和列表权限</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">FTP传输超时时间</label>
			<div class="col-sm-9">
				<input type="text" name="ftp[overtime]" class="form-control" value="<?php  if(empty($remote['ftp']['overtime'])) { ?>0<?php  } else { ?><?php  echo $remote['ftp']['overtime'];?><?php  } ?>" />
				<span class="help-block">单位：秒，0为服务器默认</span>
			</div>
		</div>
		<div class="form-group">
			<div class="">
				<button name="submit" class="btn btn-primary" value="submit">保存配置</button>
				<button name="button" type="button" class="btn btn-info js-checkremoteftp" value="check">测试配置（无需保存）</button>
				<?php  if(!empty($_W['setting']['remote_complete_info']['type']) && !empty($local_attachment)) { ?>
				<a name="button" class="btn btn-info one-key" href="javascript:;">一键上传</a>
				<?php  } ?>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			</div>
		</div>
	</div>
	<div class="remote-alioss" <?php  if(empty($remote['type']) || $remote['type'] != '2') { ?> style="display:none;" <?php  } ?>>
		<div class="alert alert-info we7-page-alert">
			<i class="wi wi-info-sign"></i> 启用阿里oss后，请把/attachment目录（不包括此目录）下的子文件及子目录上传至阿里云oss,
			<ul class="link-list we7-margin-top-sm">
				相关工具：<br>
			<li><a target="_blank" href="http://market.aliyun.com/products/52738003/cmgj000304.html?spm=5176.383663.9.21.faitxp" class="we7-margin-left color-default" data-spm-anchor-id="5176.383663.9.21">cloudfs4oss(ECS挂载文件盘工具)</a></li>
			<li><a target="_blank" href="http://market.aliyun.com/products/53690006/cmgj000281.html?spm=5176.383663.9.22.faitxp" class="we7-margin-left color-default" data-spm-anchor-id="5176.383663.9.22">官方推荐OSS客户端工具（Windows版）</a></li>
			<li><a target="_blank" href="http://market.aliyun.com/products/53690006/cmgj000282.html?spm=5176.383663.9.23.faitxp" class="we7-margin-left color-default" data-spm-anchor-id="5176.383663.9.23">官方推荐OSS客户端工具（Mac版）</a></li>
			<li><a target="_blank" href="http://market.aliyun.com/products/53690006/cmgj000208.html?spm=5176.383663.9.24.faitxp" class="we7-margin-left color-default" data-spm-anchor-id="5176.383663.9.24">Ftp4ossServer（OSS的FTP云工具）</a></li>
			<li><a target="_blank" href="http://bbs.aliyun.com/read/239565.html?spm=5176.383663.9.25.faitxp&amp;pos=2" class="we7-margin-left color-default" data-spm-anchor-id="5176.383663.9.25">OSS图片服务Demo工具</a></li>
			<li><a target="_blank" href="http://docs.aliyun.com/?spm=5176.383663.9.26.faitxp#/pub/oss/utilities/osscmd&amp;install" class="we7-margin-left color-default" data-spm-anchor-id="5176.383663.9.26">批量上传工具(Python)版</a></li>
			<li><a target="_blank" href="https://docs.aliyun.com/?spm=5176.383663.9.27.faitxp#/pub/oss/utilities/oss-import&amp;index" class="we7-margin-left color-default" data-spm-anchor-id="5176.383663.9.27">OSS数据迁移工具-OSS Import</a></li>
			<li><a target="_blank" href="http://market.aliyun.com/products/52738004/cmfw000394.html?spm=5176.383663.9.28.faitxp" class="we7-margin-left color-default" data-spm-anchor-id="5176.383663.9.28">海量数据迁移至OSS服务</a></li>
			<li><a target="_blank" href="http://bbs.aliyun.com/read/247023.html?spm=5176.383663.9.29.faitxp" class="we7-margin-left color-default" data-spm-anchor-id="5176.383663.9.29">更多官方推荐工具</a></li>
			</ul>

		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Access Key ID</label>
			<div class="col-sm-9">
				<input type="text" name="alioss[key]" class="form-control" value="<?php  echo $remote['alioss']['key'];?>" placeholder="" />
				<span class="help-block">
					Access Key ID是您访问阿里云API的密钥，具有该账户完全的权限，请您妥善保管。
				</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Access Key Secret</label>
			<div class="col-sm-9">
				<input type="text" name="alioss[secret]" class="form-control encrypt" value="<?php  echo $remote['alioss']['secret'];?>" placeholder="" />
				<span class="help-block">
					Access Key Secret是您访问阿里云API的密钥，具有该账户完全的权限，请您妥善保管。(填写完Access Key ID 和 Access Key Secret 后请选择bucket)
				</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">内网上传</label>
			<div class="col-sm-9">
				<input type="radio" name="alioss[internal]" id="type-12" value="1" <?php  if($remote['alioss']['internal'] == 1) { ?>checked<?php  } ?>>
				<label class="radio-inline" for="type-12">
					是
				</label>
				<input type="radio" name="alioss[internal]" id="type-13" value="0" <?php  if($remote['alioss']['internal'] != 1) { ?>checked<?php  } ?>>
				<label class="radio-inline" for="type-13">
					否
				</label>
				<span class="help-block">
						如果此站点使用的是阿里云ecs服务器，并且服务器与bucket在同一地区（如：同在华北一区），您可以选择通过内网上传的方式上传附件，以加快上传速度、节省带宽。
					</span>
			</div>
		</div>
		<div class="form-group" id="bucket" <?php  if(empty($remote['alioss']['key'])) { ?>style="display: none;<?php  } ?>">
			<label class="col-sm-2 control-label">Bucket选择</label>
			<div class="col-sm-9">
				<select name="alioss[bucket]" class="form-control">
				</select>
				<span class="help-block">
					完善Access Key ID和Access Key Secret资料后可以选择存在的Bucket(请保证bucket为可公共读取的)，否则请手动输入。
				</span>
			</div>
		</div>
		<div class="form-group">
		 <label class="col-sm-2 control-label">自定义URL</label>
			<div class="col-sm-9">
				<input type="text" name="custom[url]" class="form-control" <?php  if(!strexists($remote['alioss']['url'],'aliyuncs.com') && $remote['type'] == 2) { ?>value="<?php  echo $remote['alioss']['url'];?>"<?php  } ?> placeholder="默认URL不需要填写（默认包含 aliyuncs.com 的URL不显示）"/>
					<span class="help-block">
						阿里云oss支持用户自定义访问域名，如果自定义了URL则用自定义的URL，如果未自定义，则用系统生成出来的URL。注：自定义url开头加http://或https://结尾不加 ‘/’例：http://abc.com
					</span>
			</div>
		</div>
		<div class="form-group">
			<div class="">
				<button name="submit" class="btn btn-primary" value="submit">保存配置</button>
				<button name="button" type="button" class="btn btn-info js-checkremoteoss" value="check">测试配置（无需保存）</button>
				<?php  if(!empty($_W['setting']['remote_complete_info']['type']) && !empty($local_attachment)) { ?>
				<a name="button" class="btn btn-info one-key" href="javascript:;">一键上传</a>
				<?php  } ?>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			</div>
		</div>
	</div>
	<div class="remote-qiniu" <?php  if(empty($remote['type']) || $remote['type'] != '3') { ?> style="display:none;" <?php  } ?>>
	<div class="alert alert-info we7-page-alert">
		<span><i class="wi wi-info-sign"></i> 启用七牛云存储后，请把/attachment目录（不包括此目录）下的子文件及子目录上传至七牛云存储, </span><br>
		<span><i class="wi wi-info-sign"></i> 七牛云存储，现在不支持上传文件夹</span><br>
		<ul class="link-list we7-margin-top-sm">
			<li>相关工具：<a target="_blank" href="https://portal.qiniu.com/signin" class="color-default" >七牛云存储</a></li>
		</ul>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Accesskey</label>
		<div class="col-sm-9">
			<input type="text" name="qiniu[accesskey]" class="form-control" value="<?php  echo $remote['qiniu']['accesskey'];?>" placeholder="" />
			<span class="help-block">用于签名的公钥</span>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Secretkey</label>
		<div class="col-sm-9">
			<input type="text" name="qiniu[secretkey]" class="form-control encrypt" value="<?php  echo $remote['qiniu']['secretkey'];?>" placeholder="" />
			<span class="help-block">用于签名的私钥</span>
		</div>
	</div>
	<div class="form-group" id="qiniubucket">
		<label class="col-sm-2 control-label">Bucket</label>
		<div class="col-sm-9">
			<input type="text" name="qiniu[bucket]" class="form-control" value="<?php  echo $remote['qiniu']['bucket'];?>" placeholder="" />
			<span class="help-block">请保证bucket为可公共读取的</span>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Url</label>
		<div class="col-sm-9">
			<input type="text" name="qiniu[url]" class="form-control" value="<?php  echo $remote['qiniu']['url'];?>" placeholder="" />
			<span class="help-block">七牛支持用户自定义访问域名。注：url开头加http://或https://结尾不加 ‘/’例：http://abc.com</span>
		</div>
	</div>
	<div class="form-group">
		<div class="">
			<button name="submit" class="btn btn-primary" value="submit">保存配置</button>
			<button name="button" type="button" class="btn btn-info js-checkremoteqiniu" value="check">测试配置（无需保存）</button>
			<?php  if(!empty($_W['setting']['remote_complete_info']['type']) && !empty($local_attachment)) { ?>
			<a name="button" class="btn btn-info one-key" href="javascript:;">一键上传</a>
			<?php  } ?>
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>
	</div>
	</div>
	<div class="remote-cos" <?php  if(empty($remote['type']) || $remote['type'] != '4') { ?> style="display:none;" <?php  } ?>>
	<div class="alert alert-info we7-page-alert">
		<i class="wi wi-info-sign"></i> 启用腾讯云cos对象存储后，请把/attachment目录（不包括此目录）下的子文件及子目录上传至腾讯云存储, <br>
		<ul class="link-list we7-margin-top-sm">
			<li>相关工具：<a target="_blank" href="https://console.qcloud.com/cos/bucket" class="color-default" >腾讯云存储</a></li>
		</ul>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">APPID</label>
		<div class="col-sm-9">
			<input type="text" name="cos[appid]" class="form-control" value="<?php  echo $remote['cos']['appid'];?>" placeholder="" />
			<span class="help-block">APPID 是您项目的唯一ID</span>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">SecretID</label>
		<div class="col-sm-9">
			<input type="text" name="cos[secretid]" class="form-control" value="<?php  echo $remote['cos']['secretid'];?>" placeholder="" />
			<span class="help-block">SecretID 是您项目的安全密钥，具有该账户完全的权限，请妥善保管</span>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">SecretKEY</label>
		<div class="col-sm-9">
			<input type="text" name="cos[secretkey]" class="form-control encrypt" value="<?php  echo $remote['cos']['secretkey'];?>" placeholder="" />
			<span class="help-block">SecretKEY 是您项目的安全密钥，具有该账户完全的权限，请妥善保管</span>
		</div>
	</div>
	<div class="form-group" id="cosbucket">
		<label class="col-sm-2 control-label">Bucket</label>
		<div class="col-sm-9">
			<input type="text" name="cos[bucket]" class="form-control" value="<?php  echo $remote['cos']['bucket'];?>" placeholder="" />
			<span class="help-block">请保证bucket为可公共读取的</span>
		</div>
	</div>
	<div class="form-group" id="cos_local">
	<label class="col-xs-12 col-sm-3 col-md-2 control-label">bucket所在区域</label>
		<div class="col-sm-9 col-xs-12">
			<select class="form-control" name="cos[local]">
				<option value="" <?php  if($remote['cos']['local'] == '') { ?>selected<?php  } ?>>无</option>
				<option value="tj" <?php  if($remote['cos']['local'] == 'tj') { ?>selected<?php  } ?>>华北</option>
				<option value="sh" <?php  if($remote['cos']['local'] == 'sh') { ?>selected<?php  } ?>>华东</option>
				<option value="gz" <?php  if($remote['cos']['local'] == 'gz') { ?>selected<?php  } ?>>华南</option>
				<option value="cd" <?php  if($remote['cos']['local'] == 'cd') { ?>selected<?php  } ?>>西南</option>
				<option value="bj" <?php  if($remote['cos']['local'] == 'bj') { ?>selected<?php  } ?>>北京</option>
				<option value="cq" <?php  if($remote['cos']['local'] == 'cq') { ?>selected<?php  } ?>>重庆</option>
				<option value="sgp" <?php  if($remote['cos']['local'] == 'sgp') { ?>selected<?php  } ?>>新加坡</option>
				<option value="hk" <?php  if($remote['cos']['local'] == 'hk') { ?>selected<?php  } ?>>香港</option>
				<option value="ca" <?php  if($remote['cos']['local'] == 'ca') { ?>selected<?php  } ?>>多伦多</option>
				<option value="ger" <?php  if($remote['cos']['local'] == 'ger') { ?>selected<?php  } ?>>法兰克福</option>
			</select>
			<span class="help-block">选择bucket对应的区域，如果没有选择无</span>
		</div>
	</div>
	<div class="form-group" >
		<label class="col-sm-2 control-label">Url</label>
		<div class="col-sm-9">
			<input type="text" name="cos[url]" class="form-control" value="<?php  echo $remote['cos']['url'];?>" placeholder="" />
			<span class="help-block">腾讯云支持用户自定义访问域名。注：url开头加http://或https://结尾不加 ‘/’例：http://abc.com</span>
		</div>
	</div>
	<div class="form-group">
		<div class="">
			<button name="submit" class="btn btn-primary" value="submit">保存配置</button>
			<button name="button" type="button" class="btn btn-info js-checkremotecos" value="check">测试配置（无需保存）</button>
			<?php  if(!empty($_W['setting']['remote_complete_info']['type']) && !empty($local_attachment)) { ?>
			<a name="button" class="btn btn-info one-key" href="javascript:;">一键上传</a>
			<?php  } ?>
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>
	</div>
	</div>
	<div class="remote-close" <?php  if(!empty($remote['type'])) { ?> style="display:none;" <?php  } ?>>
		<div class="form-group">
			<div class="">
				<button name="submit" class="btn btn-primary" value="submit">保存配置</button>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			</div>
		</div>
	</div>
	<div class="modal fade" id="name" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="we7-modal-dialog modal-dialog we7-form">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<div class="modal-title">上传文件</div>
				</div>
				<div class="modal-body">
					正在上传....
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
	$(function() {
		$('.encrypt').val(function() {
			return util.encrypt($(this).val());
		});
	});
	$('.js-checkremoteftp').on('click', function(){
		var ssl =  parseInt($(':radio[name="ftp[ssl]"]:checked').val());
		var pasv = parseInt($(':radio[name="ftp[pasv]"]:checked').val());
		var param = {
			'ssl' : ssl,
			'host' : $.trim($(':text[name="ftp[host]"]').val()),
			'username'  : $.trim($(':text[name="ftp[username]"]').val()),
			'password' : $.trim($(':text[name="ftp[password]"]').val()),
			'pasv' : pasv,
			'dir': $.trim($(':text[name="ftp[dir]"]').val()),
			'url': $.trim($(':text[name="ftp[url]"]').val()),
			'port' : parseInt($(':text[name="ftp[port]"]').val()),
			'overtime' : parseInt($(':text[name="ftp[overtime]"]').val())
		};
		$.post("<?php  echo url('system/attachment/ftp')?>", param, function(data){
			var data = $.parseJSON(data);
			if(data.message.errno == 0) {
				util.message(data.message.message);
				return false;
			}
			if(data.message.errno < 0) {
				util.message(data.message.message);
				return false;
			}
		});
	});
	$('.one-key').click(function() {
        upload_remote();
		return false;
	});
	var upload_remote = function() {
        $('#name').modal('show');
		$.post("<?php  echo url('system/attachment/upload_remote')?>", {}, function(data) {
			var data = $.parseJSON(data);
			if (data.message.errno == 2) {
				upload_remote();
			}
			if (data.message.errno == 0) {
                util.message('上传完毕', location.reload(), 'success');
            }
            if (data.message.errno == 1) {
                util.message(data.message.message, '', 'success');
            }
		});
	}
	$('.js-checkremoteoss').on('click', function(){
		var bucket = $.trim($('select[name="alioss[bucket]"]').val());
		if (bucket == '') {
			bucket = $.trim($(':text[name="alioss[bucket]"]').val());
		}
		var param = {
			'key' : $.trim($(':text[name="alioss[key]"]').val()),
			'secret' : $.trim($(':text[name="alioss[secret]"]').val()),
			'url'  : $.trim($(':text[name="custom[url]"]').val()),
			'bucket' : bucket,
			'internal' : $('[name="alioss[internal]"]:checked').val()
        };
		$.post("<?php  echo url('system/attachment/oss')?>", param, function(data) {
			var data = $.parseJSON(data);
			if(data.message.errno == 0) {
				util.message('配置成功');
				return false;
			}
			if(data.message.errno < 0) {
				util.message(data.message.message);
				return false;
			}
		});
	});
	$('.js-checkremoteqiniu').on('click', function(){
		var key = $.trim($(':text[name="qiniu[accesskey]"]').val());
		if (key == '') {
			util.message('请填写Accesskey');
			return false;
		}
		var secret = $.trim($(':text[name="qiniu[secretkey]"]').val());
		if (secret == '') {
			util.message('请填写Secretkey');
			return false;
		}
		var param = {
			'accesskey' : $.trim($(':text[name="qiniu[accesskey]"]').val()),
			'secretkey' : $.trim($(':text[name="qiniu[secretkey]"]').val()),
			'url'  : $.trim($(':text[name="qiniu[url]"]').val()),
			'bucket' :  $.trim($(':text[name="qiniu[bucket]"]').val())
		};
		$.post("<?php  echo url('system/attachment/qiniu')?>",param, function(data) {
			var data = $.parseJSON(data);
			if(data.message.errno == 0) {
				util.message('配置成功');
				return false;
			}
			if(data.message.errno < 0) {
				util.message(data.message.message);
				return false;
			}
		});
	});
	$('.js-checkremotecos').on('click', function(){
		var appid = $.trim($(':text[name="cos[appid]"]').val());
		if (appid == '') {
			util.message('请填写APPID');
			return false;
		}
		var secretid = $.trim($(':text[name="cos[secretid]"]').val());
		if (secretid == '') {
			util.message('请填写secretid');
			return false;
		}
		var secretkey = $.trim($(':text[name="cos[secretkey]"]').val());
		if (secretkey == '') {
			util.message('请填写Secretkey');
			return false;
		}
		var bucket = $.trim($(':text[name="cos[bucket]"]').val());
		if (bucket == '') {
			util.message('请填写bucket');
			return false;
		}
		var url = $.trim($(':text[name="cos[url]"]').val());
		var local = $('[name="cos[local]"]').val();
		var param = {
			'appid' : appid,
			'secretid' : secretid,
			'secretkey'  : secretkey,
			'bucket' :  bucket,
			'url' : url,
			'local' : local
		};
		$.post("<?php  echo url('system/attachment/cos')?>",param, function(data) {
			var data = $.parseJSON(data);
			if(data.message.errno == 0) {
				util.message('配置成功');
				return false;
			}
			if(data.message.errno < 0) {
				util.message(data.message.message);
				return false;
			}
		});
	});
	var alibucket = '<?php  echo $_W['setting']['remote']['alioss']['bucket'];?>';
	var buck =  function() {
		var key = $(':text[name="alioss[key]"]').val();
		var secret = $(':text[name="alioss[secret]"]').val();
		if (secret.indexOf('*') > 0) {
			secret = '<?php  echo $_W['setting']['remote']['alioss']['secret'];?>';
		}
		if (key == '' || secret == '') {
			$('#bucket').hide();
			return false;
		}
		$.post("<?php  echo url('system/attachment/buckets')?>", {'key' : key, 'secret' : secret}, function(data) {
			try {
				var data = $.parseJSON(data);
			} catch (error) {
				util.message('Access Key ID 或 Access Key Secret 填写错误，请重新填写。', '', 'error');
				$('#bucket').hide();
				$('select[name="alioss[bucket]"]').val('');
				return false;
			}

			if (data.message.errno < 0 ) {
				return false;
			} else {
				$('#bucket').show();
				var bucket = $('select[name="alioss[bucket]"]');
				bucket.empty();
				var buckets = eval(data.message.message);
				for (var i in buckets) {
					var selected = alibucket == buckets[i]['name'] || alibucket ==  buckets[i]['name'] + '@@' + buckets[i]['location'] ? 'selected' : '';
					bucket.append('<option value="' + buckets[i]['name'] + '@@' + buckets[i]['location'] + '"' + selected + '>'+buckets[i]['loca_name'] + '</option>');
				}
				if($('select').niceSelect) {
					$('select').niceSelect('update')
				}
			}
		});
	};
	buck();
	$(':text[name="alioss[secret]"]').blur(function() {buck();});
	$('form').submit(function() {
		if ($('[name="type"]:checked').val() == 2 && ($('select[name="alioss[bucket]"]').val() == null || $('select[name="alioss[bucket]"]').val() == '')) {
			util.message('请完善信息后再保存设置！');
			return false;
		}
	});
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
