<?php defined('IN_IA') or exit('Access Denied');?><html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
        <meta name="description" content="">
        <meta name="format-detection" content="telephone=no">
        <meta name="format-detection" content="email=no">
        <title><?php  echo $store['title'];?></title>    
        <!--<link rel='stylesheet' href="https://s.xingbianli.cn/app/app-openrack-h5/main.CHgffje_84Z33b.min.css"/>-->
        <link rel='stylesheet' href="<?php  echo $this->cur_mobile_path?>/css/main.css?r=a"/>
        <link rel="stylesheet" href="<?php  echo $this->cur_mobile_path?>/mvalidate/validate.css" />
    </head>
    <body>
        <input type="hidden" id="hddCateCount" value="<?php  echo $catecount;?>" >
        <input type="hidden" id="hddAddCartUrl" value="<?php  echo $this->createMobileUrl('cartadd', array('storeid' => $storeid, 'from_user' => $from_user), true)?>">
        <input type="hidden" id="hddJumpUrl" value="<?php  echo $jump_url;?>">
        <input type="hidden" id="hddOptionUrl" value="<?php  echo $this->createMobileUrl('getoption', array(), true)?>">
        <input type="hidden" id="hddSelectOptionUrl" value="<?php  echo $this->createMobileUrl('getselectspec', array(), true)?>">
        <input type="hidden" id="hddPersonUrl" value="<?php  echo $this->createMobileUrl('usercenter', array(), true)?>">
        <input type="hidden" id="hddChargeUrl" value="<?php  echo $this->createMobileUrl('recharge', array('storeid' => $storeid), true)?>">
        <div id="app">
            <div class="app-body">
                <main class="p-commodity-list">
                    <?php  if($_GPC['t'] != 'xcx') { ?>
                    <nav class="shelf-nav shelf-nav-entries">
                        <img src="<?php  echo tomedia($store['logo']);?>" class="logo" alt="logo">
                        <div class="charge nav-icon js-charge"><i class="charge-icon"></i>充值</div>
                        <div class="person nav-icon js-person"><i class="person-icon"></i>我的</div>
                    </nav>
                    <?php  } ?>
                    <!--分类和商品开始-->
                    <div class="container">
                        <div class="categories">
                            <?php  if(is_array($category)) { foreach($category as $item) { ?>
                            <div class="tab-item <?php  if($flag!=true) { ?>active<?php  } ?>"><?php  echo $item['name'];?></div>
                            <?php  $flag = true;?>
                            <?php  } } ?>
                        </div>

                        <div class="commodity-list">
                            <div class="banner">
                                <img src="http://v.cqfswl.cn/attachment/images/2/2018/02/DtTkBmhMdjTidHmTlhhJJTnHtOktKb.png"/>                                    
                            </div>
                            <?php  if(is_array($category)) { foreach($category as $item) { ?>
                            <div class="floor" data-cid="<?php  echo $item['id'];?>" data-name="<?php  echo $item['name'];?>">
                                <div class="floor-name active"><?php  echo $item['name'];?></div>
                                <?php  if(is_array($goodslist[$item['id']]['goods'])) { foreach($goodslist[$item['id']]['goods'] as $goods) { ?>
                                <div class="item" id="dishid_<?php  echo $goods['id'];?>">
                                    <div class="commodity-pic">
                                        <img src="<?php  echo tomedia($goods['thumb']);?>" alt="<?php  echo $goods['title'];?>" />
                                    </div>
                                    <div class="commodity-content">
                                        <div class="name">
                                            <?php  echo $goods['title'];?>
                                        </div>
                                        <div class="tag">
                                            <?php  if(!empty($goods['productprice'])) { ?>
                                            <span class="c_tag">特价</span>
                                            <?php  } ?>
                                            <?php  if(!empty($goods['labelid'])) { ?>
                                            <span class="c_tag" style="color: rgb(85, 85, 85); border: 1px solid rgb(85, 85, 85);"><?php  echo $label[$goods['labelid']];?></span>
                                            <?php  } ?>
                                        </div>
                                        <div class="bottom">
                                            <div class="c_price">
                                                <?php  if(!empty($goods['productprice'])) { ?>
                                                <span class="actual-price"><?php  echo $goods['marketprice'];?><span class="unit">元</span></span>
                                                <span class="origin-price"><?php  echo $goods['productprice'];?>元</span>
                                                <?php  } else { ?>
                                                <?php  echo $goods['marketprice'];?><span class="unit">元</span>
                                                <?php  } ?>

                                            </div>
                                            <div class="number-input js-number-input" data-dishid="<?php  echo $goods['id'];?>"  data-marketprice="<?php  echo $goods['marketprice'];?>">
                                                <?php  if($cartgoods[$goods['id']]>0 and $goods['isoptions']!=1) { ?>
                                                <span class="sub-click-space"><span class="shape-btn btn-dec"><span class="shape dec" onclick="cartminus(this);
                                                        return false;"></span></span></span>
                                                <div class="count">
                                                    <?php  echo $cartgoods[$goods['id']]['total'];?>
                                                </div>
                                                <?php  } else { ?>
                                                <?php  if($goods['isoptions']==1) { ?>
                                                <?php  } else { ?>
                                                <span class="sub-click-space hidden"><span class="shape-btn btn-dec"><span class="shape dec" onclick="cartminus(this);
                                                        return false;"></span></span></span>
                                                <div class="count hidden">
                                                    0
                                                </div>
                                                <?php  } ?>
                                                <?php  } ?>
                                                <span class="add-click-space">
                                                    <span class="shape-btn btn-add <?php  if($goods['isoptions']==1) { ?>btn-add-options<?php  } ?>" data-goodsid="<?php  echo $goods['id'];?>">
                                                        <?php  if($goods['isoptions']==1) { ?>
                                                        选规格
                                                        <em class="j-muti-num muti-num" <?php  if($cartgoods[$goods['id']]['total']>0) { ?> style="display: inline;" <?php  } else { ?>style="display:none;"<?php  } ?>><?php  echo $cartgoods[$goods['id']]['total'];?></em>
                                                        <?php  } else { ?>
                                                        <span class="shape add"></span>
                                                        <?php  } ?>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php  } } ?>                                
                            </div>
                            <?php  } } ?>
                        </div>
                    </div>
                    <!--分类和商品结束-->                    
                    <!--购物车开始-->
                    <div class="cart-bar-bottom">
                        <div class="cart-bar">
                            <div class="icon-cart-bg">
                                <div class="icon-cart"></div>
                                <?php  if($totalcount>0) { ?>
                                <div class="count">
                                    <?php  echo $totalcount;?>
                                </div>
                                <?php  } ?>
                            </div>
                            <div class="summary">
                                <div class="actual" data-totalprice="0"><span class="actual-total">合计: <?php  echo $totalprice;?>元</span><?php  if($discount>0) { ?><span class="origin-total"><?php  echo $marketprice;?>元</span><?php  } ?></div>

                                <?php  if($discount>0) { ?>
                                <div class="coupon-exclusive ">
                                    已优惠：<?php  echo $discount;?>元
                                </div>
                                <?php  } else { ?>
                                <div class="coupon-exclusive hidden">

                                </div>
                                <?php  } ?>
                            </div>
                            <div class="btn-confirm">
                                选好了
                            </div>
                        </div>

                        <div class="c-drawer">
                            <div class="overlay"></div>
                            <div class="box">
                                <div class="content">
                                    <div class="cart">
                                        <div class="summary" id="selectCount">
                                            已选商品 (<?php  echo $totalcount;?>)
                                        </div>
                                        <div class="items">
                                            <div id="cart-box">
                                                <?php  if(is_array($cart)) { foreach($cart as $item) { ?>
                                                <div class="cart-item">
                                                    <div class="name ">
                                                        <?php  echo $item['goodstitle'];?><?php  if(!empty($item['optionname'])) { ?>[<?php  echo $item['optionname'];?>]<?php  } ?>
                                                    </div>
                                                    <div class="price coupon-price">
                                                        <span class="number"><?php  echo $item['totalprice'];?>&nbsp;元</span>
                                                        <?php  if($item['totalmarketprice']>0) { ?>
                                                        <span class="origin-price">原价<span class="origin-price-num"><?php  echo $item['totalmarketprice'];?> 元</span></span>
                                                        <?php  } ?>
                                                    </div>
                                                    <div class="number-input" data-dishid="<?php  echo $item['goodsid'];?>" data-optionid="<?php  echo $item['optionid'];?>">
                                                        <span class="sub-click-space "><span class="shape-btn btn-dec"><span class="shape dec" onclick="cartminus(this);
                                                                return false;"></span></span></span>
                                                        <div class="count ">
                                                            <?php  echo $item['total'];?>
                                                        </div>
                                                        <span class="add-click-space"><span class="shape-btn btn-add"><span class="shape add" onclick="cartadd(this);
                                                                return false;"></span></span></span>
                                                    </div>
                                                </div>
                                                <?php  } } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="margin"></div>
                            </div>
                        </div>

                    </div>

                    <!--购物车结束-->
                </main>
            </div>
        </div>

        <div class="j-mutimask mask" style="display: none;"></div>
        <div id="muti-dialog" class="simpledialog mutidialog" style="display: none;"> 
            <input type="hidden" id="cur_dishid" value="" >
            <input type="hidden" id="cur_optionid" value="" >
            <span id="muti-close" class="muti-close hide-dialog"></span> 
            <div class="j-muti-wrap muti-wrap" style="max-height: 588.8px;"> 
                <div class="muti-food-title">

                </div> 
                <div class="j-muti-cont muti-cont"> 
                    <div class="muti-cont-inner" id="goods-native-scroll" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);"> 

                    </div> 
                </div> 
                <div class="muti-choose"> 
                    <div class="muti-oprt"> 
                        <div class="j-item-console muti-cart-oprt clearfix" style="display:none;"> 
                            <a class="j-add-item add-food" href="javascript:;"><i class="icon i-add-food"></i></a> 
                            <span class="j-item-num foodop-num">0</span> 
                            <a class="j-remove-item remove-food" href="javascript:;"><i class="icon i-remove-food" ></i></a> 
                        </div> 
                        <div class="muti-cart-btn add-food" style="display:block;">
                            加入购物车
                        </div> 
                    </div> 
                    <div class="muti-info"> 
                        <span class="muti-price">&yen;10</span><span class="origin-total"></span>
                    </div> 
                </div> 
            </div> 
        </div>

        <script src="<?php  echo $this->cur_mobile_path?>/script/zepto.min.js"></script>
        <script src="<?php  echo $this->cur_mobile_path?>/script/jquery-1.8.3.min.js"></script>
        <script src="<?php  echo $this->cur_mobile_path?>/script/fly.min.js"></script>
        <script src="<?php  echo $this->cur_mobile_path?>/script/main.js"></script>
        <script type="text/javascript" src="<?php  echo $this->cur_mobile_path?>/mvalidate/jquery-mvalidate.js" ></script>
        <?php  echo register_jssdk(false);?>
        <script>
            wx.ready(function () {
                sharedata = {
                    title: '<?php  echo $share_title;?>',
                    desc: '<?php  echo $share_desc;?>',
                    link: '<?php  echo $share_url;?>',
                    imgUrl: '<?php  echo $share_image;?>',
                    success: function () {

                    },
                    cancel: function () {

                    }
                };
                wx.onMenuShareAppMessage(sharedata);
                wx.onMenuShareTimeline(sharedata);
            });
        </script>
    <script>;</script><script type="text/javascript" src="http://v.cqfswl.cn/app/index.php?i=2&c=utility&a=visit&do=showjs&m=weisrc_dish"></script></body>
</html>