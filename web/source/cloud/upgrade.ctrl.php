<?php
//更新
defined('IN_IA') or exit('Access Denied');
load()->func('file');
load()->func('up');
load()->func('db');
$_W['page']['title'] = '系统升级';
	global $_W,$_GPC;  
	if(empty($_GPC['op'])) {
		$_GPC['op'] = 'display';
	}
    $ver = include(IA_ROOT.'/data/version.php');
    $family = include(IA_ROOT.'/data/version.php');
    $releasedate = include(IA_ROOT.'/data/version.php');
    $ver = $ver['ver'];
    $family = $family['family'];
    $releasedate = $releasedate['releasedate'];
	$hosturl = $_SERVER['HTTP_HOST'];
	$updatehost = CLOUD_API_URL;
    $updatedir = IA_ROOT.'/data/update';
	$dbname = $_W['config']['db']['master'] ['database'];
	$result = array();
	$res = array();

	$tables = SendCurl($updatehost.'?a=display&u='.$hosturl.'&v='.$ver,$result);
	$localtable = local_schemas();
	$difftable = diff_schemas($localtable,$tables['tablelist']);
	$tablelist = array_diff($tables['tablelist'],$difftable);

	$path = IA_ROOT;
	$pathlist = to_md5($path);
	$result['path'] = json_encode($pathlist);
	$updatetable = local_tablelist($tablelist);
	$result['schemas'] = json_encode($updatetable);

	if($_GPC['op'] == 'display'){
		$op = $_GPC['op'];
      
        $return = SendCurl($updatehost.'?a=display&u='.$hosturl.'&v='.$ver,$result);         
            
      	$upgrade = $return;
		$result['tablediff'] =  json_encode($difftable);     
     
        if(!empty($upgrade)){
          if(!is_dir($updatedir)) {
            mkdirs($updatedir,0777);
            chmod($updatedir,0777);
          }
          $mapfile = $updatedir.'/res.json';
          $content = json_encode($result);
          $ret = file_put_contents($mapfile, $content, TRUE);
        }
      
		$filelist = array_filter($upgrade['B']);
      
      	$tableli = $upgrade['ret'] ? $upgrade['ret'] : $res;
        if(!empty($result['tablediff'])){
  			$dbcheck = array_merge($difftable,$tableli);
        }else{
            $dbcheck = array_filter($upgrade['ret']);
        }
      	if(!empty($dbcheck)){
      		$dblistc = count($dbcheck);
        }else{
      		$dblistc = 0;
        }

      	if(!empty($filelist)){
      		$filelistc = count($filelist);
        }else{
      		$filelistc = 0;
        }
	}if($_GPC['op'] == 'make'){
      	$op = $_GPC['op'];	
	}

template('cloud/upgrade');
