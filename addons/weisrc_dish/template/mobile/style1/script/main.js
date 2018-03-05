// 滚动条
$('.commodity-list').scroll(function() {
    var cate = $("#hddCateCount").val(); //菜单栏个数
    var ftop = $('.commodity-list').scrollTop();

    for (var i = 0; i < cate; i++) {
        var fheighti = $('.commodity-list .floor').eq(i).offset();
        var hi = fheighti.top;
        if (50 > hi) {
            $('.tab-item').removeClass('active');
            $('.tab-item').eq(i).addClass('active');
            var catename = $('.tab-item').eq(i).html()
            if ($('.list-activity').size() > 0) {
                $('.active-floor').html(catename);
            } else {
                $('<div class="list-activity active"><div class="active-floor">' + catename + '</div></div>').insertBefore(".commodity-list");
            }
        }
    }
    if (ftop < 80) {
        $('.list-activity').remove();
    }
});

// 导航菜单
$(function() {
    $('.tab-item').on('click', function() {
        var idx = $(this).index();
        var container = $('.commodity-list');
        var scrollTo = $('.commodity-list .floor').eq(idx).offset();
        container.animate({
            scrollTop: scrollTo.top - container.offset().top + container.scrollTop() + 5
        }, 500);

    });
});

// 加入购物车
$('.js-number-input .btn-add .add').on('click', function(event) {
    var txt_count = $(this).closest('.number-input').find('.count'),
            btn_dec = $(this).closest('.number-input').find('.sub-click-space'),
            obj_cart = $('.cart-bar');

    var flyer = $('<div class="circle" >'); //抛物体对象
    var offset = $('.icon-cart').offset();

    var dishid = $(this).closest('.number-input').data('dishid'),
            url = $('#hddAddCartUrl').val(),
            params = {
                'dishid': dishid
            };

    $.ajax({
        url: url,
        type: "post",
        data: params,
        dataType: 'json',
        success: function(data) {
            if (data['message']['code'] != 0) {
                $.mvalidateTip(data['message']['msg']);
                return;
            }

            totalprice = data['message']['totalprice'];
            totalcount = data['message']['totalcount'];
            goodscount = data['message']['goodscount'];
            marketprice = data['message']['marketprice'];
            discount = marketprice - totalprice;
            //discount = discount.toFixed(2);
            txt_count.text(goodscount).removeClass('hidden');
            btn_dec.removeClass('hidden');
            if (obj_cart.find('.count').size() > 0) {
                obj_cart.find('.count').text(totalcount);
            } else {
                obj_cart.find('.icon-cart-bg').append('<div class="count">' + totalcount + '</div>');
            }

            //价格
            //totalprice = totalprice.toFixed(2);
            $('.summary .actual').data('totalprice', totalprice);
            obj_cart.find('.summary .actual-total').html('合计: ' + totalprice + ' 元');
            if (discount > 0) {
                if (obj_cart.find('.origin-total').size() > 0) {
                    obj_cart.find('.origin-total').html(marketprice + ' 元');
                } else {
                    obj_cart.find('.actual').append('<span class="origin-total">' + marketprice + ' 元</span>');
                }
                obj_cart.find('.coupon-exclusive').html('已优惠：' + discount + ' 元').removeClass('hidden');
            }
            $('#cart-box').html(data['message']['cart']);
            $('#selectCount').html("已选商品 (" + totalcount + ")");
            flyer.fly({
                start: {
                    left: event.clientX - 25, //抛物体起点横坐标
                    top: event.clientY - 25 //抛物体起点纵坐标
                },
                end: {
                    left: offset.left + 10, //抛物体终点横坐标
                    top: offset.top + 20 //抛物体终点纵坐标
                },
                onEnd: function() {
                    this.destory(); //销毁抛物体
                }
            });
        }
    });
});

$('.btn-add-options').on('click', function(event) {
    var opurl = $('#hddOptionUrl').val();
    var id = $(this).data('goodsid');

    $.ajax({
        url: opurl,
        type: "post",
        data: {'id': id},
        dataType: 'json',
        success: function(data) {
            if (data != 0) {
                $("#muti-dialog").find('.muti-price').html('&yen;' + data.price);
                $("#muti-dialog").find('.muti-food-title').html(data.title);
                $("#goods-native-scroll").html(data.content);
                if (data.productprice) {
                    $("#muti-dialog").find(".origin-total").html(data.productprice + ' 元');
                }
                if (data.cur_total) {
                    $('.muti-choose').find('.j-item-console').show();
                    $('.muti-choose').find('.muti-cart-btn').hide();
                    $('.muti-choose').find('.j-item-num').text(data.optiontotal[data.cur_option]).show();
                    $('.muti-choose').find('.j-remove-item').show();
                }
                $('#cur_dishid').val(id);
                $('#cur_optionid').val(data.cur_option);
                $('#muti-dialog,.j-mutimask').css({"display": "block"});
            }
        }
    });
});



