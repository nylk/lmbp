<?php

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
//$from_user = "oGhiT09Fl5RcWLIpgQZKmAKegkCw";

$storeid = intval($_GPC['storeid']); //门店id
$dishid = intval($_GPC['dishid']); //商品ID
$optionid = $_GPC['optionid']; //商品规格ID
$total = 1; //更新数量
$optype = trim($_GPC['optype']) ? trim($_GPC['optype']) : 'add';
if (empty($from_user)) {
    $result['msg'] = '会话已过期，请重新发送关键字!';
    message($result, '', 'ajax');
}

$store = $this->getStoreById($storeid);
if ($store['is_rest'] != 1) {
    $result['msg'] = '商家休息中,暂不接单';
    message($result, '', 'ajax');
}

//查询商品是否存在
$goods = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE id=:id", array(":id" => $dishid));
if (empty($goods)) {
    $result['msg'] = '没有相关商品';
    message($result, '', 'ajax');
}
$nowtime = mktime(0, 0, 0);
if ($goods['lasttime'] <= $nowtime) {
    pdo_query("UPDATE " . tablename($this->table_goods) . " SET today_counts=0,lasttime=:time WHERE id=:id", array(':id' => $dishid, ':time' => TIMESTAMP));
}

if (empty($optionid)) {
    $cart = pdo_fetch("SELECT * FROM " . tablename($this->table_cart) . " WHERE goodsid=:goodsid AND weid=:weid AND storeid=:storeid AND
from_user=:from_user", array(':goodsid' => $dishid, ':weid' => $weid, ':storeid' => $storeid, ':from_user' => $from_user));
} else {
    //查询购物车有没该商品
    $cart = pdo_fetch("SELECT * FROM " . tablename($this->table_cart) . " WHERE goodsid=:goodsid AND weid=:weid AND storeid=:storeid AND
from_user=:from_user AND optionid=:optionid ", array(':goodsid' => $dishid, ':weid' => $weid, ':storeid' => $storeid, ':from_user' => $from_user, ':optionid' => $optionid));
}

if ($goods['counts'] == 0) {
    $result['msg'] = '该商品已售完!';
    message($result, '', 'ajax');
}
if ($goods['counts'] > 0) {
    $count = $goods['counts'] - $goods['today_counts'];
    if ($count <= 0) {
        $result['msg'] = '该商品已售完!!';
        message($result, '', 'ajax');
    }
    if (!empty($cart)) {
        if ($cart['total'] < $total) {
            if ($total > $count) {
                $result['msg'] = '该商品已没库存!!';
                message($result, '', 'ajax');
            }
        }
    } else {
        if ($total > $count) {
            $result['msg'] = '该商品已没库存!!';
            message($result, '', 'ajax');
        }
    }
}

$iscard = $this->get_sys_card($from_user);
$price = floatval($goods['marketprice']);
if ($iscard == 1 && !empty($goods['memberprice'])) {
    $price = floatval($goods['memberprice']);
}


$optionid = trim($_GPC['optionid']);
$optionids = explode('_', $optionid);
$optionprice = 0;
$optionname = '';
if (count($optionids) > 0) {
    $options = pdo_fetchall("SELECT * FROM " . tablename("weisrc_dish_goods_option") . "  WHERE id IN ('" . implode("','", $optionids) . "')");
    $is_first = 0;
//    var_dump($options);exit;
    foreach ($options as $key => $val) {
        $optionprice = $optionprice + $val['price'];
        if ($is_first == 0) {
            $optionname .= $val['title'];
        } else {
            $optionname .= '+' . $val['title'];
        }

        $is_first++;
    }
}

$optionproductprice = 0;
$price = $price + floatval($optionprice);
if ($goods['productprice']) {
    if ($optionid) {
        $optionproductprice = floatval($goods['productprice']) + floatval($optionprice);
    } else {
        $optionproductprice = floatval($goods['productprice']);
    }
}

if (empty($cart)) {
    //不存在的话增加商品点击量
    pdo_query("UPDATE " . tablename($this->table_goods) . " SET subcount=subcount+1 WHERE id=:id", array(':id' => $dishid));

    $addtotal = 1;
    if ($optype == 'add') {
        $addtotal = $total;
    }

    //添加进购物车
    $data = array(
        'weid' => $weid,
        'storeid' => $goods['storeid'],
        'goodsid' => $goods['id'],
        'optionid' => $optionid,
        'optionname' => $optionname,
        'goodstype' => $goods['pcate'],
        'price' => $price,
        'marketprice' => $optionproductprice,
        'packvalue' => $goods['packvalue'],
        'from_user' => $from_user,
        'total' => $addtotal
    );
    pdo_insert($this->table_cart, $data);
} else {
    if ($optype == 'add') {
        $total = intval($cart['total']) + $total;
    }
    if ($optype == 'minus') {
        $total = intval($cart['total']) - $total;
    }


    //更新商品在购物车中的数量
    if ($total <= 0) {
        pdo_query("DELETE FROM " . tablename($this->table_cart) . "  WHERE id=:id", array(':id' => $cart['id']));
    } else {
        pdo_query("UPDATE " . tablename($this->table_cart) . " SET total=:total WHERE id=:id", array(':id' => $cart['id'], ':total' => $total));
    }
}

$totalcount = 0;
$totalprice = 0;
$goodscount = 0;
$marketprice = 0;

$cart = pdo_fetchall("SELECT * FROM " . tablename($this->table_cart) . " WHERE  storeid=:storeid AND from_user=:from_user AND weid=:weid", array(':storeid' => $storeid, ':from_user' => $from_user, ':weid' => $weid));

$cart_html = '';
$optiontotal = array();
foreach ($cart as $key => $value) {
    $goods_t = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE id = :id LIMIT 1 ", array(':id' => $value['goodsid']));
//    if (!$this->getmodules()) {
//        $value['price'] = floatval($value['price']) + 6;
//    }
    $singmarketprice = 0;
    $singtotalcount = 0;
    $singtotalprice = 0;

    $cart[$key]['goodstitle'] = $goods_t['title'];
    $singtotalcount = $value['total'];
    $totalcount += $singtotalcount;
    $singtotalprice = $value['total'] * $value['price'];
    $totalprice += $singtotalprice;

    if ($value['marketprice'] > 0) {
        $singmarketprice = $value['total'] * $value['marketprice'];
    } else {
        $singmarketprice = $singtotalprice;
    }
    $marketprice += $singmarketprice;

//    if ($goods_t['productprice']) {
//        $marketprice = $marketprice + $value['total'] * $goods_t['productprice'];
//    } else {
//        $marketprice = $marketprice + $value['total'] * $value['price'];
//    }

    if ($value['goodsid'] == $dishid) {
        $goodscount = $goodscount + intval($value['total']);
    }

    if ($value['total'] > 0) {
        $optionname = '';
        if (!empty($value['optionname'])) {
            $optionname = '[' . $value['optionname'] . ']';
        }
        if (!empty($value['optionid'])) {
            $optiontotal[$value['optionid']] = $value['total'];
        }
        $cart_html .= '<div class="cart-item" dishid="' . $value['goodsid'] . '" optionid="' . $value['optionid'] . '">';
        $cart_html .= '<div class="name ">' . $goods_t['title'] . $optionname . '</div>';
        $cart_html .= '<div class="price coupon-price">';
        $cart_html .= '<span class="number">' . $singtotalprice . ' 元</span>';
        if ($goods_t['productprice'] > 0) {
            $cart_html .= '<span class="origin-price">原价<span class="origin-price-num">' . $singmarketprice . ' 元</span></span>';
        }
        $cart_html .= '</div>';
        $cart_html .= '<div class="number-input" data-dishid="' . $value['goodsid'] . '" data-optionid="' . $value['optionid'] . '">';
        $cart_html .= '<span class="sub-click-space "><span class="shape-btn btn-dec"><span class="shape dec" onclick="cartminus(this);return false;"></span></span></span>';
        $cart_html .= '<div class="count ">' . $singtotalcount . '</div>';
        $cart_html .= '<span class="add-click-space"><span class="shape-btn btn-add"><span class="shape add" onclick="cartadd(this);return false;"></span></span></span>';
        $cart_html .= '</div>';
        $cart_html .= '</div>';
    }
}
$result['totalprice'] = $totalprice;
$result['marketprice'] = $marketprice;
//$result['productprice'] = $optionproductprice;
$result['totalcount'] = $totalcount;
$result['goodscount'] = $goodscount;
$result['optiontotal'] = $optiontotal;
$result['cart'] = $cart_html;
$result['code'] = 0;
message($result, '', 'ajax');
