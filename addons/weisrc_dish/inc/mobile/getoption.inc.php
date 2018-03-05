<?php

global $_GPC, $_W;
$weid = $this->_weid;
$from_user = $this->_fromuser;
//$from_user = "oGhiT09Fl5RcWLIpgQZKmAKegkCw";

//查询商品是否存在
$id = intval($_GPC['id']); //商品id
$goods = pdo_fetchall("SELECT * FROM " . tablename("weisrc_dish_goods_option") . " WHERE goodsid=:goodsid
        ORDER BY price ASC,displayorder DESC", array(":goodsid" => $id));
$str = '';

if (empty($goods)) {
    echo json_encode(0);
} else {
    $goodsgroup = array();
    foreach ($goods as $key => $value) {
        if (!in_array($value['start'], $goodsgroup)) {
            $goodsgroup[] = $value['start'];
        }
    }
    $cur_option = array();
    foreach ($goodsgroup as $key1 => $val) {
        $i = 0;
        $selected = 'selected';
        $str .= '<div class="muti-sec" > <p class="muti-sec-title">' . $val . '</p><div class="muti-choice"> ';
        foreach ($goods as $key2 => $val2) {
            if ($val == $val2['start']) {
                if ($i == 0) {
                    $cur_option[] = $val2['id'];
                }
                $str .='<span onclick="return choosespec(this,' . $id . ');" data-skuid="' . $val2['id'] . '"  class="choose-sku ' . $selected . '">' . $val2['title'] . '</span>';
                $selected = '';
                $i++;
            }
        }
        $str .= '</div></div>';
    }

    $goodsitem = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE id=:id", array(":id" =>
        $id));
    $iscard = $this->get_sys_card($this->_fromuser);
    $goodsitem['dprice'] = $goodsitem['marketprice'];
    if ($iscard == 1 && !empty($goodsitem['memberprice'])) {
        $goodsitem['dprice'] = $goodsitem['memberprice'];
    }

    //现在的购物车
    $cart = pdo_fetchall("SELECT * FROM " . tablename($this->table_cart) . " WHERE  from_user=:from_user AND weid=:weid AND goodsid=:goodsid", array(':from_user' => $from_user, ':weid' => $weid, ':goodsid' => $id));

    $optiontotal = array();
    if ($cart) {
        foreach ($cart as $key => $value) {
            $optiontotal[$value['optionid']] = $value['total'];
        }
    }
    $str_cur_option = implode('_', $cur_option);
    $cur_total = false;
    if ($optiontotal[$str_cur_option] > 0) {
        $cur_total = true;
    }

    $data = array(
        'price' => $goodsitem['dprice'],
        'productprice' => $goodsitem['productprice'],
        'title' => $goodsitem['title'],
        'cur_option' => $str_cur_option,
        'optiontotal' => $optiontotal,
        'cur_total' => $cur_total,
        'content' => $str
    );
    echo json_encode($data);
}