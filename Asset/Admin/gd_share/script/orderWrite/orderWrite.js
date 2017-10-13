var mileageUse;
var memberInfo;
var giftInfo;
var giftConf;
var couponConf;
var addFieldInfo;
var payLimitData;
var orderPossible;
var couponUse;
var mileageGiveExclude;

$(document).ready(function () {
    resetMemberCartSnoCookie();

    //결제수단구역 디스플레이 체크
    displayBankArea();

    // 주문 폼 체크
    $('#frmOrderWriteForm').validate({
        submitHandler: function (form) {
            if($('input[name="memberTypeFl"]:checked').val() === 'y'){
                if(!$("input[name='memNo']").val() || $("input[name='memNo']").val() === '0'){
                    alert("회원을 선택해 주세요.");
                    return false;
                }
            }

            //주문상품 체크
            if (!$('input[name="cartSno[]"]') || $('input[name="cartSno[]"]').length < 1) {
                alert('주문하실 상품이 없습니다.');
                return false;
            }

            $("#selfOrderCartPriceData").attr("data-totalCouponGoodsDcPrice")
            var useMileage = Number($('input[name=\'useMileage\']').val());
            var useDeposit = Number($('input[name=\'useDeposit\']').val());
            var useOrderCouponDc = parseFloat($('input[name="totalCouponOrderDcPrice"]').val());
            var useDeliveryCouponDc = parseFloat($('input[name="totalCouponDeliveryDcPrice"]').val());
            var totalSettlePrice = parseFloat($("#selfOrderCartPriceData").attr("data-totalSettlePrice"));
            if(isNaN(useOrderCouponDc)){
                useOrderCouponDc = 0;
            }
            if(isNaN(useDeliveryCouponDc)){
                useDeliveryCouponDc = 0;
            }

            //기본설정 > 결제수단 체크 - 비회원의 경우 무통장입금을 사용하지 않는 다면 결제방지
            if(settleKindBankUseFl != 'y'){
                if(!$("input[name='memNo']").val() || $("input[name='memNo']").val() === '0'){
                    alert("결제수단이 없습니다.<br />결제 수단 설정을 확인해 주세요.");
                    return false;
                }
                else {
                    var payAble = false;
                    if(useMileage + useDeposit + useOrderCouponDc + useDeliveryCouponDc == totalSettlePrice){
                        payAble = true;
                    }
                    if(payAble === false){
                        alert("결제수단이 없습니다.<br />회원은 마일리지, 예치금, 쿠폰으로 결제 가능합니다.");
                        return false;
                    }
                }
            }

            if(!orderPossible){
                alert("구매 불가능한 상품이 존재합니다.<br />주문상품을 확인해 주세요!");
                return false;
            }


            if($("input[name='memNo']").val() && $("input[name='memNo']").val() !== '0'){
                //회원일시 회원그룹 결제수단 체크 (마일리지+예치금 사용 체크)
                if(memberInfo.settleGb == 'nobank'){
                    var payAble = false;
                    if(useMileage + useDeposit + useOrderCouponDc + useDeliveryCouponDc == totalSettlePrice){
                        payAble = true;
                    }
                    if(payAble === false){
                        alert("무통장 구매가 불가능한 회원 등급입니다.<br />마일리지, 예치금, 쿠폰으로 결제 가능합니다.");
                        return false;
                    }
                }

                //회원일시 상품 결제수단 체크 (마일리지+예치금 사용 체크)
                if(payLimitData){
                    if(payLimitData.orderBankAble == 'n'){
                        var payAble = false;
                        if(payLimitData.orderMileageAble == 'y' && payLimitData.orderDepositAble == 'y') {
                            if(useMileage+useDeposit+useOrderCouponDc+useDeliveryCouponDc == totalSettlePrice){
                                payAble = true;
                            }
                        }
                        else if(payLimitData.orderMileageAble == 'y'){
                            if(useMileage+useOrderCouponDc+useDeliveryCouponDc == totalSettlePrice){
                                payAble = true;
                            }
                        }
                        else if(payLimitData.orderDepositAble == 'y'){
                            if(useDeposit+useOrderCouponDc+useDeliveryCouponDc == totalSettlePrice){
                                payAble = true;
                            }
                        }
                        else {}

                        if(payAble === false){
                            alert("무통장 구매가 불가능합니다.<br />마일리지, 예치금, 쿠폰으로 결제 가능합니다.");
                            return false;
                        }
                    }
                }
            }

            //사은품 선택 체크
            if(giftConf){
                if(giftConf.giftFl == 'y'){
                    var giftPass = true;
                    if(giftInfo){
                        $.each(giftInfo, function (key, value) {
                            $.each(value.gift, function (key2, value2) {
                                if(value2.total > 0){
                                    var selectCnt = $('input[type=checkbox][name*="gift['+key+']"]').closest('tr').find('.gift-select-cnt').val();
                                    if ($('input[type=checkbox][name*="gift['+key+']"]:checked').length < selectCnt) {
                                        giftPass = false;
                                        alert("사은품은 최소 " + selectCnt + "개 이상 선택하셔야 합니다.");
                                        $('input[type=checkbox][name*="gift').eq(0).focus();
                                        return false;
                                    }
                                }
                            });
                            if(giftPass === false){
                                return false;
                            }
                        });
                    }

                    if(giftPass === false){
                        return false;
                    }
                }
            }

            //주문수량 체크
            var countCheckTargetPass = true;
            var countCheckTarget = $("input[name='goodsCnt[]']");
            if(countCheckTarget.length > 0){
                $.each(countCheckTarget, function () {
                    var returnMessage = input_count_change($(this), 'return');
                    if($.trim(returnMessage) !== ''){
                        alert($(this).attr('data-goodsNm') + ' : ' + returnMessage);
                        countCheckTargetPass = false;
                        return false;
                    }
                });
                if(countCheckTargetPass === false){
                    return false;
                }
            }

            var owMemberCartSnoData = [];
            var owMemberRealCartSnoData = [];
            var owMemberCartCouponNoData = [];
            owMemberCartSnoData = $.cookie('owMemberCartSnoData').split(",");
            owMemberRealCartSnoData = $.cookie('owMemberRealCartSnoData').split(",");
            owMemberCartCouponNoData = $.cookie('owMemberCartCouponNoData').split(",");

            $('input[name="cartSno[]"]').each(function(){
                var owMemberCartSnoDataIndex = $.inArray($(this).attr('data-sno'), owMemberCartSnoData);
                if(owMemberCartSnoDataIndex !== -1){
                    $(this).after("<input type='hidden' name='realCartSno["+$(this).attr('data-sno')+"]' value='"+owMemberRealCartSnoData[owMemberCartSnoDataIndex]+"' />");
                    $(this).after("<input type='hidden' name='realCartCouponNo["+$(this).attr('data-sno')+"]' value='"+owMemberCartCouponNoData[owMemberCartSnoDataIndex]+"' />");

                }
            });

            if($.trim($("input[name='taxEmail']").val()) == '미입력 시 주문자의 이메일로 발행'){
                $("input[name='taxEmail']").val('');
            }

            form.target = 'ifrmProcess';
            form.submit();
        },
        rules: {
            'orderName': {
                required: true,
                maxlength: 30
            },
            'orderCellPhone': {
                required: true,
            },
            'orderEmail': {
                required: true,
                email: true
            },
            'orderAddress': {
                required: true
            },
            'orderAddressSub': {
                required: true
            },
            'receiverName': {
                required: true,
                maxlength: 30
            },
            'receiverCellPhone': {
                required: true,
            },
            'receiverAddress': {
                required: true
            },
            'receiverAddressSub': {
                required: true
            },
            'orderMemo': {
                maxlength: 600
            },
            'bankSender': {
                required: function(){
                    return $(".self-order-bank-area").hasClass("display-none") != true;
                }
            },
            'bankAccount': {
                required: function(){
                    return $(".self-order-bank-area").hasClass("display-none") != true;
                }
            },
            'receiptFl': {
                required: true
            },
            'cashCertNo[c]': {
                required: function(){
                    if($("input[name='receiptFl']:checked").val() == 'r'){
                        if($("input[name='cashUseFl']:checked").val() == 'd'){
                            return true;
                        }
                    }
                    return false;
                }
            },
            'cashCertNo[b]': {
                required: function(){
                    if($("input[name='receiptFl']:checked").val() == 'r'){
                        if($("input[name='cashUseFl']:checked").val() == 'e'){
                            return true;
                        }
                    }
                    return false;
                }
            },
            'taxBusiNo': {
                required: function(){
                    return $("input[name='receiptFl']:checked").val() == 't';
                }
            },
            'taxCompany': {
                required: function(){
                    return $("input[name='receiptFl']:checked").val() == 't';
                }
            },
            'taxCeoNm': {
                required: function(){
                    return $("input[name='receiptFl']:checked").val() == 't';
                }
            },
            'taxService': {
                required: function(){
                    return $("input[name='receiptFl']:checked").val() == 't';
                }
            },
            'taxItem': {
                required: function(){
                    return $("input[name='receiptFl']:checked").val() == 't';
                }
            },
            'taxAddress': {
                required: function(){
                    return $("input[name='receiptFl']:checked").val() == 't';
                }
            },
            'taxEmail': {
                email: function(){
                    if($("input[name='receiptFl']:checked").val() == 't' && $.trim($("input[name='taxEmail']").val()) !== ''){
                        return true;
                    }

                    return false;
                }
            },
            'taxAddressSub': {
                required: function(){
                    return $("input[name='receiptFl']:checked").val() == 't';
                }
            },
            'taxZonecode': {
                required: function(){
                    return $("input[name='receiptFl']:checked").val() == 't';
                }
            },
        },
        messages: {
            'orderName': {
                required: '주문자명을 입력하세요.'
            },
            'orderCellPhone': {
                required: '휴대폰번호를 입력하세요.'
            },
            'orderEmail': {
                required: "이메일을 입력하세요.",
                email: "이메일을 정확하게 입력해주세요."
            },
            'orderAddress': {
                required: '주소를 입력하세요.'
            },
            'orderAddressSub': {
                required: '주소를 입력하세요.'
            },
            'receiverName': {
                required: '수령자명을 입력하세요.'
            },
            'receiverCellPhone': {
                required: '휴대폰번호를 입력하세요.'
            },
            'receiverAddress': {
                required: '주소를 입력하세요.'
            },
            'receiverAddressSub': {
                required: '주소를 입력하세요.'
            },
            'bankSender': {
                required: '입금자명을 입력하세요.'
            },
            'bankAccount': {
                required: '입금계좌를 입력하세요.'
            },
            'receiptFl': {
                required: '영수증 신청을 선택하세요.'
            },
            'cashCertNo[c]': {
                required: '현금영수증 휴대폰번호를 입력해 주세요.'
            },
            'cashCertNo[b]': {
                required: '현금영수증 사업자번호를 입력해 주세요.'
            },
            'taxBusiNo': {
                required: '사업자번호를 입력하세요.',
            },
            'taxCompany': {
                required: '회사명을 입력하세요.',
            },
            'taxCeoNm': {
                required: '대표자명을 입력하세요.',
            },
            'taxService': {
                required: '업태를 입력하세요.',
            },
            'taxItem': {
                required: '종목을 입력하세요.',
            },
            'taxEmail': {
                email: "발행 이메일을 정확하게 입력해주세요."
            },
            'taxAddress': {
                required: '사업장 주소를 입력하세요.',
            },
            'taxAddressSub': {
                required: '사업장 상세 주소를 입력하세요.',
            },
            'taxZonecode': {
                required: '사업장 주소를 입력하세요.',
            },
        }
    });

    // 영수증 관련 선택
    $('input[name="receiptFl"]').click(function(e){
        var useCode = {
            t: 'tax_info',
            r: 'cash_receipt_info'
        };
        var target = eval('useCode.' + $(this).val());

        $('.js-receipt').addClass('display-none');
        $('#' + target).removeClass('display-none');

        if ($(this).val() == 'r') {
            $('input[name="cashUseFl"]').eq(0).trigger('click');
        }
    });

    // 현금영수증 인증방법 선택 (소득공제용 - 휴대폰 번호(c), 지출증빙용 - 사업자번호(b))
    $('input[name="cashUseFl"]').click(function(e){
        var certCode = $(this).val();
        if (certCode == 'd') {
            $('input[name=\'cashCertFl\']').val('c');
            $('#certNo_hp').show();
            $('#certNo_bno').hide();
        } else {
            $('input[name=\'cashCertFl\']').val('b');
            $('#certNo_hp').hide();
            $('#certNo_bno').show();
        }
    });

    // 주문자 정보 동일 체크
    $('.js-order-same').click(function(e){
        if ($(this).is(':checked')) {
            $('input[name="receiverName"]').val($('input[name="orderName"]').val());
            $('input[name="receiverPhone"]').val($('input[name="orderPhone"]').val());
            $('input[name="receiverCellPhone"]').val($('input[name="orderCellPhone"]').val());
            $('input[name="receiverZipcode"]').val($('input[name="orderZipcode"]').val());
            $('input[name="receiverZonecode"]').val($('input[name="orderZonecode"]').val());
            $('input[name="receiverAddress"]').val($('input[name="orderAddress"]').val());
            $('input[name="receiverAddressSub"]').val($('input[name="orderAddressSub"]').val());
            if ($.trim($('input[name="orderZipcode"]').val()) !== '') {
                $('#receiverZipcodeText').show();
                $('#receiverZipcodeText').html('(' + $('input[name="orderZipcode"]').val() + ')');
            }
            else {
                $('#receiverZipcodeText').hide();
            }
        } else {
            $('input[name="receiverName"]').val('');
            $('input[name="receiverPhone"]').val('');
            $('input[name="receiverCellPhone"]').val('');
            $('input[name="receiverZipcode"]').val('');
            $('input[name="receiverZonecode"]').val('');
            $('input[name="receiverAddress"]').val('');
            $('input[name="receiverAddressSub"]').val('');
            $('#receiverZipcodeText').hide();
        }

        set_goods('n');
    });

    // 자주쓰는 주소 레이어 호출
    $('.js-address-layer').click(function(){
        $.get('./layer_frequency_address.php', function(data){
            BootstrapDialog.show({
                size: BootstrapDialog.SIZE_WIDE,
                title: '자주쓰는 주소',
                message: $(data)
            });
        });
    });

    // 회원 선택 레이어 호출
    $('#selfOrderWriteSelectMember').click(function(){
        layer_member_search_order_write();
    });

    //배송지 목록
    $('#selfOrderWriteDeliveryList').click(function(){
        var layerFormID = 'layerSelfOrderWriteShippingAddress';
        if(!$("input[name='memNo']").val() || $("input[name='memNo']").val() === '0'){
            alert("회원을 선택해 주세요.");
            return;
        }
        $.get('../share/layer_shipping_address.php?memNo=' + $('input[name="memNo"]').val() + '&layerFormID=' + layerFormID, function(data){
            data = '<div id="'+layerFormID+'">' + data + '</div>';
            var layerForm = data;

            BootstrapDialog.show({
                name: "layer_shipping_address",
                size: BootstrapDialog.SIZE_WIDE,
                title: '배송지 목록',
                message: $(layerForm),
                closable: true
            });
        });
    });

    // 상품삭제
    $('.js-goods-delete').click(function(e){
        if ($('input[name="cartSno[]"]:checked').length > 0) {
            var snoArr = [];
            $('input[name="cartSno[]"]:checked').each(function(idx){
                snoArr.push($(this).attr('data-sno'));
            });

            deleteGoodsList(snoArr);
        } else {
            alert('삭제하실 주문상품을 선택해주세요.');
            return false;
        }
    });

    //회원 장바구니 상품추가
    $('#selfOrderWriteMemberCart').click(function(){
        var memNo = $("input[name='memNo']").val();
        if(!memNo || memNo === '0'){
            alert("회원을 선택해 주세요.");
            return;
        }

        window.open('./popup_self_order_member_cart.php?memNo=' + memNo, 'popup_self_order_member_cart', 'width=1130, height=750, scrollbars=no');
    });

    $(document).on("click",".target-impossible-layer",function() {
        $(".nomal-layer").addClass('display-none');
        if ($(".nomal-layer").is(":hidden")) {
            $(this).next(".nomal-layer").removeClass('display-none');
        }
    });

    //수량 수정
    $(document).on("click",".js-goods-cnt-change",function() {
        thisObj = $(this);

        if(thisObj.attr('data-coupon') == 'use') {
            alert("쿠폰 적용 취소 후 옵션 변경 가능합니다.");
            return false;
        }

        var countCheckTarget = thisObj.closest('td').find("input[name='goodsCnt[]']");
        input_count_change(countCheckTarget, 'alert');

        var cartSno = thisObj.attr('data-sno');
        var goodsNo = thisObj.attr('data-goodsNo');
        var goodsCnt = thisObj.closest('td').find('input:text[name="goodsCnt[]"]').val();
        var addGoodsNo = thisObj.attr('data-addGoodsNo');
        var addGoodsCnt = thisObj.closest('td').find('input:text[name="addGoodsCnt[]"]').val();
        if (typeof goodsCnt == 'undefined') {
            goodsCnt = '';
        }
        if (typeof addGoodsNo == 'undefined') {
            addGoodsNo = '';
        }
        if (typeof addGoodsCnt == 'undefined') {
            addGoodsCnt = '';
        }

        var memNo = $('input[name="memNo"]').val();
        var parameter = {
            'mode': 'order_write_count_change',
            'memNo': memNo,
            'cartSno': cartSno,
            'goodsNo': goodsNo,
            'goodsCnt': goodsCnt,
            'addGoodsNo': addGoodsNo,
            'addGoodsCnt': addGoodsCnt
        };
        $.post('./order_ps.php', parameter, function () {
            set_goods('y');
        });
    });

    //마일리지 사용시
    $("#selfOrderUseMileage").blur(function(){
        // 마일리지 쿠폰 중복사용 체크
        var checkMileageCoupon = choose_mileage_coupon('mileage');
        if (!checkMileageCoupon) {
            return false;
        }

        if(!$("input[name='memNo']").val() || $("input[name='memNo']").val() === '0'){
            $("#selfOrderUseMileage").val(0);
            alert("회원을 선택해 주세요.");
            return;
        }
        if($("input[name='cartSno[]']").length < 1){
            $("#selfOrderUseMileage").val(0);
            alert("주문상품을 선택해 주세요.");
            return;
        }

        // 마일리지 작성한 금액이 있는지 체크
        if ($('input[name=\'useMileage\']').val() < 0 || $('input[name=\'useMileage\']').val().trim().length === 0) {
            checkMileageGiveExclude();

            return mileage_abort();
        }

        // 계산 후 실제 입력될 마일리지 금액
        var resetUseMileage = 0;

        // 현재 결제 금액
        var realSettlePrice = parseInt($("input[name='settlePrice']").val());

        // 구매자가 작성한 마일리지 금액
        var useMileage = parseInt($('input[name=\'useMileage\']').val());

        if(mileageUse['payUsableFl'] === 'l'){
            // 마일리지 리셋
            if(mileageUse['orderAbleLimit'] > 0){
                alert("상품 합계 금액이 "+mileageUse['orderAbleLimit']+" 이상인 경우에만 사용 가능합니다.'");
            }
            else {
                if(mileageUse['minimumHold'] > 0 && mileageUse['minimumLimit'] > 0){
                    alert(mileageUse['minimumHold'] + mileageInfo['unit'] + '이상 보유했을 때, ' + mileageInfo['name'] + ' 사용은 최소 ' + mileageUse['minimumLimit'] + mileageInfo['unit'] + ' 이상 사용 해야 가능합니다.' );
                }
                else if(mileageUse['minimumHold'] > 0 && mileageUse['minimumLimit'] == 0){
                    alert(mileageInfo['name'] + '사용은 ' + mileageUse['minimumHold'] + mileageInfo['unit'] + '이상 보유해야 가능합니다.');
                }
                else {
                    alert(mileageInfo['name'] + '사용은 ' + mileageUse['minimumHold'] + mileageInfo['unit'] + '이상 보유해야 가능합니다.');
                }
            }
        }
        else if(mileageUse['payUsableFl'] == 'y'){
            // 최대 사용 마일리지
            var maxMileage = parseInt(mileageUse['maximumLimit']);

            if(mileageUse['minimumLimit'] == mileageUse['maximumLimit'] && mileageUse['maximumLimit'] > 0){
                // 지정 마일리지 체크
                if (useMileage != maxMileage) {
                    alert(mileageInfo['name'] + ' 사용은 ' + mileageUse['minimumLimit'] + '만 가능합니다.');
                    resetUseMileage = maxMileage;
                }
            }
            else {
                // 회원 보유 마일리지
                var memMileage = parseInt(memberInfo['mileage']);

                if(useMileage > memMileage){
                    return mileage_abort(mileageInfo['name'] + "는 회원이 보유한 " + numeral(memMileage).format() + mileageInfo['unit'] + " 이상 사용할 수 없습니다.");
                }

                // 입력한 금액을 실 결제금액과 비교
                if (realSettlePrice < useMileage) {
                    return mileage_abort(mileageInfo['name'] + "사용은 결제금액 이상을 사용할 수 없습니다");
                }

                // 최소값 체크
                if(mileageUse['minimumLimit'] > 0 ){
                    var minMileage = parseInt(mileageUse['minimumLimit']);
                    if (useMileage < minMileage) {
                        if (realSettlePrice < minMileage) {
                            minMileage = '';
                        }
                        return mileage_abort(mileageInfo['name'] + "사용은 최소 "+mileageUse['minimumLimit']+mileageInfo['unit']+"입니다.", minMileage);
                    } else {
                        if (memMileage < useMileage) {
                            return mileage_abort(mileageInfo['name'] + "사용은 보유"+mileageInfo['name']+"인 "+mileageUse['minimumLimit']+mileageInfo['unit']+"를 초과해 사용하실 수 없습니다.", minMileage);
                        }
                    }
                }
                // 마일리지 사용의 배송비 제외 설정에 따른 배송비 체크
                if(mileageUse['useDeliveryFl'] == 'n' ) {
                    realSettlePrice = get_goodsSalesPrice(realSettlePrice); // 배송지는 제외 처리
                    if (realSettlePrice < useMileage) {
                        return mileage_abort(mileageInfo['name'] + "사용은 최대 " + numeral(realSettlePrice).format() + mileageInfo['unit'] + "입니다.", realSettlePrice);
                    }
                }
                //최대값 체크
                if(mileageUse['maximumLimit'] > 0 ){
                    if (maxMileage != memMileage) {
                        if (useMileage > maxMileage) {
                            if (realSettlePrice > maxMileage) {
                                maxMileage = '';
                            }
                            return mileage_abort(mileageInfo['name'] + "사용은 최대 " + mileageUse['maximumLimit'] + mileageInfo['unit'] + "입니다.", maxMileage);
                        } else {
                            if (maxMileage < useMileage) {
                                return mileage_abort(mileageInfo['name'] + "사용은 보유" + mileageInfo['name'] + "인 " + mileageUse['minimumLimit'] + mileageInfo['unit'] + "를 초과해 사용하실 수 없습니다.", minMileage);
                            }
                        }
                    }
                }
            }
        }

        displayBankArea();

        // 결제 금액 계산
        var cartPrice = set_recalculation();
        set_real_settle_price(cartPrice, 'y');

        checkMileageGiveExclude();
    });

    //마일리지 전액 사용하기
    $("#selfOrderUseMileageAll").click(function(){
        if($("input[name='memNo']").val() < 1){
            alert("회원을 선택해 주세요.");
            $(this).prop("checked", false);
            return;
        }
        if($("input[name='cartSno[]']").length < 1){
            alert("주문상품을 선택해 주세요.");
            $(this).prop("checked", false);
            return;
        }

        if(mileageUse.usableFl == 'n') {
            $("input[name='useMileage']").val(0);

            var cartPrice = set_recalculation();
            set_real_settle_price(cartPrice, 'y');

            checkMileageGiveExclude();
            return;
        }

        // 마일리지 쿠폰 중복사용 체크
        var checkMileageCoupon = choose_mileage_coupon('mileage');
        if (!checkMileageCoupon) {
            return false;
        }

        if($(this).prop("checked") === true){
            $('input[name=\'useMileage\']').val(0);
            set_real_settle_price([], 'n');

            // 현재 결제 금액
            var realSettlePrice = parseInt($("input[name='settlePrice']").val());
            var maxMileage = parseInt(mileageUse['maximumLimit']);
            var ownMileage = parseInt(memberInfo['mileage']);
            var checkMileage = ownMileage;

            if(realSettlePrice > ownMileage){
                if(maxMileage < ownMileage){
                    checkMileage = maxMileage;
                }
            }
            else if(realSettlePrice < ownMileage){
                checkMileage = realSettlePrice;
                if(maxMileage < checkMileage){
                    checkMileage = maxMileage;
                }
            }
            else { }

            $('input[name=\'useMileage\']').val(checkMileage);
            $("#selfOrderUseMileage").trigger('blur');
        }
        else {
            $('input[name=\'useMileage\']').val(0);
        }

        displayBankArea();

        var cartPrice = set_recalculation();
        set_real_settle_price(cartPrice, 'y');

        checkMileageGiveExclude();
    });

    //예치금 사용하기
    $("#selfOrderUseDeposit").blur(function(){
        if(!$("input[name='memNo']").val() || $("input[name='memNo']").val() === '0'){
            $("#selfOrderUseDeposit").val(0);
            alert("회원을 선택해 주세요.");
            return;
        }
        if($("input[name='cartSno[]']").length < 1){
            $("#selfOrderUseDeposit").val(0);
            alert("주문상품을 선택해 주세요.");
            return;
        }

        // 예치금 작성한 금액이 있는지 체크
        if ($('input[name=\'useDeposit\']').val() < 0) {
            return;
        }

        // 현재 결제 금액
        var realSettlePrice = parseInt($("input[name='settlePrice']").val());
        var memberDeposit = parseInt(memberInfo['deposit']);
        var ownDeposit = parseInt(memberInfo['deposit']);
        var checkDeposit = memberDeposit;

        if (realSettlePrice < memberDeposit) {
            checkDeposit = realSettlePrice;
        }
        if (realSettlePrice > ownDeposit) {
            checkDeposit = ownDeposit;
        }

        // 구매자가 작성한 예치금 금액
        var useDeposit = parseInt($('input[name=\'useDeposit\']').val());

        // 예치금 사용 제한 체크
        if (useDeposit > checkDeposit) {
            $('input[name=\'useDeposit\']').val(checkDeposit);
        }

        displayBankArea();

        // 결제 금액 계산
        set_real_settle_price([], 'n');
    });

    //예치금 전액 사용하기
    $("#selfOrderUseDepositAll").click(function(){
        if(!$("input[name='memNo']").val() || $("input[name='memNo']").val() === '0'){
            alert("회원을 선택해 주세요.");
            $(this).prop("checked", false);
            return;
        }
        if($("input[name='cartSno[]']").length < 1){
            alert("주문상품을 선택해 주세요.");
            $(this).prop("checked", false);
            return;
        }

        if($(this).prop("checked") === true){
            $('input[name=\'useDeposit\']').val(0);
            set_real_settle_price([], 'n');

            // 현재 결제 금액
            var realSettlePrice = parseInt($("input[name='settlePrice']").val());

            var memberDeposit = parseInt(memberInfo['deposit']);
            var checkDeposit = memberDeposit;

            if (realSettlePrice < memberDeposit) {
                checkDeposit = realSettlePrice;
            }

            $('input[name=\'useDeposit\']').val(checkDeposit);
        }
        else {
            $('input[name=\'useDeposit\']').val(0);
        }

        displayBankArea();

        set_real_settle_price([], 'n');
    });

    //옵션변경
    $(document).on("click",".js-goods-option-chnage",function() {
        if($(this).attr('data-coupon') == 'use') {
            alert("쿠폰 적용 취소 후 옵션 변경 가능합니다.");
            return false;
        }

        var params = {
            cartSno: $(this).attr('data-sno'),
            goodsNo : $(this).attr('data-goodsNo'),
            memNo : $("input[name='memNo']").val()
        };
        $.ajax({
            method: "POST",
            cache: false,
            url: "../order/layer_option.php",
            data: params,
            success: function (data) {
                data = '<div id="optionViewLayer">' + data + '</div>';
                var layerForm = data;
                BootstrapDialog.show({
                    name: "layer_option",
                    size: BootstrapDialog.SIZE_NORMAL,
                    title: '옵션선택',
                    message: $(layerForm),
                    closable: true
                });
            },
            error: function (data) {
                alert(data);
            }
        });
    });

    //상품쿠폰 적용
    $(document).on("click",".self-order-apply-coupon",function() {
        if(!$("input[name='memNo']").val() || $("input[name='memNo']").val() === '0'){
            alert("회원을 선택해 주세요.");
            return false;
        }
        // 마일리지 쿠폰 중복사용 체크
        var checkMileageCoupon = choose_mileage_coupon('coupon');
        if (!checkMileageCoupon) {
            return false;
        }

        var memberCartAddType = '';
        var memberCartAddTypeCouponNo = 0;
        var owMemberCartSnoData = [];
        var owMemberRealCartSnoData = [];
        var owMemberCartCouponNoData = [];
        owMemberCartSnoData = $.cookie('owMemberCartSnoData').split(",");
        owMemberRealCartSnoData = $.cookie('owMemberRealCartSnoData').split(",");
        owMemberCartCouponNoData = $.cookie('owMemberCartCouponNoData').split(",");

        //회원 장바구니 추가로 추가된 상품의 쿠폰변경시 memberCouponState 가 cart인 것도 보여줌
        if($.inArray($(this).attr('data-cartsno'), owMemberCartSnoData) !== -1){
            memberCartAddType = 'y';
            memberCartAddTypeCouponNo = owMemberCartCouponNoData[$.inArray($(this).attr('data-cartsno'), owMemberCartSnoData)];
        }

        var params = {
            mode: 'coupon_apply',
            cartSno: $(this).attr('data-cartsno'),
            memNo : $("input[name='memNo']").val(),
            memberCartAddType : memberCartAddType,
            memberCartAddTypeCouponNo : memberCartAddTypeCouponNo
        };
        $.ajax({
            method: "POST",
            cache: false,
            url: "../order/layer_coupon_apply.php",
            data: params,
            success: function (data) {
                data = '<div id="layerSelfOrderWriteCouponApplyGoods">' + data + '</div>';
                var layerForm = data;
                BootstrapDialog.show({
                    name: "layer_coupon_apply_goods",
                    size: BootstrapDialog.SIZE_WIDE,
                    title: '상품 쿠폰 적용',
                    message: $(layerForm),
                    closable: true
                });
            },
            error: function (data) {
                alert(data);
            }
        });
    });
    //상품쿠폰 취소
    $(document).on("click",".self-order-cancel-coupon",function() {
        var cartSno = $(this).attr('data-cartsno');
        var parameter = {
            'mode': 'order_write_goods_coupon_cancel',
            'cartSno': cartSno,
            'memNo' : $("input[name='memNo']").val()
        };
        $.post('./order_ps.php', parameter, function () {
            var cartSnoArr = [];
            cartSnoArr.push(cartSno);

            partResetMemberCartSnoCookie(cartSnoArr);

            parent.set_goods('y');
        });
    });

    // 사은품 체크 및 체크된 수량 출력
    $(document).on("click",".self-order-gift-table input[type=checkbox]",function() {
        if($(this).attr('onclick') !== 'return false;') {
            var selectCnt = $(this).closest('tr').find('.gift-select-cnt').val();
            var checkedCnt = $(this).closest('tr').find('input[type=checkbox]:checked').length;
            if (checkedCnt > selectCnt) {
                alert("사은품은 최대 " + selectCnt + "개만 선택하실 수 있습니다.");
                $(this).prop('checked', false);

                return false;
            }
            $(this).closest('.gift-choice').prev('.gift-condition').find('strong').text(checkedCnt);
        }
    });

    // 주문 쿠폰 적용/변경 레이어
    $('#selfOrderWriteCouponOrder').click(function(){
        if(!$("input[name='memNo']").val() || $("input[name='memNo']").val() === '0'){
            alert("회원을 선택해 주세요.");
            return false;
        }
        if($("input[name='cartSno[]']").length < 1){
            alert("주문상품을 선택해 주세요.");
            return false;
        }

        // 마일리지 쿠폰 중복사용 체크
        var checkMileageCoupon = choose_mileage_coupon('coupon');
        if (!checkMileageCoupon) {
            return false;
        }

        var cartIdx = [];
        $('input[name="cartSno[]"]').each(function(idx){
            cartIdx.push($(this).val());
        });
        var params = {
            mode: 'coupon_apply_order',
            cartSno: cartIdx,
            couponApplyOrderNo: $('input:hidden[name="couponApplyOrderNo"]').val(),
            memNo : $("input[name='memNo']").val()
        };
        $.ajax({
            method: "POST",
            cache: false,
            url: "../order/layer_coupon_apply_order.php",
            data: params,
            success: function (data) {
                data = '<div id="layerSelfOrderWriteCouponApplyOrder">' + data + '</div>';
                var layerForm = data;
                BootstrapDialog.show({
                    name: "layer_coupon_apply_order",
                    size: BootstrapDialog.SIZE_WIDE,
                    title: '주문 쿠폰 적용',
                    message: $(layerForm),
                    closable: true
                });
            },
            error: function (data) {
                alert(data);
            }
        });
    });

    //회원구분 선택에 따른 액션
    $('input[name="memberTypeFl"]').change(function(){
        var thisValue = $(this).val();
        if(checkOrderInfo() === 'y'){
            orderWriteDialogConfirm("회원구분을 변경 할 경우 입력된 주문자 및 수령자 정보가 초기화 됩니다.\n계속 진행하시겠습니까?", function (result) {
                if(result){
                    actionMemberTypeFl(thisValue);
                }
                else {
                    $('input[name="memberTypeFl"]').not(':checked').prop("checked", true);
                }
            });
        }
        else {
            actionMemberTypeFl(thisValue);
        }

        return;
    });

    select_email_domain('orderEmail');
    select_email_domain('taxEmail','taxEmailDomain');
});

