<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div  id="js-system-account-expired-message" ng-controller="SystemAccountExpiredMessageCtrl" ng-cloak>
	<!-- 列表数据 start -->
	<table class="table we7-table table-hover vertical-middle table-manage">
		<col width="200px"/>
		<col width="600px"/>
		<col width="50px"/>
		<col width="100px"/>
		<tr>
			<th>平台类型</th>
			<th colspan="2">到期提示</th>
			<th>状态</th>
			<th>操作</th>
		</tr>
		<tr ng-repeat="(account_type, account_type_info) in account_types" >
			<td ng-bind="account_type_info.title"></td>
			<td colspan="2" ng-bind="account_type_info.expired_message.message"></td>
			<td>
				<label>
					<div ng-class="account_type_info.expired_message.status == undefined || account_type_info.expired_message.status == 0 ? 'switch' : 'switch switchOn'"  ng-click="saveSettingSwitch('status', account_type, account_type_info.expired_message)"></div>
				</label>
			</td>
			<td class="form-file">
				<div class="form-edit">
				<we7-modal-form label="'到期提示'" on-confirm="saveSetting(formValue, account_type, account_type_info.expired_message)" value="account_type_info.expired_message.message"></we7-modal-form>
				</div>
			</td>
		</tr>
	</table>
	<!-- 列表数据 end -->
</div>
<script>
	$(function(){
		$('[data-toggle="tooltip"]').tooltip();
	});
	angular.module('accountApp').value('config', {
		account_types: <?php echo !empty($account_all_type_sign) ? json_encode($account_all_type_sign) : 'null'?>,
		links: {
			saveSettingUrl: "<?php  echo url('account/expired-message/save')?>",
		}
	});
	angular.bootstrap($('#js-system-account-expired-message'), ['accountApp']);
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>