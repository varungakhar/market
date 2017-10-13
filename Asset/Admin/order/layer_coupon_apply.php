<div class="self-order-coupon-apply">
    <div class="box coupon-down-layer apply-layer">
        <div class="view">
            <p>선택한 상품에 적용가능한 총 <strong><?=count($memberCouponArrData)?>개 의 보유쿠폰이 있습니다</strong></p>
            <div class="scroll-box">
                <form name="frmCouponApply" id="frmCouponApply" method="post">
                    <input type="hidden" name="memNo" value="<?=$memNo?>"/>
                    <input type="hidden" name="cart[cartSno]" value=""/>
                    <input type="hidden" name="cart[goodsNo]" value=""/>
                    <input type="hidden" name="cart[goodsCnt]" value=""/>
                    <input type="hidden" name="cart[addGoodsNo]" value=""/>
                    <input type="hidden" name="cart[addGoodsCnt]" value=""/>
                    <input type="hidden" name="cart[couponApplyNo]" value=""/>
                    <input type="hidden" name="memberCartAddTypeCouponNo" value="<?=$memberCartAddTypeCouponNo?>"/>

                    <div class="table1">
                        <table class="table table-cols">
                            <colgroup>
                                <col style="width:60px"/>
                                <col/>
                                <col/>
                                <col/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th></th>
                                <th>쿠폰</th>
                                <th>사용조건</th>
                                <th>사용기한</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($cartCouponArrData as $key => $value){
                            ?>
                            <tr>
                                <td class="ta-c">
                                    <span class="form-element">
                                        <?php if($convertCartCouponPriceArrData['memberCouponAlertMsg'][$value['memberCouponNo']] == 'LIMIT_MIN_PRICE'){ ?>
                                            <input type="checkbox" id="check<?=$value['memberCouponNo']?>" name="memberCouponNo[]" class="checkbox" checked="checked" disabled="disabled">
                                        <?php } else { ?>
                                            <?php if($value['couponKindType'] == 'sale'){ ?>
                                            <input type="checkbox" id="check<?=$value['memberCouponNo']?>" name="memberCouponNo[]" class="checkbox" checked="checked" data-price="<?=$convertCartCouponPriceArrData['memberCouponSalePrice'][$value['memberCouponNo']]?>" data-type="<?=$value['couponKindType']?>" data-duplicate="<?=$value['couponApplyDuplicateType']?>" value="<?=$value['memberCouponNo']?>">
                                            <?php } else if($value['couponKindType'] == 'add'){ ?>
                                            <input type="checkbox" id="check<?=$value['memberCouponNo']?>" name="memberCouponNo[]" class="checkbox" checked="checked" data-price="<?=$convertCartCouponPriceArrData['memberCouponAddMileage'][$value['memberCouponNo']]?>" data-type="<?=$value['couponKindType']?>" data-duplicate="<?=$value['couponApplyDuplicateType']?>" value="<?=$value['memberCouponNo']?>">
                                            <?php } else if($value['couponKindType'] == 'delivery'){ ?>
                                            <input type="checkbox" id="check<?=$value['memberCouponNo']?>" name="memberCouponNo[]" class="checkbox" checked="checked" data-price="<?=$convertCartCouponPriceArrData['memberCouponDeliveryPrice'][$value['memberCouponNo']]?>" data-type="<?=$value['couponKindType']?>" data-duplicate="<?=$value['couponApplyDuplicateType']?>" value="<?=$value['memberCouponNo']?>">
                                            <?php } ?>
                                        <?php } ?>
                                    </span>
                                </td>
                                <td class="guide2">
                                    <label for="check<?=$value['memberCouponNo']?>">
                                        <b><?=gd_currency_symbol()?></b>
                                        <?php if($value['couponKindType'] == 'sale'){ ?>
                                            <strong><?=gd_money_format($convertCartCouponPriceArrData['memberCouponSalePrice'][$value['memberCouponNo']])?></strong>
                                        <?php } else if($value['couponKindType'] == 'add'){ ?>
                                            <strong><?=gd_money_format($convertCartCouponPriceArrData['memberCouponAddMileage'][$value['memberCouponNo']])?></strong>
                                        <?php } else if($value['couponKindType'] == 'delivery'){ ?>
                                            <strong><?=gd_money_format($convertCartCouponPriceArrData['memberCouponDeliveryPrice'][$value['memberCouponNo']])?></strong>
                                        <?php } ?>
                                        <b><?=gd_currency_string()?></b>
                                        <span><?=$convertCartCouponArrData[$key]['couponKindType']?></span>
                                        <em><?=$value['couponNm']?></em>
                                    </label>
                                </td>
                                <td>
                                    <div class="msg">
                                        <?php if($convertCartCouponArrData[$key]['couponMaxBenefit']){ ?>
                                            <span>- <?=$convertCartCouponArrData[$key]['couponMaxBenefit']?></span>
                                        <?php } ?>
                                        <?php if($convertCartCouponArrData[$key]['couponMinOrderPrice']){ ?>
                                            <span>- <?=$convertCartCouponArrData[$key]['couponMinOrderPrice']?></span>
                                        <?php } ?>
                                        <span>- <?=$convertCartCouponArrData[$key]['couponApplyDuplicateType']?></span>
                                    </div>
                                </td>
                                <td class="ta-c date">
                                    <?=$value['memberCouponEndDate']?>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                            <?php
                            $idx = 0;
                            foreach($memberCouponArrData as $key => $value){
                            ?>
                            <tr>
                                <td class="ta-c">
                                <span class="form-element">
                                    <?php if($convertMemberCouponPriceArrData['memberCouponAlertMsg'][$value['memberCouponNo']] == 'LIMIT_MIN_PRICE'){ ?>
                                    <input type="checkbox" id="check<?=$idx?>" class="checkbox" disabled="disabled">
                                    <?php } else { ?>
                                        <?php if($value['couponKindType'] == 'sale'){ ?>
                                        <input type="checkbox" id="check<?=$idx?>" name="memberCouponNo[]" class="checkbox" data-paytype="<?=$value['couponUseAblePaymentType']?>" data-price="<?=$convertMemberCouponPriceArrData['memberCouponSalePrice'][$value['memberCouponNo']]?>" data-type="<?=$value['couponKindType']?>" data-duplicate="<?=$value['couponApplyDuplicateType']?>" value="<?=$value['memberCouponNo']?>">
                                        <?php } else if($value['couponKindType'] == 'add'){ ?>
                                        <input type="checkbox" id="check<?=$idx?>" name="memberCouponNo[]" class="checkbox" data-paytype="<?=$value['couponUseAblePaymentType']?>" data-price="<?=$convertMemberCouponPriceArrData['memberCouponAddMileage'][$value['memberCouponNo']]?>" data-type="<?=$value['couponKindType']?>" data-duplicate="<?=$value['couponApplyDuplicateType']?>" value="<?=$value['memberCouponNo']?>">
                                        <?php } else if($value['couponKindType'] == 'delivery'){ ?>
                                        <input type="checkbox" id="check<?=$idx?>" name="memberCouponNo[]" class="checkbox" data-paytype="<?=$value['couponUseAblePaymentType']?>" data-price="<?=$convertMemberCouponPriceArrData['memberCouponDeliveryPrice'][$value['memberCouponNo']]?>" data-type="<?=$value['couponKindType']?>" data-duplicate="<?=$value['couponApplyDuplicateType']?>" value="<?=$value['memberCouponNo']?>">
                                        <?php } ?>
                                    <?php } ?>
                                </span>
                                </td>
                                <td class="guide2">
                                    <label for="check<?=$idx?>">
                                        <b><?=gd_currency_symbol()?></b>
                                        <?php if($value['couponKindType'] == 'sale'){ ?>
                                            <strong><?=gd_money_format($convertMemberCouponPriceArrData['memberCouponSalePrice'][$value['memberCouponNo']])?></strong>
                                        <?php } else if($value['couponKindType'] == 'add'){ ?>
                                        <strong><?=gd_money_format($convertMemberCouponPriceArrData['memberCouponAddMileage'][$value['memberCouponNo']])?></strong>
                                        <?php } else if($value['couponKindType'] == 'delivery'){ ?>
                                            <strong><?=gd_money_format($convertMemberCouponPriceArrData['memberCouponDeliveryPrice'][$value['memberCouponNo']])?></strong>
                                        <?php } ?>
                                        <b><?=gd_currency_string()?></b>
                                        <span><?=$convertMemberCouponArrData[$key]['couponKindType']?></span>
                                        <em><?=$value['couponNm']?></em>
                                    </label>
                                </td>
                                <td>
                                    <div class="msg">
                                        <?php if($convertMemberCouponArrData[$key]['couponMaxBenefit']){ ?>
                                        <span>- <?=$convertMemberCouponArrData[$key]['couponMaxBenefit']?></span>
                                        <?php } ?>
                                        <?php if($convertMemberCouponArrData[$key]['couponMinOrderPrice']){ ?>
                                        <span>- <?=$convertMemberCouponArrData[$key]['couponMinOrderPrice']?></span>
                                        <?php } ?>
                                        <span>- <?=$convertMemberCouponArrData[$key]['couponApplyDuplicateType']?></span>
                                    </div>
                                </td>
                                <td class="ta-c date">
                                    <?=$value['memberCouponEndDate']?>
                                </td>
                            </tr>
                            <?php
                                $idx++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="benefits">
                <div class="detail">
                    <div>
                        <span>총 할인금액</span> <strong><?=gd_currency_symbol()?><b id="couponSalePrice">0</b><?=gd_currency_string()?></strong>
                    </div>
                    <div>
                        <span>총 적립금액</span> <strong><?=gd_currency_symbol()?><b id="couponAddPrice">0</b><?=gd_currency_string()?></strong>
                    </div>
                </div>
            </div>
            <div class="text-center mgt20">
                <button class="skinbtn point1 layer-close btn-close"><em>취소</em></button>
                <button class="skinbtn point2 lca-couponapply" id="btnCouponApply"><em>쿠폰 적용</em></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    <!--
    $(document).ready(function () {
        $('input:checkbox[name="memberCouponNo[]"]').click(function (e) {
            if (($(this).prop('checked') == true) && ($(this).data('duplicate') == 'n')) {
                $('input:checkbox[name="memberCouponNo[]"]').not($(this)).each(function (index) {
                    $(this).attr("checked", false);
                    $(this).next('label').removeClass('on');
                    $(this).attr('disabled', 'disabled');
                });
            } else if (($(this).prop('checked') == false) && ($(this).data('duplicate') == 'n')) {
                $('input:checkbox[name="memberCouponNo[]"]').not($(this)).each(function (index) {
                    $(this).removeAttr('disabled', 'disabled');
                });
            }
            couponPriceSum();
        });

        $('.btn-close').click(function () {
            layer_close();
        });

        $('#btnCouponApply').click(function (e) {
            var couponApplyNoArr = [];
            $('input:checkbox[name="memberCouponNo[]"]:checked').each(function (index) {
                couponApplyNoArr[index] = $(this).val();
            });
            var couponApplyNoString = couponApplyNoArr.join('<?=$int_division?>');
            $('[name="cart[cartSno]"]').val('<?=$cartSno?>');
            $('[name="cart[couponApplyNo]"]').val(couponApplyNoString);

            $('#frmCouponApply input[name=\'mode\']').val('order_write_goods_coupon_apply');
            var params = $( "#frmCouponApply" ).serialize();

            var ajaxUrl = "../order/order_ps.php?mode=order_write_goods_coupon_apply";

            $.ajax({
                method: "get",
                cache: false,
                url: ajaxUrl,
                data: params,
                success: function (memberCouponNo) {
                    //사용처리된 쿠폰 번호. (실제 cart 에서 삭제되어야 할 쿠폰번호 array 형태)
                    if($.trim($("input[name='memberCartAddTypeCouponNo']").val()) !== ''){
                        parent.updateMemberCartSnoCookie($('[name="cart[cartSno]"]').val(), memberCouponNo);
                    }

                    parent.set_goods('y');

                    layer_close();
                },
                error: function (data) {
                    alert(data.message);

                }
            });

            return false;
        });
        couponApplySetting();
        couponPriceSum();
    });

    // 쿠폰 적용 내용 초기화 (설정)
    function couponApplySetting() {
        $.each($('input:checkbox[name="memberCouponNo[]"]:checked'), function (index){
            if ($(this).data('duplicate') == 'n') {
                $('input:checkbox[name="memberCouponNo[]"]').not($(this)).each(function (index) {
                    $(this).attr("checked", false);
                    $(this).next('label').removeClass('on');
                    $(this).attr('disabled', 'disabled');
                });
            } else if ($(this).data('duplicate') == 'n') {
                $('input:checkbox[name="memberCouponNo[]"]').not($(this)).each(function (index) {
                    $(this).removeAttr('disabled', 'disabled');
                });
            }
        });
    }

    // 선택 쿠폰 금액 계산
    function couponPriceSum() {
        var salePrice = 0;
        var addPrice = 0;
        $('input:checkbox[name="memberCouponNo[]"]:checked').each(function (index) {
            if ($(this).data('type') == 'sale') {
                salePrice += parseFloat($(this).data('price'));
            } else if ($(this).data('type') == 'add') {
                addPrice += parseFloat($(this).data('price'));
            }
        });

        $('#couponSalePrice').text(numeral(salePrice).format());
        $('#couponAddPrice').text(numeral(addPrice).format());
    }
    //-->
</script>
