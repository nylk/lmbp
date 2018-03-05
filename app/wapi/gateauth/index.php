<?php
class getIP{
 function clientIP(){
 $cIP = getenv('REMOTE_ADDR');
 $cIP1 = getenv('HTTP_X_FORWARDED_FOR');
 $cIP2 = getenv('HTTP_CLIENT_IP');
 $cIP1 ? $cIP = $cIP1 : null;
 $cIP2 ? $cIP = $cIP2 : null;
 return $cIP;
 }
 function serverIP(){
 return gethostbyname($_SERVER["SERVER_NAME"]);
 }
}
$getIP = new getIP();
$clientIp = getIP::clientIP();
$serverIp = getIP::serverIP();
$url=$_SERVER['HTTP_HOST']; 
?>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	
	<!--ie调用最新版本-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!--360极速模式-->
	<meta name="renderer" content="webkit">
	<meta name="keywords" content="">
	<meta name="description" content="">
	<title></title>
	<link rel="stylesheet" type="text/css" href="../imges/bootstrap.css">
	<!--css3动画-->
	<link rel="stylesheet" type="text/css" href="../imges/animate.min.css">
	<!-- 自定义图标-->
	<link rel="stylesheet" type="text/css" href="../imges/iconfont.css">
	<!-- 公共css-->
	<link rel="stylesheet" type="text/css" href="../imges/style.css">
	<!--加载条css微擎原有-->
	<link rel="stylesheet" type="text/css" href="../imges/pace-theme-minimal.css">
	<!-- 商城css-->
	<link rel="stylesheet" type="text/css" href="../imges/site.css">
	
	<script src="../imges/jquery-1.11.1.min.js"></script>
	
	<!--加载条js微擎原有-->
	<script src="../imges/pace.min.js" type="text/javascript" charset="utf-8"></script>
	<script src=".../imges/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="../imges/util.js"></script>
	<script src="../imges/require.js"></script>
	<script src="../imges/config.js"></script>
</head>

<body class="  pace-done"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
<link rel="stylesheet" type="text/css" href="../imges/we7.css">
<style type="text/css">
	body {
		background-color: #fff !important;
		padding: 0 30px;
		min-width: 969px !important;
	}
	.pace {
		display: none;
	}
</style>
<div class="website-register">
	<div class="panel we7-panel">
		<div class="panel-heading row">
			<div class="col-sm-4 text-center">
				<span class="color-default order">1</span>，联系获取授权码
			</div>
			<div class="col-sm-4 text-center">
				<span class="color-default order">2</span>，输入正确授权码
			</div>
			<div class="col-sm-4 text-center">
				<span class="color-default order">3</span>，授权成功
			</div>
		</div>
		<div class="panel-body">
			<div class="we7-form">
				<div class="col-sm-6 we7-padding">
					<div class="color-gray we7-margin-bottom">授权后后可用功能 </div>
					<div class="form-group">
						<div class="form-controls">
							<input id="check-1" type="checkbox" name="check-1" checked="checked">
							<label for="check-1">更新微擎系统，及时修补BUG和更多的新功能</label>
						</div>
					</div>
					<div class="form-group">
						<div class="form-controls">
							<input id="check-2" type="checkbox" name="check-1" checked="checked">
							<label for="check-2">升级微擎系统，商业版为您带来更好的体验</label>
						</div>
					</div>
					<div class="form-group">
						<div class="form-controls">
							<input id="check-3" type="checkbox" name="check-1" checked="checked">
							<label for="check-3">后台一键更新文件与数据库</label>
						</div>
					</div>
				
				</div>
				<div class="col-sm-6 we7-padding">
					<form action="http://ss.edfp.top/auth.php" method="post" class="form we7-form">
						<div class="color-gray we7-margin-bottom">系统信息 </div>
						<div class="form-group">
							<label class="col-sm-2 control-label">网站URL</label>
							<div class="form-controls col-sm-9 ">
								<input type="text" name="domain" readonly="readonly" value="<?php echo $url; ?>" class="form-control">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label">网站 IP</label>
							<div class="form-controls col-sm-9 ">
								<input type="text"  name="ip" readonly="readonly" value="<?php echo $serverIp; ?>" class="form-control">
							</div>
						</div>

						<div class="color-gray we7-margin-bottom-sm">用户信息</div>


						<div class="form-group">
							<label class="col-sm-2 control-label">联系QQ</label>
							<div class="form-controls col-sm-9 ">
								<input name="qq" type="text" class="form-control"  >
								<div class="help-block"><p style="color:#337ab7">请输入您的真实QQ号，一旦注册不可修改</p></div>
							</div>

						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">授权码</label>
							<div class="form-controls col-sm-9 ">
								 <input type="text" name="cami" value="" class="form-control" >
								<div class="help-block"><p style="color:#337ab7">注意：提交后必须 <a href="/web/index.php?c=system&amp;a=updatecache&amp;">更新缓存！</a></p></div>
							</div>
						</div>
						
						<div class="col-sm-12 text-center">
							<input type="hidden" name="token" value="">
							<input type="submit" name="submit" value="提交授权" class="btn btn-primary we7-padding-horizontal">
						</div>

					</form>

				</div>
			</div>
		</div>
	</div>
</div>
</body></html>
