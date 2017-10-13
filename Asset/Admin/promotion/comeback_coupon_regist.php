<form id="frmCoupon" name="frmCoupon" action="coupon_ps.php" method="post" class="content_form" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="<?= $couponData['mode']; ?>"/>
    <input type="hidden" name="sno" value="<?= $couponData['sno']; ?>"/>
    <input type="hidden" name="ypage" value="<?= $ypage; ?>"/>

    <div class="page-header js-affix">
        <h3><?php echo end($naviMenu->location); ?></h3>
        <div class="btn-group">
            <input type="button" value="목록" class="btn btn-white btn-icon-list" onclick="goList('./comeback_coupon_list.php');" />
            <?php if ($checkMode == 'canModify') { ?>
            <input type="submit" value="저장" class="btn btn-red"/>
            <?php } ?>
        </div>
    </div>

    <h5 class="table-title gd-help-manual">조건설정</h5>
    <table class="table table-cols">
        <colgroup>
            <col class="width-lg"/>
            <col/>
            <col class="width-md"/>
            <col/>
        </colgroup>
        <tbody>
        <tr>
            <th class="require">
                제목
            </th>
            <td>
                <input type="text" name="title" value="<?= $couponData['title'] ?>" class="form-control width-xl js-maxlength" maxlength="30" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>/>
            </td>
        </tr>
        <tr>
            <th class="require">
                대상 선택
            </th>
            <td>
                <div class="radio form-inline">
                    <label class="radio-inline">
                        <input type="radio" name="targetFl" value="o" <?= $checked['targetFl']['o'] ?>  <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>/>
                        마지막
                        <select name="targetOrderFl" class="form-control select" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>>
                            <option value="p" <?= $selected['targetOrderFl']['p'] ?>>결제완료일</option>
                            <option value="s" <?= $selected['targetOrderFl']['s'] ?>>배송완료일</option>
                            <option value="c" <?= $selected['targetOrderFl']['c'] ?>>구매확정일</option>
                        </select>
                        로부터
                        <input type="text" name="targetOrderDay" value="<?= $couponData['targetOrderDay'] ?>" class="form-control width-2xs" maxlength="3" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>/> 일이 지난 회원
                    </label>
                    <div class="pdt5 pdl20">
                        결제금액이
                        <input type="text" name="targetOrderPriceMin" value="<?= $couponData['targetOrderPriceMin'] ?>" class="form-control width-xs" maxlength="8" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>/>
                        원 ~
                        <input type="text" name="targetOrderPriceMax" value="<?= $couponData['targetOrderPriceMax'] ?>" class="form-control width-xs" maxlength="8" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>/>
                        원인 회원만 선택
                        <p class="notice-info">
                            빈칸으로 두면 결제금액에 관계없이 대상이 선택됩니다.
                        </p>
                    </div>
                </div>
                <div class="form-inline pdt10">
                    <label class="radio-inline">
                        <input type="radio" name="targetFl" value="g" <?= $checked['targetFl']['g'] ?> <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?> />
                        특정상품의 마지막
                        <select name="targetGoodFl" class="form-control select" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>>
                            <option value="p" <?= $selected['targetGoodFl']['p'] ?>>결제완료일</option>
                            <option value="s" <?= $selected['targetGoodFl']['s'] ?>>배송완료일</option>
                            <option value="c" <?= $selected['targetGoodFl']['c'] ?>>구매확정일</option>
                        </select>
                        로부터
                        <input type="text" name="targetGoodDay" value="<?= $couponData['targetGoodDay'] ?>" class="form-control width-2xs" maxlength="3" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>/> 일이 지난 회원
                </div>
                <div class="tr-apply-goods form-inline">
                    <button type="button" id="selectApplyGoods" class="btn btn-sm btn-gray" title="적용할 상품을 선택해주세요." <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>>상품선택</button>
                    <table id="targetGoodGoods" class="table table-cols">
                        <thead>
                        <tr>
                            <th>번호</th>
                            <th>이미지</th>
                            <th>상품명</th>
                            <th>삭제</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($couponData['targetFl'] == 'g' && $couponData['targetGoodGoods']) {
                            foreach ($couponData['couponApplyGoods'] as $key => $val) {
                                echo '<tr id="idGoods_' . $val['goodsNo'] . '">' . chr(10);
                                echo '<td class="center">' . ($key + 1) . '<input type="hidden" name="targetGoodGoods[]" value="' . $val['goodsNo'] . '" /></td>' . chr(10);
                                echo '<td class="center">' . gd_html_goods_image($val['goodsNo'], $val['imageName'], $val['imagePath'], $val['imageStorage'], 50, $val['goodsNm'], '_blank') . '</td>' . chr(10);
                                echo '<td>' . gd_remove_tag($val['goodsNm']) . '</td>' . chr(10);
                                if ($checkMode == 'cantModify') {
                                    echo '<td class="center"><input type="button"  data-toggle="delete" data-target="#idGoods_' . $val['goodsNo'] . '" value="삭제" class="btn btn-sm btn-gray" disabled></td>' . chr(10);
                                } else {
                                    echo '<td class="center"><input type="button"  data-toggle="delete" data-target="#idGoods_' . $val['goodsNo'] . '" value="삭제" class="btn btn-sm btn-gray"></td>' . chr(10);
                                }
                                echo '</tr>' . chr(10);
                            }
                        }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4">
                                <input type="button" value="전체삭제" class="btn btn-sm btn-gray" onclick="$('#targetGoodGoods tbody').html('');" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </td>
        </tr>

        <tr>
            <th>프로모션 정보</th>
            <td>
                <div>
                    <input type="button" value="쿠폰선택" class="btn btn-sm btn-gray js-layer-register" data-type="coupon" data-coupon-save-type="manual" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>/>
                    <input type="button" value="신규쿠폰 등록" id="link-regist-coupon" class="btn btn-sm btn-white" data-type="coupon" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>/>
                    <label class="checkbox-inline mgl10"></label>
                    <div id="couponLayer" class="selected-btn-group <?=!empty($couponData['couponNo']) ? 'active' : ''?>">
                        <h5>선택된 쿠폰 : </h5>
                        <?php if (empty($couponData['couponNo']) === false) { ?>
                            <div id="info_coupon_<?= $couponData['couponNo'] ?>" class="btn-group btn-group-xs">
                                <input type="hidden" name="couponNo" value="<?= $couponData['couponNo'] ?>"/>
                                <input type="hidden" name="couponNoNm" value="<?= $couponData['couponNoNm'] ?>"/>
                                <span class="btn"><?= $couponData['couponNoNm'] ?></span>
                                <button type="button" class="btn btn-icon-delete" data-toggle="delete" data-target="#info_coupon_<?= $couponData['couponNo'] ?>" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>>삭제</button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <th>
                SMS 발송여부
            </th>
            <td>
                <div class="checkbox">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="smsFl" value="y" <?= $checked['smsFl']['y'] ?> <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?> />SMS 동시 발송
                    </label>
                </div>
            </td>
        </tr>
        <tr style="display:none">
            <th>
                광고성 문자
            </th>
            <td>
                <div class="checkbox">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="smsSpamFl" value="y" <?= $checked['smsSpamFl']['y'] ?> />광고성 문구 추가
                    </label>
                </div>
                <div class="notice-info">
                    광고SMS 발송 문구를 추가하려면 <a href="/member/sms_auto.php#promotion" target="_blank" class="btn-link-underline">[080수신거부 서비스 사용신청]</a>을 먼저 해주시기 바랍니다.
                </div>
            </td>
        </tr>
        <tr class="tr-sms-contents">
            <th class="require">발송 내용</th>
            <td class="form-inline sms-replace-code-area">
                <div class="row">
                    <div class="col-xs-3 pdr0">
                        <span class="sms-type notice-info">SMS : 건당 1포인트 차감</span>
                        <span class="lms-type notice-danger display-none">LMS : 건당 <?php echo $lmsPoint; ?>포인트 차감</span>
                        <button type="button" class="btn btn-white btn-sm pull-right" data-target=".replace_code_area" data-text="치환코드 닫기" onclick="lawAlert();">치환코드 보기</button>
                    </div>
                </div>
                <div class="row pdt5">
                    <div class="col-xs-3 pdr0">
                        <label class="width100p">
                            <textarea name="smsContents" rows="13" class="smsContents form-control width100p" data-close="true" <?php if ($checkMode == 'cantModify') { ?>disabled<?php } ?>><?= $couponData['smsContents'] ?></textarea>
                        </label>
                    </div>
                </div>
                <div class="row pdt10">
                    <div class="col-xs-12">
                        <!-- //@formatter:off -->
                        <input type="text" id="smsStringCount" value="0" readonly="readonly" class="form-control width-3xs"> / <span class="sms-type"><?php echo $smsStringLimit; ?></span><span class="lms-type display-none"><?php echo number_format($lmsStringLimit); ?></span> Bytes
                        <!-- //@formatter:on -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <p class="notice-danger">정통망법에 따른 광고성 정보 전송 준수사항을 꼭 확인해주세요.
                            <a href="http://www.godo.co.kr/news/notice_view.php?board_idx=1237&page=2" target="_blank" class="btn-link">자세히보기></a>
                        </p>
                        <div class="notice-info">
                            링크는 단축 URL로 자동 변환하였습니다.<br/>
                            플러스샵 [단축 URL] 앱을 이용하여 단축 URL을 생성하면, 조회수 및 통계 자료를 확인하실 수 있습니다. <a href="http://plus.godo.co.kr/goods/view.gd?idx=12" class="btn-link" target="_blank">바로가기</a>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        </tbody>
    </table>

</form>

<script type="text/javascript">
    $(document).ready(function () {
        // 상품선택창 기본 가림
        $('.tr-apply-goods').hide();

        $('.tr-sms-contents').hide();

        // 신규쿠폰 등록 버튼 클릭
        $('#link-regist-coupon').click(function(){
            window.open('/promotion/coupon_regist.php');
            return false;
        });

        /**
         * 문자열 Byte 체크 (한글 2byte)
         */
            function stringToByte(str) {
            var length = 0;
            for (var i = 0; i < str.length; i++) {
                if (escape(str.charAt(i)).length >= 4)
                    length += 2;
                else if (escape(str.charAt(i)) != "%0D")
                    length++;
            }
            return length;
        }

        /**
         * SMS 내용 길이 체크
         */
        function setContentsLength(contentsNm, countId) {
            var textarea = $('textarea[name=' + contentsNm + ']');
            var contentsText = textarea.val();
            var textLength = stringToByte(contentsText);
            if (textLength > <?php echo $smsStringLimit;?>) {
                if (textLength > <?php echo $lmsStringLimit;?>) {
                    if (textarea.data('close')) {
                        textarea.data('close', false);
                        BootstrapDialog.show({
                            message: 'LMS 전송은 최대 2,000 Byte 까지 가능합니다.',
                            onhidden: function () {
                                textarea.data('close', true);
                            }
                        });
                    }
                }
                $('#' + countId).css("color", "#FF0000");
                $('.sms-type').hide();
                $('.lms-type').show();
                $('input[name=sendFl]').val('lms');
            } else {
                $('#' + countId).css("color", "");
                $('.sms-type').show();
                $('.lms-type').hide();
                $('input[name=sendFl]').val('sms');
            }
            $('#' + countId).val(textLength);
        }

        function setSendLength() {
            setContentsLength('smsContents', 'smsStringCount');
        }

        // 글자수 체크
        $('textarea[name=smsContents]').keyup(setSendLength).change(setSendLength);

        $("#frmCoupon").validate({
            submitHandler: function (form) {
                // 대상선택에서 특정상품기준일경우
                if ($('[name=targetFl]:checked').val() == 'g' && $('[name="targetGoodGoods[]"]').length == 0) {
                    alert('대상이 될 특정상품(들)을 선택해주세요.');
                    return false;
                }
                // sms 발송여부체크시 내용 길이 체크
                if ($('[name=smsFl]').prop('checked') == true && $('#smsStringCount').val() == '0') {
                    alert('발송 내용을 입력해주세요.');
                    return false;
                }
                // 쿠폰 혹은 sms 둘중 하나는 체크가 되어있어야함
                if ($('[name=smsFl]').prop('checked') == false && $('[name=couponNo]').length == 0) {
                    alert('"쿠폰"이나 "SMS" 중 하나는 반드시 입력해야 저장할 수 있습니다.');
                    return false;
                } else {
                    form.target = 'ifrmProcess';
                    form.submit();
                }
            },
            rules: {
                mode: {
                    required: true,
                },
                title: {
                    required: true,
                },
                targetFl: {
                    required: true,
                },
                targetOrderDay: {
                    required: function (input) {
                        var required = false;
                        if ($('[name=targetFl]:checked').val() == 'o') {
                            required = true;
                        }
                        return required;
                    },
                    digits: true,
                    min: 1,
                },
                targetOrderPriceMin: {
                    required: function (input) {
                        var required = false;
                        if ($('[name=targetFl]:checked').val() == 'o' && $('[name=targetOrderPriceMin]').val() == '' && $('[name=targetOrderPriceMax]').val() != '') {
                            $('[name=targetOrderPriceMin]').val('0');
                            //required = true;
                        }
                        return required;
                    },
                    digits: true,
                    min: 0,
                },
                targetOrderPriceMax: {
                    required: function (input) {
                        var required = false;
                        if ($('[name=targetFl]:checked').val() == 'o' && $('[name=targetOrderPriceMin]').val() != '' && $('[name=targetOrderPriceMax]').val() == '') {
                            $('[name=targetOrderPriceMax]').val('99999999');
                            //required = true;
                        }
                        return required;
                    },
                    digits: true,
                    min: 1,
                },
                targetGoodDay: {
                    required: function (input) {
                        var required = false;
                        if ($('[name=targetFl]:checked').val() == 'g') {
                            required = true;
                        }
                        return required;
                    },
                    digits: true,
                    min: 1,
                },
            },
            messages: {
                mode: {
                    required: '정상 접속이 아닙니다.(mode)',
                },
                title: {
                    required: '컴백쿠폰 제목을 입력하세요.',
                },
                targetFl: {
                    required: '대상 선택 유형을 선택하세요.',
                },
                targetOrderDay: {
                    required: function () {
                        var required = '마지막 ' + $('[name=targetOrderFl] option:selected').text() + '로부터 검색할 일자를 입력하세요.';
                        return required;
                    },
                    digits: function () {
                        var required = '마지막 ' + $('[name=targetOrderFl] option:selected').text() + '로부터 검색할 일자에는 숫자만 입력하세요.';
                        return required;
                    },
                    min: function () {
                        var required = '마지막 ' + $('[name=targetOrderFl] option:selected').text() + '로부터 검색할 일자에는 1일이상 입력하세요.';
                        return required;
                    },
                },
                targetOrderPriceMin: {
                    required: '대상 결제금액의 시작금액을 입력하세요.',
                    digits : '대상 결제금액 입력란은 숫자만 입력하세요.',
                },
                targetOrderPriceMax: {
                    required: '대상 결제금액의 종료금액을 입력하세요.',
                    digits : '대상 결제금액 입력란은 숫자만 입력하세요.',
                },
                targetGoodDay: {
                    required: function () {
                        var required = '특정상품의 마지막 ' + $('[name=targetGoodFl] option:selected').text() + '로부터 검색할 일자를 입력하세요.';
                        return required;
                    },
                    digits: function () {
                        var required = '특정상품의 마지막 ' + $('[name=targetGoodFl] option:selected').text() + '로부터 검색할 일자에는 숫자만 입력하세요.';
                        return required;
                    },
                    min: function () {
                        var required = '특정상품의 마지막 ' + $('[name=targetGoodFl] option:selected').text() + '로부터 검색할 일자에는 1일이상 입력하세요.';
                        return required;
                    },
                },
            }
        });
        
        $('input:radio[name="targetFl"]').click(function (e) {
            changeTargetType();
        });

        // 쿠폰 적용 해당 버튼 선택 시
        $('[id^=selectApply]').click(function (e) {
            var code = (this.id).split('selectApply');
            code = code[1];
            layer_register(code);
        });

        $('input:checkbox[name="smsFl"]').click(function (e) {
            checkSms();
        });

        changeTargetType();
        checkSms();
        setSendLength();
    });

    // 대상선택에 따른 폼 변경
    function changeTargetType() {
        if ($('input:radio[name="targetFl"]:checked').val() == 'o') {
            // 상품 대상 초기화/가림
            $('.tr-apply-goods').hide();
            $('#targetGoodGoods tbody').html('');
        } else  {
            // 상품 대상 초기화/가림
            $('.tr-apply-goods').show();
        }
    }

    // sms발송 선택에 따른 폼 변경
    function checkSms() {
        if ($('input:checkbox[name="smsFl"]').prop("checked")) {
            // sms내용 보여줌
            $('.tr-sms-contents').show();
        } else  {
            // sms내용 가림
            $('.tr-sms-contents').hide();
        }
    }

    /**
     * 구매 상품 범위 등록 / 예외 등록 Ajax layer
     *
     * @param string codeStr 타입
     * @param string modeStr 예외 여부
     */
    function layer_register(codeStr, isDisabled) {
        var layerFormID = 'couponRangeForm';
        var addParam = '';
        var fileStr = '';

        if (codeStr == 'Goods') {
            // 레이어 창
            var parentFormID = 'targetGood' + codeStr;
            var dataFormID = 'id' + codeStr;
            var dataInputNm = 'targetGood' + codeStr;
            var layerTitle = '컴백 쿠폰 적용 대상 ';
            layerTitle = layerTitle + '상품선택';
            fileStr = 'goods';
            mode = 'simple';
            $("#" + parentFormID + " thead").show();
            $("#" + parentFormID + " tfoot").show();
        } else {
            var parentFormID = 'couponExcept' + codeStr;
            var dataFormID = 'idExcept' + codeStr;
            var dataInputNm = 'couponExcept' + codeStr;
            var layerTitle = '쿠폰 제외 ';
        }

        var addParam = {
            "mode": mode,
            "layerFormID": layerFormID,
            "parentFormID": parentFormID,
            "dataFormID": dataFormID,
            "dataInputNm": dataInputNm,
            "layerTitle": layerTitle,
            "disabled": isDisabled,
//            "callFunc": "",
        };

        layer_add_info(fileStr, addParam);
    }

    // 출석체크 신규쿠폰 등록 시 등록 후 호출되는 함수
    function unload_callback() {
        <?php
        if(gd_isset($callback, '') != ''){?>
        var callback = window.opener.<?=$callback?>;
        if ($.isFunction(callback)) {
            callback();
        }
        <?php }
        ?>
    }

    // 여신전문금융업법 안내
    function lawAlert() {
        var message = '';
        message += '<table style="border: 1px solid #dddddd; width:100%">';
        message += '<colgroup>';
        message += '<col width="10%">';
        message += '<col width="45%">';
        message += '<col width="45%">';
        message += '</colgroup>';
        message += '<thead>';
        message += '<tr>';
        message += '<th style="border: 1px solid #dddddd; text-align:center; background-color: #f2f2f2;">번호</th>';
        message += '<th style="border: 1px solid #dddddd; text-align:center; background-color: #f2f2f2;">치환코드</th>';
        message += '<th style="border: 1px solid #dddddd; text-align:center; background-color: #f2f2f2;">설명</th>';
        message += '</tr>';
        message += '</thead>';
        message += '<tbody>';
        message += '<tr>';
        message += '<td style="border: 1px solid #dddddd; text-align:center;">1</td>';
        message += '<td style="border: 1px solid #dddddd; padding-left: 5px;">{rc_mallNm}</td>';
        message += '<td style="border: 1px solid #dddddd; padding-left: 5px;">쇼핑몰 이름</td>';
        message += '</tr>';
        message += '<tr>';
        message += '<td style="border: 1px solid #dddddd; text-align:center;">2</td>';
        message += '<td style="border: 1px solid #dddddd; padding-left: 5px;">{rc_memNm}</td>';
        message += '<td style="border: 1px solid #dddddd; padding-left: 5px;">회원명</td>';
        message += '</tr>';
        message += '</tbody>';
        message += '</table>';

        dialog_alert(message, '치환코드 보기');
    }
</script>