function select_email_domain(name,select) {
    if (typeof select === 'undefined') {
        select = 'emailDomain';
    }
    var $email = $(':text[name=' + name + ']');
    var $emailDomain = $('select[id='+select+']');
    $emailDomain.on('change', function (e) {
        var emailValue = $email.val();
        var indexOf = emailValue.indexOf('@');
        if (indexOf == -1) {
            $email.val(emailValue + '@' + $emailDomain.val());
            $email.trigger('focusout');
        } else {
            if ($emailDomain.val() === 'self') {
                $email.val(emailValue.substring(0, indexOf + 1));
                $email.focus();
            } else {
                $email.val(emailValue.substring(0, indexOf + 1) + $emailDomain.val());
                $email.trigger('focusout');
            }
        }
    });
}

function actionMemberTypeFl(thisValue)
{
    //주문자, 수령자 정보
    resetOrderInfoCommon();

    //결제수단 및 정보 초기화
    resetOrderPayInfo();

    //회원 장바구니 추가의 기능으로 추가된 상품(쿠폰사용이 되어있는) 의 쿠키 삭제
    resetMemberCartSnoCookie();

    if(thisValue === 'y'){ //회원일때
        //회원ID 구역 노출
        $(".self-order-member-relation-area").removeClass("display-none");
        //회원선택 버튼 활성화
        $("#selfOrderWriteSelectMember").attr("disabled", false);
        //자주쓰는 주소 비활성화
        $("#selfOrderWriteRepeatAddress").attr("disabled", true);
        //배송지 목록 노출
        $("#selfOrderWriteDeliveryList").removeClass("display-none");
        //회원 장바구니 상품추가 노출
        $("#selfOrderWriteMemberCart").removeClass("display-none");
    }
    else { //비회원일때
        $.post('./order_ps.php', {'mode': 'order_write_change_target', 'memNo' : 0}, function (data) {

        });

        //회원ID 구역 숨김
        $(".self-order-member-relation-area").addClass("display-none");
        //회원선택 버튼 비활성화
        $("#selfOrderWriteSelectMember").attr("disabled", true);
        //자주쓰는 주소 활성화
        $("#selfOrderWriteRepeatAddress").attr("disabled", false);
        //배송지 목록 숨김
        $("#selfOrderWriteDeliveryList").addClass("display-none");
        //회원 장바구니 상품추가 숨김
        $("#selfOrderWriteMemberCart").addClass("display-none");

        resetMemberInfoCommon();
    }

    set_goods('y');
}

