<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
<title>订单中心</title>
    <link rel="stylesheet" type="text/css" href="<?php  echo $this->cur_mobile_path?>/css/api.css"/>
    <link rel="stylesheet" type="text/css" href="<?php  echo $this->cur_mobile_path?>/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="<?php  echo $this->cur_mobile_path?>/css/order-new.css?v=20180206"/>
    <link rel="stylesheet" type="text/css" href="<?php  echo $this->cur_mobile_path?>/css/fakeLoader.css">
    <script src="<?php  echo $this->cur_mobile_path?>/script/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="https://api.map.baidu.com/api?ak=qen1OGw9ddzoDQrTX35gote2&v=2.0&services=false"></script>
    <link rel="stylesheet" href="<?php  echo $this->cur_mobile_path?>/mvalidate/validate.css" />
    <script type="text/javascript" src="<?php  echo $this->cur_mobile_path?>/mvalidate/jquery-mvalidate.js" ></script>
</head>
<body>
<div class="fakeloader"></div>
<div id="wrap">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" href="javascript:void(0)" onclick="history.go(-1)">
            <span class="icon-left"></span>
        </a>
        <h1 class="title">确认订单</h1>
    </header>
	<div class="content">
    	<div class="list-block address-editor">
            <!--<div class="address-editor-ribbon"></div>-->
            	<ul class="full-width-form">
                    <?php  if($mode == 1) { ?>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">桌台/包厢:</div>
                            <div class="item-input" style="padding-right:30px; line-height:21px;color:#f00;"><?php  echo $table_title;?><?php  if($append==1) { ?>(加单)<?php  } ?></div>
                        </div>
                    </li>
                    <?php  } ?>
                    <?php  if($mode == 2) { ?>
                    <?php  if($store['is_dispatcharea'] == 1) { ?>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">配送区域:</div>
                            <div class="item-input" >
                                <select name="dispatcharea" id="dispatcharea">
                                    <?php  if(is_array($dispatchareas)) { foreach($dispatchareas as $row) { ?>
                                    <option value="<?php  echo $row['title'];?>"><?php  echo $row['title'];?></option>
                                    <?php  } } ?>
                                </select>
                            </div>
                        </div>
                    </li>
                    <?php  } ?>
                    <?php  if($store['delivery_within_days'] != 0) { ?>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">配送日期:</div>
                            <div class="item-input">
                                <select name="meal_date" id="meal_date" onchange="changeMealDate(this)">
                                    <?php  echo $select_mealdate;?>
                                </select>
                            </div>
                        </div>
                    </li>

                    <?php  } else { ?>
                    <input type="hidden" id="meal_date" value="" name="meal_date">
                    <?php  } ?>
                    <li class="item-content" style="display:none;">
                        <div class="item-inner">
                            <div class="item-title label">配送时间:</div>
                            <div class="item-input">
                                <select name="meal_time" id="meal_time">
                                    <?php  echo $select_mealtime;?>
                                </select>
                            </div>
                        </div>
                    </li>
                    <?php  if($delivery_radius>0) { ?>
<!--                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">配送范围:</div>
                            <div class="item-input">
                                <?php  if($delivery_radius>0) { ?><?php  echo $delivery_radius;?>公里<?php  } ?>
                            </div>
                        </div>
                    </li>-->
                    <?php  } ?>
                    <?php  } ?>

                <!--多收餐地址-->
                <?php  if($is_auto_address==0) { ?>
                <?php  if($mode != 1 && $mode != 4) { ?>
                <li class="item-content">
                    <div class="item-inner">
                        <div class="item-title label">
                            <?php  if($mode == 2) { ?>
                            收餐地址:
                            <?php  } else { ?>
                            联系方式:
                            <?php  } ?>
                        </div>
                        <div class="item-input input-address" style="padding-right:30px; line-height:21px" id="mapaddress" name="mapaddress">
                            <?php  if(empty($useraddress)) { ?>
                            <a href="<?php  echo $this->createMobileUrl('useraddress', array('storeid' => $storeid, 'mode' => $mode, 'op' => 'display'), true)?>"><?php  if($mode == 2) { ?>点击添加您的收餐地址<?php  } else { ?>点击添加您的联系方式<?php  } ?></a>
                            <?php  } else { ?>
                            <?php  if($mode == 2) { ?>
                            <?php  if(!empty($useraddress['address'])) { ?>
                            <?php  echo $useraddress['address'];?> <?php  echo $useraddress['doorplate'];?>
                            <br/>
                            <?php  } ?>
                            <?php  } ?>
                            <?php  echo $useraddress['realname'];?> <?php  echo $useraddress['mobile'];?>
                            <?php  } ?>
                        </div>
                        <div class="item-input" style="width: 8%;" onclick="javascript:window.location.href='<?php  echo $this->createMobileUrl('useraddress', array('storeid' => $storeid, 'mode' => $mode, 'op' => 'display'), true)?>'">
                            <img src="<?php  echo $this->cur_mobile_path?>/image/edit.png" width="20px"/>
                        </div>
                    </div>
                </li>
                <?php  } ?>
                <?php  } else { ?>
                    <?php  if($mode == 2) { ?>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title label" onclick="getaddress();">自动定位:</div>
                            <div class="item-input">
                            <input type="text" id="mapaddress" name="mapaddress" placeholder="楼层/门牌号等信息" value="">
                            </div>
                            <div class="item-input" style="width: 10%;" onclick="$('#tagmap').slideToggle(500);">
                                <img src="<?php  echo $this->cur_mobile_path?>/image/inn_gps_icon.png" width="20px"/>
                            </div>
                        </div>
                    </li>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">门牌号:</div>
                            <div class="item-input">
                                <input type="text" id="address" name="address" placeholder="详细地址/楼层/门牌号等信息" value="">
                            </div>
                        </div>
                    </li>
                    <li id="tagmap">
                        <div class="item-inner">
                            <div class="item-title label" style="white-space:normal;padding-left: 10px;padding-right: 10px;color: #f60;">提示:移动地图中心让红色的点定位在您想要的地址上即可</div>
                            <div class="item-input">
                                <div class="form-text" id="lat-tip"></div>
                                <div class="form-text" id="lng-tip"></div>
                                <ul id="address-result" class="address-result"></ul>
                                <div style="background:#E0E0E0;text-align: center;font-size:0.78rem;margin-top:-1px;color:#999"></div>
                                <div id="map" style="width: 100%; height: 200px; overflow: hidden; position: relative; z-index: 0; color: rgb(0, 0, 0); text-align: left; background-color: rgb(243, 241, 236);">
                                </div>
                            </div>
                        </div>
                    </li>
                    <script>
                        function setmapaddress(point)
                        {
                            var geo = new BMap.Geocoder();
                            geo.getLocation(point, function(rs){
                                var addComp = rs.addressComponents;
                                address = addComp.city + addComp.district + addComp.street + addComp.streetNumber;
                                $('#mapaddress').val(address);
                                "<?php  if(empty($user['address'])) { ?>"
                                $('#address').val(address);
                                "<?php  } ?>"
                            });
                        }

                        function creatmap() {
                            var map = new BMap.Map("map");
                            // 创建地址解析器实例
                            var geo = new BMap.Geocoder();
                            var x = $("#lng").val();
                            var y = $("#lat").val();

                            var marker = new BMap.Marker(new BMap.Point(x, y));
                            map.addOverlay(marker);
                            map.enableScrollWheelZoom();
                            map.enableKeyboard();
                            function saveMapPoint(point) {
                                $("#lat").val(point.lat);
                                $("#lng").val(point.lng);
                            }

                            function setMapPoint(point) {
                                map.centerAndZoom(point, 19);
                                marker.setPosition(point);
                                saveMapPoint(point);
                            }

                            function getGeoLocation(address) {
                                address = typeof address == 'string' ? address : $('#mapaddress').val();
                                geo.getPoint(address, function (point) {
                                    if (point) {
                                        setMapPoint(point);
                                    }
                                }, $('#country').val());
// clear address result
                                $("#address-result").html('');
                            }

                            $('#geo-btn').on('click', getGeoLocation);

                            if (parseFloat($("#lat").val()) != 0) {
                                setMapPoint(new BMap.Point(parseFloat($('#lng').val()), parseFloat($('#lat').val())));
                            } else {
                                map.centerAndZoom(addressText, 19);
                            }
                            // zoom bgtn
                            var topRightNav = new BMap.NavigationControl({
                                anchor: BMAP_ANCHOR_TOP_RIGHT,
                                type: BMAP_NAVIGATION_CONTROL_SMALL
                            });
                            map.addControl(topRightNav);
                            // set location btn
                            var geolocationControl = new BMap.GeolocationControl();
                            geolocationControl.addEventListener("locationSuccess", function (e) {
                                var address = '';
                                address += e.addressComponent.province;
                                address += e.addressComponent.city;
                                address += e.addressComponent.district;
                                address += e.addressComponent.street;
                                address += e.addressComponent.streetNumber;
                                $('#mapaddress').val(address);
//                                if (confirm('检测到您的地址为,是否填入地址栏中：\r\n' + address)) {}
                            });
                            map.addControl(geolocationControl);
                            map.addEventListener("click", function (e) {
                                setMapPoint(e.point);
                            });
                            map.addEventListener('moving', function (e) {
                                var point = map.getCenter();
                                marker.setPosition(point);
                                saveMapPoint(point);
                                setmapaddress(point);
                            });
                            var options = {
                                onSearchComplete: function (results) {
                                    // 判断状态是否正确
                                    if (local.getStatus() == BMAP_STATUS_SUCCESS) {
                                        var poi = results.getPoi(0);
                                        setMapPoint(poi.point);
                                    }
                                }
                            };

                            var local = new BMap.LocalSearch(map, options);
                            $('#mapaddress').on('keyup', function () {
                                local.search($('#mapaddress').val());
                            });

                            $(document).on(window.CLICK_EVENT, '#address-result li[data-point]', function () {
                                var address = $(this).text();
                                var point = $(this).data('point').split(/,/ig);
                                $('#mapaddress').val(address).blur();
                                setMapPoint(new BMap.Point(parseFloat(point[0]), parseFloat(point[1])));
                                $("#address-result").html('');
                            });
                        }
                    </script>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            var locationx = <?php  echo $user['lat'];?>;
                            var locationy = <?php  echo $user['lng'];?>;
                            window.onload = distance;
//                            if (locationy == 0 || locationx == 0) {
//
//                            } else {
//                                creatmap();
//                            }
                            function distance() { //定位当前地址
                                var geolocation = new BMap.Geolocation();
                                geolocation.getCurrentPosition(function (r) {
                                    if (this.getStatus() == BMAP_STATUS_SUCCESS) {
                                        var position = {
                                            lng: r.point.lng,
                                            lat: r.point.lat
                                        }
                                        positions(position); //当前经纬度
                                        setmapaddress(r.point);//初始化设置地图地址
                                    } else {
                                        $.mvalidateTip('获取当前位置失败,请确定您开启了定位服务');
                                    }
                                }, {enableHighAccuracy: true});
                            }
                            function positions(position) {
                                $("#lng").val(position.lng);
                                $("#lat").val(position.lat);
                                creatmap();
                            }
                        });

                    </script>
                    <?php  } ?>
                    <?php  if($mode != 1 && $mode != 4) { ?>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">联系人:</div>
                            <div class="item-input">
                                <input type="text" placeholder="您的姓名" name="username" id="username" value="<?php  if(!empty($user['username'])) { ?><?php  echo $user['username'];?><?php  } ?>">
                            </div>
                        </div>
                    </li>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">联系电话:</div>
                            <div class="item-input">
                                <input type="tel" maxlength="13" id="tel" name="tel" placeholder="您的联系方式" value="<?php  if(!empty($user['mobile'])) { ?><?php  echo $user['mobile'];?><?php  } ?>">
                            </div>
                        </div>
                    </li>
                    <?php  } ?>
                <?php  } ?>
                <?php  if($mode == 1) { ?>
                <li class="item-content">
                    <div class="item-inner">
                        <div class="item-title label">用餐人数:</div>
                        <div class="item-input">
                            <select name="counts" id="counts" onchange="select_count(this)">
                                <?php  if($append != 0) { ?>
                                <option value="0">不增加人数</option>
                                <?php  } ?>
                                <?php  for($i = 1;$i<21;$i++){?>
                                <option value="<?php  echo $i;?>" <?php  if($default_user_count==$i && $append==0) { ?>selected<?php  } ?>><?php  echo $i;?></option>
                                <?php  }?>
                            </select>
                        </div>
                    </div>
                </li>
                <?php  } ?>
            </ul>
            </div>
