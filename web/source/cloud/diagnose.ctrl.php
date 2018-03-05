<?php 
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

load()->model('cloud');
load()->func('communication');

$dos = array('diagnose');
$do = in_array($do, $dos) ? $do : 'diagnose';

if($do == 'diagnose') {
	define('ACTIVE_FRAME_URL', url('cloud/diagnose'));
	$iframe = cloud_auth_url('profile');
	$title = '注册站点';
}

	
template('cloud/diagnose');