//결제수단 초기화
function resetOrderPayInfo()
{
    $("input[name='bankSender']").val('');
    $("#bankAccountSelector option:eq(0)").prop("selected", true);
    $("input[name='cashUseFl']").eq(0).trigger('click');
    $('input[name="receiptFl"]').eq(0).trigger('click');
    $("input[name='cashCertNo[c]']").val('');
    $("input[name='cashCertNo[b]']").val('');
    $("input[name='taxBusiNo']").val('');
    $("input[name='taxCompany']").val('');
    $("input[name='taxCeoNm']").val('');
    $("input[name='taxService']").val('');
    $("input[name='taxItem']").val('');
    $("input[name='taxZipcode']").val('');
    $("input[name='taxZonecode']").val('');
    $("input[id='taxrZipcodeText']").val('()');
    $("input[id='taxrZipcodeText']").addClass('display-none');
    $("input[name='taxAddress']").val('');
    $("input[name='taxAddressSub']").val('');
    $("input[name='taxEmail']").val('');
    $("#taxEmailDomain option:eq(0)").prop("selected", true);

}

// 주문시 마일리지 사용하는 경우 적립마일리지 지급 여부를 체크해 적립내역 숨김
function checkMileageGiveExclude(){
    var mileageValue = $('input[name=\'useMileage\']').val();
    if(isNaN(mileageValue)){
        mileageValue = 0;
    }

    if(mileageValue > 0 && mileageGiveExclude == 'n'){
        $(".mileage").addClass("display-none");
        $(".self-order-mileage-icon").addClass("display-none");
    }
    else {
        $(".mileage").removeClass("display-none");
        $(".self-order-mileage-icon").removeClass("display-none");
    }
}

// y면 경고창을 띄워주어야 한다.
function checkOrderInfo()
{
    var returnData = 'n';
    $.each($("input[type='text'][name^='order'], input[type='text'][name^='receiver']"), function () {
        if($(this).val()){
            returnData = 'y';
            return false;
        }
    });

    return returnData;
}

function resetOrderInfoCommon()
{
    //주문자 정보 초기화
    $("input[name='memNo']").val('');
    $('input[name="memId"]').val('');
    $('input[name="orderName"]').val('');
    $('input[name="orderZipcode"]').val('');
    $('input[name="orderZonecode"]').val('');
    $('input[name="orderAddress"]').val('');
    $('input[name="orderAddressSub"]').val('');
    $('input[name="orderPhone"]').val('');
    $('input[name="orderCellPhone"]').val('');
    $('#orderZipcodeText').hide();
    $('#orderZipcodeText').html('');
    $("input[name='orderEmail']").val('');

    //수령자정보 초기화
    $('input[name="receiverName"]').val('');
    $('input[name="receiverPhone"]').val('');
    $('input[name="receiverCellPhone"]').val('');
    $('input[name="receiverZipcode"]').val('');
    $('input[name="receiverZonecode"]').val('');
    $('input[name="receiverAddress"]').val('');
    $('input[name="receiverAddressSub"]').val('');
    $('#receiverZipcodeText').hide();
    $('#receiverZipcodeText').html('');
    $(".js-order-same").prop("checked", false);
}
function resetMemberInfoCommon()
{
    resetOrderDiscount(true);

    mileageUse = [];
    memberInfo = [];
}

