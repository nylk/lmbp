<?php
//更新
defined('IN_IA') or exit('Access Denied');
load()->func('file');
load()->func('up');
load()->func('db');
$_W['page']['title'] = '授权';
	global $_W,$_GPC;  
	if(empty($_GPC['op'])) {
		$_GPC['op'] = 'display';
	}
	$hosturl = $_SERVER['HTTP_HOST'];
	$updatehost = CLOUD_API_URL;
	$auth = CLOUD_API_AUTH;
	if($_GPC['op'] == 'display'){
		$op = $_GPC['op'];      
        $return = SendCurl($updatehost.'?a=display&u='.$hosturl);
        $hostip = $updatehost.'?a=getip';
        $hostip_info=file_get_contents($hostip);                     
      	$upgrade = $return;    
	}

template('cloud/auth');
