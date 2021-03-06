<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="alert we7-page-alert">
	<?php  if($_W['account']['type_sign'] == 'wxapp') { ?>
	<p><i class="wi wi-info-sign"></i>从关联账号应用同步的会员信息,在小程序应用登录时会被判断为新用户,此处无法识别为同一会员；</p>
	<?php  } ?>
	<p><i class="wi wi-info-sign"></i>本<?php  echo $_W['account']['type_name'];?>可操作的模块中，仅显示可进行同步的模块；</p>
	<p><i class="wi wi-info-sign"></i>可以把PC应用或公众号应用的会员、数据等信息同步至<?php  echo $_W['account']['type_name'];?>应用当中；</p>
	<p><i class="wi wi-info-sign"></i>一个模块，只能同步了PC、公众号、小程序中的其中一种，且只可同步一个；</p>
	<p><i class="wi wi-info-sign"></i>若小程序A应用已同步<?php  echo $_W['account']['type_name'];?>，则<?php  echo $_W['account']['type_name'];?>同步小程序应用时，无法选择小程序A且小程序A不会显示在同步列表中，即两者不可相互同步,PC同理。</p>

	<p><i class="wi wi-info-sign"></i>框架精品 源 码 独家发售 破解 微信 mx216972 qq 213 614862</p>
</div>
<div id="js-module-link-uniacid" ng-controller="moduleLinkUniacidCtrl" ng-cloak>
	<table class="table we7-table table-hover vertical-middle">
		<col width="180px" />
		<col width="140px"/>
		<col width="140px" />
		<tr>
			<th class="text-left">关联设置</th>
			<th class="text-left">关联账号</th>
			<th class="text-right">操作</th>
		</tr>
		<tr ng-repeat="module in versionInfo.modules" ng-if="versionInfo.modules">
			<td class="text-left">
				<img ng-src="{{module.logo}}" style="width:50px;height:50px;">
				{{module.title}}
			</td>
			<td class="text-left" ng-if="module.account">
				<img ng-src="{{module.account.logo}}" style="width:50px;height:50px;">
				<span>{{module.account.name}}</span>
			</td>
			<td class="text-left" ng-if="!module.account && !module.passive_link_uniacid">
				<span>暂无</span>
			</td>
			<td class="text-left" ng-if="!module.account && module.passive_link_uniacid">
				<div ng-repeat="item in module.passive_link_uniacid">
					已被 {{item.type_name}} <span class="color-default" ng-bind="item.name"></span> 同步
				</div>
			</td>
			<td>
				<div class="link-group" ng-if="module.account">
					<a href="javascript:;" ng-click="initModule(module.name)">修改</a>
					<a href="javascript:;" ng-click="module_unlink_uniacid(module.name)">删除</a>
				</div>
				<div class="link-group" ng-if="!module.account && !module.passive_link_uniacid">
					<a href="javascript:;" ng-click="initModule(module.name)">添加</a>
				</div>
				<div class="link-group" ng-if="!module.account && module.passive_link_uniacid">
					<a href="javascript:;">---</a>
				</div>
			</td>
		</tr>
	</table>

	<div class="modal fade modal-app" tabindex="-1" id="show-account" role="dialog" >
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header clearfix">
					<h4 class="text-over"> 选择账号 </h4>
					<div class="type"></div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="search-box">
						<span class="we7-margin-right">账号类型:</span>
						<select class="we7-select we7-margin-right"
								ng-model="activeTypeSign"
								ng-change="changeType(activeTypeSign)">
							<option value="{{key}}"
									ng-repeat="(key, item) in we7TypeDefault"
							>{{item.name}}</option>
						</select>
						<div class="search-form">
							<div class="input-group">
								<input class="form-control" type="text" ng-model="searchKeyword" autocomplete="false" >
								<span class="input-group-btn"><button class="btn btn-default"><i class="fa fa-search"></i></button></span>
							</div>
						</div>
					</div>
					<div class="modal-app-list" ng-show="loadData">
						<span class="help-block text-center"><img src="./resource/images/loading.gif" alt="" width="45px"></span>
					</div>
					<div class="modal-app-list" ng-show="!loadData">
						<div class="modal-item" ng-class="{'active': account.checked == 1}" ng-repeat="account in linkAccounts" ng-show="!searchKeyword || searchKeyword && account.name.indexOf(searchKeyword) > -1">
							<div class="logo" ng-click="selectLinkAccount(account)">
								<img ng-src="{{account.logo}}" class="account-logo" alt="">
								<div class="mark">
									<i class="wi wi-right"></i>
								</div>
							</div>
							<div class="name text-over" ng-click="selectLinkAccount(account, $event)">
								<i class="{{we7TypeDefault[account.type_sign]['icon']}}"></i>{{account.name}}
							</div>
						</div>
						<div class="we7-empty-block" ng-if="linkAccounts | we7IsEmpty">没有可以关联的账号</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" ng-click="moduleLinkUniacid()">确定</button>
					<button type="button" class="btn btn-default"  data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	angular.module('wxApp').value('config', {
		'version_info': <?php echo !empty($version_info) ? json_encode($version_info) : 'null'?>,
		'token': "<?php  echo $_W['token']?>",
		'typeSign': "<?php  echo $_W['account']['type_sign']?>",
		'links' : {
			'search_link_account': "<?php  echo url('wxapp/module-link-uniacid/search_link_account')?>",
			'module_link_uniacid': "<?php  echo url('wxapp/module-link-uniacid')?>",
			'module_unlink_uniacid':"<?php  echo url('wxapp/module-link-uniacid/module_unlink_uniacid')?>"
		},
	});

	angular.bootstrap($('#js-module-link-uniacid'), ['wxApp']);
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>