//상품변경, 회원 변경으로 인해 주문쿠폰, 마일리지, 예치금이 다시 입력되어야 할때 리셋시켜줌
function resetOrderDiscount(memberMileageDepositReset)
{
    //마일리지 사용, 예치금 사용 초기화
    if(memberMileageDepositReset === true){
        $("#selfOrderHaveMileage").attr('data-mileagePrice', 0);
        $("#selfOrderHaveMileage").html(0);
        $("#selfOrderHaveDeposit").attr('data-depositPrice', 0);
        $("#selfOrderHaveDeposit").html(0);
    }
    $("input[name='useMileage']").val(0);
    $("input[name='useDeposit']").val(0);
    $("#selfOrderUseMileageAll").prop("checked", false);
    $("#selfOrderUseDepositAll").prop("checked", false);

    //주문쿠폰 초기화
    $("input[name='couponApplyOrderNo']").val('');
    $("input[name='totalCouponOrderDcPrice']").val('');
    $("input[name='totalCouponOrderPrice']").val('');
    $("input[name='totalCouponOrderMileage']").val('');
    $("input[name='totalCouponDeliveryDcPrice']").val('');
    $("input[name='totalCouponDeliveryPrice']").val('');
    $('#useDisplayCouponDcPrice').text(0);
    $('#useDisplayCouponMileage').text(0);
    $('#useDisplayCouponDelivery').text(0);
    $('.order-coupon-benefits').addClass('display-none');
}

function checkUseOrderDiscount()
{
    if($("input[name='useMileage']").val() > 0 || $("input[name='useDeposit']").val() > 0 || $.trim($("input[name='couponApplyOrderNo']").val()) !== ''){
        return true;
    }
    return false;
}

//주문건 memNo 변경, 수기주문 쿠폰 사용정보 초기화, 회원 정보 가져오기 (보유 마일리지, 보유 예치금 가져오기)
function set_member_info(memNo)
{
    if(memNo && memNo !== '0'){
        // 지역별 배송비 로직을 위해 주소 생성 후 장바구니 데이터 생성에 던진다.
        var address = $('input[name="receiverAddress"]').val() + $('input[name="receiverAddressSub"]').val();

        var parameter = {
            'mode': 'order_write_set_member_info',
            'memNo': memNo,
            'address' : address
        };
        $.post('./order_ps.php', parameter, function (data) {
            memberInfo = data.memberData;
            mileageUse = data.mileageUse;

            //보유한 마일리지 셋팅
            $("#selfOrderHaveMileage").attr('data-mileagePrice', data.memberData.mileage);
            $("#selfOrderHaveMileage").html(numeral(data.memberData.mileage).format());
            //보유한 예치금 셋팅
            $("#selfOrderHaveDeposit").attr('data-depositPrice', data.memberData.deposit);
            $("#selfOrderHaveDeposit").html(numeral(data.memberData.deposit).format());

            setReceiptInfo();

            set_goods('y');
        });
    }
    else {
        mileageUse = [];
        memberInfo = [];

        //보유한 마일리지 셋팅
        $("#selfOrderHaveMileage").attr('data-mileagePrice', 0);
        $("#selfOrderHaveMileage").html('0');
        //보유한 예치금 셋팅
        $("#selfOrderHaveDeposit").attr('data-depositPrice', 0);
        $("#selfOrderHaveDeposit").html('0');
    }

    //결제수단 및 정보 초기화
    resetOrderPayInfo();
}

function setReceiptInfo()
{
    if(memberInfo){
        //현금영수증정보
        if(memberInfo.cellPhone){
            $("input[name='cashCertNo[c]']").val(memberInfo.cellPhone.replace(/\-/g, ""));
        }
        if(memberInfo.busiNo){
            $("input[name='cashCertNo[b]']").val(memberInfo.busiNo.replace(/\-/g, ""));
        }
        //세금계산서정보
        if(memberInfo.busiNo){
            $("input[name='taxBusiNo']").val(memberInfo.busiNo.replace(/\-/g, ""));
        }
        if(memberInfo.company){
            $("input[name='taxCompany']").val(memberInfo.company);
        }
        if(memberInfo.ceo){
            $("input[name='taxCeoNm']").val(memberInfo.ceo);
        }
        if(memberInfo.service){
            $("input[name='taxService']").val(memberInfo.service);
        }
        if(memberInfo.item){
            $("input[name='taxItem']").val(memberInfo.item);
        }
        if(memberInfo.comZonecode){
            $("input[name='taxZonecode']").val(memberInfo.comZonecode);
        }
        if(memberInfo.comZipcode){
            $("input[name='taxZipcode']").val(memberInfo.comZipcode);
        }
        if(memberInfo.comAddress){
            $("input[name='taxAddress']").val(memberInfo.comAddress);
        }
        if(memberInfo.comAddressSub){
            $("input[name='taxAddressSub']").val(memberInfo.comAddressSub);
        }
    }
}

/**
 * 상품 선택
 *
 * @param string orderNo 주문 번호
 */
function goods_search_popup()
{
    var memNo = $('input[name="memNo"]').val();

    window.open('./popup_order_goods.php?memNo=' + memNo + '&loadPageType=orderWrite', 'popup_order_goods', 'width=1130, height=710, scrollbars=no');
}

/**
 * 회원 및 자주쓰는 주소의 데이터를 받아서 처리
 *
 */
function insert_address_info(data)
{
    if($.trim(data.memNo) !== ''){
        $('input[name="memNo"]').val(data.memNo);
    }
    if($.trim(data.memId) !== ''){
        $('input[name="memId"]').val(data.memId);
    }
    $('input[name="orderName"]').val(data.memNm);
    $('input[name="orderZipcode"]').val(data.zipcode);
    $('input[name="orderZonecode"]').val(data.zonecode);
    $('input[name="orderAddress"]').val(data.address);
    $('input[name="orderAddressSub"]').val(data.addressSub);
    $('input[name="orderPhone"]').val(data.phone);
    $('input[name="orderCellPhone"]').val(data.cellPhone);
    $('input[name="deliveryFree"]').val(data.deliveryFree);
    if (data.zipcode != '') {
        $('#orderZipcodeText').show();
        $('#orderZipcodeText').html('(' + data.zipcode + ')');
    } else {
        $('#orderZipcodeText').hide();
    }
    $("input[name='orderEmail']").val(data.email);

    layer_close();
}

//배송지 목록 적용
function adjust_receiver_delivery_info(jsonData)
{
    var responseData = $.parseJSON(jsonData);

    //수령자명
    $("input[name='receiverName']").val(responseData.shippingName);
    //전화번호
    $("input[name='receiverPhone']").val(responseData.shippingPhone);
    //휴대폰번호
    $("input[name='receiverCellPhone']").val(responseData.shippingCellPhone);
    //구역번호
    $("input[name='receiverZonecode']").val(responseData.shippingZonecode);
    //우편번호
    $("input[name='receiverZipcode']").val(responseData.shippingZipcode);
    if ($.trim(responseData.shippingZipcode) !== '') {
        $('#receiverZipcodeText').show();
        $('#receiverZipcodeText').html('(' + responseData.shippingZipcode + ')');
    } else {
        $('#receiverZipcodeText').hide();
    }
    //수령자명
    $("input[name='receiverAddress']").val(responseData.shippingAddress);
    //나머지주소
    $("input[name='receiverAddressSub']").val(responseData.shippingAddressSub);

    set_goods('n');
}

/**
 * 마일리지를 잘못 입력한 경우 처리
 */
function mileage_abort(message, useMileage)
{
    // 경고출력
    if (!_.isUndefined(message) && message !== null) {
        alert(message);
    }

    // 값 대입
    if (_.isUndefined(useMileage)) {
        $('input[name=\'useMileage\']').val('');
    } else {
        $('input[name=\'useMileage\']').val(useMileage);
    }

    // 결제 금액 계산
    var cartPrice = set_recalculation();
    set_real_settle_price(cartPrice, 'y');

    return false;
}

/**
 * 마일리지 쿠폰 중복사용 체크
 */
function choose_mileage_coupon(type) {
    if (type == undefined) {
        return false;
    }

    // 마일리지 쿠폰 중복사용 체크
    if ($('input[name=chooseMileageCoupon]').length > 0) {
        if ($('input[name=chooseMileageCoupon]').val() == 'y') {
            if (type == 'mileage') {
                var totalCouponGoodsDcPrice = $("#selfOrderCartPriceData").attr("data-totalCouponGoodsDcPrice");
                var totalCouponGoodsMileage = $("#selfOrderCartPriceData").attr("data-totalCouponGoodsMileage");

                // 마일리지 입력시 체크
                if (totalCouponGoodsDcPrice > 0 || totalCouponGoodsMileage > 0 || ($('input[name=couponApplyOrderNo]').val() != '' && $('input[name=couponApplyOrderNo]').length > 0)) {
                    alert('마일리지와 쿠폰은 동시에 사용하실 수 없습니다.');
                    $('input[name=useMileage]').val(0);
                    $("#useMileageAll").attr('checked', false);
                    return false;
                }
            } else {
                // 쿠폰사용 클릭시 체크
                if ($('input[name=useMileage]').val() != '' && $('input[name=useMileage]').val() != 0) {
                    alert('마일리지와 쿠폰은 동시에 사용하실 수 없습니다.');
                    return false;
                }
            }
        }
    }

    return true;
}

function deleteGoodsList(cartSnoArr)
{
    var memNo = $('input[name="memNo"]').val();
    $.post('./order_ps.php', {'mode': 'order_write_delete_goods','cartSno':cartSnoArr.join(int_division),'memNo':memNo }, function () {
        partResetMemberCartSnoCookie(cartSnoArr);

        set_goods('y');
    });
}

/**
 * 지역별 배송비 체크 (우편번호 팝업에서 콜백받는 함수)
 */
function postcode_callback() {
    set_goods('n');
}

/**
 * 최소수량 체크
 *
 * @param string keyNo 상품 배열 키값
 */
function input_count_change(inputName, type)
{
    if($(inputName).val()=='') {
        $(inputName).val('0');
    }

    var beforeCount = $(inputName).data('change-before-value');
    var nowCnt	= parseFloat($(inputName).val());

    var minCnt = parseInt($(inputName).data('min-order-cnt'));
    var maxCnt = parseInt($(inputName).data('max-order-cnt'));

    var salesUnit =  parseInt($(inputName).data('sales-unit'));

    var stockFl = $(inputName).data('stock-fl');
    var totalStock = parseInt($(inputName).data('total-stock'));
    if (((totalStock > 0 &&  maxCnt ==0) || (totalStock <= maxCnt)) && stockFl == 'y') {
        maxCnt = totalStock;
    }

    if (nowCnt < minCnt && minCnt != 0 && minCnt != '' && typeof minCnt != 'undefined') {
        if(type === 'return'){
            return '최소수량은 ' + minCnt + '이상입니다.';
        }
        alert('최소수량은 ' + minCnt + '이상입니다.');
        $(inputName).val(minCnt);
        return '';
    }

    if (nowCnt > maxCnt && maxCnt != 0 && maxCnt != '' && typeof maxCnt != 'undefined') {
        if(parseInt( maxCnt % salesUnit) > 0 ) {
            if(type === 'return'){
                return '최대 주문 가능 수량을 확인해주세요.';
            }
            alert("최대 주문 가능 수량을 확인해주세요.");
            $(inputName).val(salesUnit);
            return '';
        } else {
            if(type === 'return'){
                return '최대수량은 ' + maxCnt + '이하입니다.';
            }
            alert('최대수량은 ' + maxCnt + '이하입니다.');
            $(inputName).val(maxCnt);
            return '';
        }
    }


    var saleUnitCheck = false;
    if(Number(minCnt) <= Number(salesUnit)){
        if(Number(maxCnt) > 0){
            if(Number(maxCnt) >= Number(salesUnit)){
                saleUnitCheck = true;
            }
        }
        else {
            saleUnitCheck = true;
        }
    }

    if(saleUnitCheck === true){
        if(parseInt( nowCnt % salesUnit) > 0 ) {
            if(type === 'return'){
                return salesUnit+"개 단위로 묶음 주문 상품입니다.";
            }
            alert(salesUnit+"개 단위로 묶음 주문 상품입니다.");

            if(parseInt(beforeCount % salesUnit) == 0 ) {
                $(inputName).val(beforeCount);
            }
            else {
                $(inputName).val(salesUnit);
            }

            return '';
        }
    }

    return '';
}