//打开购物车
$('.cart-bar,.overlay').click(function(e) {
    var cartnum = $('.cart-bar .count').text();
    var obj_drawer = $('.c-drawer');
    if (obj_drawer.hasClass('open')) {
        obj_drawer.removeClass('open').removeClass('drawer');
        $('.btn-confirm').text('选好了').unbind('click');
    } else {
        if (cartnum > 0) {
            obj_drawer.addClass('drawer').addClass('open');
            $('.btn-confirm').text('去支付').on('click', function(e) {
                location.href = $('#hddJumpUrl').val();
                return false;
            });
        }
    }
});

$('.add-food').on('click', function() {
    var txt_count = $('.muti-cart-oprt').find('.foodop-num'),
            btn_dec = $('.muti-cart-oprt').find('.remove-food'),
            obj_cart = $('.cart-bar');
    cur_dishid = $('#cur_dishid').val();
    cur_optionid = $('#cur_optionid').val();
    obj = $('#dishid_' + cur_dishid);

    for (var i = 0; i < $('.muti-sec').length; i++) {
        if ($('.muti-sec').eq(i).find('span.selected').length < 1) {
            $.mvalidateTip("请选择" + $('.muti-sec').eq(i).find('.muti-sec-title').html());
            return false;
        }
    }

    var url = $('#hddAddCartUrl').val(),
            params = {
                'dishid': cur_dishid,
                'optionid': cur_optionid
            };

    $.ajax({
        url: url,
        type: "post",
        data: params,
        dataType: 'json',
        success: function(data) {
            if (data['message']['code'] != 0) {
                alert(data['message']['msg']);
                //$.mvalidateTip(data['message']['msg']);
                return;
            }

            totalprice = data['message']['totalprice'];
            totalcount = data['message']['totalcount'];
            goodscount = data['message']['goodscount'];
            marketprice = data['message']['marketprice'];
            productprice = data['message']['productprice'];
            discount = marketprice - totalprice;
            //discount = discount.toFixed(2);
            txt_count.text(data['message']['optiontotal'][cur_optionid]).show();
            obj.find('.j-muti-num').text(goodscount).show();
            btn_dec.show().closest('.muti-cart-oprt').show();
            $(this).hide();
            if (obj_cart.find('.count').size() > 0) {
                obj_cart.find('.count').text(totalcount);
            } else {
                obj_cart.find('.icon-cart-bg').append('<div class="count">' + totalcount + '</div>');
            }

            //价格
            //totalprice = totalprice.toFixed(2);
            $('.summary .actual').data('totalprice', totalprice);
            obj_cart.find('.summary .actual-total').html('合计: ' + totalprice + ' 元');

            if (discount > 0) {
                if (obj_cart.find('.origin-total').size() > 0) {
                    obj_cart.find('.origin-total').html(marketprice + ' 元');
                } else {
                    obj_cart.find('.actual').append('<span class="origin-total">' + marketprice + ' 元</span>');
                }
                obj_cart.find('.coupon-exclusive').html('已优惠：' + discount + ' 元').removeClass('hidden');
            }
            $('#cart-box').html(data['message']['cart']);
            $('#selectCount').html("已选商品 (" + totalcount + ")");
        }
    });

});

