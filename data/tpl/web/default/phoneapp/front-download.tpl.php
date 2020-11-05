<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="front-download">
	<ul class="we7-page-tab">
		<li class="<?php  if($type == 'apk') { ?>active<?php  } ?>"><a
				href="<?php  echo url('phoneapp/front-download', array('version_id' => $version_id, 'type' => 'apk'))?>">安卓APP下载</a></li>
		<li class="<?php  if($type == 'ipa') { ?>active<?php  } ?>"><a
				href="<?php  echo url('phoneapp/front-download', array('version_id' => $version_id, 'type' => 'ipa'))?>">苹果APP下载</a></li>
	
	</ul>
	<?php  if($type == 'apk') { ?>
	<div class="alert we7-page-alert">
		<p><i class="wi wi-info-sign"></i>以360市场为例，下载“360加固保”对下载的文件进行加固。地址：<a href="http://jiagu.360.cn/" target="_blank">http://jiagu.360.cn/</a>	</p>
		<p><i class="wi wi-info-sign"></i>加固后，请对apk文件重新配置签名。<a href="javascript:;" class="showSetting">配置APK</a></p>
	</div>
	<?php  } ?>
	<div class="app-down-box">
		<img src="<?php  echo $module['logo'];?>" alt="<?php  echo $module['title'];?>" class="logo">
		<div class="title"><?php  if($type == 'ipa') { ?>苹果<?php  } else { ?>安卓<?php  } ?>APP应用<?php  echo $type;?>包</div>
		<a class="btn btn-primary"
			href="<?php  echo url('phoneapp/front-download/getpackage', array('version_id' => $version_id, 'type' => $type))?>">
			立即下载
		</a>
	</div>
	<div class="modal apk-select-modal" id="ApkSelect">
		<div class="modal-dialog we7-modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title">配置APK</h3>
				</div>
				<div class="modal-body we7-form">
					<div class="alert we7-page-alert">
						<i class="wi wi-info-sign"></i>	上传加固后的apk，配置签名后重新下载apk。
					</div>
					<div class="form-group">
						<label for="" class="control-label col-sm-3">上传APK文件</label>
						<div class="form-controls col-sm-8">
							<div class="input-group">
								<input type="text" readonly name="cname" id="apkName"  class="form-control " autocomplete="off" />
								<div class="input-group-addon">
									<input type="file" name="apk" id="apk" accept=".apk" class="form-control hidden" autocomplete="off" />
									<label for="apk" >选择文件</label>	
								</div>
							</div>
							
							<span class="help-block"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary js-ApkSelect" disabled>下载配置APK</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var siteinfo = '<?php  echo json_encode($siteinfo)?>';
	$('.showSetting').click(function() {
		$('#ApkSelect').modal('show')
	})
	$('#apk').change(function(event) {
		var file = event.target.files[0];
		if (file) {
			$('#apkName').val(file.name);
			$('.js-ApkSelect').attr('disabled', false)
		}
	})
	$('.js-ApkSelect').click(function(event) {
		var file = document.getElementById("apk").files[0];
		if (file) {
			require(['jszip', 'fileSaver'], function(JSZip, fileSaver){
				JSZip.loadAsync(file).then(function(zip){
					console.log(zip.comment);
					zip.generateAsync({comment: siteinfo,type:"blob"})
					.then(function (blob) {
						saveAs(blob, "配置.apk");
					});
				});
			});
		}
	})
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>