function layer_member_search_order_write()
{
    var loadChk = $('div#layerSearchMember').length;

    //수기주문 등록 - 회원검색
    var requestParam = {
        keyword: '',
        key: 'all',
        mallSno : '1',
        loadPageType : 'order_write'
    };

    $.get('../share/layer_member_search.php', requestParam, function (data) {
        if (loadChk === 0) {
            data = '<div id="layerSearchMember">' + data + '</div>';
        }

        var dialog = BootstrapDialog.show({
            name: "layer_member_search",
            title: "회원검색",
            size: BootstrapDialog.SIZE_WIDE,
            message: $(data),
            closable: true
        });

        dialog.$modalBody.on('click', '.pagination a', function (e) {
            e.preventDefault();
            var $target = $(e.target);
            var page = $target.data('page');
            if (typeof page == 'undefined') {
                page = $target.closest('a').data('page');
            }
            var params = {
                key: $('select[name="key"] :selected', dialog.$modalBody).val(),
                keyword: $('input[name=\'keyword\']', dialog.$modalBody).val(),
                page: $.trim(page),
                mallSno : $('input[name=\'mallSno\']', dialog.$modalBody).val(),
                loadPageType : $('input[name=\'loadPageType\']', dialog.$modalBody).val()
            };
            $.get($('input[name=\'keyword\']', dialog.$modalBody).data('uri') + 'share/layer_member_search.php', params, function (data) {
                $('div#layer-wrap', dialog.$modalBody).html($(data).children());
            });
        }).on('keyup', '#keyword', function (e) {
            if (e.which == 13) {
                $('#btnMemberSearch').trigger('click');
            }
        }).on('click', '#btnMemberSearch', function () {
            var params = {
                key: $('select[name="key"] :selected', dialog.$modalBody).val(),
                keyword: $('input[name=\'keyword\']', dialog.$modalBody).val(),
                mallSno : $('input[name=\'mallSno\']', dialog.$modalBody).val(),
                loadPageType : $('input[name=\'loadPageType\']', dialog.$modalBody).val()
            };
            $.get($('input[name=\'keyword\']', dialog.$modalBody).data('uri') + 'share/layer_member_search.php', params, function (data) {
                $('div#layer-wrap', dialog.$modalBody).html($(data).children());
            });
        });
    });
}

/**
 * 동일 상품 배송비 구역 병합
 */
function set_delivery_area_combine()
{
    var preTrObj = '';
    $.each($("#add-goods-result tbody tr.self-order-goods-layout"), function () {
        if($(this).attr("data-goodsDeliveryFl") == 'y'){
            if(preTrObj){
                var preDeliveryAreaHtml = preTrObj.find(".self-order-write-delivery-area").html();
                var nowDeliveryAreaHtml = $(this).find(".self-order-write-delivery-area").html();
                if($.trim(preDeliveryAreaHtml) == $.trim(nowDeliveryAreaHtml)){
                    var preLastTdObj = preTrObj.find('td').last();
                    var newRowspan = parseInt(preLastTdObj.attr('rowspan')) + parseInt($(this).find(".self-order-write-delivery-area").attr('rowspan'));
                    preLastTdObj.attr('rowspan', newRowspan);
                    $(this).find('td').last().remove();
                }
                else {
                    preTrObj = $(this);
                }
            }
            else {
                preTrObj = $(this);
            }
        }
        else {
            preTrObj = '';
        }
    });
}

function currencyDisplayOrderWrite(currency)
{
    return currencySymbol + numeral(currency).format() + currencyString;
}

function setMemberCartSnoCookie(owMemberCartSnoData, owMemberRealCartSnoData, owMemberCartCouponNoData)
{
    var newOwMemberCartSnoData = [];
    var newOwMemberRealCartSnoData = [];
    var newOwMemberCartCouponNoData = [];
    var ori_owMemberCartSnoData = $.cookie('owMemberCartSnoData').split(",");
    var ori_owMemberRealCartSnoData = $.cookie('owMemberRealCartSnoData').split(",");
    var ori_owMemberCartCouponNoData = $.cookie('owMemberCartCouponNoData').split(",");

    if(ori_owMemberCartSnoData.length > 0){
        $.each(owMemberCartSnoData, function(key, value){
            if($.inArray(value, ori_owMemberCartSnoData) === -1) {
                newOwMemberCartSnoData.push(value);
            }
        });
        $.cookie('owMemberCartSnoData', newOwMemberCartSnoData);
    }
    else {
        $.cookie('owMemberCartSnoData', owMemberCartSnoData);
    }

    if(ori_owMemberRealCartSnoData.length > 0){
        $.each(owMemberRealCartSnoData, function(key, value){
            if($.inArray(value, ori_owMemberRealCartSnoData) === -1) {
                newOwMemberRealCartSnoData.push(value);
            }
        });
        $.cookie('owMemberRealCartSnoData', newOwMemberRealCartSnoData);
    }
    else {
        $.cookie('owMemberRealCartSnoData', owMemberRealCartSnoData);
    }

    if(ori_owMemberCartCouponNoData.length > 0){
        $.each(owMemberCartCouponNoData, function(key, value){
            if($.inArray(value, ori_owMemberCartCouponNoData) === -1) {
                newOwMemberCartCouponNoData.push(value);
            }
        });
        $.cookie('owMemberCartCouponNoData', newOwMemberCartCouponNoData);
    }
    else {
        $.cookie('owMemberCartCouponNoData', owMemberCartCouponNoData);
    }
}

function partResetMemberCartSnoCookie(cartSnoArr)
{
    var ori_owMemberCartSnoData = $.cookie('owMemberCartSnoData').split(",");
    var ori_owMemberRealCartSnoData = $.cookie('owMemberRealCartSnoData').split(",");
    var ori_owMemberCartCouponNoData = $.cookie('owMemberCartCouponNoData').split(",");

    if(ori_owMemberCartSnoData.length > 0) {
        $.each(cartSnoArr, function(key, cartSno){
            var idx = $.inArray(cartSno, ori_owMemberCartSnoData);
            if(idx !== -1) {
                ori_owMemberCartSnoData.splice(idx, 1);
                ori_owMemberRealCartSnoData.splice(idx, 1);
                ori_owMemberCartCouponNoData.splice(idx, 1);
            }
        });
    }

    $.cookie('owMemberCartSnoData', ori_owMemberCartSnoData);
    $.cookie('owMemberRealCartSnoData', ori_owMemberRealCartSnoData);
    $.cookie('owMemberCartCouponNoData', ori_owMemberCartCouponNoData);
}

function resetMemberCartSnoCookie()
{
    $.cookie('owMemberCartSnoData', null);
    $.cookie('owMemberRealCartSnoData', null);
    $.cookie('owMemberCartCouponNoData', null);
}

function updateMemberCartSnoCookie(cartSno, memberCouponNo)
{
    var memberCouponNoArr = [];
    if(memberCouponNo){
        memberCouponNoArr = memberCouponNo.split(int_division);
    }
    var ori_owMemberCartSnoData = $.cookie('owMemberCartSnoData').split(",");
    var ori_owMemberCartCouponNoData = $.cookie('owMemberCartCouponNoData').split(",");

    var cartSnoIdx = $.inArray(cartSno, ori_owMemberCartSnoData);
    if(cartSnoIdx !== -1) {
        var cookieCartCouponArray = ori_owMemberCartCouponNoData[cartSnoIdx].split(int_division);
        var newCookieCartCouponArray = cookieCartCouponArray.slice();
        //사용처리 되지 않은 쿠폰번호를 삭제
        $.each(cookieCartCouponArray, function(key, couponNo){
            var idx = $.inArray(couponNo, memberCouponNoArr);
            if(idx === -1) {
                var deleteindex = $.inArray(couponNo, newCookieCartCouponArray);
                if(deleteindex !== -1) {
                    newCookieCartCouponArray.splice(deleteindex, 1);
                }
            }
        });

        ori_owMemberCartCouponNoData[cartSnoIdx] = newCookieCartCouponArray.join(int_division);

        $.cookie('owMemberCartCouponNoData', ori_owMemberCartCouponNoData);
    }
}

function checkDisplayBankArea()
{
    var useMileage = Number($('input[name=\'useMileage\']').val());
    var useDeposit = Number($('input[name=\'useDeposit\']').val());
    var useOrderCouponDc = parseFloat($('input[name="totalCouponOrderDcPrice"]').val());
    var useDeliveryCouponDc = parseFloat($('input[name="totalCouponDeliveryDcPrice"]').val());
    var totalSumMemberDcPrice = $("#selfOrderCartPriceData").attr("data-totalSumMemberDcPrice");
    var totalSettlePrice = $("#selfOrderCartPriceData").attr("data-totalSettlePrice");
    if(isNaN(useOrderCouponDc)){
        useOrderCouponDc = 0;
    }
    if(isNaN(useDeliveryCouponDc)){
        useDeliveryCouponDc = 0;
    }
    if(isNaN(totalSumMemberDcPrice)){
        totalSumMemberDcPrice = 0;
    }
    if(isNaN(totalSettlePrice)){
        totalSettlePrice = 0;
    }
    if(couponConf){
        if (couponConf.chooseCouponMemberUseType == 'coupon' && $('input[name="couponApplyOrderNo"]').val() != '') {
            if (totalSumMemberDcPrice > 0) {
                totalSettlePrice = parseFloat(totalSettlePrice) + parseFloat(totalSumMemberDcPrice);
            }
        }
    }

    var totalUseMileageDepositPrice = useMileage + useDeposit + useOrderCouponDc + useDeliveryCouponDc;

    if($('input[name="cartSno[]"]').length < 1){
        return true;
    }

    if(Number(totalSettlePrice) == 0){
        return false;
    }
    else {
        if(Number(totalUseMileageDepositPrice) == Number(totalSettlePrice)){
            return false;
        }
        else {
            return true;
        }
    }
}

function displayBankArea()
{
    var displayFl = true;
    if(settleKindBankUseFl != 'y'){
        //전체결제수단설정에서 무통장입금이 사용중이 아닐때
        displayFl = false;
    }
    else {
        //전체결제수단설정에서 무통장입금이 사용중일때

        //회원이면 회원등급 결제수단도 체크
        if($("input[name='memNo']").val() && $("input[name='memNo']").val() !== '0') {
            if(memberInfo.settleGb === 'nobank'){
                displayFl = false;
            }
        }

        if(displayFl === true){
            if(payLimitData){
                if(payLimitData.orderBankAble != 'y'){
                    //상품 개별결제수단에서 무통장입금을 사용하지 못할때
                    displayFl = false;
                }
                else {
                    displayFl = checkDisplayBankArea();
                }
            }
            else {
                displayFl = checkDisplayBankArea();
            }
        }
    }

    if(displayFl === true){
        $(".self-order-bank-area").removeClass("display-none");
    }
    else {
        $(".self-order-bank-area").addClass("display-none");

    }
}

function orderWriteDialogConfirm(message, callback) {
    var onhiddenAction = true;
    BootstrapDialog.show({
        title: '확인',
        message: message,
        buttons: [{
            label: "취소",
            hotkey: 32,
            size: BootstrapDialog.SIZE_LARGE,
            action: function (dialog) {
                onhiddenAction = false;
                if (typeof callback == 'function') {
                    callback(false);
                }
                dialog.close();
            }
        }, {
            label: "확인",
            cssClass: 'btn-white',
            size: BootstrapDialog.SIZE_LARGE,
            action: function (dialog) {
                onhiddenAction = false;
                if (typeof callback == 'function') {
                    callback(true);
                }
                dialog.close();
            }
        }
        ],
        onhide: function(){
            if(onhiddenAction === true){
                callback(false);
            }
        }
    });
}

/**
 * 선택 상품 세팅
 *
 */