$('.j-remove-item').on('click', function() {
    var dishid = $('#cur_dishid').val(),
            optionid = $('#cur_optionid').val(),
            obj = $('#dishid_' + dishid),
            txt_count = $('.muti-cart-oprt').find('.foodop-num'),
            btn_dec = $('.muti-cart-oprt').find('.remove-food'),
            url = $('#hddAddCartUrl').val(),
            obj_cart = $('.cart-bar'),
            params = {
                'dishid': dishid,
                'optionid': optionid,
                'optype': 'minus'
            };

    $.ajax({
        url: url,
        type: "post",
        data: params,
        dataType: 'json',
        success: function(data) {
            if (data['message']['code'] != 0) {
                alert(data['message']['msg']);
                $.mvalidateTip(data['message']['msg']);
                return;
            }

            totalprice = data['message']['totalprice'];
            totalcount = data['message']['totalcount'];
            goodscount = data['message']['goodscount'];
            optiontotal = data['message']['optiontotal'][optionid];
            marketprice = data['message']['marketprice'];
            discount = marketprice - totalprice;
            //discount = discount.toFixed(2);
            //txt_count.text(data['message']['optiontotal'][optionid]);
            if (optiontotal > 0) {
                txt_count.text(optiontotal).show();
                btn_dec.show();
            } else {
                $('.muti-choose').find('.muti-cart-btn').show();
                $('.muti-choose').find('.j-item-console').hide();
                txt_count.text(optiontotal).hide();
                btn_dec.hide();
            }

            if (goodscount > 0) {
                obj.find('.j-muti-num').text(goodscount).show();
            } else {
                obj.find('.j-muti-num').text(optiontotal).hide();
            }

            if (obj_cart.find('.count').size() > 0) {
                obj_cart.find('.count').text(totalcount);
            } else {
                obj_cart.find('.icon-cart-bg').append('<div class="count">' + totalcount + '</div>');
            }

            //价格
            //totalprice = totalprice.toFixed(2);
            $('.summary .actual').data('totalprice', totalprice);
            obj_cart.find('.summary .actual-total').html('合计: ' + totalprice + ' 元');
            if (discount > 0) {
                if (obj_cart.find('.origin-total').size() > 0) {
                    obj_cart.find('.origin-total').html(marketprice + ' 元');
                } else {
                    obj_cart.find('.actual').append('<span class="origin-total">' + marketprice + ' 元</span>');
                }
                obj_cart.find('.coupon-exclusive').html('已优惠：' + discount + ' 元').removeClass('hidden');
            }
            else {
                obj_cart.find('.origin-total').remove();
                obj_cart.find('.coupon-exclusive').addClass('hidden');
            }

            $('#cart-box').html(data['message']['cart']);
            $('#selectCount').html("已选商品 (" + totalcount + ")");
            if (totalprice <= 0) {
                obj_cart.find('.count').remove();
                obj_cart.find('.origin-total').remove();
                obj_cart.find('.coupon-exclusive').addClass('hidden');
                var obj_drawer = $('.c-drawer');
                if (obj_drawer.hasClass('open')) {
                    obj_drawer.removeClass('open').removeClass('drawer');
                    $('.btn-confirm').text('选好了').unbind('click');
                }
            }

        }
    });
});

function cartadd(obj) {
    var dishid = $(obj).closest('.number-input').data('dishid'),
            optionid = $(obj).closest('.number-input').data('optionid'),
            url = $('#hddAddCartUrl').val(),
            dobj = $('#dishid_' + dishid),
            txt_count = $(dobj).find('.number-input').find('.count'),
            btn_dec = $(dobj).find('.number-input').find('.sub-click-space'),
            obj_cart = $('.cart-bar'),
            params = {
                'dishid': dishid,
                'optionid': optionid
            };
    if (optionid) {
        txt_count = $(dobj).find('.j-muti-num');
    }

    $.ajax({
        url: url,
        type: "post",
        data: params,
        dataType: 'json',
        success: function(data) {
            if (data['message']['code'] != 0) {
                alert(data['message']['msg']);
                $.mvalidateTip(data['message']['msg']);
                return;
            }

            totalprice = data['message']['totalprice'];
            totalcount = data['message']['totalcount'];
            goodscount = data['message']['goodscount'];
            marketprice = data['message']['marketprice'];
            discount = marketprice - totalprice;
            //discount = discount.toFixed(2);
            txt_count.text(goodscount).removeClass('hidden');
            btn_dec.removeClass('hidden');
            if (obj_cart.find('.count').size() > 0) {
                obj_cart.find('.count').text(totalcount);
            } else {
                obj_cart.find('.icon-cart-bg').append('<div class="count">' + totalcount + '</div>');
            }

            //价格
            //totalprice = totalprice.toFixed(2);
            $('.summary .actual').data('totalprice', totalprice);
            obj_cart.find('.summary .actual-total').html('合计: ' + totalprice + ' 元');
            if (discount > 0) {
                if (obj_cart.find('.origin-total').size() > 0) {
                    obj_cart.find('.origin-total').html(marketprice + ' 元');
                } else {
                    obj_cart.find('.actual').append('<span class="origin-total">' + marketprice + ' 元</span>');
                }
                obj_cart.find('.coupon-exclusive').html('已优惠：' + discount + ' 元').removeClass('hidden');
            }
            $('#cart-box').html(data['message']['cart']);
            $('#selectCount').html("已选商品 (" + totalcount + ")");

        }
    });
    return false;
}

