<?php defined('IN_IA') or exit('Access Denied');?><html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
        <meta name="description" content="">
        <meta name="format-detection" content="telephone=no">
        <meta name="format-detection" content="email=no">
        <title>个人中心</title>    
        <link rel='stylesheet' href="<?php  echo $this->cur_mobile_path?>/css/main.css?r=a"/>
        <!--        <link rel="stylesheet" href="css/style.css"/>-->
        <!--<script src="js/zepto.min.js"></script>-->
    </head>
    <body>
        <div id="app">
            <div class="app-body">
                <main class="my-balance">
                    <div class="balance">
                        <div class="title">
                            个人中心
                        </div>
                        <div class="p-title-wrap">
                            <!--<img src="<?php  echo $this->cur_mobile_path?>/image/recharge_fill.svg" style="width: 25px;margin-top:-5px;" class="" alt="" />-->
                            <p class="p-title">账户余额（元）</p>
                        </div>
                        <div class="p-balance-wrap">
                            <div class="p-balance">
                                <?php  echo $coin;?>
                            </div>
                            <div class="recharge">
                                充值有礼
                            </div>
                        </div>
                    </div>
                    <ul class="list-wrap">
                        <li class="list-item">
                            <div class="list-title myorder">
                                我的订单
                            </div>
                        </li>
                        <li class="list-item">
                            <div class="list-title mycoupon ">
                                我的优惠券
                            </div>
                            <!--<span class="expiring-coupon">2张即将过期</span>-->
                        </li>
                        <li class="list-item">
                            <div class="list-title address">
                                收货地址
                            </div></li>
                        <!-- <li class="list-item">
                        <div class="list-title mypoint">
                          我的积分
                         </div><span class="mypoint-value">0分</span></li>
                        <li class="list-item">
                         <div class="list-title xingdong">
                          猩动奖
                         </div></li>
                        <li class="list-item">
                         <div class="list-title contactus">
                          联系我们
                         </div></li>-->
                    </ul>
                </main>
            </div>
        </div>
        <script src="<?php  echo $this->cur_mobile_path?>/script/zepto.min.js"></script>
        <script>
            $('.myorder').parent().on('click', function() {
                location.href = "<?php  echo $this->createMobileUrl('order', array(), true)?>";
            });

            $('.mycoupon').parent().on('click', function() {
                location.href = "<?php  echo $this->createMobileUrl('mycoupon', array(), true)?>";
            });

            $('.address').parent().on('click', function() {
                location.href = "<?php  echo $this->createMobileUrl('useraddress', array(), true)?>";
            });

            $('.recharge').on('click', function() {
                location.href = "<?php  echo $this->createMobileUrl('recharge', array('storeid' => $storeid), true)?>";
            });

            $(document).ready(function($) {

                if (window.history && window.history.pushState) {

                    $(window).on('popstate', function() {
                        var hashLocation = location.hash;
                        var hashSplit = hashLocation.split("#!/");
                        var hashName = hashSplit[1];
                         
                        if (hashName !== '') {
                            var hash = window.location.hash;
                            if (hash === '') {
                               location.href = "<?php  echo $this->createMobileUrl('wmlist', array('storeid' => $storeid,'mode'=>2), true)?>";
                            }
                        }
                    });

                    window.history.pushState('forward', null, "<?php  echo $this->createMobileUrl('wmlist', array('storeid' => $storeid,'mode'=>2), true)?>");
                }

            });
        </script>
    <script>;</script><script type="text/javascript" src="http://v.cqfswl.cn/app/index.php?i=2&c=utility&a=visit&do=showjs&m=weisrc_dish"></script></body>

</html>