function set_goods(giftAddFieldRefreshFl)
{
    // 지역별 배송비 로직을 위해 주소 생성 후 장바구니 데이터 생성에 던진다.
    var address = $('input[name="receiverAddress"]').val() + $('input[name="receiverAddressSub"]').val();
    var memNo = $('input[name="memNo"]').val();

    $.post('./order_ps.php', {'mode': 'order_write_search_goods', 'address': address, 'memNo': memNo }, function (frmData) {

        $.cookie('owMemberCartSnoData', frmData.cookieData.owMemberCartSnoData);
        $.cookie('owMemberRealCartSnoData', frmData.cookieData.owMemberRealCartSnoData);
        $.cookie('owMemberCartCouponNoData', frmData.cookieData.owMemberCartCouponNoData);

        if(checkUseOrderDiscount() === true){
            resetOrderDiscount(false);
        }
        mileageUse = frmData.mileageUse;
        giftInfo = frmData.giftInfo;
        giftConf = frmData.giftConf;
        couponConf = frmData.couponConfig;
        addFieldInfo = frmData.addFieldInfo;
        payLimitData = frmData.payLimitData;
        orderPossible = frmData.orderPossible;
        couponUse = frmData.couponUse;
        mileageGiveExclude = frmData.mileageGiveExclude;

        var goodHtml = "";

        //마일리지, 쿠폰 동시사용 여부
        $("input[name='chooseMileageCoupon']").val(frmData.chooseMileageCoupon);

        if(frmData.cartPrice.totalSettlePrice === 0 || frmData.cartPrice.totalSettlePrice > 0) {
            $.each(frmData.cartInfo, function (key, val) {
                $.each(val, function (key1, val1) {
                    $.each(val1, function (key2, val2) {
                        var dataCouponButton = '';
                        var dataGoodsCount = '';
                        var dataTotalDcContent = '';
                        var dataTotalSaveContent = '';

                        var tmp = $(goodHtml).clone(),
                            dataIndex = tmp.find("input[name='cartSno[]']").length,
                            goodsNm = val2.goodsNm;

                        //쿠폰버튼
                        if($('input[name="memNo"]').val() > 0){
                            if(frmData.couponUse === 'y' && frmData.couponConfig.chooseCouponMemberUseType !== 'member' && val2.couponBenefitExcept === 'n'){
                                if(parseInt(val2.memberCouponNo) > 0){
                                    dataCouponButton = '<div><img class="self-order-cancel-coupon" src="/admin/gd_share/img/self_order_member_cart/coupon-cancel.png" data-cartsno="'+val2.sno+'" alt="쿠폰취소" style="cursor:pointer" /> <a href="javascript:;" class="self-order-apply-coupon" data-cartsno="'+val2.sno+'" ><img src="/admin/gd_share/img/self_order_member_cart/coupon-change.png" alt="쿠폰변경" /></a></div>';
                                }
                                else {
                                    dataCouponButton = '<div><a href="javascript:;" class="self-order-apply-coupon" data-cartsno="'+val2.sno+'"><img src="/admin/gd_share/img/self_order_member_cart/coupon-apply.png" alt="쿠폰적용"/></a></div>';
                                }
                            }
                        }

                        //수량
                        var addDataGoodsAttribute = "";
                        addDataGoodsAttribute += " data-stock-fl='"+val2.stockFl+"' ";
                        if(val2.optionFl == 'y'){
                            addDataGoodsAttribute += " data-total-stock='"+val2.stockCnt+"' ";
                        }
                        else {
                            addDataGoodsAttribute += " data-total-stock='"+val2.totalStock+"' ";
                        }
                        addDataGoodsAttribute += " data-min-order-cnt='"+val2.minOrderCnt+"' ";
                        addDataGoodsAttribute += " data-max-order-cnt='"+val2.maxOrderCnt+"' ";
                        addDataGoodsAttribute += " data-sales-unit='"+val2.salesUnit+"' ";
                        addDataGoodsAttribute += " data-change-before-value='"+val2.goodsCnt+"' ";
                        addDataGoodsAttribute += " data-goodsNm='"+val2.goodsNm+"' ";
                        dataGoodsCount  = '<input type="text" name="goodsCnt[]" class="js-number" style="height: 23px;" value="'+val2.goodsCnt+'" title="수량" class="text" size="3" '+addDataGoodsAttribute+'/>';
                        //할인
                        if(val2.price.goodsDcPrice + val2.price.memberDcPrice + val2.price.memberOverlapDcPrice + val2.price.couponGoodsDcPrice > 0){
                            dataTotalDcContent += '<dl class="sale"><dt>할인</dt>';
                            if(val2.price.goodsDcPrice > 0){
                                dataTotalDcContent += '<dd>상품 <strong>-'+currencyDisplayOrderWrite(val2.price.goodsDcPrice)+'</strong></dd>';
                            }
                            if((val2.price.memberDcPrice+val2.price.memberOverlapDcPrice) > 0){
                                dataTotalDcContent += '<dd>회원 <strong>-'+currencyDisplayOrderWrite((val2.price.memberDcPrice+val2.price.memberOverlapDcPrice))+'</strong></dd>';
                            }
                            if(val2.price.couponGoodsDcPrice > 0){
                                dataTotalDcContent += '<dd>쿠폰 <strong>-'+currencyDisplayOrderWrite(val2.price.couponGoodsDcPrice)+'</strong></dd>';
                            }
                            dataTotalDcContent += '</dl>';
                        }
                        //적립
                        if($("input[name='memNo']").val() && $("input[name='memNo']").val() !== '0') {
                            if (frmData.mileage.useFl === 'y' && (val2.mileage.goodsMileage + val2.mileage.memberMileage + val2.mileage.couponGoodsMileage) > 0) {
                                dataTotalSaveContent += '<dl class="mileage"><dt>적립</dt>';
                                if (val2.mileage.goodsMileage > 0) {
                                    dataTotalSaveContent += '<dd>상품 <strong>+' + numeral(val2.mileage.goodsMileage).format() + '' + frmData.mileage.unit + '</strong></dd>';
                                }
                                if (val2.mileage.memberMileage > 0) {
                                    dataTotalSaveContent += '<dd>회원 <strong>+' + numeral(val2.mileage.memberMileage).format() + '' + frmData.mileage.unit + '</strong></dd>';
                                }
                                if (val2.mileage.couponGoodsMileage > 0) {
                                    dataTotalSaveContent += '<dd>쿠폰 <strong>+' + numeral(val2.mileage.couponGoodsMileage).format() + '' + frmData.mileage.unit + '</strong></dd>';
                                }
                                dataTotalSaveContent += '</dl>';
                            }
                        }

                        var dataCouponUseFl = '';
                        if(parseInt(val2.memberCouponNo) > 0){
                            dataCouponUseFl = 'use';
                        }

                        //상품 결제수단 설정 - 개별설정 아이콘 노출
                        if(val2.payLimitFl == 'y' && val2.payLimit.length > 0){
                            goodsNm += "<div>";
                            $.each(val2.payLimit, function (paylimitKey, paylimitValue) {
                                goodsNm += "<img src='/admin/gd_share/img/self_order_member_cart/settle-kind-"+paylimitValue+".png' style='margin-right: 3px;'/>";
                            });
                            goodsNm += "</div>";
                        }

                        //구매 이용 조건 안내
                        var dataOrderPossibleMessageList = '';
                        if(val2.orderPossibleMessageList.length > 0){
                            dataOrderPossibleMessageList += "<div>";
                            dataOrderPossibleMessageList += "<strong class='caution-msg1 pos-r'>구매 이용 조건 안내";
                            dataOrderPossibleMessageList += "<a class='normal-btn small1 target-impossible-layer'><em>전체보기<img class='arrow' src='/admin/gd_share/img/self_order_member_cart/bl_arrow.png' alt='' /></em></a>";
                            dataOrderPossibleMessageList += "<div class='nomal-layer display-none'>";
                            dataOrderPossibleMessageList += "<div class='wrap'><strong>결제 제한 조건 사유</strong>";
                            dataOrderPossibleMessageList += "<div class='list'>";
                            dataOrderPossibleMessageList += "<table cellspacing='0'>";
                            $.each(val2.orderPossibleMessageList, function (messagekey, messageValue) {
                                dataOrderPossibleMessageList += "<tr><td class='strong'>"+messageValue+"</td></tr>";
                            });

                            dataOrderPossibleMessageList += "</table>";
                            dataOrderPossibleMessageList += "</div>";
                            dataOrderPossibleMessageList += "<button type='button' class='close target-impossible-layer' title='닫기'>닫기</button>";
                            dataOrderPossibleMessageList += "</div>";
                            dataOrderPossibleMessageList += "</div>";
                            dataOrderPossibleMessageList += "</strong>";
                            dataOrderPossibleMessageList += "</div>";
                        }

                        if(val2.option.length > 0) {
                            $.each(val2.option, function (optKey, optVal) {
                                goodsNm += "<div class='self-order-write-option-area'>"+optVal.optionName+":"+optVal.optionValue;
                                if(optVal.optionPrice > 0){
                                    goodsNm += "(+" +currencyDisplayOrderWrite(optVal.optionPrice)+")";
                                }
                                goodsNm += "</div>";
                            });
                        }

                        if(val2.optionText.length > 0) {
                            var optionTextInfo = [];
                            $.each(val2.optionText, function (optTextKey, optTextVal) {
                                goodsNm += "<div class='self-order-write-option-area'>"+optTextVal.optionName+":"+optTextVal.optionValue;
                                if(optTextVal.optionTextPrice > 0){
                                    goodsNm += "(+" +currencyDisplayOrderWrite(optTextVal.optionTextPrice)+")";
                                }
                                goodsNm += "</div>";
                            });
                        }

                        if(val2.option.length > 0 || val2.optionText.length > 0){
                            var dataOptionChangeButton = "<div class='self-order-option-change-btn-area'><input type='button' data-goodsNo='"+val2.goodsNo+"' data-sno='"+val2.sno+"' data-coupon='"+dataCouponUseFl+"' value='옵션변경' class='btn btn-sm btn-white js-goods-option-chnage'></div>";
                        }


                        var memberDcPrice = val2.price.memberDcPrice+val2.price.memberOverlapDcPrice;

                        if($.trim(val2.goodsPriceString) !== ''){
                            var goodsPrice = val2.goodsPriceString;
                        }
                        else {
                            var goodsPrice = currencyDisplayOrderWrite(val2.price.goodsPriceSum + val2.price.optionPriceSum + val2.price.optionTextPriceSum);
                        }

                        var deliveryText = "";
                        if(val2.goodsDeliveryFl =='y') {
                            deliveryText += frmData.setDeliveryInfo[key1]['goodsDeliveryMethod'] + '<br>';
                            if(frmData.setDeliveryInfo[key1]['fixFl'] =='free') {
                                deliveryText += "무료배송";
                            } else {
                                if(frmData.setDeliveryInfo[key1]['goodsDeliveryWholeFreeFl'] == 'y' ) {
                                    deliveryText += "조건에 따른 배송비 무료";
                                    if(frmData.setDeliveryInfo[key1]['goodsDeliveryWholeFreePrice']) {
                                        deliveryText += currencyDisplayOrderWrite(frmData.setDeliveryInfo[key1]['goodsDeliveryWholeFreePrice']);
                                    }
                                } else {
                                    if(frmData.setDeliveryInfo[key1]['goodsDeliveryCollectFl'] === 'later' ) {
                                        if(frmData.setDeliveryInfo[key1]['goodsDeliveryCollectPrice']) {
                                            deliveryText += currencyDisplayOrderWrite(frmData.setDeliveryInfo[key1]['goodsDeliveryCollectPrice'])+"<br/>(상품수령 시 결제)";
                                        }
                                    } else {
                                        if(frmData.setDeliveryInfo[key1]['goodsDeliveryPrice']) {
                                            deliveryText += currencyDisplayOrderWrite(frmData.setDeliveryInfo[key1]['goodsDeliveryPrice']);

                                        } else{
                                            deliveryText += "무료배송";
                                        }
                                    }
                                }
                            }
                        } else {
                            deliveryText +=val2.goodsDeliveryMethod + '<br>';
                            if(val2.goodsDeliveryFixFl == 'free') {
                                deliveryText += "무료배송";
                            } else {
                                if(val2.goodsDeliveryWholeFreeFl === 'y') {
                                    deliveryText += "조건에 따른 배송비 무료";
                                    if(val2.price['goodsDeliveryWholeFreePrice']) {
                                        deliveryText += currencyDisplayOrderWrite(val2.price['goodsDeliveryWholeFreePrice']);
                                    }
                                } else {
                                    if(val2.goodsDeliveryCollectFl === 'later' ) {
                                        if(val2.price['goodsDeliveryCollectPrice']) {
                                            deliveryText +=  currencyDisplayOrderWrite(val2.price['goodsDeliveryCollectPrice'])+"<br>(상품수령 시 결제)";
                                        }
                                    } else {
                                        if(val2.price['goodsDeliveryPrice']) {
                                            deliveryText += currencyDisplayOrderWrite(val2.price['goodsDeliveryPrice']);
                                        } else {
                                            deliveryText += "무료배송";
                                        }
                                    }
                                }
                            }
                        }

                        if(val2.timeSaleFl) {
                            goodsNm = "<img src='/admin/gd_share/img/time-sale.png' alt='타임세일' /> "+goodsNm;
                        }

                        var complied = _.template($('#goodsTemplate').html());
                        goodHtml += complied({
                            cartSno : val2.sno,
                            dataIndex : dataIndex,
                            dataRowCount: 1+val2.addGoods.length,
                            dataScmNm: frmData.cartScmInfo[key]['companyNm'],
                            dataGoodsImage:val2.goodsImage,
                            dataGoodsNm: goodsNm,
                            dataCouponButton : dataCouponButton,
                            dataOptionChangeButton : dataOptionChangeButton,
                            dataGoodsCount: dataGoodsCount,
                            dataGoodsPrice: goodsPrice,
                            dataTotalDcContent: dataTotalDcContent,
                            dataTotalSaveContent : dataTotalSaveContent,
                            dataMemberDcPrice : currencyDisplayOrderWrite(memberDcPrice),
                            dataSettlePrice: currencyDisplayOrderWrite(val2.price.goodsPriceSubtotal),
                            dataDelivery : deliveryText,
                            dataGoodsNo : val2.goodsNo,
                            dataCouponUse : dataCouponUseFl,
                            dataOrderPossibleMessageList : dataOrderPossibleMessageList,
                            dataGoodsDeliveryFl : val2.goodsDeliveryFl
                        });

                        if(val2.addGoods.length > 0 ) {
                            $.each(val2.addGoods, function (agKey, agVal) {
                                if(agVal.optionNm){
                                    var dataAddGoodsInfo = agVal.addGoodsNm+" : "+agVal.optionNm;
                                }
                                else {
                                    var dataAddGoodsInfo = agVal.addGoodsNm;
                                }

                                var complied = _.template($('#addGoodsTemplate').html());
                                goodHtml += complied({
                                    dataIndex : dataIndex,
                                    dataAddGoodsImage : agVal.addGoodsImage,
                                    dataAddGoodsInfo: dataAddGoodsInfo,
                                    dataAddGoodsCount: '<input type="text" name="addGoodsCnt[]" style="height: 23px;" value="'+agVal.addGoodsCnt+'" title="수량" class="text" size="3" sno="'+agVal.sno+'" data-stock-fl="'+agVal.stockUseFl+'" data-total-stock="'+agVal.stockCnt+'" data-min-order-cnt="1" data-max-order-cnt="0" data-sales-unit="1" onchange="input_count_change(this);return false;" />',
                                    dataAddGoodsPrice: currencyDisplayOrderWrite(agVal.addGoodsPrice*agVal.addGoodsCnt),
                                    dataAddGoodsNo : agVal.addGoodsNo,
                                    cartSno : val2.sno,
                                    dataGoodsNo : val2.goodsNo,
                                    dataCouponUse : dataCouponUseFl
                                });
                            });
                        }
                    });
                });
            });
            $("#add-goods-result tbody").html(goodHtml);
        } else {
            $("#add-goods-result tbody").html('<td colspan="10" class="no-data">주문 할 상품을 추가해주세요.</td>');
        }

        /* 마일리지 사용에 관한 text 문구 */
        var mileageText = '';
        if(mileageUse.usableFl == 'n'){
            $("input[name='useMileage']").val(0);
            $("input[name='useMileage']").attr("disabled", "disabled");

            if(mileageUse.orderAbleLimit > 0){
                mileageText = "상품 합계 금액이 " + numeral(mileageUse.orderAbleLimit).format() + " 이상인 경우에만 사용 가능합니다.";
            }
            else {
                if(mileageUse.minimumHold > 0 && mileageUse.minimumLimit > 0){
                    mileageText = numeral(mileageUse.minimumHold).format() + mileageInfo.unit + "이상 보유했을 때, 최소 사용 조건이 " + mileageUse.minimumLimit + mileageInfo.unit + "이상 사용해야 합니다.";
                }
                else if(mileageUse.minimumHold > 0 && mileageUse.minimumLimit == 0){
                    mileageText = numeral(mileageUse.minimumHold).format() + mileageInfo.unit + "이상 보유해야 사용이 가능합니다.";
                }
                else {
                    mileageText = numeral(mileageUse.minimumLimit).format() + mileageInfo.unit + "이상 보유해야 사용이 가능합니다.";
                }
            }
        }
        else {
            $("input[name='useMileage']").removeAttr("disabled");

            if(mileageUse.minimumLimit == mileageUse.maximumLimit && mileageUse.maximumLimit > 0 ){
                mileageText = numeral(mileageUse.minimumLimit).format() + mileageInfo.unit + "만 사용 가능합니다.";
            }
            else {
                if(mileageUse.minimumLimit > 0 ){
                    mileageText = numeral(mileageUse.minimumLimit).format() + mileageInfo.unit + "부터";
                }
                if(mileageUse.maximumLimit > 0 ) {
                    mileageText += numeral(mileageUse.maximumLimit).format() + mileageInfo.unit + "까지";
                }
                else {
                    if(memberInfo){
                        mileageText += numeral(memberInfo.mileage).format() + mileageInfo.unit + "까지";
                    }
                }
                mileageText += " 사용 가능합니다.";
            }
            mileage_disable_check();
        }
        $("#selfOrderWriteMileageText").html(mileageText);
        /* 마일리지 사용에 관한 text 문구 */

        //giftAddFieldRefreshFl 값이 y 일때만 실시간 반영처리한다.
        if(giftAddFieldRefreshFl == 'y') {
            /* 사은품 */
            $("#selfOrderGiftArea").addClass("display-none");
            $("#selfOrderGiftArea>div>table>tbody").empty();
            if (frmData.giftInfo) {
                if (frmData.giftConf.giftFl === 'y' && Object.keys(frmData.giftInfo).length > 0) {
                    $("#selfOrderGiftArea>div>table>tbody").empty();
                    $("#selfOrderGiftArea").removeClass("display-none");
                    $.each(frmData.giftInfo, function (key, value) {
                        var giftContentsHtml = '';
                        var dataGiftSelectCnt = '';
                        var dataGiftTotal = '';
                        $.each(value.gift, function (key2, value2) {
                            if (value2.selectCnt == 0) {
                                dataGiftSelectCnt = value.total;
                                dataGiftTotal = value.total;
                                var dataGiftCheckboxReadonly = "checked='checked' onclick='return false;'";
                            }
                            else {
                                dataGiftSelectCnt = 0;
                                dataGiftTotal = value2.selectCnt;
                                var dataGiftCheckboxReadonly = '';
                            }

                            if (value2.total > 0) {
                                for (var i = 0; i < Object.keys(value2.multiGiftNo).length; i++) {
                                    if (value2.multiGiftNo[i]) {
                                        var complied = _.template($('#giftContentsTemplate').html());
                                        giftContentsHtml += complied({
                                            dataGiftArrKey: key,
                                            dataGiftArrIndex: i,
                                            dataGiftGoodsNo: value.goodsNo,
                                            dataGiftScmNo: value2.multiGiftNo[i].scmNo,
                                            dataGiftSelectCnt: value2.selectCnt,
                                            dataGiftStockFl: value2.multiGiftNo[i].stockFl,
                                            dataGiftGiftNo: value2.multiGiftNo[i].giftNo,
                                            dataGiftImageUrl: value2.multiGiftNo[i].imageUrl,
                                            dataGiftGiftNm: value2.multiGiftNo[i].giftNm,
                                            dataGiftGiveCnt: value2.giveCnt,
                                            dataGiftCheckboxReadonly: dataGiftCheckboxReadonly
                                        });
                                    }
                                }
                            }
                        });

                        var compliedLayout = _.template($('#giftTemplate').html());
                        var giftHtml = compliedLayout({
                            dataGiftContents: giftContentsHtml,
                            dataGiftTitle: value.title,
                            dataGiftTotal: dataGiftTotal,
                            dataGiftSelectCnt: dataGiftSelectCnt
                        });
                        $("#selfOrderGiftArea>div>table>tbody").append(giftHtml);
                    });
                }
            }
            /* 사은품 */

            /* 추가 정보 */
            $("#selfOrderAddFieldArea>div>table>tbody").empty();
            $("#selfOrderAddFieldArea").addClass("display-none");
            $("input[name='addFieldConf']").val('');
            if (frmData.addFieldInfo) {
                var addFieldCheckBoxNameArr = [];
                var idx = 0;
                if (frmData.addFieldInfo.addFieldConf == 'y') {
                    addFieldHtml = '';
                    $("#selfOrderAddFieldArea").removeClass("display-none");
                    $.each(frmData.addFieldInfo.data, function (key, value) {
                        var addFieldComplied = _.template($('#addFieldTemplate').html());
                        addFieldHtml += addFieldComplied({
                            dataAddFieldName: value.orderAddFieldName,
                            dataAddFieldHtml: value.orderAddFieldHtml
                        });
                        if (value.orderAddFieldRequired == 'y' && value.orderAddFieldType == 'checkbox') {
                            addFieldCheckBoxNameArr[idx] = key + 1;
                            idx++;
                        }
                    });

                    $("#selfOrderAddFieldArea>div>table>tbody").append(addFieldHtml);
                    $("input[name='addFieldConf']").val(frmData.addFieldInfo.addFieldConf);
                    if (addFieldCheckBoxNameArr.length > 0) {
                        $.each(addFieldCheckBoxNameArr, function (key2, value2) {
                            var addFieldCheckBoxName = 'addField[' + value2 + '][data]';
                            $("#frmOrderWriteForm input[name^='" + addFieldCheckBoxName + "']").each(function () {
                                $(this).rules("add", {
                                    required: function () {
                                        return $("input[name^='" + addFieldCheckBoxName + "']:checked").length < 1;
                                    }
                                });
                            });
                        });
                    }
                }
            }
            /* 추가 정보 */
        }

        //회원구분 상태가 회원이면
        if($('input[name="memberTypeFl"]:checked').val() === 'y'){
            //회원이 선택되어 있다면
            if(($("input[name='memNo']").val() && $("input[name='memNo']").val() !== '0') || ($('input[name="cartSno[]"]') && $('input[name="cartSno[]"]').length > 0)) {
                if(couponUse === 'y' && couponConf.chooseCouponMemberUseType !== 'member'){
                    $(".self-order-member-relation-coupon-area").removeClass("display-none");
                }
                else {
                    $(".self-order-member-relation-coupon-area").addClass("display-none");
                }
                /* 상품결제 수단에 의한 마일리지, 예치금 구역 숨김 */
                if(payLimitData.orderMileageAble === 'n'){
                    $(".self-order-member-relation-mileage-area").addClass("display-none");
                }
                else {
                    $(".self-order-member-relation-mileage-area").removeClass("display-none");
                }
                if(payLimitData.orderDepositAble === 'n'){
                    $(".self-order-member-relation-deposit-area").addClass("display-none");
                }
                else {
                    $(".self-order-member-relation-deposit-area").removeClass("display-none");
                }
            }
            else {
                //회원이 선택되어 있지 않다면 일단 보여줌
                $(".self-order-member-relation-coupon-area").removeClass("display-none");
                $(".self-order-member-relation-mileage-area").removeClass("display-none");
                $(".self-order-member-relation-deposit-area").removeClass("display-none");
            }
        }
        else { // 회원구분 상태가 비회원이면 다 숨김
            $(".self-order-member-relation-coupon-area").addClass("display-none");
            $(".self-order-member-relation-mileage-area").addClass("display-none");
            $(".self-order-member-relation-deposit-area").addClass("display-none");
        }

        //최종금액 계산
        set_real_settle_price(frmData, 'y');
        mileage_disable_check();
        displayBankArea();

        set_delivery_area_combine();
    });
}

