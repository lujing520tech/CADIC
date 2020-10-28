<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
define('HTTP_X_FOR', (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://');
define('CLOUD_GATEWAY_URL', HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php');
define('CLOUD_GATEWAY_URL_NORMAL', HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php');
function cloud_client_define() {
	return array(
		'/framework/function/communication.func.php',
		'/framework/model/cloud.mod.php',
		'/web/source/cloud/upgrade.ctrl.php',
		'/web/source/cloud/process.ctrl.php',
		'/web/source/cloud/dock.ctrl.php',
		'/web/themes/default/cloud/upgrade.html',
		'/web/themes/default/cloud/process.html'
	);
}


function _cloud_build_params($must_authorization_host = true) {
	global $_W;
	$pars = array();
	$pars['host'] = $_SERVER['HTTP_HOST'];
	$pars['family'] = IMS_FAMILY;
	$pars['version'] = IMS_VERSION;
	$pars['release'] = IMS_RELEASE_DATE;
	$pars['host'] = trim(preg_replace('/http(s)?:\\/\\//', '', trim($_W['siteroot'], '/')));
        $pars['key'] = $_W['setting']['site']['key'];
        $pars['token'] = $_W['setting']['site']['token'];
        $pars['password'] = md5($_W['setting']['site']['key'] . $_W['setting']['site']['token']);
	$clients = cloud_client_define();
	$string = '';
	foreach($clients as $cli) {
		$string .= md5_file(IA_ROOT . $cli);
	}
	$pars['client'] = md5($string);
	return $pars;
}

function _cloud_shipping_parse($dat, $file) {
	if (is_error($dat)) {
		return error(-1, '网络传输错误, 请检查您的cURL是否可用, 或者服务器网络是否正常. ' . $dat['message']);
	}
	$tmp = iunserializer($dat['content']);
	if (is_array($tmp) && is_error($tmp)) {
		if ($tmp['errno'] == '-2') {
			$data = file_get_contents(IA_ROOT . '/framework/version.inc.php');
			file_put_contents(IA_ROOT . '/framework/version.inc.php', str_replace("'x'", "'v'", $data));
		}
		return $tmp;
	}
	if ($dat['content'] == 'patching') {
		return error(-1, '补丁程序正在更新中，请稍后再试！');
	}
	if ($dat['content'] == 'frequent') {
		return error(-1, '更新操作太频繁，请稍后再试！');
	}
	if ($dat['content'] == 'blacklist') {
		return error(-1, '抱歉，您的站点已被列入云服务黑名单，云服务一切业务已被禁止，请联系客服！');
	}
	$data = @file_get_contents($file);
	@unlink($file);
	$ret = @iunserializer($data);
	$ret = iunserializer($ret['data']);
	if (is_array($ret) && is_error($ret)) {
		if ($ret['errno'] == '-2') {
			$data = file_get_contents(IA_ROOT . '/framework/version.inc.php');
			file_put_contents(IA_ROOT . '/framework/version.inc.php', str_replace("'x'", "'v'", $data));
		}
	}
	if (!is_error($ret) && is_array($ret)) {
		if (!empty($ret) && $ret['state'] == 'fatal') {
			return error($ret['errorno'], '发生错误: ' . $ret['message']);
		}
		return $ret;
	} else {
		return error($ret['errno'], "发生错误: {$ret['message']}");
	}
}

function cloud_request($url, $post = '', $extra = array(), $timeout = 60) {
	global $_W;
	load()->func('communication');
	if (!empty($_W['setting']['cloudip']['ip']) && empty($extra['ip'])) {
		$extra['ip'] = $_W['setting']['cloudip']['ip'];
	}
	if (strexists($url, 's.w7.cc')) {
		$extra = array();
	}

	$response = ihttp_request($url, $post, $extra, $timeout);
	if (is_error($response)) {
		setting_save(array(), 'cloudip');
	}
	return $response;
}

function cloud_api($method, $data = array(), $extra = array(), $timeout = 60) {
	$cache_key = cache_system_key($method);
	$cache = cache_load($cache_key);
		if (!empty($cache) && $cache['expire'] > time() && $method != 'module/query') {
		return $cache;
	}
	$api_url = 'http://api.vip432.com/%s';
	$pars = _cloud_build_params();
	if ($method != 'site/token/index') {
		$pars['token'] = cloud_build_transtoken();
	}
	$data = array_merge($pars, $data);
	if ($GLOBALS['_W']['config']['setting']['development'] == 3) {
		$extra['CURLOPT_USERAGENT'] = 'development';
	}
	$response = ihttp_request(sprintf($api_url, $method), $data, $extra, $timeout);
	$file = IA_ROOT . '/data/' . (!empty($data['file']) ? $data['file'] : $data['method']);
	$ret = _cloud_shipping_parse($response, $file);
	cache_write($cache_key, $ret, 300);
	return $ret;
}

function cloud_prepare() {
	global $_W;
	setting_load();
	if(empty($_W['setting']['site']['key']) || empty($_W['setting']['site']['token'])) {
		return error('-1', '站点注册信息丢失, 请通过"重置站点ID和通信密钥"重新获取 !');
	}
	return true;
}

function cloud_build() {
	$pars = _cloud_build_params();
	$pars['method'] = 'application.build3';
	$dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	$file = IA_ROOT . '/data/application.build';
	$ret = _cloud_shipping_parse($dat, $file);

	if (is_error($ret)) {
		return $ret;
	}

	if($ret['state'] == 'warning') {
		$ret['files'] = cloud_client_define();
		unset($ret['schemas']);
		unset($ret['scripts']);
	} else {
		$patch_path = sprintf('%s/data/patch/upgrade/%s', IA_ROOT, date('Ymd'));
		if (!is_dir($patch_path)) {
			mkdirs($patch_path);
		}

		$files = $files_allowed = array();
		if(!empty($ret['files'])) {
			foreach($ret['files'] as $file) {
				$entry = IA_ROOT . $file['path'];
				if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
					$files_allowed[] = $file['path'];
				}

				$entry = $patch_path . $file['path'];
				if (!is_file($entry)) {
					$entry = IA_ROOT . $file['path'];
				}
				if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
					$files[] = $file['path'];
				}
			}
		}
		$ret['files'] = $files;
		if (!empty($ret['files'])) {
			cloud_bakup_files($ret['files']);
		} else {
			if (!empty($files_allowed)) {
				foreach ($files_allowed as $file) {
					$dir = pathinfo(IA_ROOT . $file, PATHINFO_DIRNAME);
					if (!is_dir($dir)) {
						mkdirs($dir);
					}
					file_put_contents(IA_ROOT . $file, file_get_contents($patch_path . $file));
				}
				rmdirs($patch_path);
			}
		}
		$schemas = array();
		if(!empty($ret['schemas'])) {
			load()->func('db');
			foreach($ret['schemas'] as $remote) {
				$name = substr($remote['tablename'], 4);
				$local = db_table_schema(pdo(), $name);
				unset($remote['increment']);
				unset($local['increment']);
				if(empty($local)) {
					$schemas[] = $remote;
				} else {
					$sqls = db_table_fix_sql($local, $remote);
					if(!empty($sqls)) {
						$schemas[] = $remote;
					}
				}
			}
		}
		$ret['schemas'] = $schemas;
	}

	if($ret['family'] == 'x' && IMS_FAMILY == 'v') {
		load()->model('setting');
		setting_upgrade_version('x', IMS_VERSION, IMS_RELEASE_DATE);
		message('您已经购买了商业授权版本, 系统将转换为商业版, 并重新运行自动更新程序.', 'refresh');
	}
	$ret['upgrade'] = false;
	if(!empty($ret['files']) || !empty($ret['schemas']) || !empty($ret['scripts'])) {
		$ret['upgrade'] = true;
	}
	$upgrade = array();
	$upgrade['upgrade'] = $ret['upgrade'];
	$upgrade['data'] = $ret;
	$upgrade['lastupdate'] = TIMESTAMP;
	cache_write(cache_system_key('upgrade'), $upgrade);
	cache_write(cache_system_key('cloud_transtoken'), authcode($ret['token'], 'ENCODE'));

	return $ret;
}

function cloud_schema() {
	$pars = _cloud_build_params();
	$pars['method'] = 'application.schema';
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	$file = IA_ROOT . '/data/application.schema';
	$ret = _cloud_shipping_parse($dat, $file);
	if(!is_error($ret)) {
		$schemas = array();
		if(!empty($ret['schemas'])) {
			load()->func('db');
			foreach($ret['schemas'] as $remote) {
				$name = substr($remote['tablename'], 4);
				$local = db_table_schema(pdo(), $name);
				unset($remote['increment']);
				unset($local['increment']);
				if(empty($local)) {
					$schemas[] = $remote;
				} else {
					$diffs = db_schema_compare($local, $remote);
					if(!empty($diffs)) {
						$schemas[] = $remote;
					}
				}
			}
		}
		$ret['schemas'] = $schemas;
	}
	return $ret;
}

function cloud_download($path, $type = '') {
	global $_W;
	$pars = _cloud_build_params();
	$pars['method'] = 'application.shipping';
	$pars['path'] = $path;
	$pars['type'] = $type;
	$pars['gz'] = function_exists('gzcompress') && function_exists('gzuncompress') ? 'true' : 'false';
	$pars['download'] = 'true';
	$headers = array('content-type' => 'application/x-www-form-urlencoded');
	$dat = cloud_request(HTTP_X_FOR . 'shouquan.daima.net.cn/api/api.php', $pars, $headers, 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	if($dat['content'] == 'success') {
		return true;
	}
	$ret = @json_decode($dat['content'], true);
	if (isset($ret['error'])) {
		return error(1, $ret['error']);
	}
	if(is_error($ret)) {
		return $ret;
	} else {
		$post = $dat['content'];
		$data = base64_decode($post);
		if (base64_encode($data) !== $post) {
			$data = $post;
		}
		$ret = iunserializer($data);
		$gz = function_exists('gzcompress') && function_exists('gzuncompress');
		$file = base64_decode($ret['file']);
		if($gz) {
			$file = gzuncompress($file);
		}
		$_W['setting']['site']['token'] = authcode(cache_load(cache_system_key('cloud_transtoken')), 'DECODE');
		$string = (md5($file) . $ret['path'] . $_W['setting']['site']['token']);
		if(!empty($_W['setting']['site']['token']) && md5($string) === $ret['sign']) {
			$error_file_list = array();
			if (!cloud_file_permission_pass($error_file_list)) {
				return error(-1, '请修复下列文件读写权限 : ' . implode('; ', $error_file_list));
			}
						if ($type == 'module' || $type == 'theme') {
				$patch_path = IA_ROOT;
			} else {
				$patch_path = sprintf('%s/data/patch/upgrade/%s', IA_ROOT, date('Ymd'));
			}
			$path = $patch_path . $ret['path'];
			load()->func('file');
			@mkdirs(dirname($path));
			if (file_put_contents($path, $file)) {
				return true;
			} else {
				return error(-1, '写入失败');
			}
		}
		return error(-1, '写入失败');
	}
}

function cloud_m_prepare($name) {
	$pars['method'] = 'module.check';
	$pars['module'] = $name;
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	if (is_error($dat)) {
		return $dat;
	}
	if ($dat['content'] == 'install-module-protect') {
		return error('-1', '此模块已设置版权保护，您只能通过云平台来安装。');
	}
	return true;
}

/**
 * 获取云服务应用详情
 * @param string $modulename 应用名称
 * @param string $type 附加操作类型
 * /*
 *	'install' => 安装
 *	'upgrade' => 更新
 * 	'uninstall' => 卸载
 * 	默认为空，表示没有任何附加操作
 * /
 * @return array|mixed|string
 */
function cloud_m_build($modulename, $type = '') {
	$type = in_array($type, array('uninstall')) ? $type : '';
	if (empty($modulename)) {
		return array();
	}
	$module = table('modules')->getByName($modulename);
	$pars['method'] = 'module.build';
	$pars['module'] = $modulename;
	$pars['type'] = $type;
	if (!empty($module)) {
		$pars['module_version'] = $module['version'];
	}
	
	$pars['file'] = 'module.build';
	$ret = cloud_api('module/build', $pars);

	if (!is_error($ret)) {
		$dir = IA_ROOT . '/addons/' . $modulename;
		$files = array();
		if (!empty($ret['files'])) {
			foreach ($ret['files'] as $file) {
				$entry = $dir . $file['path'];
				if (!is_file($entry) || md5_file($entry) != $file['checksum']) {
					$files[] = '/' . $modulename . $file['path'];
				}
			}
		}
		$ret['files'] = $files;
		$schemas = array();
		if (!empty($ret['schemas'])) {
			load()->func('db');
			foreach ($ret['schemas'] as $remote) {
				$name = substr($remote['tablename'], 4);
				$local = db_table_schema(pdo(), $name);
				unset($remote['increment']);
				unset($local['increment']);
				if (empty($local)) {
					$schemas[] = $remote;
				} else {
					$diffs = db_table_fix_sql($local, $remote);
					if (!empty($diffs)) {
						$schemas[] = $remote;
					}
				}
			}
		}
		$ret['upgrade'] = true;
		$ret['type'] = 'module';
		$ret['schemas'] = $schemas;
		                //如果是安装模块,根据这个标志不处理script
				if (empty($module)) {
			$ret['install'] = 1;
		}
	}
	return $ret;
}


/**
 * 获取当前站点本地和云服务所有模块详细信息
 * @return array 应用或错误信息
 */
function cloud_m_query($module = array(), $page = 1) {
	$pars['method'] = 'module.query';
	if (empty($module)) {
		$module = cloud_extra_module();
	}
	if (!is_array($module)) {
		$module = array($module);
	}
	$pars['page'] = max(1, intval($page));
	$pars['module'] = base64_encode(iserializer($module));
	$ret = cloud_api('module/query/index', $pars);
	if (isset($ret['error'])) {
		return error(1, $ret['error']);
	}
	if (!is_error($ret)) {
		$pirate_apps = $ret['pirate_apps'];
		unset($ret['pirate_apps']);
		$support_names = array('app', 'wxapp', 'webapp', 'system_welcome', 'android', 'ios', 'xzapp', 'aliapp', 'baiduapp', 'toutiaoapp');

		foreach ($ret['data'] as $modulename => &$info) {
			foreach ($support_names as $support) {
				if (in_array($support, $info['site_branch']['bought']) && !empty($info['site_branch']["{$support}_support"]) && $info['site_branch']["{$support}_support"] == 2) {
					$info['site_branch']["{$support}_support"] = 2;
				} else {
					$info['site_branch']["{$support}_support"] = 1;
				}
			}
		}
		$ret['pirate_apps'] = $pirate_apps;
	}
	return $ret;
}


function cloud_m_bought() {
	$pars = _cloud_build_params();
	$pars['method'] = 'module.bought';
	$dat = cloud_request(HTTP_X_FOR . 'shouquan.daima.net.cn/api/api.php', $pars);
	$file = IA_ROOT . '/data/module.bought';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}

function cloud_m_info($name) {
	$pars['method'] = 'module.info';
	$pars['module'] = $name;
	$ret = cloud_api('module/info', $pars);
	return $ret;
}


/**
 * 获取云服务模块更新信息详情
 * @param string $name 应用名称
 * @return array|mixed|string
 */
function cloud_m_upgradeinfo($modulename) {
	load()->model('module');

	$module = module_fetch($modulename);

	$pars['method'] = 'module.info';
	$pars['module'] = $modulename;
	$pars['curversion'] = $module['version'];
	$pars['isupgrade'] = 1;
	$ret = cloud_api('module/info', $pars);

	if (empty($ret)) {
		return array();
	}
	if (is_error($ret)) {
		return $ret;
	}
	if (version_compare($ret['version']['version'], $module['version'], '>')) {
		$ret['upgrade'] = true;
	}

	$ret['site_branch'] = $ret['branches'][$ret['version']['branch_id']];
	$ret['from'] = 'cloud';
	foreach ($ret['branches'] as &$branch) {
		if ($branch['displayorder'] > $ret['site_branch']['displayorder'] || ($branch['displayorder'] == $ret['site_branch']['displayorder'] && $ret['site_branch']['id'] < intval($branch['id']))) {
			$ret['new_branch'] = true;
		}
		$branch['id'] = intval($branch['id']);
		$branch['version']['description'] = preg_replace('/\n/', '<br/>', $branch['version']['description']);
		$branch['displayorder'] = intval($branch['displayorder']);
		$branch['day'] = intval(date('d', $branch['version']['createtime']));
		$branch['month'] = date('Y.m', $branch['version']['createtime']);
		$branch['hour'] = date('H:i', $branch['version']['createtime']);
	}
	unset($branch);
	return $ret;
}

function cloud_t_prepare($name) {
	$pars['method'] = 'theme.check';
	$pars['theme'] = $name;
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	if (is_error($dat)) {
		return $dat;
	}
	if ($dat['content'] == 'install-theme-protect') {
		return error('-1', '此模板已设置版权保护，您只能通过云平台来安装。');
	}
	return true;
}


/**
 * 获取当前站点本地和云服务所有模板详细信息
 * @return array 应用或错误信息
 */
function cloud_t_query() {
	$pars = _cloud_build_params();
	$pars['method'] = 'theme.query';
	$pars['theme'] = cloud_extra_theme();
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	$file = IA_ROOT . '/data/theme.query';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}

function cloud_t_info($name) {
	$pars = _cloud_build_params();
	$pars['method'] = 'theme.info';
	$pars['theme'] = $name;
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	$file = IA_ROOT . '/data/theme.info';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}

function cloud_t_build($name) {
	if (empty($name)) {
		return array();
	}
	$theme = table('site_templates')->getByName(trim($name));
	$pars = _cloud_build_params();
	$pars['method'] = 'theme.build';
	$pars['theme'] = $name;
	$pars['token'] = cloud_build_transtoken();
	if(!empty($theme)) {
		$pars['themeversion'] = $theme['version'];
	}
    $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	$file = IA_ROOT . '/data/theme.build';
	$ret = _cloud_shipping_parse($dat, $file);
	if(!is_error($ret)) {
		$dir = IA_ROOT . '/app/themes/' . $name;
		$files = array();
		if(!empty($ret['files'])) {
			foreach($ret['files'] as $file) {
				$entry = $dir . $file['path'];
				if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
					$files[] = '/'. $name . $file['path'];
				}
			}
		}
		$ret['files'] = $files;
		$ret['upgrade'] = true;
		$ret['type'] = 'theme';
		                //如果是安装模块,根据这个标志不处理script
				if(empty($theme)) {
			$ret['install'] = 1;
		}
	}
	return $ret;
}

/**
 * 获取云服务模板更新信息详情
 * @param string $name 模板名称
 * @return array|mixed|string
 */
function cloud_t_upgradeinfo($name) {
	if (empty($name)) {
		return array();
	}
	$theme = table('site_templates')->getByName(trim($name));
	$pars = _cloud_build_params();
	$pars['method'] = 'theme.upgrade';
	$pars['theme'] = $theme['name'];
	$pars['version'] = $theme['version'];
	$pars['isupgrade'] = 1;
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	$file = IA_ROOT . '/data/module.info';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}

/**
 * 后台皮肤接口 start
 */
function cloud_w_prepare($name) {
	$pars['method'] = 'webtheme.check';
	$pars['webtheme'] = $name;
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	if (is_error($dat)) {
		return $dat;
	}
	if ($dat['content'] == 'install-webtheme-protect') {
		return error('-1', '此后台皮肤已设置版权保护，您只能通过云平台来安装。');
	}
	return true;
}

/**
 * 获取当前站点本地和云服务所有后台皮肤详细信息
 * @return array 应用或错误信息
 */
function cloud_w_query() {
	$pars = _cloud_build_params();
	$pars['method'] = 'webtheme.query';
	$pars['webtheme'] = cloud_extra_webtheme();
	$dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	$file = IA_ROOT . '/data/webtheme.query';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}


function cloud_w_info($name) {
	$pars = _cloud_build_params();
	$pars['method'] = 'webtheme.info';
	$pars['webtheme'] = $name;
	$dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	$file = IA_ROOT . '/data/webtheme.info';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}


function cloud_w_build($name) {
	$sql = 'SELECT * FROM ' . tablename('webtheme_homepages') . ' WHERE `name`=:name';
	$webtheme = pdo_fetch($sql, array(':name' => $name));

	$pars = _cloud_build_params();
	$pars['method'] = 'webtheme.build';
	$pars['webtheme'] = $name;
	if(!empty($webtheme)) {
		$pars['webtheme_version'] = $webtheme['version'];
	}
	$dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	$file = IA_ROOT . '/data/webtheme.build';
	$ret = _cloud_shipping_parse($dat, $file);
	if(!is_error($ret)) {
		$dir = IA_ROOT . '/web/themes/' . $name;
		$files = array();
		if(!empty($ret['files'])) {
			foreach($ret['files'] as $file) {
				$entry = $dir . $file['path'];
				if(!is_file($entry) || md5_file($entry) != $file['checksum']) {
					$files[] = '/'. $name . $file['path'];							}
			}
		}
		$ret['files'] = $files;
		$ret['upgrade'] = true;
		$ret['type'] = 'webtheme';
				if(empty($webtheme)) {
			$ret['install'] = 1;
		}
		cache_write(cache_system_key('cloud_transtoken'), authcode($ret['token'], 'ENCODE'));
	}
	return $ret;
}

/**
 * 获取云服务主页模板更新信息详情
 * @param string $name 主页模板名称
 * @return array|mixed|string
 */
function cloud_w_upgradeinfo($name) {
	$sql = 'SELECT `name`, `version` FROM ' . tablename('webtheme_homepages') . ' WHERE `name` = :name';
	$webtheme = pdo_fetch($sql, array(':name' => $name));
	$pars = _cloud_build_params();
	$pars['method'] = 'webtheme.upgrade';
	$pars['webtheme'] = $webtheme['name'];
	$pars['version'] = $webtheme['version'];
	$pars['isupgrade'] = 1;
	$dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	$file = IA_ROOT . '/data/webtheme.info';
	$ret = _cloud_shipping_parse($dat, $file);
	return $ret;
}

/**
 * 后台皮肤接口 end
 */
function cloud_sms_send($mobile, $content, $postdata = array(), $custom_sign = '', $use_system_balance = false) {
	global $_W;
	if(!preg_match('/^1\d{10}$/', $mobile) || empty($content)) {
		return error(1, '发送短信失败, 原因: 手机号错误或内容为空.');
	}

		$row = pdo_get('uni_settings' , array('uniacid' => $uniacid), array('notify'));
		$row['notify'] = @iunserializer($row['notify']);

		$config = $row['notify']['sms'];
		$balance = intval($config['balance']);
        
        $sign = $config['signature'];
        if(empty($sign)) {
                $sign = '短信宝';
        }
        //判断剩余条数
        if($balance<1){
                return error(-1, '短信发送失败, 原因:余额不足');
        }
        //短信宝账号
        $smsbao_info=pdo_get('uni_settings' , array('uniacid' => $_W['uniacid']), array('copyright'));
        $sms_param['u']=$_W['setting']['copyright']['sms_name'];
        $sms_param['p']=md5($_W['setting']['copyright']['sms_password']);
        $sms_param['m']=$mobile;
        $sms_param['c']='【'.$sign.'】'.$content;
        $response = file_get_contents("http://api.smsbao.com/sms?".http_build_query($sms_param));
        if (trim($response)!='0') {
                return error($response, '短信发送失败, 原因:'.$response);
	}
 
        if (trim($response)=='0') {
			$row['notify']['sms']['balance'] = $row['notify']['sms']['balance'] - 1;
			if ($row['notify']['sms']['balance'] < 0) {
				$row['notify']['sms']['balance'] = 0;
			}
			pdo_update('uni_settings', array('notify' => iserializer($row['notify'])), array('uniacid' => $uniacid));
			uni_setting_save('notify', $row['notify']);
			}
	return true;
}


/**
 * 获取当前站点可用短信签名.
 */
function cloud_sms_info() {
        global $_W;
 
        $data=[];
        //返回短信的剩余条数以及签名
        $sms_name=$_W['setting']['copyright']['sms_name'];
        $sms_password=md5($_W['setting']['copyright']['sms_password']);
        $res=file_get_contents("http://api.smsbao.com/query?u={$sms_name}&p={$sms_password}");
        $retArr = split("\n", $res);
        $balanceArr = split(",", $retArr[1]);
        $data['sms_count']=$retArr[0] == 0 ? $balanceArr[1]:0;
        return $data;
}

/**
 * 获取当前站点所有公众号信息
 * @return string 公众号序列化
 */
function cloud_extra_account() {
	$data = array();
	$data['accounts'] = pdo_fetchall("SELECT name, account, original FROM ".tablename('account_wechats') . " GROUP BY account");
	return serialize($data);
}


/**
 * 获取当前站点所有本地模块
 * @return string 模块标识序列化
 */
function cloud_extra_module() {
	return table('modules')->getInstalled();
}


/**
 * 获取当前站点所有本地模板
 * @return string 模板标识序列化
 */
function cloud_extra_theme() {
	$sql = 'SELECT `name` FROM ' . tablename('site_templates') . ' WHERE `name` <> :name';
	$themes = pdo_fetchall($sql, array(':name' => 'default'), 'name');
	if (!empty($themes)) {
		return base64_encode(iserializer(array_keys($themes)));
	} else {
		return '';
	}
}


/**
 * 获取当前站点所有本地后台皮肤
 * @return string 后台皮肤标识序列化
 */
function cloud_extra_webtheme() {
	$sql = 'SELECT `name` FROM ' . tablename('webtheme_templates') . ' WHERE `name` <> :name';
	$themes = pdo_fetchall($sql, array(':name' => 'default'), 'name');
	if (!empty($themes)) {
		return base64_encode(iserializer(array_keys($themes)));
	} else {
		return '';
	}
}

function cloud_module_setting($acid, $module) {
	$pars = array(
		'acid' => $acid,
		'module_name' => $module['name'],
		'module_version' => $module['version'],
	);
	return cloud_api('module/setting/index', $pars);
}

function cloud_module_setting_save($acid, $module_name, $setting) {
	$pars = array(
		'acid' => $acid,
		'module_name' => $module_name,
		'setting' => $setting,
	);
	return cloud_api('module/setting/save', $pars);
}

function cloud_api_redirect($method, $params = array()) {
	$pars = array(
		'method' => $method,
		'params' => $params,
	);
	return cloud_api('util/api/index', $pars);
}

/**
 * 云服务创建计划任务
 * @param array $cron 计划任务数据
 * @return array 创建结果
 */
function cloud_cron_create($cron) {
	$pars = array(
		'cron' => base64_encode(iserializer($cron)),
	);
	return cloud_api('site/cron/save', $pars);
}


/**
 * 云服务更新计划任务
 * @param array $cron 计划任务数据
 * @return array 更新结果
 */
function cloud_cron_update($cron) {
	return cloud_cron_create($cron); }


/**
 * 获取云服务计划任务信息
 * @param int $cron_id 计划任务ID
 * @return array 计划任务或错误信息
 */
function cloud_cron_get($cron_id) {
	$pars = array(
		'cron_id' => $cron_id,
	);
	return cloud_api('site/cron/get', $pars);
}

/**
 * 云服务计划任务状态修改
 * @param int $cron_id 计划任务ID
 * @param int $status 计划任务状态
 * @return array 状态更改结果或错误信息
 */
function cloud_cron_change_status($cron_id, $status) {
	$pars = array(
		'cron_id' => $cron_id,
		'status' => $status,
	);
	return cloud_api('site/cron/status', $pars);
}

/**
 * 云服务计划任务删除
 * @param int $cron_id 计划任务ID
 * @return array 删除结果或错误信息
 */
function cloud_cron_remove($cron_id) {
	$pars = array(
		'cron_id' => $cron_id,
	);
	return cloud_api('site/cron/remove', $pars);
}


/**
 * 云服务计划任务返回数据解析
 * @param array $result 计划任务返回数据
 * @return array 解析结果或错误信息
 */
function _cloud_response_parse($result) {
	if (empty($result)) {
		return error(-1, '没有接收到服务器的传输的数据');
	}
	if ($result['content'] == 'blacklist') {
		return error(-1, '抱歉，您的站点已被列入云服务黑名单，云服务一切业务已被禁止，请联系客服！');
	}
	$result = json_decode($result['content'], true);
	if (null === $result) {
		return error(-1, '云服务通讯发生错误，请稍后重新尝试！');
	}
	if (!empty($result['error'])) {
		return error(-1, $result['error']);
	}
	return $result;
}

function cloud_site_info() {
	return cloud_api('site/info');
}

function cloud_reset_siteinfo() {
	global $_W;
	return cloud_api('site/register/profile', array('url' => $_W['siteroot']));
}
/**
 * 生成附件站点信息的云服务跳转地址
 * @param string $forward 回调地址
 * @param array $data 站点数据
 * @return string 跳转地址
 */
function cloud_auth_url($forward, $data = array()){
	global $_W;
	if (!empty($_W['setting']['site']['url']) && !strexists($_W['siteroot'], $_W['setting']['site']['url'])) {
		$url = $_W['setting']['site']['url'];
	} else {
		$url = rtrim($_W['siteroot'], '/');
	}
	$auth = array();
	$auth['key'] = '';
	$auth['password'] = '';
	$auth['url'] = $url;
	$auth['referrer'] = intval($_W['config']['setting']['referrer']);
	$auth['version'] = IMS_VERSION;
	$auth['forward'] = $forward;
	$auth['family'] = IMS_FAMILY;

	if(!empty($_W['setting']['site']['key']) && !empty($_W['setting']['site']['token'])) {
		$auth['key'] = $_W['setting']['site']['key'];
		$auth['password'] = md5($_W['setting']['site']['key'] . $_W['setting']['site']['token']);
	}
	if ($data && is_array($data)) {
		$auth = array_merge($auth, $data);
	}
	$query = base64_encode(json_encode($auth));
        $auth_url = HTTP_X_FOR .'shouquan.daima.net.cn/api/auth.php?__auth=' . $query;
	return $auth_url;
}


/**
 * module setting cloud
 * @param array $module
 * @param string $bindings
 * @return string iframe
 */
function cloud_module_setting_prepare($module, $binding) {
	global $_W;
	$auth = _cloud_build_params();
	$auth['arguments'] = array(
		'binding' => $binding,
		'acid' => $_W['uniacid'],
		'type' => 'module',
		'module' => $module,
	);
	$iframe_auth_url = cloud_auth_url('module', $auth);

	return $iframe_auth_url;
}

/**
 * 云文件资源保存为本地资源
 * @param int $uniacid
 * @param string $type
 * @param string $url
 * @return array attachment
 */
function cloud_resource_to_local($uniacid, $type, $url){
	global $_W;

	load()->func('file');

	$setting = $_W['setting']['upload'][$type];
	if (substr($url, 0, 2) == '//') {
		$url = 'http:' . $url;
	}

	if (!file_is_image($url)) {
		return error(1, '远程图片后缀非法,请重新上传');
	}
	$pathinfo = pathinfo($url);
	$extension = $pathinfo['extension'];

	if ($uniacid == 0) {
		$setting['folder'] = "{$type}s/global/".date('Y/m/');
	} else {
		$setting['folder'] = "{$type}s/{$uniacid}/".date('Y/m/');
	}

	$originname = pathinfo($url, PATHINFO_BASENAME);
	$filename = file_random_name(ATTACHMENT_ROOT .'/'. $setting['folder'], $extension);
	$pathname = $setting['folder'] . $filename;
	$fullname = ATTACHMENT_ROOT . $pathname;

	mkdirs(dirname($fullname));

	load()->func('communication');
	$response = ihttp_get($url);
	if (is_error($response)) {
		return error(1, $response['message']);
	}
	if (file_put_contents($fullname, $response['content']) == false) {
		return error(1, '提取文件失败');
	}

	if (!empty($_W['setting']['remote']['type'])) {
		$remotestatus = file_remote_upload($pathname);
		if (is_error($remotestatus)) {
			return error(1, '远程附件上传失败，请检查配置并重新上传');
		} else {
			file_delete($pathname);
		}
	}

	$data = array(
		'uniacid' => $uniacid,
		'uid' => intval($_W['uid']),
		'filename' => $originname,
		'attachment' => $pathname,
		'type' => $type == 'image' ? 1 : 2,
		'createtime' => TIMESTAMP,
	);
	pdo_insert('core_attachment', $data);

	$data['url'] = tomedia($pathname);
	$data['id'] = pdo_insertid();

	return $data;
}

function cloud_bakup_files($files) {
	global $_W;
	if (empty($files)) {
		return false;
	}
	$map = json_encode($files);
	$hash  = md5($map.$_W['config']['setting']['authkey']);
	if ($handle = opendir(IA_ROOT . '/data/patch/backup/' . date('Ymd'))) {
		while (false !== ($patchpath = readdir($handle))) {
			if ($patchpath != '.' && $patchpath != '..') {
				if (strexists($patchpath, $hash)) {
					return false;
				}
			}
		}
	}

	$path = IA_ROOT . '/data/patch/backup/' . date('Ymd') . '/' . date('Hi') . '_' . $hash;
	load()->func('file');
	if (!is_dir($path) && mkdirs($path)) {
		foreach ($files as $file) {
			if (file_exists(IA_ROOT . $file)) {
				mkdirs($path . '/' . dirname($file));
				file_put_contents($path . '/' . $file, file_get_contents(IA_ROOT . $file));
			}
		}
		file_put_contents($path . '/' . 'map.json', $map);
	}
	return false;
}

/**
 * 流量
 * @param array $flow_master
 * @return array|error
 */
function cloud_flow_master_post($flow_master) {
	$pars = _cloud_build_params();
	$pars['method'] = 'flow.master_post';
	$pars['flow_master'] = array(
		'linkman' => $flow_master['linkman'],
		'mobile' => $flow_master['mobile'],
		'address' => $flow_master['address'],
		'id_card_photo' => $flow_master['id_card_photo'], 		'business_licence_photo' => $flow_master['business_licence_photo'], 	);
	$dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars, array(), 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	cache_delete(cache_system_key('cloud_flow_master'));
	$ret = @json_decode($dat['content'], true);
	return $ret;
}

/**
 *
 * @param array $flow_master
 * @return array
 */
function cloud_flow_master_get() {
	$cachekey = cache_system_key('cloud_flow_master');
	$cache = cache_load($cachekey);
	if(!empty($cache) && $cache['expire'] > TIMESTAMP) {
		return $cache['setting'];
	}
	$pars = _cloud_build_params();
	$pars['method'] = 'flow.master_get';
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars, array() , 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	$ret = @json_decode($dat['content'], true);
	if ($ret['status'] == '3') {
		cache_write($cachekey, array('expire' => TIMESTAMP + 300, 'setting' => $ret));
	} else if ($ret['status'] == '4') {
		cache_write($cachekey, array('expire' => TIMESTAMP + 12 * 3600, 'setting' => $ret));
	}
	return $ret;
}


function cloud_flow_uniaccount_post($uniaccount) {
	$pars = _cloud_build_params();
	$pars['method'] = 'flow.uniaccount_post';
	$pars['uniaccount'] = array(
		'uniacid' => $uniaccount['uniacid'],
	);
	isset($uniaccount['title']) && $pars['uniaccount']['title'] = $uniaccount['title']; 	isset($uniaccount['original']) && $pars['uniaccount']['original'] = $uniaccount['original']; 	isset($uniaccount['gh_type']) && $pars['uniaccount']['gh_type'] = $uniaccount['gh_type']; 	isset($uniaccount['ad_tags']) && $pars['uniaccount']['ad_tags'] = $uniaccount['ad_tags']; 	isset($uniaccount['enable']) && $pars['uniaccount']['enable'] = $uniaccount['enable']; 	$dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars, array(), 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	cache_delete(cache_system_key('cloud_ad_uniaccount', array('uniacid' => $uniaccount['uniacid'])));
	cache_delete(cache_system_key('cloud_ad_uniaccount_list'));
	$ret = @json_decode($dat['content'], true);
	return $ret;
}


function cloud_flow_uniaccount_get($uniacid) {
	$cachekey = cache_system_key('cloud_ad_uniaccount', array('uniacid' => $uniacid));
	$cache = cache_load($cachekey);
	if(!empty($cache) && $cache['expire'] > TIMESTAMP) {
		return $cache['setting'];
	}
	$pars = _cloud_build_params();
	$pars['method'] = 'flow.uniaccount_get';
	$pars['uniaccount'] = array(
		'uniacid' => $uniacid,
	);
	$pars['md5'] = md5(base64_encode(serialize($pars['uniaccount'])));
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars, array() , 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	$ret = @json_decode($dat['content'], true);
	cache_write($cachekey, array('expire' => TIMESTAMP + 600, 'setting' => $ret));
	return $ret;
}


function cloud_flow_uniaccount_list_get() {
	$cachekey = cache_system_key('cloud_ad_uniaccount_list');
	$cache = cache_load($cachekey);
	if(!empty($cache) && $cache['expire'] > TIMESTAMP) {
		return $cache['setting'];
	}
	$pars = _cloud_build_params();
	$pars['method'] = 'flow.uniaccount_list_get';
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars, array() , 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	$ret = @json_decode($dat['content'], true);
	cache_write($cachekey, array('expire' => TIMESTAMP + 600, 'setting' => $ret));
	return $ret;
}


function cloud_flow_ad_tag_list() {
	$cachekey = cache_system_key('cloud_ad_tags');
	$cache = cache_load($cachekey);
	if(!empty($cache) && $cache['expire'] > TIMESTAMP) {
		return $cache['items'];
	}
	$pars = _cloud_build_params();
	$pars['method'] = 'flow.ad_tag_list';
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars, array() , 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	$ret = @json_decode($dat['content'], true);
	cache_write($cachekey, array('expire' => TIMESTAMP + 6 * 3600, 'items' => $ret));
	return $ret;
}


function cloud_flow_ad_type_list() {
	$cachekey = cache_system_key('cloud_ad_type_list');
	$cache = cache_load($cachekey);
	if(!empty($cache) && $cache['expire'] > TIMESTAMP) {
		return $cache['items'];
	}
	$pars = _cloud_build_params();
	$pars['method'] = 'flow.ad_type_list';
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars, array() , 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	$ret = @json_decode($dat['content'], true);
	cache_write($cachekey, array('expire' => TIMESTAMP + 3600, 'items' => $ret));
	return $ret;
}


function cloud_flow_app_post($uniacid, $module_name, $enable = 0, $ad_types = null) {
	$pars = _cloud_build_params();
	$pars['method'] = 'flow.app_post';
	$pars['uniaccount_app'] = array(
		'uniacid' => $uniacid,
		'module' => $module_name,
	);
	if (!empty($enable)) {
		$pars['uniaccount_app']['enable'] = $enable; 	}
	if (is_array($ad_types)) {
		$pars['uniaccount_app']['ad_types'] = $ad_types; 	}
	$dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars, array(), 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	cache_delete(cache_system_key('cloud_ad_app_list', array('uniacid' => $uniacid)));
	$ret = @json_decode($dat['content'], true);
	return $ret;
}


/*
 * 公众号下所有应用的设置
 */
function cloud_flow_app_list_get($uniacid) {
	$cachekey = cache_system_key('cloud_ad_app_list', array('uniacid' => $uniacid));
	$cache = cache_load($cachekey);
	if(!empty($cache) && $cache['expire'] > TIMESTAMP) {
		return $cache['setting'];
	}
	$pars = _cloud_build_params();
	$pars['method'] = 'flow.app_list_get';
	$pars['uniaccount'] = array(
		'uniacid' => $uniacid,
	);
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars, array() , 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	$ret = @json_decode($dat['content'], true);
	cache_write($cachekey, array('expire' => TIMESTAMP + 300, 'setting' => $ret));
	return $ret;
}


function cloud_flow_app_support_list($module_names) {
	if (empty($module_names)) {
		return array();
	}
	$cachekey = cache_system_key('cloud_ad_app_support_list');
	$cache = cache_load($cachekey);
	if(!empty($cache) && $cache['expire'] > TIMESTAMP) {
		return $cache['setting'];
	}
	$pars = _cloud_build_params();
	$pars['method'] = 'flow.app_support_list';
	$pars['modules'] = $module_names;
        $dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars, array() , 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	$ret = @json_decode($dat['content'], true);
	cache_write($cachekey, array('expire' => TIMESTAMP + 300, 'setting' => $ret));
	return $ret;
}


function cloud_flow_site_stat_day($condition) {
	$cachekey = cache_system_key('cloud_ad_site_finance');
	$cache = cache_load($cachekey);
	if(!empty($cache) && $cache['expire'] > TIMESTAMP) {
		return $cache['info'];
	}
	$pars = _cloud_build_params();
	$pars['method'] = 'flow.site_stat_day';
	$pars['condition'] = array();
	$pars['condition']['starttime'] = $condition['starttime'];
	$pars['condition']['endtime'] = $condition['endtime'];
	$pars['condition']['page'] = $condition['page'];
	$pars['condition']['size'] = $condition['size'];

	$dat = cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars, array() , 300);
	if(is_error($dat)) {
		return error(-1, '网络存在错误， 请稍后重试。' . $dat['message']);
	}
	$ret = @json_decode($dat['content'], true);
	cache_write($cachekey, array('expire' => TIMESTAMP + 300, 'info' => $ret));
	return $ret;
}

function cloud_build_transtoken() {
	$pars = _cloud_build_params();
	$pars['method'] = 'application.token';
	$dat = cloud_request(CLOUD_GATEWAY_URL_NORMAL, $pars);
	$file = IA_ROOT . '/data/application.build';
	$ret = _cloud_shipping_parse($dat, $file);
	cache_write(cache_system_key('cloud_transtoken'), authcode($ret['token'], 'ENCODE'));
	return $ret['token'];
}
/**
 * 数据库更新信息
 * @param $schems array 云服务返回的数据库信息
 * @return array 数据库更新信息
 */
function cloud_build_schemas($schemas) {
	$database = array();
	if (empty($schemas) || !is_array($schemas)) {
		return $database;
	}
	foreach ($schemas as $remote) {
		$row = array();
		$row['tablename'] = $remote['tablename'];
		$name = substr($remote['tablename'], 4);
		$local = db_table_schema(pdo(), $name);
		unset($remote['increment']);
		unset($local['increment']);
		if (empty($local)) {
			$row['new'] = true;
		} else {
			$row['new'] = false;
			$row['fields'] = array();
			$row['indexes'] = array();
			$diffs = db_schema_compare($local, $remote);
			if (!empty($diffs['fields']['less'])) {
				$row['fields'] = array_merge($row['fields'], $diffs['fields']['less']);
			}
			if (!empty($diffs['fields']['diff'])) {
				$row['fields'] = array_merge($row['fields'], $diffs['fields']['diff']);
			}
			if (!empty($diffs['indexes']['less'])) {
				$row['indexes'] = array_merge($row['indexes'], $diffs['indexes']['less']);
			}
			if (!empty($diffs['indexes']['diff'])) {
				$row['indexes'] = array_merge($row['indexes'], $diffs['indexes']['diff']);
			}
			$row['fields'] = implode($row['fields'], ' ');
			$row['indexes'] = implode($row['indexes'], ' ');
		}
		$database[] = $row;
	}
	return $database;
}

/**
 * 检测网站关键文件是否有写入权限，从而判断是否执行更新操作
 * @param array $error_file_list 存放权限有问题的文件名
 * @return boolean 为
 */
function cloud_file_permission_pass(&$error_file_list = array()) {

	$check_path = array(
		'/app/common',
		'/app/source',
		'/app/themes/default',
		'/web/common',
		'/web/source',
		'/web/themes/default',
		'/web/themes/black',
		'/web/themes/classical',
		'/web/themes/2.0',
		'/framework/class',
		'/framework/model',
		'/framework/function',
		'/framework/table',
		'/framework/library/agent',
	);

	$check_file = array(
		'/web/index.php',
		'/framework/bootstrap.inc.php',
		'/framework/version.inc.php',
		'/framework/const.inc.php',
	);
	$sub_paths = array();
	foreach ($check_path as $path) {
		$file_list = cloud_file_tree(IA_ROOT . $path);
		if (!empty($file_list)) {
			foreach ($file_list as $file) {
				if (is_file($file)) {
					$sub_path = pathinfo($file, PATHINFO_DIRNAME);
					if (empty($sub_paths[$sub_path])) {
						if (!cloud_path_is_writable($sub_path)) {
							$error_file_list[] = str_replace(IA_ROOT, '', $sub_path);
						}
						$sub_paths[$sub_path] = $sub_path;
					}
				}
				if (!is_writable($file)) {
					$error_file_list[] = str_replace(IA_ROOT, '', $file);
				}
			}
		}
	}

	foreach ($check_file as $file) {
		if (!is_writable(IA_ROOT . $file)) {
			$error_file_list[] = str_replace(IA_ROOT, '', $file);
		}
	}
	return empty($error_file_list) ? true : false;
}

function cloud_file_tree($path, $include = array()) {
	$files = array();
	if (!empty($include)) {
		$ds = glob($path . '/{' . implode(',', $include) . '}', GLOB_BRACE);
	} else {
		$ds = glob($path . '/*');
	}
	if (is_array($ds)) {
		foreach ($ds as $entry) {
			if (is_file($entry)) {
				$files[] = $entry;
			}
			if (is_dir($entry)) {
				$rs = cloud_file_tree($entry);
				foreach ($rs as $f) {
					$files[] = $f;
				}
			}
		}
	}
	return $files;
}

function cloud_path_is_writable($dir) {
	$writeable = false;
	if (!is_dir($dir)) {
		@mkdir($dir, 0755);
	}
	if (is_dir($dir)) {
		if($fp = fopen("$dir/test.txt", 'w')) {
			fclose($fp);
			unlink("$dir/test.txt");
			$writeable = true;
		} else {
			$writeable = false;
		}
	}
	return $writeable;
}


/**
 * 从云服务获取推送的消息
 * @return array()
 */
function cloud_get_store_notice() {
	load()->classs('cloudapi');
	$api = new CloudApi();
	$result = $api->get('store', 'official_dynamics');
	return $result;
}


function cloud_v_to_xs($url) {
	if (empty($url)) {
		return false;
	}
	$pars = _cloud_build_params();
	$pars['method'] = 'module.query';
	$pars['url'] = urlencode($url);
	cloud_request(HTTP_X_FOR .'shouquan.daima.net.cn/api/api.php', $pars);
	return true;
}

function cloud_workorder() {
	$result = cloud_api('work-order/status/index');
	return $result;
}

function get_url_content(){
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	$result=curl_exec($ch);
	return $result;
}