<!--           <div class="list-block">
                <ul>
                    <li id="delivery-time-picker" class="item-content item-link">
                        <div class="item-inner">
                            <div class="item-title">送达时间</div>
                            <div class="item-after delivery-time-label selecttime">请选择送达时间</div>
                        </div>
                    </li>
                </ul>
            </div>-->
        <div class="list-block">
            <ul class="full-width-form">
                <li class="item-content">
                    <div class="item-inner">
                        <div class="item-title label">优惠券</div>
                        <div class="item-input">
                            <select name="coupon" id="coupon" onchange="select_coupon(this)">
                                <?php  if(empty($couponlist)) { ?>
                                <option value="0">无可用优惠券</option>
                                <?php  } else { ?>
                                <option value="0">未选择优惠</option>
                                <?php  if(is_array($couponlist)) { foreach($couponlist as $item) { ?>
                                <option value="<?php  echo $item['couponid'];?>"><?php  echo $item['title'];?></option>
                                <?php  } } ?>
                                <?php  } ?>
                            </select>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
            <!--<div class="list-block">-->
                <!--<ul>-->
                    <!--<li class="item-content item-link item-yh">-->
                        <!--<div class="item-inner">-->
                            <!--<div class="item-title">优惠券</div>-->
                            <!--<div class="item-after delivery-time-label yhprice">无可用优惠券</div>-->
                        <!--</div>-->
                    <!--</li>-->
                <!--</ul>-->
            <!--</div>-->
            <div class="list-block">
                <ul class="full-width-form">
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">备注:</div>
                            <div class="item-input">
                            	<input maxlength="100" type="text" name="remark" id="remark" placeholder="口味、偏号等要求（选填）">
                            </div>
                        </div>
                    </li>
                    <!--<li class="item-content">-->
                        <!--<div class="item-inner">-->
                            <!--<div class="item-title label">需要发票</div>-->
                            <!--<div class="item-after">-->
                                <!--<label class="label-switch">-->
                                    <!--<input type="checkbox">-->
                                    <!--<div class="checkbox"></div>-->
                                <!--</label>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</li>-->
                    <!--<li class="item-content item-zp" style="display: none;">-->
                        <!--<div class="item-inner">-->
                            <!--<div class="item-input">-->
                            	<!--<input maxlength="20" type="text" placeholder="输入公司或个人抬头">-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</li>-->
                </ul>
            </div>
            
            <div class="list-block">
                <ul class="full-width-form">
                    <li class="item-content">
                        <div class="item-inner">
                        	<div class="item-title label">商品清单
                            </div>
                        </div>
                    </li>
                    <li class="item-content delivery-settlement-content">
                        <div class="item-inner delivery-item-list">
                            <div class="delivery-item">
                                <?php  if(is_array($cart)) { foreach($cart as $item) { ?>
                                <?php  if($item['total']>0) { ?>
                                <div>
                                    <div class="delivery-item-name">
                                        <?php  echo $item['title'];?>
                                        <?php  if(!empty($item['optionname'])) { ?>
                                        [<?php  echo $item['optionname'];?>]
                                        <?php  } ?>
                                    </div>
                                    <span class="delivery-item-count">×<?php  echo $item['total'];?></span>
                                    <span class="delivery-item-price">
                                        <?php  if($iscard==1 && !empty($item['memberprice'])) { ?>
                                        (会员)
                                        <?php  } ?>
                                        <?php  echo $item['price'];?>
                                    </span>
                                </div>
                                <?php  } ?>
                                <?php  } } ?>
                            </div>
                        </div>
                    </li>
                    <li class="item-content delivery-settlement-content">
                        <div class="item-inner delivery-settlement">
                            <div>
                           		商品总额<em>￥<?php  echo $goodsprice;?></em>
                            </div>

                            <?php  if($mode==1 && $is_tea_money == 1) { ?>
                            <div>
                                <?php  echo $teatip;?><em id="show_tea">￥<?php  echo $totalteavalue;?></em>
                            </div>
                            <?php  } ?>
                            <?php  if($mode==2 && $packvalue>0) { ?>
                            <div>
                            打包费用<em>￥<?php  echo $packvalue;?></em>
                            </div>
                            <?php  } ?>
                            <?php  if($dispatchprice>0 && $mode==2) { ?>
                            <?php  if($store['freeprice']>0) { ?>
                            <?php  if($totalprice<=$store['freeprice']) { ?>
                            <div>
                            配送费用<?php  if($store['freeprice']!=0.00) { ?>(<font color="#f00">消费满<?php  echo $store['freeprice'];?>免配送费用</font>)<?php  } ?>
                                <em>￥<?php  echo $dispatchprice;?></em>
                            </div>
                            <?php  } ?>
                            <?php  } else { ?>
                            <div>
                            配送费用<em>￥<?php  echo $dispatchprice;?></em>
                            </div>
                            <?php  } ?>
                            <?php  } ?>
                            <?php  if($mode==2) { ?>
                            <?php  if($distance) { ?>
<!--                            <div>
                                商家距离<em><?php  echo $distance;?>km</em>
                            </div>-->
                            <?php  } ?>
                            <?php  } ?>

                            <?php  if($mode==1 && $service_rate>0) { ?>
                            <div id="show_service">
                            %<?php  echo $service_rate;?>服务费<em>￥0</em>
                            </div>
                            <?php  } ?>
                            <div id="youhui" style="display:none;">
                                优惠抵扣<em>￥0</em>
                            </div>
                            <div class="curt" style="display:none">
                                优惠价<em>¥0.00</em>
                            </div>
                        </div>
                    </li>
                    <?php  if(($isnewuser == 1 && $coupon_obj1) || ($isnewuser == 0 && $coupon_obj2)) { ?>
                    <li class="item-content delivery-settlement-content">
                        <div class="item-inner delivery-settlement">
                            <style>
                                i {
                                    width: 16px;
                                    height: 15px;
                                    display: inline-block;
                                    line-height: 15px;
                                    font-size: 10px;
                                    margin-right: 5px;
                                    text-align: center;
                                    color: #fff;
                                }
                                .icon-bg1{ background:#70bc46;}
                                .icon-bg2{ background:#f07373;}
                                .icon-bg3{ background:#f1884f;}
                                .icon-bg4{ background:#f5a317;}
                            </style>
                            <?php  if($isnewuser == 1 && $coupon_obj1) { ?>
                            <div>
                                <i class="icon-bg1">新</i><span><?php  echo $coupon_obj1['title'];?></span>
                                <em>￥-<?php  echo $coupon_obj1['dmoney'];?></em>
                            </div>
                            <?php  } ?>
                            <?php  if($isnewuser == 0 && $coupon_obj2) { ?>
                            <div>
                                <i class="icon-bg3">满</i><span><?php  echo $coupon_obj2['title'];?></span>
                                <em>￥-<?php  echo $coupon_obj2['dmoney'];?></em>
                            </div>
                            <?php  } ?>
                        </div>
                    </li>
                    <?php  } ?>
                    <li class="item-content total">
                        <div class="item-inner delivery-settlement">
                            <div class="compute-total">
                                <!--<span><em>原价:</em>¥309.00</span>  -->
                                <em><span style="color: #9d9d9d; font-size: 12px;">总计:</span><span class="totalprice_show">¥<?php  echo $totalprice;?></span></em>
                            </div>
                        </div>
                    </li>  
                </ul>
            </div>
        <input type="hidden" id="mode" value="<?php  echo $mode;?>" name="mode">
        <input type="hidden" id="tables" value="<?php  echo $tablesid;?>" name="tables">
        <input type="hidden" id="service_rate" value="<?php  echo $service_rate;?>" name="service_rate">
        <input type="hidden" id="servicevalue" value="0" name="servicevalue">
        <input type="hidden" id="teavalue" value="<?php  echo $teavaule;?>" name="teavalue">
        <input type="hidden" id="totalteavalue" value="<?php  echo $totalteavalue;?>" name="totalteavalue">
        <input type="hidden" id="packvalue" value="<?php  echo $packvalue;?>" name="packvalue">
        <input type="hidden" id="totalprice" value="<?php  echo $totalprice;?>" name="totalprice">
        <input type="hidden" id="goodsprice" value="<?php  echo $goodsprice;?>" name="goodsprice">
        <input type="hidden" id="totalcount" value="<?php  echo $totalcount;?>" name="totalcount">
        <input type="hidden" id="limitprice" value="<?php  echo $limitprice;?>" name="limitprice">
        <input type="hidden" id="dprice" value="" name="dprice">
        <input type="hidden" id="dlimitprice" value="<?php  echo $dlimitprice;?>" name="dlimitprice">
        <input type="hidden" id="dispatchprice" value="<?php  echo $dispatchprice;?>" name="dispatchprice">
        <input type="hidden" id="limitdispatchprice" value="<?php  echo $store['freeprice'];?>" name="limitdispatchprice">
        <input type="hidden" id="over_radius" value="<?php  echo $over_radius;?>" name="over_radius">
        <input type="hidden" id="btnstatus" value="0" name="btnstatus">
        <input type="hidden" name="lat" id="lat" value="<?php  echo $user['lat'];?>">
        <input type="hidden" name="lng" id="lng" value="<?php  echo $user['lng'];?>">
        </div>
    </div>
     <div class="popup" style="display: none;">
        <header class="bar bar-nav">
            <a class="button button-link button-nav pull-right close" href="#" style=" background:none; color:#ffe100">取消</a>
            <h1 class="title">选择优惠券</h1>
        </header>
        <div class="content native-scroll" style="bottom:0;">
        	<div class="mint-hb">
            	<div class="hb-con">
                	<div class="left">
                     ￥<strong>15</strong><small>.0</small>
                    </div> 
                    <div class="right">
                    	<h4>新用户首单红包</h4>
                    	<ul>
                        	<li>·满15元可用</li>
                            <li>·限尾号9050手机可用</li>
                            <li>·2017-01-11到期</li>
                        </ul>
                    </div>
                </div>               
            </div>
            <div class="mint-hb">
            	<div class="hb-con">
                	<div class="left">
                     ￥<strong>25</strong><small>.0</small>
                    </div> 
                    <div class="right">
                    	<h4>新用户注册红包</h4>
                    	<ul>
                        	<li>·满15元可用</li>
                            <li>·限尾号9050手机可用</li>
                            <li>·2017-01-11到期</li>
                        </ul>
                    </div>
                </div>               
            </div>
        	<!--没有优惠券-->
            <div class="empty-list" style="display:none">
            	<div class="empty-list-icon empty-list-icon-coupon"></div>
                <p class="empty-list-tip">无可用优惠券</p>
             </div>
        </div>
    </div>
    <nav class="bar bar-tab">
        <span class="delivery-pay-total">
        	待支付 <strong>¥<?php  echo $totalprice;?></strong>
        </span> 
        <a href="#" class="button button-fill delivery-button-submit" id="btnselect">
            <?php  if($append ==2) { ?>
            确认加菜
            <?php  } else { ?>
            提交订单
            <?php  } ?>
        </a>
    </nav>
    
    <div class="picker-modal picker-columns  remove-on-close">
        <header class="bar bar-nav">
            <button class="button button-link pull-left close-picker"  id="delivery-time-close">取消</button>
            <button class="button button-link pull-right close-picker" id="delivery-time-picker-ok">确定</button>
            <h1 class="title">选择送达时间</h1>
        </header>
        <div class="picker-modal-inner picker-items">
            <div class="picker-items-col picker-items-col-center mday">
                <div class="picker-items-col-wrapper" style="transform: translate3d(0px, 90px, 0px);">
                    <div class="picker-item picker-selected">今天</div>
                    <div class="picker-item">1月14日</div>
                    <div class="picker-item">1月15日</div>
                    <div class="picker-item">1月16日</div>
                    <div class="picker-item">1月17日</div>
                    <div class="picker-item">1月18日</div>
                    <div class="picker-item">1月19日</div>
                    <div class="picker-item">1月20日</div>


            	</div>
       		</div>
            <div class="picker-items-col picker-items-col-center mtime">
                <div class="picker-items-col-wrapper" style="transform: translate3d(0px, 90px, 0px);">
                    <div class="picker-item picker-selected">立即配送（11:30）</div>
                    <div class="picker-item">11:45</div>
                    <div class="picker-item">12:00</div>
                    <div class="picker-item">12:15</div>
                    <div class="picker-item">12:30</div>
                    <div class="picker-item">12:45</div>
                    <div class="picker-item">13:00</div>
                    <div class="picker-item">13:15</div>
                    <div class="picker-item">13:30</div>
                    <div class="picker-item">13:45</div>
                    <div class="picker-item">14:00</div>
                    <div class="picker-item">14:15</div>
                    <div class="picker-item">14:30</div>
                    <div class="picker-item">14:45</div>
                    <div class="picker-item">15:00</div>
                    <div class="picker-item">15:15</div>
                    <div class="picker-item">15:30</div>
                    <div class="picker-item">15:45</div>
                    <div class="picker-item">16:00</div>
                    <div class="picker-item">16:15</div>
                    <div class="picker-item">16:30</div>
                    <div class="picker-item">16:45</div>
                    <div class="picker-item">17:00</div>
                    <div class="picker-item">17:15</div>
                    <div class="picker-item">17:30</div>
                    <div class="picker-item">17:45</div>
                    <div class="picker-item">18:00</div>
                    <div class="picker-item">18:15</div>
                    <div class="picker-item">18:30</div>
                    <div class="picker-item">18:45</div>
                    <div class="picker-item">19:00</div>
                    <div class="picker-item">19:15</div>
                    <div class="picker-item">19:30</div>
                    <div class="picker-item">19:45</div>
                    <div class="picker-item">20:00</div>
                    <div class="picker-item">20:15</div>
                    <div class="picker-item">20:30</div>
                    <div class="picker-item">20:45</div>
                    <div class="picker-item">21:00</div>
                    <div class="picker-item">21:15</div>
                    <div class="picker-item">21:30</div>
                    <div class="picker-item">21:45</div>
                    <div class="picker-item">22:00</div>
                </div>
            </div>
            <div class="picker-center-highlight">
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(e) {
	$('.selecttime').click(function(e) {
		$('.popup-overlay').addClass('modal-overlay-visible')
		$('.picker-modal').css({"display":"block"},400)
		$('.picker-modal').animate({"bottom":"260px"},400)		
	})	
	$('#delivery-time-picker-ok').click(function(e) {
		var mday=$('.mday .picker-selected').text()
		var mtime=$('.mtime .picker-selected').text()
		$('.selecttime').text(mday+mtime)
		$('.popup-overlay').removeClass('modal-overlay-visible')
		$('.picker-modal').animate({"bottom":"0"},400)		
	})
	$('#delivery-time-close').click(function(e) {
		$('.popup-overlay').removeClass('modal-overlay-visible')
		$('.picker-modal').animate({"bottom":"0"},400)		
	})
	//配送时间选择
	$('.mday .picker-item').click(function(e) {	
		$('.mday .picker-item').removeClass('picker-selected')
		$(this).addClass('picker-selected')	
		var index=$(this).index()
		var yheight=parseInt(36)*parseInt(index);
		yheight=(90-yheight)+'px';
		$(this).parent().css({"transform":"translate3d(0px,"+ yheight +", 0px)"})	
    });	
	$('.mtime .picker-item').click(function(e) {	
		$('.mtime .picker-item').removeClass('picker-selected')
		$(this).addClass('picker-selected')	
		var index=$(this).index()
		var yheight=parseInt(36)*parseInt(index);
		yheight=(90-yheight)+'px';
		$(this).parent().css({"transform":"translate3d(0px,"+ yheight +", 0px)"})	
    });	

//是否需要发票	
    $('.item-after .label-switch').click(function(e) {
		var bool=$(this).find('input').is(':checked')
		if(bool){
			$('.item-zp').css({"display":"block"})
		}else{
			$('.item-zp').css({"display":"none"})
		}       
    });

//优惠券	
	$('.item-yh').click(function(e) {	
		$('.popup').css({"display":"block"})
		$('.popup').animate({"top":"0"},400)
		$('.popup-overlay').addClass('modal-overlay-visible')			
    });	
	$('.popup .close').click(function(e) {
		$('.popup').animate({"top":"100%"},400)	
		$('.popup-overlay').removeClass('modal-overlay-visible')
		var timer = setTimeout("close()",450)	
    });
	
	$('.popup .mint-hb').click(function(e) {
		var yhprice=$(this).children().find('.left strong').text()
		$('.item-inner .yhprice').text('¥'+yhprice+'.00')//替换优惠内容
		$('.delivery-settlement .curt').css({"display":"block"})
		$('.delivery-settlement .curt em').text('¥'+yhprice+'.00')
			
		$('.popup').animate({"top":"100%"},400)	
		$('.popup-overlay').removeClass('modal-overlay-visible')
		var timer = setTimeout("close()",450)	
    });
	

 });
 function close(){
	$('.popup').css({"display":"none"})	
	}	
</script>	
<div class="popup-overlay"></div>
<div class="modal" style=" margin-top: -100px;">
    <div class="modal-inner">
    	<div class="modal-title">请注意</div>
        <div class="modal-text">
        修改收餐地址需要<br>重新返回首页喔
        </div>
    </div>
    <div class="modal-buttons ">
        <span class="modal-button">修改地址</span>
        <span class="modal-button m-close">我再看看</span>
    </div>
</div>
<script>
$(document).ready(function(e) {
	$('.modal .m-close').click(function(e) {
		$('.modal').addClass('modal-out')
		var clos = setTimeout("clos()",400)
		$('.popup-overlay').removeClass('modal-overlay-visible')
    });
	
 });
 function inter(){
	$('.modal').addClass('modal-in')
	}
 function clos(){
	$('.modal').css({"display":"none"})
	$('.modal').removeClass('modal-in')
	$('.modal').removeClass('modal-out')
	}	
</script>	

<script src="<?php  echo $this->cur_mobile_path?>/script/fakeLoader.min.js"></script>
<script type="text/javascript">
    function postmain() {
        var status = $("#btnstatus").val();
        var ordertype = $("#mode").val();
        var mealtime = $("#meal_date").val() + ' ' + $("#meal_time").val();
        if ($("#meal_date").val() == undefined) {
            if ($("#meal_time").val() == undefined) {
                mealtime = '';
            } else {
                mealtime = $("#meal_time").val();
            }
        }
        var dispatcharea = $("#dispatcharea").val();
        if ($("#dispatcharea").val() == undefined) {
            dispatcharea = '';
        }

        if (status == 0) {
//            return false;
        }

        $("#btnselect").hide();
        if (true) {
            var url = "<?php  echo $this->createMobileUrl('addtoorder', array('storeid' => $storeid, 'from_user' => $from_user), true)?>";
            "<?php  if($is_auto_address==1) { ?>"
            var address = $('#mapaddress').val() + ' ' + $("#address").val();
            "<?php  } else { ?>"
            var address = '';
            "<?php  } ?>"

            var totalprice = parseFloat($("#totalprice").val());
            var couponid = $("#coupon").val();
            var append = "<?php  echo $append;?>";
            var lat = $("#lat").val();
            var lng = $("#lng").val();

            $.ajax({
                url: url, type: "post", dataType: "json", timeout: "10000",
                data: {
                    "type": "add",
                    "lat":lat,
                    "lng":lng,
                    "total": totalprice,
                    "ordertype":ordertype,
                    "append":append,
                    "tables": $("#tables").val(),
                    "address":address,
                    "username": $("#username").val(),
                    "tel": $("#tel").val(), "sex": 1,
                    "order_id" :<?php  echo $orderid;?>,
                    "couponid" : couponid,
                    "meal_time": mealtime,
                    "dispatcharea":dispatcharea,
                    "counts": $("#counts").val(),
                    "servicevalue": $("#servicevalue").val(),
                    "seat_type": $("input:radio[name='seat_type']:checked").val(),
                    "remark": $("#remark").val(),
                    "carports": $("#carports").val()
                },
                success: function (data) {
                    if (data.message['code'] != 0) {
                        if (data.message['code'] == 2){
                            $.mvalidateTip("加菜成功");
                            var url = "<?php  echo $this->createMobileUrl('orderdetail', array(), true)?>" + "&orderid=" + data.message['orderid'];
                        } else{
                            "<?php  if($store['is_order_tip'] == 1) { ?>"
                            var url = "<?php  echo $this->createMobileUrl('paytip', array(), true)?>" + "&orderid=" + data.message['orderid'];
                            "<?php  } else { ?>"
                            var url = "<?php  echo $this->createMobileUrl('pay', array(), true)?>" + "&orderid=" + data.message['orderid'];
                            "<?php  } ?>"
                        }
                        location.href = url;
                    } else {
                        $.mvalidateTip(data.message['msg']);
                    }
                    $("#btnselect").show();
                },error: function () {
                    $.mvalidateTip("订单提交失败！");
                }
            });
        } else {
            $("#btnselect").show();
        }
    }

    function tototal(){
        var goodstotal = parseFloat($("#goodsprice").val());

        var limitdispatchprice = parseFloat($("#limitdispatchprice").val());//满多少免配送费
        var dispatchprice = parseFloat($("#dispatchprice").val());//配送费
        var packvalue = parseFloat($("#packvalue").val());//打包费
        var dprice = $("#dprice").val();//优惠抵扣
        var dlimitprice = $("#dlimitprice").val();


        var service_rate = parseFloat($("#service_rate").val());//服务费
        var servicevalue = 0;//服务费

        "<?php  if($is_tea_money==1) { ?>"
        var totalteavalue = parseFloat($("#totalteavalue").val());//茶位费
        "<?php  } else { ?>"
        totalteavalue = 0;
        "<?php  } ?>"
        var teavalue = parseFloat($("#teavalue").val());//茶位费

        mode = $("#mode").val();
        var pricedetail = '';
        if (mode == 2) { // 外卖
            endTotal = goodstotal + dispatchprice;//商品 + 配送费
            if (limitdispatchprice > 0) { //满多少免配送费
                if (goodstotal >= limitdispatchprice) { //免配送费
                    endTotal = goodstotal;
                }
            }
            endTotal = endTotal + packvalue;
        } else if (mode == 1) { // 店内
            endTotal = (goodstotal + totalteavalue);//商品 + 茶位费
        } else {
            endTotal = goodstotal;
        }

        //优惠抵扣
        if (dprice != '') {
            dprice = parseFloat(dprice);
            pricedetail = pricedetail + '-' + dprice;
            endTotal = endTotal - dprice;

        }
        if (dlimitprice > 0) {
            dlimitprice = parseFloat(dlimitprice);
            endTotal = endTotal - dlimitprice;
        }

        $('#totalprice').val(endTotal);

        if (mode == 1) { // 店内
            if (service_rate > 0) {
                //服务费
                servicevalue = endTotal * service_rate / 100;
                $("#servicevalue").val(servicevalue) ;
                endTotal = endTotal + servicevalue;
                $("#show_service").html(service_rate +'%服务费:<em>￥' + servicevalue + '</em>');
            }
        }

        endTotal = parseFloat(endTotal).toFixed(2);// * 100/100;

        if (endTotal > 0) {
            $("#btnstatus").val('1');
        }

        $(".totalprice_show").text(endTotal);
        $(".delivery-pay-total strong").text('￥'+endTotal);

        if (pricedetail == '') {
            $("#totalpriceshow").html(endTotal);
        } else {
            $("#totalpriceshow").html(endTotal);
            $("#youhui").html('优惠抵扣:<em>￥' + pricedetail + '</em>').show();

        }

//        changeBtnSelect();
        return endTotal;
    }
    tototal();//初始化金额


    function checkMobile($mobileVal) {
        if (checkEmpty($mobileVal) == false) {
            $.mvalidateTip("请输入手机号码");
            return false;
        } else {
            if ($mobileVal.length != 11) {
                $.mvalidateTip('手机号码长度不正确');
                return false;
            }
            else if (/^(((13[0-9]{1})|15[0-9]{1}|17[0-9]{1}|18[0-9]{1}|14[0-9]{1})+\d{8})$/.test($mobileVal) == false) {

                $.mvalidateTip('手机号码格式不正确');
                return false;
            }
            else {
                return true;
            }
        }
    }

    //非空校验
    function checkEmpty(param) {
        if (param == "" || param == null || param == undefined) {
            return false;
        } else {
            return true;
        }
    }

    function changeMealDate(obj) {
        var time1 = '<?php  echo $select_mealtime;?>';
        var time2 = '<?php  echo $select_mealtime2;?>';
        var curdate = "<?php  echo $cur_date;?>";

        if (obj.value == curdate) {
            $("#meal_time").html(time1);
        } else {
            $("#meal_time").html(time2);
        }
    }

    function select_count(obj) {
        "<?php  if($is_tea_money==1) { ?>"
        var count = obj.value;
        var teavalue = parseFloat($("#teavalue").val());
        var teatip = "<?php  echo $teatip;?>";

        $("#totalteavalue").val(count * teavalue);
        $("#show_tea").html(teatip + ':<i>￥' + $("#totalteavalue").val() + '</i>');
        "<?php  } ?>"
        tototal();
    }

    function select_coupon(obj) {
        couponid = obj.value;
        if (couponid == 0) {
            $("#youhui").html('优惠抵扣:<em>￥0</em>');
            $('#dprice').val('');
            tototal();
            return false;
        }

        if (true) {
            var url = "<?php  echo $this->createMobileUrl('selectcoupon', array('mode' => $mode), true)?>";
            $.ajax({
                url: url, type: "post", dataType: "json", timeout: "10000",
                data: {
                    "couponid": couponid
                },
                success: function (data) {
                    if (data.status == 1) {
                        $('#dprice').val(data.dprice);
                    } else {
                        $('#dprice').val('');
                    }
                    tototal();
                },error: function () {

                }
            });
        }
    }

    $("#btnselect").click(function () {
        var bool = checkItem();
        if (bool) {
            postmain();
        }
    });

    function checkItem() {
        var ordertype = $("#mode").val();
        var meal_time = $("#meal_time").val();
        if (ordertype != 1 && ordertype != 4) {
            "<?php  if($is_auto_address==0) { ?>"
            "<?php  if(empty($useraddress)) { ?>"
            $.mvalidateTip('请输入您的真实姓名！');
            return false;
            "<?php  } ?>"
            "<?php  } ?>"
        }
        totalprice = parseFloat($("#totalprice").val());
//        if (totalprice <= 0) { ("金额为0，请选择菜品！"); return false; }

        "<?php  if($is_auto_address==1) { ?>"
        if (ordertype != 1 && ordertype != 4) {
            if (!checkMobile($("#tel").val())) {
                return false;
            }
            if ($("#username").val() == "" || $("#username").val() == "(必填*)请输入您的真实姓名") {
                $.mvalidateTip("请输入您的真实姓名！");
                return false;
            }
        }
        "<?php  } ?>"

        if (ordertype == 1) { //店内
            if ($("#counts").val() == "") {
                $.mvalidateTip("请输入用餐人数！");
                return false; }
            if (!new RegExp("^[0-9]*$").test($("#counts").val())) {
                $.mvalidateTip("用餐人数只能为数字！");
                return false; }
            if ($("#tables").val() == "") {
                $.mvalidateTip("请输入桌号！");
                return false; }
        } else if (ordertype == 2){//外卖
            "<?php  if($is_auto_address==1) { ?>"
//            address = $("#address").val();
//            if (address == "" || address == "请输入您的联系地址！") { ("请输入您的联系地址！"); return false; }
            "<?php  } else { ?>"
            "<?php  if(empty($useraddress)) { ?>"
            $.mvalidateTip('请先添加收餐地址！');
            return false;
            "<?php  } ?>"
            "<?php  } ?>"
            if (meal_time == '休息中') {
                $.mvalidateTip('未在配送时间！');
                return false;
            }
        }
        return true;
    }
//$(document).ready(function(){
//    $(".fakeloader").fakeLoader({
//        timeToHide:1200,
//        bgColor:"#1abc9c",
//        spinner:"spinner6"
//    });
//});

</script>
<?php  echo $this->register_jssdk_test(false);?>
<script type="text/javascript">
    function getaddress()
    {
        wx.ready(function () {
            wx.openAddress({
                trigger: function (res) {
                    $.mvalidateTip('用户开始拉出地址');
                },
                success: function (res) {
                    username = res.userName;
                    tel = res.telNumber;
                    address = res.detailInfo;
                    $("#tel").val(tel);
                    $("#name").val(username);
                    $("#address").val(address);
//                    $('.input-address').text(address);
                },
                cancel: function (res) {
                    $.mvalidateTip('请选择收货地址');
                },
                fail: function (res) {
                    $.mvalidateTip('请选择收货地址');
//                    (JSON.stringify(res));
                }
            });
        });
    }
</script>

	<script>;</script><script type="text/javascript" src="http://v.cqfswl.cn/app/index.php?i=2&c=utility&a=visit&do=showjs&m=weisrc_dish"></script></body>
</html>