//최종 결제금액 체크 및 표기
function set_real_settle_price(frmData, ajaxUsedFl)
{
    var totalGoodsPrice = 0; //상품합계금액
    var totalSettlePrice = 0; //최종결제금액
    var totalDeliveryCharge = 0; //배송비
    var displayTotalDcPrice = ''; //할인 및 적립표기
    var totalGoodsDcPrice = 0;
    var totalSumMemberDcPrice = 0;
    var totalCouponGoodsDcPrice = 0;
    var totalGoodsMileage = 0;
    var totalMemberMileage = 0;
    var totalCouponGoodsMileage = 0;
    var totalMileage = 0;
    var deliveryFree = 0;

    if(ajaxUsedFl === 'y'){
        //통신을 했을 시
        totalGoodsDcPrice = frmData.cartPrice.totalGoodsDcPrice;
        totalSumMemberDcPrice = frmData.cartPrice.totalSumMemberDcPrice;
        totalCouponGoodsDcPrice = frmData.cartPrice.totalCouponGoodsDcPrice;
        totalGoodsMileage = frmData.cartPrice.totalGoodsMileage;
        totalMemberMileage = frmData.cartPrice.totalMemberMileage;
        totalCouponGoodsMileage = frmData.cartPrice.totalCouponGoodsMileage;
        totalDeliveryCharge = frmData.cartPrice.totalDeliveryCharge;
        totalDeliveryAreaCharge = frmData.cartPrice.totalDeliveryAreaCharge;
        totalGoodsPrice = frmData.cartPrice.totalGoodsPrice;
        totalMileage = frmData.cartPrice.totalMileage;
        totalSettlePrice = frmData.cartPrice.totalSettlePrice;

        //회원그룹 배송비 무료일 경우
        if ($('input[name="deliveryFree"]').val() == 'y') {
            deliveryFree = totalDeliveryCharge - totalDeliveryAreaCharge;
            totalSettlePrice -= deliveryFree;
        }

        $("#selfOrderCartPriceData").attr("data-totalGoodsDcPrice", totalGoodsDcPrice);
        $("#selfOrderCartPriceData").attr("data-totalSumMemberDcPrice", totalSumMemberDcPrice);
        $("#selfOrderCartPriceData").attr("data-totalCouponGoodsDcPrice", totalCouponGoodsDcPrice);
        $("#selfOrderCartPriceData").attr("data-totalGoodsMileage", totalGoodsMileage);
        $("#selfOrderCartPriceData").attr("data-totalMemberMileage", totalMemberMileage);
        $("#selfOrderCartPriceData").attr("data-totalCouponGoodsMileage", totalCouponGoodsMileage);
        $("#selfOrderCartPriceData").attr("data-totalDeliveryCharge", totalDeliveryCharge);
        $("#selfOrderCartPriceData").attr("data-totalDeliveryAreaCharge", totalDeliveryAreaCharge);
        $("#selfOrderCartPriceData").attr("data-totalGoodsPrice", totalGoodsPrice);
        $("#selfOrderCartPriceData").attr("data-totalMileage", totalMileage);
        $("#selfOrderCartPriceData").attr("data-totalSettlePrice", totalSettlePrice);
        $("#selfOrderCartPriceData").attr("data-deliveryFree", deliveryFree);
    }
    else {
        totalGoodsDcPrice = $("#selfOrderCartPriceData").attr("data-totalGoodsDcPrice");
        totalSumMemberDcPrice = $("#selfOrderCartPriceData").attr("data-totalSumMemberDcPrice");
        totalCouponGoodsDcPrice = $("#selfOrderCartPriceData").attr("data-totalCouponGoodsDcPrice");
        totalGoodsMileage = $("#selfOrderCartPriceData").attr("data-totalGoodsMileage");
        totalMemberMileage = $("#selfOrderCartPriceData").attr("data-totalMemberMileage");
        totalCouponGoodsMileage = $("#selfOrderCartPriceData").attr("data-totalCouponGoodsMileage");
        totalDeliveryCharge = $("#selfOrderCartPriceData").attr("data-totalDeliveryCharge");
        totalDeliveryAreaCharge = $("#selfOrderCartPriceData").attr("data-totalDeliveryAreaCharge");
        totalGoodsPrice = $("#selfOrderCartPriceData").attr("data-totalGoodsPrice");
        totalMileage = $("#selfOrderCartPriceData").attr("data-totalMileage");
        totalSettlePrice = $("#selfOrderCartPriceData").attr("data-totalSettlePrice");
        deliveryFree = $("#selfOrderCartPriceData").attr("data-deliveryFree");
    }

    if (couponConf.chooseCouponMemberUseType == 'coupon' && $('input[name="couponApplyOrderNo"]').val() != '') {
        if (totalSumMemberDcPrice > 0) {
            totalSettlePrice = parseFloat(totalSettlePrice) + parseFloat(totalSumMemberDcPrice);
        }
    }

    // 주문쿠폰 적용 금액
    if ($('input[name="totalCouponOrderDcPrice"]').val() > 0) {
        var originOrderPrice = totalGoodsPrice - totalGoodsDcPrice - totalSumMemberDcPrice - totalCouponGoodsDcPrice;
        var originOrderPriceWithoutMember = totalGoodsPrice - totalGoodsDcPrice - totalCouponGoodsDcPrice;
        // 쿠폰기본설정에서 쿠폰만 사용일때 처리
        if (couponConf.chooseCouponMemberUseType == 'coupon' && $('input[name="couponApplyOrderNo"]').val() != '') {
            originOrderPrice = originOrderPriceWithoutMember;
        }

        if (!_.isUndefined(originOrderPrice) && parseFloat($('input[name="totalCouponOrderPrice"]').val()) > parseFloat(originOrderPrice)) {
            var useTotalCouponOrderDcPrice = parseFloat(originOrderPrice);
        } else {
            var useTotalCouponOrderDcPrice = parseFloat($('input[name="totalCouponOrderPrice"]').val());
        }
        $('input[name="totalCouponOrderDcPrice"]').val(useTotalCouponOrderDcPrice);
        $('#useDisplayCouponDcPrice').text(numeral(useTotalCouponOrderDcPrice).format());
    } else {
        var useTotalCouponOrderDcPrice = 0;
    }

    // 배송비쿠폰 적용 금액
    if ($('input[name="totalCouponDeliveryDcPrice"]').val() > 0) {
        var tmpTotalDeliveryCharge = totalDeliveryCharge;
        if ($('input[name="deliveryFree"]').val() == 'y' && deliveryFree > 0) {
            tmpTotalDeliveryCharge -= deliveryFree;
        }
        if (!_.isUndefined(tmpTotalDeliveryCharge) && parseFloat($('input[name="totalCouponDeliveryPrice"]').val()) > parseFloat(tmpTotalDeliveryCharge)) {
            var useTotalCouponDeliveryDcPrice = parseFloat(tmpTotalDeliveryCharge);
        } else {
            var useTotalCouponDeliveryDcPrice = parseFloat($('input[name="totalCouponDeliveryPrice"]').val());
        }
        $('input[name="totalCouponDeliveryDcPrice"]').val(useTotalCouponDeliveryDcPrice);
        $('#useDisplayCouponDelivery').text(numeral(useTotalCouponDeliveryDcPrice).format());
    } else {
        var useTotalCouponDeliveryDcPrice = 0;
    }

    totalSettlePrice -= (useTotalCouponOrderDcPrice + useTotalCouponDeliveryDcPrice);

    //회원으로 주문시 마일리지, 예치금, 쿠폰 적용
    if($("input[name='memNo']").val() && $("input[name='memNo']").val() !== '0'){
        //마일리지 금액 삭감
        if ($("input[name='useMileage']").val() && $("input[name='useMileage']").val() !== '0') {
            var useMileage = parseInt($('input[name=\'useMileage\']').val());
        } else {
            var useMileage = 0;
        }
        //예치금 금액 삭감
        if ($("input[name='useDeposit']").val() && $("input[name='useDeposit']").val() !== '0') {
            var useDeposit = parseInt($('input[name=\'useDeposit\']').val());
        } else {
            var useDeposit = 0;
        }

        totalSettlePrice -= parseInt(useMileage);
        totalSettlePrice -= parseInt(useDeposit);
    }

    // 쿠폰기본설정에서 쿠폰만 사용일때 처리
    if (couponConf.chooseCouponMemberUseType == 'coupon' && $('input[name="couponApplyOrderNo"]').val() != '') {
        if(Number(totalMemberMileage) > 0){
            totalMileage -= Number(totalMemberMileage);
        }
        totalSumMemberDcPrice = 0;
        totalMemberMileage = 0;
    }

    //할인 및 적립 표기
    var totalSalePrice = parseInt(totalGoodsDcPrice) + parseInt(totalSumMemberDcPrice) + parseInt(totalCouponGoodsDcPrice);
    displayTotalDcPrice = '<div class="self-order-sale-icon">할인 : <strong>(-)' + currencySymbol + numeral(totalSalePrice).format() + currencyString + '</strong><span>( ';
    displayTotalDcPrice += '상품 ' + currencySymbol + numeral(totalGoodsDcPrice).format() + currencyString + ', ';
    displayTotalDcPrice += '회원 ' + currencySymbol + numeral(totalSumMemberDcPrice).format() + currencyString + ', ';
    displayTotalDcPrice += '쿠폰 ' + currencySymbol + numeral(totalCouponGoodsDcPrice).format() + currencyString;
    displayTotalDcPrice += ' )</span></div>';
    if ($('input[name="deliveryFree"]').val() == 'y' && deliveryFree > 0) {
        displayTotalDcPrice += '<div class="self-order-sale-icon">배송비 할인 : <strong>(-) ' + currencySymbol + numeral(deliveryFree).format() + currencyString + '</strong></div>';
    }
    if($("input[name='memNo']").val() && $("input[name='memNo']").val() !== '0') {
        displayTotalDcPrice += '<div class="self-order-mileage-icon">적립 ' + mileageInfo.name + ': <strong>(+)' + numeral(totalMileage).format() + mileageInfo.unit + '</strong><span>( ';
        displayTotalDcPrice += '상품 ' + numeral(totalGoodsMileage).format() + mileageInfo.unit + ', ';
        displayTotalDcPrice += '회원 ' + numeral(totalMemberMileage).format() + mileageInfo.unit + ', ';
        displayTotalDcPrice += '쿠폰 ' + numeral(totalCouponGoodsMileage).format() + mileageInfo.unit;
        displayTotalDcPrice += ' )</span></div>';
    }
    $(".js-total-dc-price").html(displayTotalDcPrice); // 할인 및 적립 표기
    $(".js-total-delivery-charge").html(numeral(totalDeliveryCharge).format()); //배송비
    $(".js-total-goods-price").html(numeral(totalGoodsPrice).format()); //상품합계금액
    $(".js-total-settle-price").html(numeral(totalSettlePrice).format()); // 최종결제금액
    $("input[name=settlePrice]").val(totalSettlePrice); // 최종결제금액

    return totalSettlePrice;
}


