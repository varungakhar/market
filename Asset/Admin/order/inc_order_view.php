<div class="table-responsive">
    <table class="table table-rows">
        <thead>
        <tr class="nowrap">
            <th rowspan="2" class="width3p">
                <input type="checkbox" id="allCheck" value="y" class="js-checkall" data-target-name="bundle[statusCheck]"/>
            </th>
            <th rowspan="2" class="width3p">번호</th>
            <th rowspan="2" class="width5p">상품<br/>주문번호</th>
            <th rowspan="2" class="width-3xs">이미지</th>
            <th rowspan="2">주문상품</th>
            <th rowspan="2" class="width5p">수량</th>
            <th rowspan="2" class="width5p">판매가</th>
            <th rowspan="2" class="width5p">매입가</th>
            <?php
            if (!$isProvider) { ?>
                <th rowspan="2" class="width5p">상품할인</th>
                <th rowspan="2" class="width5p">회원할인(상품)</th>
                <th rowspan="2" class="width5p">회원할인(배송비)</th>
                <th colspan="3" class="width10p">쿠폰할인</th>
                <!--                <th rowspan="2" class="width10p">사은품</th>-->
                <?php if(gd_is_plus_shop(PLUSSHOP_CODE_PURCHASE) === true) { ?><th rowspan="2"  class="width5p">매입처</th><?php } ?>
                <th rowspan="2" class="width5p">공급사</th>
            <?php } ?>
            <th colspan="2" class="width5p">배송비</th>
            <th rowspan="2" class="width7p">송장번호</th>
            <th rowspan="2" class="width7p">처리상태<br><input type="button" value="전체로그" class="btn btn-sm btn-white js-order-log"/></th>
        </tr>
        <?php if (!$isProvider) { ?>
            <tr class="nowrap text-center">
                <th class="width5p table-left-border">상품쿠폰</th>
                <th class="width5p table-left-border">주문쿠폰</th>
                <th class="width5p">배송비쿠폰</th>
                <th class="width5p">구분</th>
                <th class="width5p">합계</th>
            </tr>
        <?php } ?>
        </thead>
        <tbody>
        <?php
        // 주문 처리가 주문기준으로 되어야 할 주문 단계의 경우 체크가 동일 처리되게
        $onclickAction = (in_array(gd_isset($data['statusMode']), $order->statusListCombine) ? 'js-checkall' : '');
        if (isset($data['goods']) === true) {
            $sortNo = $data['cnt']['goods']['goods'];// 번호 설정
            $rowAll = 0;
            foreach ($data['goods'] as $sKey => $sVal) {
                $rowScm = 0;
                foreach ($sVal as $dKey => $dVal) {
                    $rowDelivery = 0;
                    foreach ($dVal as $key => $val) {
                        // 주문상태 모드
                        $statusMode = substr($val['orderStatus'], 0, 1);

                        // rowspan 처리
                        $orderAddGoodsRowSpan = $val['addGoodsCnt'] > 0 ? 'rowspan="' . ($val['addGoodsCnt'] + 1) . '"' : '';
                        $orderScmRowSpan = ' rowspan="' . ($data['cnt']['scm'][$sKey]) . '"';
                        $orderDeliveryRowSpan = ' rowspan="' . ($data['cnt']['delivery'][$dKey]) . '"';

                        // 결제정보에 사용할 데이터 만들기
                        if ($val['goodsDcPrice'] > 0) {
                            $goodsDcPrice[$val['sno']] = $val['goodsDcPrice'];
                        }

                        //배송업체가 설정되어 있지 않을시 기본 배송업체 select
                        $selectInvoiceCompanySno = $val['invoiceCompanySno'];
                        if((int)$selectInvoiceCompanySno < 1){
                            $selectInvoiceCompanySno = $deliverySno;
                        }
                        $totalMemberDeliveryDcPrice = $divisionDeliveryCharge = 0;
                        if (empty($val['totalMemberDeliveryDcPrice']) === false) {
                            $totalMemberDeliveryDcPrice = $val['divisionDeliveryCharge'];
                        } else {
                            $divisionDeliveryCharge = $val['divisionDeliveryCharge'];
                        }
                        ?>
                        <tr id="statusCheck_<?= $statusMode; ?>_<?= $val['sno']; ?>" class="text-center">
                            <td <?= $orderAddGoodsRowSpan; ?> class="center">
                                <div class="display-block">
                                    <input type="checkbox" name="bundle[statusCheck][<?= $val['sno']; ?>]" value="<?= $val['sno']; ?>" class="<?= gd_isset($onclickAction); ?>"/>
                                    <input type="hidden" name="bundle[statusMode][<?= $val['sno']; ?>]" value="<?= $val['orderStatus']; ?>"/>
                                    <input type="hidden" name="bundle[goods][sno][<?= $val['sno']; ?>]" value="<?= $val['sno']; ?>"/>
                                </div>
                            </td>
                            <td <?= $orderAddGoodsRowSpan; ?>><?= $sortNo ?></td>
                            <td <?= $orderAddGoodsRowSpan; ?>>
                                <?= $val['sno'] ?>
                                <?php if ($data['orderChannelFl'] == 'naverpay') { ?>
                                    <p class="mgt5"><img src="<?=UserFilePath::adminSkin('gd_share', 'img', 'channel_icon', 'naverpay.gif')->www()?>" /> <?= $val['apiOrderGoodsNo']; ?></p>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if ($val['goodsType'] === 'addGoods') { ?>
                                    <?= gd_html_add_goods_image($val['goodsNo'], $val['addImageName'], $val['addImagePath'], $val['addImageStorage'], 40, $val['goodsNm'], '_blank'); ?>
                                <?php } else { ?>
                                    <?= gd_html_goods_image($val['goodsNo'], $val['imageName'], $val['imagePath'], $val['imageStorage'], 40, $val['goodsNm'], '_blank'); ?>
                                <?php } ?>
                            </td>
                            <td class="text-left">
                                <?php if ($val['goodsType'] === 'addGoods') { ?>
                                    <span class="label label-default" title="<?= $val['sno'] ?>">추가</span>
                                    <a href="javascript:void();" class="one-line bold mgb5" title="추가상품명"
                                       onclick="addgoods_register_popup('<?= $val['goodsNo']; ?>', <?= $isProvider ? 'true' : 'false' ?>);">
                                        <?=gd_html_cut($val['goodsNmStandard'] && $isUseMall === false ? $val['goodsNmStandard'] :  $val['goodsNm'], 46, '..') ?></a>
                                <?php } else { ?>
                                    <?php if($val['timeSaleFl'] =='y') { ?>
                                        <img src='<?=PATH_ADMIN_GD_SHARE?>img/time-sale.png' alt='타임세일' />
                                    <?php } ?>
                                    <a href="javascript:void()" class="one-line" title="상품명" onclick="goods_register_popup('<?= $val['goodsNo']; ?>', <?= $isProvider ? 'true' : 'false' ?>);">
                                        <?=$val['goodsNmStandard'] && $isUseMall === false ? $val['goodsNmStandard'] :  $val['goodsNm'] ?></a>
                                <?php } ?>

                                <div class="info">
                                    <?php
                                    // 상품 코드
                                    if (empty($val['goodsCd']) === false) {
                                        echo '<div class="font-kor" title="상품코드">[' . $val['goodsCd'] . ']</div>';
                                    }

                                    // 옵션 처리
                                    if (empty($val['optionInfo']) === false) {
                                        foreach ($val['optionInfo'] as $oKey => $oVal) {
                                            echo '<dl class="dl-horizontal" title="옵션명">';
                                            echo '<dt>' . $oVal['optionName'] . ' :</dt>';
                                            echo '<dd>' . $oVal['optionValue'] . '</dd>';
                                            echo '</dl>';
                                        }
                                    }

                                    // 텍스트 옵션 처리
                                    if (empty($val['optionTextInfo']) === false) {
                                        foreach ($val['optionTextInfo'] as $oKey => $oVal) {
                                            echo '<ul class="list-unstyled" title="텍스트 옵션명">';
                                            echo '<li>' . $oVal['optionName'] . ' :</li>';
                                            echo '<li>' . $oVal['optionValue'] . ' ';
                                            if ($oVal['optionTextPrice'] > 0) {
                                                echo '<span>(추가금 ';
                                                if ($isUseMall) {
                                                    echo gd_global_order_currency_display(gd_isset($oVal['optionTextPrice']), $data['exchangeRate'], $data['currencyPolicy']);
                                                } else {
                                                    echo gd_currency_display($oVal['optionTextPrice']);
                                                }
                                                echo ')</span>';
                                            }
                                            echo '</li>';
                                            echo '</ul>';
                                        }
                                    }
                                    ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <strong><?= number_format($val['goodsCnt']); ?></strong>
                                <?php if (isset($val['stockCnt']) === true) { ?>
                                    <div title="재고">재고: <?= $val['stockCnt']; ?></div>
                                <?php } ?>
                            </td>
                            <td class="text-right">
                                <?php if ($isUseMall == true) { ?>
                                    <?= gd_global_order_currency_display(($val['goodsPrice'] + $val['optionPrice'] + $val['optionTextPrice']) * $val['goodsCnt'], $data['exchangeRate'], $data['currencyPolicy']); ?>
                                <?php } else { ?>
                                    <?= gd_currency_display(($val['goodsPrice'] + $val['optionPrice'] + $val['optionTextPrice']) * $val['goodsCnt']); ?>
                                <?php } ?>
                            </td>
                            <td class="text-right">
                                <?php if ($isUseMall == true) { ?>
                                    <?= gd_global_order_currency_display(($val['costPrice'] + $val['optionCostPrice']) * $val['goodsCnt'], $data['exchangeRate'], $data['currencyPolicy']); ?>
                                <?php } else { ?>
                                    <?= gd_currency_display(($val['costPrice'] + $val['optionCostPrice']) * $val['goodsCnt']); ?>
                                <?php } ?>
                            </td>
                            <?php if (!$isProvider) { ?>
                                <td <?= $orderAddGoodsRowSpan; ?> class="text-right">
                                    <?php if ($isUseMall == true) { ?>
                                    <?= gd_global_order_currency_display($val['goodsDcPrice'], $data['exchangeRate'], $data['currencyPolicy']); ?>
                                    <?php } else { ?>
                                    <?= gd_currency_display($val['goodsDcPrice']); ?>
                                    <?php } ?>
                                </td>
                                <td <?= $orderAddGoodsRowSpan; ?> class="text-right">
                                    <?php if ($isUseMall == true) { ?>
                                    <?= gd_global_order_currency_display($val['totalMemberDcPrice'] + $val['totalMemberOverlapDcPrice'], $data['exchangeRate'], $data['currencyPolicy']); ?>
                                    <?php } else { ?>
                                    <?= gd_currency_display($val['totalMemberDcPrice'] + $val['totalMemberOverlapDcPrice']); ?>
                                    <?php } ?>
                                </td>
                                <?php if (($rowDelivery == 0 && $data['mallSno'] == DEFAULT_MALL_NUMBER) || ($rowAll == 0 && $data['mallSno'] > DEFAULT_MALL_NUMBER)) { ?>
                                    <td <?=$orderDeliveryRowSpan?> class="text-right">
                                        <?php if ($isUseMall == true) { ?>
                                            <?= gd_global_order_currency_display($totalMemberDeliveryDcPrice, $data['exchangeRate'], $data['currencyPolicy']); ?>
                                        <?php } else { ?>
                                            <?= gd_currency_display($totalMemberDeliveryDcPrice); ?>
                                        <?php } ?>
                                    </td>
                                <?php } ?>
                                <td <?= $orderAddGoodsRowSpan; ?> class="text-right">
                                    <?php if ($isUseMall == true) { ?>
                                    <?= gd_global_order_currency_display($val['totalCouponGoodsDcPrice'], $data['exchangeRate'], $data['currencyPolicy']); ?>
                                    <?php } else { ?>
                                    <?= gd_currency_display($val['totalCouponGoodsDcPrice']); ?>
                                    <?php } ?>
                                </td>
                                <td <?= $orderAddGoodsRowSpan; ?> class="text-right">
                                    <?php if ($isUseMall == true) { ?>
                                    <?= gd_global_order_currency_display($val['totalDivisionCouponOrderDcPrice'], $data['exchangeRate'], $data['currencyPolicy']); ?>
                                    <?php } else { ?>
                                    <?= gd_currency_display($val['totalDivisionCouponOrderDcPrice']); ?>
                                    <?php } ?>
                                </td>
                                <?php if (($rowDelivery == 0 && $data['mallSno'] == DEFAULT_MALL_NUMBER) || ($rowAll == 0 && $data['mallSno'] > DEFAULT_MALL_NUMBER)) { ?>
                                    <td <?=$orderDeliveryRowSpan?> class="text-right">
                                        <?php if ($isUseMall == true) { ?>
                                            <?= gd_global_order_currency_display($divisionDeliveryCharge, $data['exchangeRate'], $data['currencyPolicy']); ?>
                                        <?php } else { ?>
                                        <?= gd_currency_display($divisionDeliveryCharge); ?>
                                        <?php } ?>
                                    </td>
                                <?php } ?>
                                <?php if (false) { //$rowScm === 0?>
                                    <td <?= $orderScmRowSpan; ?> class="font-kor text-left">
                                        <ul class="list-unstyled mgb0">
                                            <?php
                                            if ($val['gift']) {
                                                foreach ($val['gift'] as $gift) { ?>
                                                    <li><?=$gift['presentTitle']?> | <?=$gift['giftNm']?> | <?=$gift['giveCnt']?>개</li>
                                                <?php } } ?>
                                        </ul>
                                    </td>
                                <?php } ?>
                            <?php if(gd_is_plus_shop(PLUSSHOP_CODE_PURCHASE) === true) { ?><td><?= $val['purchaseNm']; ?></td><?php } ?>
                                <?php if ($rowScm == 0) { ?>
                                <td <?=$orderScmRowSpan?> class="border-left text-center"><?= $val['companyNm']; ?></td>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($val['goodsDeliveryFl'] === 'n') { ?>
                            <td <?= $orderAddGoodsRowSpan; ?>>
                                <?=gd_currency_display($val['goodsDeliveryCollectPrice'])?><br />
                                <?=$val['goodsDeliveryCollectFl'] == 'pre' ? '(선불)' : '(착불)';?>
                            </td>
                            <?php } else { ?>
                                <?php if (($rowDelivery == 0 && $data['mallSno'] == DEFAULT_MALL_NUMBER) || ($rowAll == 0 && $data['mallSno'] > DEFAULT_MALL_NUMBER)) { ?>
                            <td <?= $orderDeliveryRowSpan; ?>>
                                <?php if ($isUseMall == true) { ?>
                                    <?= gd_global_order_currency_display($val['deliveryCharge'], $data['exchangeRate'], $data['currencyPolicy']); ?><br />
                                <?php } else { ?>
                                    <?=$val['goodsDeliveryCollectFl'] == 'pre' ? gd_currency_display($val['deliveryCharge']) : gd_currency_display($val['deliveryCollectPrice'])?><br />
                                    <?=$val['goodsDeliveryCollectFl'] == 'pre' ? '(선불)' : '(착불)';?>
                                <?php } ?>
                            </td>
                            <?php } ?>
                            <?php } ?>
                            <?php if (($rowDelivery == 0 && $data['mallSno'] == DEFAULT_MALL_NUMBER) || ($rowAll == 0 && $data['mallSno'] > DEFAULT_MALL_NUMBER)) { ?>
                                <td <?=$orderDeliveryRowSpan?> class="border-right">
                                    <?php if ($isUseMall == true) { ?>
                                    <?= gd_global_order_currency_display($val['deliveryCharge'], $data['exchangeRate'], $data['currencyPolicy']); ?>
                                    <?php } else { ?>
                                    <?= gd_currency_display($val['deliveryCharge']); ?>
                                    <?php } ?>
                                    <?php if (empty($data['isDefaultMall']) === true) { ?>
                                    <br>(총무게 : <?=$data['totalDeliveryWeight']?>kg)
                                    <?php } ?>
                                </td>
                            <?php } ?>
                            <td <?= $orderAddGoodsRowSpan; ?>>
                                <?php if ($data['orderChannelFl'] == 'naverpay') { ?>
                                    <?=$val['apiDeliveryDataText']?>
                                <?php }?>

                                <?php if($val['hideDeliveryCompanySelectBox'] != 'y') { ?>
                                    <?= gd_select_box(null, 'bundle[goods][invoiceCompanySno][' . $val['sno'] . ']', $deliveryCom, null, $selectInvoiceCompanySno, null); ?>
                                <?php }?>
                                <input type="text" name="bundle[goods][invoiceNo][<?= $val['sno']; ?>]" value="<?= $val['invoiceNo']; ?>" <?php if($val['disableInputTrackNumber'] == 'y') echo 'disabled'?> class="form-control input-sm mgt5"/>

                            </td>
                            <td <?= $orderAddGoodsRowSpan; ?> class="center">
                                <?php if ($val['beforeStatusStr'] && $statusMode == 'r') { ?>
                                    <div class="text-muted" title="이전 상품별 주문 상태"><?= $val['beforeStatusStr']; ?> &gt;</div>
                                <?php } ?>
                                <p><?= $val['orderStatusStr']; ?></p>
                                <?php if($val['naverpayStatus']['code'] == 'DelayProductOrder'){    //발송지연?>
                                    <div style="padding-bottom:5px" data-sno="<?=$val['sno']?>" data-info="<?=$val['naverpayStatus']['text']?>" class="js-btn-naverpay-status-detail">
                                        (<?=$val['naverpayStatus']['notice']?>)
                                    </div>
                                <?php }?>

                                <div><?php if ($val['orderStatus'] == 'd1') {
                                        echo gd_date_format('m-d H:i', gd_isset($val['deliveryDt']));
                                    } else if ($val['orderStatus'] == 'd3') {
                                        echo gd_date_format('m-d H:i', gd_isset($val['finishDt']));
                                    } ?></div>
                                <div>
                                    <input type="button" data-sno="<?= $val['sno']; ?>" data-name="<?= $val['goodsNm']; ?>" value="로그보기" class="btn btn-sm btn-white js-order-log"/>
                                </div>
                                <?php if (empty($val['invoiceCompanySno']) === false && empty($val['invoiceNo']) === false) { ?>
                                    <div>
                                        <input type="button" onclick="delivery_trace('<?= $val['invoiceCompanySno']; ?>', '<?= $val['invoiceNo']; ?>');" value="배송추적" class="btn btn-sm btn-gray mgt5"/>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                        if ($val['addGoodsCnt'] > 0) {
                            foreach ($val['addGoods'] as $aVal) {
                                ?>
                                <tr id="addStatusCheck_<?= $statusMode; ?>_<?= $val['sno']; ?>" class="text-center add-goods">
                                    <td class="text-center"><span class="label label-default" title="<?= $aVal['sno'] ?>">추가</span></td>
                                    <td class="text-left">
                                        <?= gd_html_add_goods_image($aVal['addGoodsNo'], $aVal['imageNm'], $aVal['imagePath'], $aVal['imageStorage'], 30, $aVal['goodsNm'], '_blank'); ?>
                                        <a href="javascript:void()" class="one-line" title="추가 상품명" onclick="addgoods_register_popup('<?= $aVal['addGoodsNo']; ?>');"><?= gd_html_cut($aVal['goodsNm'], 46, '..'); ?>
                                            <small>(<?= gd_html_cut($aVal['optionNm'], 46, '..'); ?>)</small>
                                        </a>
                                    </td>
                                    <td class="goods_cnt"><?= number_format($aVal['goodsCnt']); ?></td>
                                    <td class="text-right"><?= gd_currency_display($aVal['goodsPrice'] * $aVal['goodsCnt']); ?></td>
                                </tr>
                                <?php
                            }
                        }

                        $sortNo--;
                        $rowDelivery++;
                        $rowScm++;
                        $rowAll++;
                    }
                }
            }
        } else {
            ?>
            <tr>
                <td class="no-data" colspan="25"><?=$incTitle?>이 없습니다.</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php if (isset($data['goods']) === true) { ?>
    <div class="table-action">
        <div class="pull-left form-inline">
            <?php if (!$isProvider || $data['statusMode'] !== 'r') { // 공급사 환불내역은 보여주면 안됨?>
                <?php if ($selectBoxOrderStatus) { ?>
                    <span class="action-title">선택한 상품을</span>
                    <?php if ($data['statusMode'] === 'f') { ?>
                        <button type="button" class="btn btn-white js-order-delete">삭제처리</button>
                    <?php } ?>
                    <?= gd_select_box('bundleOrderStatus', 'bundle[orderStatus]', $selectBoxOrderStatus, null, null, '==상품상태==', null, 'form-control js-status-change') ?>
                    으(로)
                    <button type="button" class="btn btn-white js-ordergoods-status">변경</button>
                <?php } else { ?>
                    <span class="action-title">선택할 상품이 없습니다.</span>
                <?php } ?>
            <?php } ?>
        </div>
        <div class="pull-right form-inline">
            <?= gd_select_box('applyDeliverySno', null, $deliveryCom, null, $deliverySno, null, null, 'form-control'); ?>
            <input type="text" id="applyInvoiceNo" value="" class="form-control input-lg width-sm"/>
            <input type="button" value="송장일괄등록" class="btn btn-white js-invoice-apply"/>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">
    <!--
    var statusStandardCode = eval(<?= $scriptStatusCode;?>);

    $(function(){
        $('.js-btn-naverpay-status-detail').bind('click',function () {  //네이버페이 상세사유 보기
            var sno = $(this).data('sno');
            var info = $(this).data('info');
            $.get('../order/layer_naverpay_order.php', {'mode': 'view','orderNo': '<?= gd_isset($data['orderNo']);?>',  'orderGoodsNo' : sno }, function (data) {
                if(data.substring(0,5) == 'error'){
                    var errorData = data.split("|");
                    alert(errorData[1]);
                    return;
                }

                BootstrapDialog.show({
                    title: info+'정보',
                    size: get_layer_size('wide'),
                    message: data,
                    closable: true,
                });

//                    layer_popup(data, dom.statusSelect.find('option:selected').text(), 'wide');
            });

        }).css('cursor','pointer').css('text-decoration','underline');
    })

    /**'
     * 주문 처리 상품의 가능 여부를 체크
     * 부모창인 order_view.php에서 참조한다.
     *
     * @param string chkMode 처리 코드
     */
    function set_check_status(chkCode) {
        // 주문 처리 가능한 상품만 체크
        for (var codeKey in statusStandardCode) {
            for (var i = 0; i < statusStandardCode[codeKey].length; i++) {
                var codeSubKey = statusStandardCode[codeKey][i];
                if (codeSubKey == chkCode) {
                    $('tr[id*="statusCheck_' + codeKey + '"]').removeClass('disabled');
                    $('tr[id*="addStatusCheck_' + codeKey + '"]').removeClass('disabled');
                    $('tr[id*="statusCheck_' + codeKey + '"] input').prop('disabled', false);
                    $('tr[id*="statusCheck_' + codeKey + '"] select').prop('disabled', false);
                }
            }
        }

        // 비활성화 셀에서 체크박스가 체크된 경우 자동 해제
        $('tr[id*=\'statusCheck_\']').each(function (i) {
            var checkboxId = $(this).find('td:eq(0)').find('input:checkbox').attr('id');
            if (typeof checkboxId != 'undefined') {
                if ($(this).hasClass('disabled')) {
                    $('#' + checkboxId).prop('checked', false);
                }
            }
        });
    }
    //-->
</script>
