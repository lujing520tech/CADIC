<?php
/**
 * cadiy_daily模块定义
 *
 * @author lujing520
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Cadiy_dailyModule extends WeModule {


	public function welcomeDisplay($menus = array()) {
		//这里来展示DIY管理界面
		include $this->template('welcome');
	}
}