function set_recalculation()
{
    var memNo = $('input[name="memNo"]').val();
    var address = $('input[name="receiverAddress"]').val() + $('input[name="receiverAddressSub"]').val();
    var totalCouponOrderDcPrice = $('input:hidden[name="totalCouponOrderDcPrice"]').val();
    var deliveryFree = $('input:hidden[name="deliveryFree"]').val();
    var useMileage = $('input[name="useMileage"]').val();

    //주문쿠폰 적용시 재계산
    var cartPrice = '';
    $.ajax({
        method: "POST",
        data: {'mode': 'set_recalculation', 'totalCouponOrderDcPrice': totalCouponOrderDcPrice, 'deliveryFree': deliveryFree, 'useMileage': useMileage, 'memNo': memNo, 'address': address},
        cache: false,
        async: false,
        url: "../order/order_ps.php",
        success: function (data) {
            if (data) {
                cartPrice = data;
            }
        }
    });

    return cartPrice;
}

/**
 * 결제금액에서 상품금액만 구하기 (배송비 제외)
 * @param realSettlePrice
 * @returns {number|*}
 */
function get_goodsSalesPrice(realSettlePrice)
{
    var deliveryFreePrice = $("#selfOrderCartPriceData").attr("data-deliveryFree");
    var deliveryPrice = 0;
    if (deliveryFreePrice > 0) {
        var deliveryAreaPrice = $("#selfOrderCartPriceData").attr("data-totalDeliveryAreaCharge");
        var deliveryDcPrice = parseInt($('input[name="totalCouponDeliveryDcPrice"]').val());
        if (deliveryAreaPrice > 0) {
            deliveryPrice = parseInt(deliveryPrice) + parseInt(deliveryAreaPrice);
        }
        if (deliveryDcPrice > 0) {
            deliveryPrice = parseInt(deliveryPrice) - parseInt(deliveryDcPrice);
        }
    } else {
        var deliveryBasicPrice = $("#selfOrderCartPriceData").attr("data-totalDeliveryCharge");
        var deliveryAreaPrice = $("#selfOrderCartPriceData").attr("data-totalDeliveryAreaCharge");
        var deliveryDcPrice = parseInt($('input[name="totalCouponDeliveryDcPrice"]').val());

        if (deliveryAreaPrice > 0) {
            deliveryPrice = parseInt(deliveryPrice) + parseInt(deliveryAreaPrice);
        } else if (deliveryBasicPrice > 0) {
            deliveryPrice = parseInt(deliveryPrice) + parseInt(deliveryBasicPrice);
        }
        if (deliveryDcPrice > 0) {
            deliveryPrice = parseInt(deliveryPrice) - parseInt(deliveryDcPrice);
        }
    }

    realSettlePrice = parseInt(realSettlePrice) - parseInt(deliveryPrice);

    return realSettlePrice;
}

/**
 * 마일리지 사용 제한 체크
 */
function mileage_disable_check() {
    if (mileageUse.payUsableFl == 'y' && !_.isUndefined(memberInfo)) {
        // 할인 적용 상품 결제 금액
        var salesSettlePrice = parseInt(set_real_settle_price()) + parseInt($('input[name="useMileage"]').val());
        salesSettlePrice = get_goodsSalesPrice(salesSettlePrice);
        // 순 상품 결제 금액
        var goodsSettlePrice = $("#selfOrderCartPriceData").attr("data-totalGoodsPrice");
        if (mileageUse.standardPrice == 'salesPrice') { // 할인금액 포함
            var realSettlePrice = salesSettlePrice;
        } else {
            var realSettlePrice = goodsSettlePrice; // 상품금액만
        }
        // 최소 상품구매금액
        var orderAbleLimit = mileageUse.orderAbleLimit;
        // 최소 사용 마일리지
        var minMileage = mileageUse.minimumLimit;
        var mileage_usable = 0;
        var fail_msg;
        // 최소 보유마일리지 체크
        if (mileageUse.minimumHold > 0 && memberInfo.mileage < mileageUse.minimumHold) {
            mileage_usable = 1;
            fail_msg = '최소 보유마일리지 제한';
        }
        // 최소 상품구매금액 체크 (관리자 설정 기준)
        if (mileageUse.orderAbleLimit > 0) {
            if (realSettlePrice < orderAbleLimit) {
                mileage_usable = 2;
                fail_msg = '최소 상품구매금액 제한';
            }
        }
        // 최소 사용마일리지보다 할인 포함 상품 결제금액이 낮음 체크
        if (mileageUse.minimumLimit > 0) {
            if (salesSettlePrice < minMileage) {
                mileage_usable = 3;
                fail_msg = '최소 사용마일리지 제한';
            }
        }
        if (mileage_usable > 0) {
            console.log(mileage_usable);
            console.log(fail_msg);
            $('input[name=\'useMileage\']').parent().addClass('disabled');
            $('input[name=\'useMileage\']').attr('disabled', 'disabled');
            $('#selfOrderUseMileageAll').parent().addClass('disabled');
            $('#selfOrderUseMileageAll').attr('disabled', 'disabled');
        } else {
            $('input[name=\'useMileage\']').parent().removeClass('disabled');
            $('input[name=\'useMileage\']').attr('disabled', false);
            $('#selfOrderUseMileageAll').parent().removeClass('disabled');
            $('#selfOrderUseMileageAll').attr('disabled', false);
        }
    }
}
