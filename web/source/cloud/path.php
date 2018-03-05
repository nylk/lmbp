<?php

require_once './../../../framework/bootstrap.inc.php';

load()->func('communication');
load()->model('cloud');
load()->func('file');
load()->func('up');


$path = $_GET['path'];

	$pathl = IA_ROOT;
	$hosturl = $_SERVER['HTTP_HOST'];
	$updatehost = CLOUD_API_URL;
    $updatedir = IA_ROOT.'/data/update';
	$backdir = IA_ROOT.'/data/patch';

if($_GET['type'] == 'file'){
  
  	$paths = array('file' => $path );
  	$file = SendCurl($updatehost.'?a=file&u='.$hosturl,$paths);
  	$filterl = file_back($pathl, $file, $backdir, $path);

	echo $filterl;
}

if($_GET['type'] == 'module'){
  	$mname = $_GET['mname'];
  	$pathl = IA_ROOT."/addons/".$mname;
  	$paths = array('file' => $path );
  	$file = SendCurl($updatehost.'?a=mfile&u='.$hosturl.'&m='.$mname,$paths);
  	$filterl = file_back($pathl, $file, $backdir, $path);

	echo $filterl;
}

if($_GET['type'] == 'db'){
  
	echo '1111';
}

if($_GET['type'] == 'del'){
  	$updatedir = $updatedir.'/map.json';
  	unlink($updatedir);
}