function cartminus(obj) {
    var dishid = $(obj).closest('.number-input').data('dishid'),
            optionid = $(obj).closest('.number-input').data('optionid'),
            url = $('#hddAddCartUrl').val(),
            dobj = $('#dishid_' + dishid),
            txt_count = $(dobj).find('.number-input').find('.count'),
            btn_dec = $(dobj).find('.number-input').find('.sub-click-space'),
            obj_cart = $('.cart-bar'),
            params = {
                'dishid': dishid,
                'optionid': optionid,
                'optype': 'minus'
            };

    if (optionid) {
        txt_count = $(dobj).find('.j-muti-num');
    }

    $.ajax({
        url: url,
        type: "post",
        data: params,
        dataType: 'json',
        success: function(data) {
            if (data['message']['code'] != 0) {
                alert(data['message']['msg']);
                $.mvalidateTip(data['message']['msg']);
                return;
            }

            totalprice = data['message']['totalprice'];
            totalcount = data['message']['totalcount'];
            goodscount = data['message']['goodscount'];
            marketprice = data['message']['marketprice'];
            discount = marketprice - totalprice;
            //discount = discount.toFixed(2);
            if (goodscount > 0) {
                txt_count.text(goodscount).removeClass('hidden');
                btn_dec.removeClass('hidden');
            } else {
                txt_count.text(goodscount).addClass('hidden');
                btn_dec.addClass('hidden');
            }

            if (obj_cart.find('.count').size() > 0) {
                obj_cart.find('.count').text(totalcount);
            } else {
                obj_cart.find('.icon-cart-bg').append('<div class="count">' + totalcount + '</div>');
            }

            //价格
            //totalprice = totalprice.toFixed(2);
            $('.summary .actual').data('totalprice', totalprice);
            obj_cart.find('.summary .actual-total').html('合计: ' + totalprice + ' 元');
            if (discount > 0) {
                if (obj_cart.find('.origin-total').size() > 0) {
                    obj_cart.find('.origin-total').html(marketprice + ' 元');
                } else {
                    obj_cart.find('.actual').append('<span class="origin-total">' + marketprice + ' 元</span>');
                }
                obj_cart.find('.coupon-exclusive').html('已优惠：' + discount + ' 元').removeClass('hidden');
            }
            else {
                obj_cart.find('.origin-total').remove();
                obj_cart.find('.coupon-exclusive').addClass('hidden');
            }
            $('#cart-box').html(data['message']['cart']);
            $('#selectCount').html("已选商品 (" + totalcount + ")");
            if (totalcount <= 0) {
                obj_cart.find('.count').remove();
                obj_cart.find('.origin-total').remove();
                obj_cart.find('.coupon-exclusive').addClass('hidden');
                var obj_drawer = $('.c-drawer');
                if (obj_drawer.hasClass('open')) {
                    obj_drawer.removeClass('open').removeClass('drawer');
                    $('.btn-confirm').text('选好了').unbind('click');
                }
            }

        }
    });
}

function choosespec(obj, dishid) {
    $(obj).addClass('selected').siblings().removeClass('selected');
    var all_select = 0;
    for (var i = 0; i < $('.muti-sec').length; i++) {
        if ($('.muti-sec').find('span.selected').length == $('.muti-sec').length) {
            all_select = 1;
        }
    }
    if (all_select == 1) {
        var specs = "";
        for (var i = 0; i < $('.muti-sec').length; i++) {
            if (i == ($('.muti-sec').length - 1)) {
                specs += $('.muti-sec').eq(i).find('span.selected').data("skuid");
            } else {
                specs += $('.muti-sec').eq(i).find('span.selected').data("skuid") + "_";
            }
        }
        $('#cur_dishid').val(dishid);
        $('#cur_optionid').val(specs);
        var url = $('#hddSelectOptionUrl').val();
        $.ajax({
            url: url, type: "post", dataType: "json", timeout: "10000",
            data: {
                "dishid": dishid,
                "optionid": specs
            },
            success: function(data) {
                if (data == 0) {
                    $.mvalidateTip('调试中');
                } else {
                    $(".muti-price").html('&yen;' + data["price"]);
                    if (data['productprice']) {
                        $('.muti-info').find('.origin-total').html(data['productprice'] + '元');
                    }
                    if (data['optiontotal'] > 0) {
                        $('.muti-choose').find('.j-item-console').show();
                        $('.muti-choose').find('.muti-cart-btn').hide();
                        $('.muti-choose').find('.j-item-num').text(data['optiontotal']).show();
                        $('.muti-choose').find('.j-remove-item').show();
                    } else {
                        $('.muti-choose').find('.j-item-console').hide();
                        $('.muti-choose').find('.muti-cart-btn').show();
                    }
                }
            }, error: function() {
                $.mvalidateTip("数据请求失败");
            }
        });
    }
}

$('#muti-close,.j-mutimask').on('click', function() {
    $('#muti-dialog,.j-mutimask').hide();
});

$('.js-person').on('click', function() {
    location.href = $('#hddPersonUrl').val();
});

$('.js-charge').on('click', function() {
    location.href = $('#hddChargeUrl').val();
});




