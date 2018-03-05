<?php

global $_GPC, $_W;
$weid = $this->_weid;
$from_user = $this->_fromuser;
//$from_user = "oGhiT09Fl5RcWLIpgQZKmAKegkCw";

//查询商品是否存在
$id = intval($_GPC['dishid']); //商品id
$goods = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE id=:id", array(":id" => $id));
if (empty($goods)) {
    echo json_encode(0);
} else {
    $iscard = $this->get_sys_card($this->_fromuser);
    $goods['dprice'] = $goods['marketprice'];
    if ($iscard == 1 && !empty($goods['memberprice'])) {
        $goods['dprice'] = $goods['memberprice'];
    }
    $optionid = trim($_GPC['optionid']);
    $optionids = explode('_', $optionid);
    $optionprice = 0;

    if (count($optionids) > 0) {
        $optionprice = pdo_fetchcolumn("SELECT sum(price) FROM " . tablename("weisrc_dish_goods_option") . "  WHERE id IN ('" . implode("','", $optionids) . "')");
    }
    $goods['price'] = floatval($goods['dprice']) + floatval($optionprice);
    if ($goods['productprice']) {
        $goods['productprice'] = floatval($goods['productprice']) + floatval($optionprice);
    }

    $cart = pdo_fetchcolumn("SELECT total FROM " . tablename($this->table_cart) . " WHERE  from_user=:from_user AND weid=:weid AND optionid=:optionid", array(':from_user' => $from_user, ':weid' => $weid, ':optionid' => $optionid));
    $goods['optiontotal'] = 0;
    if ($cart['total']) {
        $goods['optiontotal'] = $cart['total'];
    }
    echo json_encode($goods);
}